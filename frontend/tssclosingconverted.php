<?
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

require_once dirname(__FILE__).'/start.inc.php';

																																


$COLNAMES = array (
	array('heinv_id','ORIGINAL'),
	array('descr','DESCR'),
	array('C01','Qty'),
	array('COST','COST'),
	array('SUBVALUE','SUBVALUE'),
	array('id','CONVERTED')
);




print "<form>";
print "Region ";
print "<select name='region_id'>";
print "<option value=\"01700\" " . (($_GET['region_id']=='01700') ? "selected" : "") . ">TSS</option>";
print "<option value=\"03000\" " . (($_GET['region_id']=='03000') ? "selected" : "") . ">CandyShopee</option>";
print "</select>";

print "Tanggal ";
print "<select name='tanggal'>";
print "<option value=\"2012-04-30\" " . (($_GET['tanggal']=='2012-04-30') ? "selected" : "") . ">April</option>";
print "<option value=\"2012-05-31\" " . (($_GET['tanggal']=='2012-05-31') ? "selected" : "") . ">Mei</option>";
print "<option value=\"2012-06-30\" " . (($_GET['tanggal']=='2012-06-30') ? "selected" : "") . ">Juni</option>";
print "<option value=\"2012-07-31\" " . (($_GET['tanggal']=='2012-07-31') ? "selected" : "") . ">Juli</option>";
print "<option value=\"2012-08-31\" " . (($_GET['tanggal']=='2012-08-31') ? "selected" : "") . ">Agustus</option>";
print "<option value=\"2012-09-30\" " . (($_GET['tanggal']=='2012-09-30') ? "selected" : "") . ">September</option>";
print "<option value=\"2012-10-31\" " . (($_GET['tanggal']=='2012-10-31') ? "selected" : "") . ">Oktober</option>";
print "<option value=\"2012-11-30\" " . (($_GET['tanggal']=='2012-11-30') ? "selected" : "") . ">November</option>";
print "<option value=\"2012-12-31\" " . (($_GET['tanggal']=='2012-12-31') ? "selected" : "") . ">Desember</option>";
print "</select>";


print "<input type=\"hidden\" name=\"t\" value=\"".time()."\">";
print "<input type=\"submit\" value=\"ok\" name=\"sub\">";
print "</form>";





if ($_GET['sub']=='ok') {
 
 	$tanggal = $_GET['tanggal'];
 	$region_id = $_GET['region_id'];
 
	$SQL = "EXEC  inv05_RptClosingReportSummaryConvertedItemDetil
			 @date = '$tanggal',
			 @region_id = '$region_id' ";

			 
	$rs = $conn->Execute($SQL);
	
	
	print "<html>";
	print "<body>";
	print "<table border=1> ";


	print "<tr>";
	foreach ($COLNAMES as $data)
	{
	 	$colname = $data[1];
		print "<td bgcolor=\"#CCCCCC\">";
		print $colname;
		print "</td>";	 	
	}	
	print "</tr>";
	

	
	while (!$rs->EOF)
	{

	
		$c = 0;
		print "<tr>";
		foreach ($COLNAMES as $data)
		{
		 	$colname = $data[0];
		 
			print "<td>";
			print $rs->fields[$colname];
			print "</td>";
			
		}
		print "</tr>";
		
	
		$rs->MoveNext();
	}		 
	
	 
	print "</table>";
	print "EOF";
	print "</body>";
	print "</html>";		 

}
?>