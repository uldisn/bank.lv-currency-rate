bank.lv-currency-rate
=====================
PHP klase Latvijas bankas valūtas kurs automPHP klase Latvijas bankas valūtas kurs automātiskai nolasīšanai un glabāšanai datubāzē

Izmantošana
=====================
$oCurr = new fCurrency();
echo $oCurr->getCurrencyRateByCode(CUR_USD, '2013.01.01);
echo $oCurr->getCurrencyRateById(3, '2013.01.01);

goana
=====================
jāaizstāj:
1) MySQL::q()  ar mysql_query() un mysql_featch_assoc() 
2) MySQL::s()  ar mysql_query()

