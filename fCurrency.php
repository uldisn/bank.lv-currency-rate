<?php

/**
 * valūtu kursi no bank.lv un to kešošana tabulā currency_rate 
 */
class fCurrency {

    var $sError = FALSE;

    /**
     * valutas kurs valūtai uz konkrēto datumu
     * @param char $sCode currency code
     * @param date $dDate formāts yyyy.mm.dd vai yyyymmdd
     * @return boolean|float - currency rate
     */
    function getCurrencyRateByCode($sCode, $dDate) {
        $this->sError = FALSE;

        /**
         * pārbauda vlūtas kodu un dabū id
         */
        $sSql = "SELECT 
                    id 
                FROM
                    currency 
                WHERE
                    currency_code = '" . $sCode . "'
                ";
        $aRow = MySQL::q($sSql);

        if (count($aRow) == 0) {
            $this->sError = 'Nekorekts valūtas kods: ' . $sCode;
            return FALSE;
        }

        return $this->getCurrencyRate($aRow[0]['id'], $dDate);
    }

    /**
     * valutas kurrs valūtai uz konkrēto datumu
     * @param int $nId currency id
     * @param date $dDate format yyyy.mm.dd
     * @return boolean|float - currency rate
     */
    function getCurrencyRateById($nId, $dDate) {
        $this->sError = FALSE;

        // valūtas id parbauda
        $sSql = "SELECT 
                    currency_code 
                FROM
                    currency 
                WHERE
                    id = " . $nId . "
                ";
        $aRow = MySQL::q($sSql);
        if (count($aRow) == 0) {
            $this->sError = 'Nekorekts valūtas id: ' . $nId;
            return FALSE;
        }

        return $this->getCurrencyRate($nId, $dDate);
    }

    /**
     * valutas kurrs valūtai uz konkrēto datumu
     * @param int $nId currency id
     * @param date $dDate format yyyy.mm.dd
     * @return boolean|float - currency rate
     */
    private function getCurrencyRate($nId, $dDate) {
        $this->sError = FALSE;

        /**
         * sys valutas kurs ir 1 vienmer
         */
        if ($nId == CONFIG_SYS_CURRENCY) {
            return 1;
        }

        if ($dDate) {
            $aDates = MySQL::q("SELECT IF(DATEDIFF('" . $dDate . "',CURDATE())>0, 1, 0) in_future ");
            if ($aDates[0]['in_future'] == 1) {
                $this->sError = "Can not get currency rate, Date(" . $nDate . ") is in future.";
                return FALSE;
            }
        }

        /**
         * meklē kursu iekš DB
         */
        $nRate = $this->_getRateFromDb($nId, $dDate);
        if ($nRate) {
            return $nRate;
        }

        /**
         * kursu uz aktuālo datumu nolasa no bank.lv
         */
        $aRate = $this->_getRateFromBankLv($dDate);
        if (!$aRate) {
            return FALSE;
        }

        /**
         * saglabā bank.lv datus
         */
        $this->_saveRate($aRate, $dDate);

        /**
         * meklē kursu iekš DB
         */
        $nRate = $this->_getRateFromDb($nId, $dDate);
        if (!$aRate) {
            $this->sError = 'Neizdevās nolasīt valūtas kursu no bank.lv';
            return FALSE;
        }

        return $nRate;
    }

    /**
     * nolasa valūtas kursus no bank.lv prasītajam datumam
     * @param char $nDate date in yyyy.mm.dd  vai yyyymmdd format
     * @return boolean|int
     */
    private function _getRateFromBankLv($nDate) {
        $aResRate = array();

        $nDate = preg_replace('#[^0-9]*#', '', $nDate);
        $sUrl = "http://www.bank.lv/vk/xml.xml?date=" . $nDate;

        $cXML = file_get_contents($sUrl);
        if (!$cXML) {
            $this->sError = 'Neizdevās pieslēgties bank.lv';
            return false;
        }

        preg_match_all("#<ID>(.*?)</ID>#", $cXML, $aIDs);
        preg_match_all("#<Units>(.*?)</Units>#", $cXML, $aUnits);
        preg_match_all("#<Rate>(.*?)</Rate>#", $cXML, $aRate);

        foreach ($aIDs[1] as $k => $v) {
            if ($aUnits[1][$k] > 1)
                $nCurrencyRate = $aRate[1][$k] / $aUnits[1][$k];
            else
                $nCurrencyRate = $aRate[1][$k];

            $aResRate[$v] = $nCurrencyRate;
        }
        $aResRate['LVL'] = 1;
        return $aResRate;
    }

    /**
     * saglabā valūtas kursus DB
     * @param type $aRate
     * @param type $dDate
     * @return boolean
     */
    private function _saveRate($aRate, $dDate) {

        /**
         * savāc vajadzīgās valūtas
         */
        $aCurr = array();
        $sSql = "
            SELECT 
                id,
                currency_code 
            FROM
                currency 
        ";
        $aC = MySQL::q($sSql);
        foreach ($aC as $aRow) {
            $aCurr[$aRow['currency_code']] = $aRow['id'];
        }

        /**
         * iet cauri bank.lv kursiem un saglabā tos
         */
        foreach ($aRate as $sCurCode => $nRate) {

            if (!isset($aCurr[$sCurCode])) {
                /**
                 * valūta nav starp vajadzīgajām valūtam
                 */
                continue;
            }
            $nCurrId = $aCurr[$sCurCode];

            /**
             * vai tāds ieraksts jau nav tabulā
             */
            $sSql = "
                SELECT 
                    id
                FROM
                    currency_rate
                WHERE
                    currency_id = " . $nCurrId . "
                    and `date` = '" . $dDate . "'
            ";
            $aC = MySQL::q($sSql);
            if (count($aC) > 0) {
                continue;
            }

            /**
             * saglabā ierakstu tabulā
             */
            $sSql = "
                insert into
                    currency_rate
                set
                    currency_id = " . $nCurrId . ",
                    `date` = '" . $dDate . "',
                    rate = " . $nRate . "    
            ";
            $r = MySQL::s($sSql);
        }
        return true;
    }

    /**
     * nolasa valūtas kursu no DB
     * @param int $nId currency_id
     * @param date $dDate yyyy.mm.dd
     * @return boolean/float 
     */
    private function _getRateFromDb($nId, $dDate) {
        $sSql = "
                SELECT 
                  rate 
                FROM
                  currency_rate 
                WHERE `currency_id` = " . $nId . " 
                  AND `date` = '" . $dDate . "' 
                ";
        $aRate = MySQL::q($sSql);

        if (count($aRate) == 0) {
            return FALSE;
        }
        return $aRate[0]['rate'];
    }

}