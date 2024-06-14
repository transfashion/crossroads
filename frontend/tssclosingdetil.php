<?
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

require_once dirname(__FILE__).'/start.inc.php';

																																


$COLNAMES = array (
	array('heinv_id','ID'),
	array('heinv_art','ART'),
	array('heinv_mat','MAT'),
	array('heinv_col','COL'),
	array('heinv_name','NAME'),
	array('season_id','SEA'),
	array('region_id','REGION'),
	array('branch_id','BRANCH'),
	array('branch_name','BRANCH.NAME'),
	array('LASTCOST','LASTCOST'),
	array('RVUNIT','RV.UNITPRICE'),
	array('COST','COST'),
	array('BEG','BEG'),
	array('BEG_VAL','BEG_VAL'),
	array('RV','RV'),
	array('RV_VAL','RV_VAL'),
	array('TRO','TRO'),
	array('TRO_VAL','TRO_VAL'),
	array('TRI','TRI'),
	array('TRI_VAL','TRI_VAL'),
	array('TRT','TTS'),
	array('TRT_VAL','TTS_VAL'),
	array('SL','SL'),
	array('SL_VAL','SL_VAL'),
	array('DO','DO'),
	array('DO_VAL','DO_VAL'),
	array('AJ','AJ'),
	array('AJ_VAL','AJ_VAL'),
	array('ASO','ASO'),
	array('ASO_VAL','ASO_VAL'),
	array('ASI','ASI'),
	array('ASI_VAL','ASI_VAL'),
	array('OTH','OTH'),
	array('OTH_VAL','OTH_VAL'),
	array('END','END'),
	array('ENDVAL','END_VAL'),
	array('heinvctg_id','CTG_ID'),
	array('heinvctg_name','CTG_NAME'),
	array('PRICE', 'PRICE'),
	array('DISC', 'PRICEDISC') 
);




print "<form>";
print "Region ";
print "<select name='region_id'>";
print "<option value=\"01700\" " . (($_GET['region_id']=='01700') ? "selected" : "") . ">TSS</option>";
print "<option value=\"03000\" " . (($_GET['region_id']=='03000') ? "selected" : "") . ">CandyShopee</option>";
print "</select>";

print "Tanggal ";
print "<select name='tanggal'>";

/*
print "<option value=\"2012-04-30\" " . (($_GET['tanggal']=='2012-04-30') ? "selected" : "") . ">April 2012</option>";
print "<option value=\"2012-05-31\" " . (($_GET['tanggal']=='2012-05-31') ? "selected" : "") . ">Mei 2012</option>";
print "<option value=\"2012-06-30\" " . (($_GET['tanggal']=='2012-06-30') ? "selected" : "") . ">Juni 2012</option>";
print "<option value=\"2012-07-31\" " . (($_GET['tanggal']=='2012-07-31') ? "selected" : "") . ">Juli 2012</option>";
print "<option value=\"2012-08-31\" " . (($_GET['tanggal']=='2012-08-31') ? "selected" : "") . ">Agustus 2012</option>";
print "<option value=\"2012-09-30\" " . (($_GET['tanggal']=='2012-09-30') ? "selected" : "") . ">September 2012</option>";
print "<option value=\"2012-10-31\" " . (($_GET['tanggal']=='2012-10-31') ? "selected" : "") . ">Oktober 2012</option>";
print "<option value=\"2012-11-30\" " . (($_GET['tanggal']=='2012-11-30') ? "selected" : "") . ">November 2012</option>";
print "<option value=\"2012-12-31\" " . (($_GET['tanggal']=='2012-12-31') ? "selected" : "") . ">Desember 2012</option>";
*/

print "<option value=\"2013-01-31\" " . (($_GET['tanggal']=='2013-01-31') ? "selected" : "") . ">Januari 2013</option>";
print "<option value=\"2013-02-28\" " . (($_GET['tanggal']=='2013-02-28') ? "selected" : "") . ">Februari 2013</option>";
print "<option value=\"2013-03-31\" " . (($_GET['tanggal']=='2013-03-31') ? "selected" : "") . ">Maret 2013</option>";
print "<option value=\"2013-04-30\" " . (($_GET['tanggal']=='2013-04-30') ? "selected" : "") . ">April 2013</option>";
print "<option value=\"2013-05-31\" " . (($_GET['tanggal']=='2013-05-31') ? "selected" : "") . ">Mei 2013</option>";
print "<option value=\"2013-06-30\" " . (($_GET['tanggal']=='2013-06-30') ? "selected" : "") . ">Juni 2013</option>";
print "<option value=\"2013-07-31\" " . (($_GET['tanggal']=='2013-07-31') ? "selected" : "") . ">Juli 2013</option>";
print "<option value=\"2013-08-31\" " . (($_GET['tanggal']=='2013-08-31') ? "selected" : "") . ">Agustus 2013</option>";
print "<option value=\"2013-09-30\" " . (($_GET['tanggal']=='2013-09-30') ? "selected" : "") . ">September 2013</option>";
print "<option value=\"2013-10-31\" " . (($_GET['tanggal']=='2013-10-31') ? "selected" : "") . ">Oktober 2013</option>";
print "<option value=\"2013-11-30\" " . (($_GET['tanggal']=='2013-11-30') ? "selected" : "") . ">November 2013</option>";
print "<option value=\"2013-12-31\" " . (($_GET['tanggal']=='2013-12-31') ? "selected" : "") . ">Desember 2013</option>";


print "<option value=\"2014-01-31\" " . (($_GET['tanggal']=='2014-01-31') ? "selected" : "") . ">Januari 2014</option>";
print "<option value=\"2014-02-28\" " . (($_GET['tanggal']=='2014-02-28') ? "selected" : "") . ">Februari 2014</option>";
print "<option value=\"2014-03-31\" " . (($_GET['tanggal']=='2014-03-31') ? "selected" : "") . ">Maret 2014</option>";
print "<option value=\"2014-04-30\" " . (($_GET['tanggal']=='2014-04-30') ? "selected" : "") . ">April 2014</option>";
print "<option value=\"2014-05-31\" " . (($_GET['tanggal']=='2014-05-31') ? "selected" : "") . ">Mei 2014</option>";
print "<option value=\"2014-06-30\" " . (($_GET['tanggal']=='2014-06-30') ? "selected" : "") . ">Juni 2014</option>";
print "<option value=\"2014-07-31\" " . (($_GET['tanggal']=='2014-07-31') ? "selected" : "") . ">Juli 2014</option>";
print "<option value=\"2014-08-31\" " . (($_GET['tanggal']=='2014-08-31') ? "selected" : "") . ">Agustus 2014</option>";
print "<option value=\"2014-09-30\" " . (($_GET['tanggal']=='2014-09-30') ? "selected" : "") . ">September 2014</option>";
print "<option value=\"2014-10-31\" " . (($_GET['tanggal']=='2014-10-31') ? "selected" : "") . ">Oktober 2014</option>";
print "<option value=\"2014-11-30\" " . (($_GET['tanggal']=='2014-11-30') ? "selected" : "") . ">November 2014</option>";
print "<option value=\"2014-12-31\" " . (($_GET['tanggal']=='2014-12-31') ? "selected" : "") . ">Desember 2014</option>";


print "</select>";


print "<input type=\"hidden\" name=\"t\" value=\"".time()."\">";
print "<input type=\"submit\" value=\"ok\" name=\"sub\">";
print "</form>";







if ($_GET['sub']=='ok') {
 
 	$tanggal = $_GET['tanggal'];
 	$region_id = $_GET['region_id'];
 
	$SQL = "EXEC  inv05_RptClosingReportSummaryDetil
			 @date = '$tanggal',
			 @region_id = '$region_id ',
			 @CACHEID = null,
			 @SILENT =null";
			 
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

		/*
		$i++;
		if ($i==100)
		{
			break;
		}
		*/
		$heinv_id = $rs->fields['heinv_id'];
		$sql = "SELECT heinv_price01, heinv_pricedisc01 FROM master_heinv WHERE heinv_id='$heinv_id'";
		$rsPrice = $conn->Execute($sql);
		
		$price = $rsPrice->fields['heinv_price01'];
		$pricedisc = $rsPrice->fields['heinv_pricedisc01'];
		
		
		$c = 0;
		print "<tr>";
		foreach ($COLNAMES as $data)
		{
		 	$colname = $data[0];
		 
		 
		 	print "<td>";
		 	switch ($colname)
		 	{
				case 'PRICE' :
					print $price;
					break;
					
				case 'DISC' :
					print $pricedisc;
					break;	
		 	 
		 		default:
					print $rs->fields[$colname];
			}
			print "</td>";
			$c++;
			if ($c>12 && $c<=36)
			{
				$SUM[$colname] += (float)$rs->fields[$colname];
			}
			
			
		}
		print "</tr>";
		
	
		$rs->MoveNext();
	}		 
	
	
	$c = 0;
	print "<tr>";
	foreach ($COLNAMES as $data)
	{
	 	$colname = $data[0];
		$c++;
		print "<td bgcolor=\"#CCCCCC\">";
		if ($c>12 && $c<=36)
		{
			print $SUM[$colname];
		}
		else
		{
			print ".";
		}
		print "</td>";

	}	
	print "</tr>";
			 
	print "</table>";
	print "EOF";
	print "</body>";
	print "</html>";		 

}
?>