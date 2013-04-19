bank.lv-currency-rate
=====================
PHP klase Latvijas bankas valūtas kursu 
automātiskai nolasīšanai un glabāšanai datubāzē

Izmantošana
=====================
1) Jaizveido tabulas izmantojot skritus:
 - table_currency.sql
 - table_currency_rate.sql

2) Configurācijā definēt sistēmas valūtu - skaitīt failu config.inc.php

3) ieviest visām valūtam konstantes - skatīt constants.php

4) PHP kods:
$oCurr = new fCurrency();
echo $oCurr->getCurrencyRateByCode(CUR_USD, '2013.01.01);
echo $oCurr->getCurrencyRateById(3, '2013.01.01);

Pielāgošana
=====================
jāaizstāj:

1) MySQL::q()  ar mysql_query() un mysql_featch_assoc() 

2) MySQL::s()  ar mysql_query()

