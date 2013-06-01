bank.lv-currency-rate
=====================
Pieprasot valūtas kursu, tas tiek nolasīts no datubāzes. Ja nav iekš datubāzes, 
tad nolasa no Latvijas Bankas mājas lapas un pie reizes saglabā datubāzē.
Tiek minimzēts pierasījumu skaits LB lapai, kā arī palielinās skripta izpildes ātrums.
Par valūtas kursiem xml skatīt: http://www.bank.lv/monetara-politika/latvijas-bankas-noteiktie-valutu-kursi-xml-formata

Izmantošana
=====================
1) Jaizveido tabulas izmantojot skritus:
 - table_currency.sql - valūtu saraksts
 - table_currency_rate.sql - valūtu kursi

2) Configurācijā definēt sistēmas valūtu - skaitīt failu config.inc.php

3) ieviest visām valūtam konstantes - skatīt constants.php

4) PHP pielāgošana
jāaizstāj:
 - MySQL::q()  ar mysql_query() un mysql_featch_assoc() 
 - MySQL::s()  ar mysql_query()


5) PHP koda piemērs:

$oCurr = new fCurrency();

$nRate = $oCurr->getCurrencyRateByCode('USD', '2013.01.01);

if ($nRate === FALSE){

    echo $oCurr->sError;

}

$nRate = $oCurr->getCurrencyRateById(CUR_USD, '2013.01.01);

if ($nRate === FALSE){

    echo $oCurr->sError;

}

