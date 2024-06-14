<?
// FOREIGN RATE EXCHANGE
// SESSION I
$SAVE_SESSION = 1;

#[database]#
$db[type] = 'ado_mssql';
$db[host] = 'WORKSHOP';
$db[user] = 'sa';
$db[pass] = 'rahasia';
$db[name] = 'E_FRM';


require_once 'adodb/adodb-exceptions.inc.php';
require_once 'adodb/adodb.class.php';

$ADODB_FETCH_MODE = 2;

$conn = &ADONewConnection($db[type]);
$DSN  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db[host]."; DATABASE=".$db[name]."; UID=".$db[user]."; PWD=".$db[pass].";";


try {
	$conn->Connect($DSN);
} catch(Exception $e) {
	die($e->getMessage());
}

$ip = "172.16.8.25"; 			// proxy IP
$port = 8080; 						// proxy port
$fp = fsockopen($ip,$port); 	// connect to proxy
fputs($fp, "GET http://www.bi.go.id/web/id/Indikator+Moneter+dan+Perbankan/Kurs+BI/ HTTP/1.0\r\nHost:www.bi.go.id:80\r\n\r\n");

$data="";
while (!feof($fp)) $data.=fgets($fp,64000);
fclose($fp);

echo time()."\n\n\n----------------------\n\n\n";


$arr_valas = array("AUD","BND","CAD","CHF","DKK","EUR","GBP","HKD","JPY","NOK","NZD","PGK","SEK","SGD","THB","USD");
foreach ($arr_valas as &$valas) {

	//$valas = "EUR";

	//ambil tabel
	$pattern1 = "%KURS UANG KERTAS ASING(.*?)Akses Data Time Series%is";
	preg_match($pattern1, $data, $matches1);
	$tabel = $matches1[1];

	$pattern_tgl = "%(Update)(.*?)(Kode)%is";
	preg_match($pattern_tgl, $tabel, $matches_tgl);
	$tanggal = trim(str_replace("Terakhir", "", strip_tags($matches_tgl[2])));

	$pattern2 = "%(<tr.*?>)(<td.*?>)$valas(.*?)(<\/td.*?>)(<\/tr.*?>)%is";
	preg_match($pattern2, $tabel, $matches2);
	$row   = $matches2[4];

	$pattern3 = "%(<td.*?>)(.*?)(<\/td.*?>)(<td.*?>)(.*?)(<\/td.*?>)(<td.*?>)(.*?)(<\/td.*?>)%is";
	preg_match($pattern3, $row, $matches3);
	$nilai = 1*strip_tags($matches3[2]);
	$jual  = 1*str_replace(",", "", strip_tags($matches3[5]));
	$beli  = 1*str_replace(",", "", strip_tags($matches3[8]));



	$sql  = "INSERT INTO master_currencyrate ";
	$sql .= "(currency_id, currencyrate_session, currencyrate_date, currencyrate_datestr, currencyrate_unit, currencyrate_buy, currencyrate_sell) ";
	$sql .= "VALUES ";
	$sql .= "('$valas', $SAVE_SESSION, getdate(), '$tanggal', $nilai, $beli, $jual)";

	
	echo "Kurs $valas tanggal $tanggal\n";
	echo "- nilai : ".$nilai."\n";
	echo "- jual  : ".$jual."\n";
	echo "- beli  : ".$beli."\n";
	echo "\n";
	echo $sql."\n";;
	echo "------------------\n\n";
	
	$conn->Execute($sql);


}

?>
