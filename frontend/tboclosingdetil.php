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
);




print "<form>";
print "Region ";
print "<select name='region_id'>";

	print "<option value=\"02500\" " . (($_GET['region_id']=='02500') ? "selected" : "") . ">Armani Jeans</option>";    
	print "<option value=\"02550\" " . (($_GET['region_id']=='02550') ? "selected" : "") . ">Armani Junior</option>";    
	print "<option value=\"01140\" " . (($_GET['region_id']=='01140') ? "selected" : "") . ">Boss Kids</option>";    
	print "<option value=\"01130\" " . (($_GET['region_id']=='01130') ? "selected" : "") . ">Boss Orange</option>";    
	print "<option value=\"00200\" " . (($_GET['region_id']=='00200') ? "selected" : "") . ">Brioni</option>";    
	print "<option value=\"01400\" " . (($_GET['region_id']=='01400') ? "selected" : "") . ">Canali</option>";    
	//print "<option value=\"03000\" " . (($_GET['region_id']=='03000') ? "selected" : "") . ">Candy Shoppe</option>";    
	//print "<option value=\"00600\" " . (($_GET['region_id']=='00600') ? "selected" : "") . ">Celio</option>";    
	print "<option value=\"03100\" " . (($_GET['region_id']=='03100') ? "selected" : "") . ">Chloe Kids</option>";    
	//print "<option value=\"00800\" " . (($_GET['region_id']=='00800') ? "selected" : "") . ">Cocinelle</option>";    
	//print "<option value=\"00100\" " . (($_GET['region_id']=='00100') ? "selected" : "") . ">Corporate</option>";    
	print "<option value=\"03300\" " . (($_GET['region_id']=='03300') ? "selected" : "") . ">DKNY Kids</option>";    
	print "<option value=\"02300\" " . (($_GET['region_id']=='02300') ? "selected" : "") . ">Emporio Armani</option>";    
	print "<option value=\"00900\" " . (($_GET['region_id']=='00900') ? "selected" : "") . ">Etienne Aigner</option>";    
	//print "<option value=\"00910\" " . (($_GET['region_id']=='00910') ? "selected" : "") . ">Etienne Aigner Whosale</option>";    
	print "<option value=\"00400\" " . (($_GET['region_id']=='00400') ? "selected" : "") . ">F.Biasia</option>";    
	print "<option value=\"01800\" " . (($_GET['region_id']=='01800') ? "selected" : "") . ">Ferragamo</option>";    
	print "<option value=\"02600\" " . (($_GET['region_id']=='02600') ? "selected" : "") . ">Furla</option>";    
	print "<option value=\"03400\" " . (($_GET['region_id']=='03400') ? "selected" : "") . ">Geox</option>";    
	print "<option value=\"02400\" " . (($_GET['region_id']=='02400') ? "selected" : "") . ">Giorgio Armani</option>";    
	//print "<option value=\"02000\" " . (($_GET['region_id']=='02000') ? "selected" : "") . ">HEEL</option>";    
	print "<option value=\"01100\" " . (($_GET['region_id']=='01100') ? "selected" : "") . ">Hugo Boss</option>";    
	//print "<option value=\"01120\" " . (($_GET['region_id']=='01120') ? "selected" : "") . ">Hugo Boss Wholesale</option>";    
	print "<option value=\"01110\" " . (($_GET['region_id']=='01110') ? "selected" : "") . ">Hugo Boss Woman</option>";    
	print "<option value=\"01200\" " . (($_GET['region_id']=='01200') ? "selected" : "") . ">Jimmy Choo</option>";    
	//print "<option value=\"02900\" " . (($_GET['region_id']=='02900') ? "selected" : "") . ">Kidz Store</option>";    
	print "<option value=\"03200\" " . (($_GET['region_id']=='03200') ? "selected" : "") . ">Little Marc Jacobs</option>";    
	//print "<option value=\"00500\" " . (($_GET['region_id']=='00500') ? "selected" : "") . ">Mango</option>";    
	//print "<option value=\"00510\" " . (($_GET['region_id']=='00510') ? "selected" : "") . ">Mango Touch</option>";    
	//print "<option value=\"01300\" " . (($_GET['region_id']=='01300') ? "selected" : "") . ">Miu miu</option>";    
	//print "<option value=\"01600\" " . (($_GET['region_id']=='01600') ? "selected" : "") . ">NC</option>";    
	//print "<option value=\"00300\" " . (($_GET['region_id']=='00300') ? "selected" : "") . ">Prada</option>";    
	print "<option value=\"01510\" " . (($_GET['region_id']=='01510') ? "selected" : "") . ">Red Valentino</option>";    
	print "<option value=\"00700\" " . (($_GET['region_id']=='00700') ? "selected" : "") . ">Tods</option>";    
	print "<option value=\"02100\" " . (($_GET['region_id']=='02100') ? "selected" : "") . ">Tommy Hilfiger</option>";    
	print "<option value=\"02110\" " . (($_GET['region_id']=='02110') ? "selected" : "") . ">Tommy Hilfiger Kids</option>";    
	//print "<option value=\"02200\" " . (($_GET['region_id']=='02200') ? "selected" : "") . ">Trans Chicks</option>";    
	//print "<option value=\"01700\" " . (($_GET['region_id']=='01700') ? "selected" : "") . ">TRANSSTUDIO</option>";    
	//print "<option value=\"01704\" " . (($_GET['region_id']=='01704') ? "selected" : "") . ">TRS - Andara</option>";    
	//print "<option value=\"01703\" " . (($_GET['region_id']=='01703') ? "selected" : "") . ">TRS - Harumi</option>";    
	//print "<option value=\"01702\" " . (($_GET['region_id']=='01702') ? "selected" : "") . ">TRS - Miranda</option>";    
	//print "<option value=\"01701\" " . (($_GET['region_id']=='01701') ? "selected" : "") . ">TRS - Sarinah</option>";    
	print "<option value=\"01500\" " . (($_GET['region_id']=='01500') ? "selected" : "") . ">Valentino</option>";    
	print "<option value=\"02710\" " . (($_GET['region_id']=='02710') ? "selected" : "") . ">Versace Collection</option>";    
	print "<option value=\"02700\" " . (($_GET['region_id']=='02700') ? "selected" : "") . ">Versace Jeans</option>";    
	print "<option value=\"02800\" " . (($_GET['region_id']=='02800') ? "selected" : "") . ">Versus</option>";    
	print "<option value=\"02720\" " . (($_GET['region_id']=='02720') ? "selected" : "") . ">Young Versace</option>";    

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

/*
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
*/

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

print "<option value=\"2015-01-31\" " . (($_GET['tanggal']=='2015-01-31') ? "selected" : "") . ">Januari 2015</option>";
print "<option value=\"2015-02-28\" " . (($_GET['tanggal']=='2015-02-28') ? "selected" : "") . ">Februari 2015</option>";
print "<option value=\"2015-03-31\" " . (($_GET['tanggal']=='2015-03-31') ? "selected" : "") . ">Maret 2015</option>";
print "<option value=\"2015-04-30\" " . (($_GET['tanggal']=='2015-04-30') ? "selected" : "") . ">April 2015</option>";
print "<option value=\"2015-05-31\" " . (($_GET['tanggal']=='2015-05-31') ? "selected" : "") . ">Mei 2015</option>";
print "<option value=\"2015-06-30\" " . (($_GET['tanggal']=='2015-06-30') ? "selected" : "") . ">Juni 2015</option>";
print "<option value=\"2015-07-31\" " . (($_GET['tanggal']=='2015-07-31') ? "selected" : "") . ">Juli 2015</option>";
print "<option value=\"2015-08-31\" " . (($_GET['tanggal']=='2015-08-31') ? "selected" : "") . ">Agustus 2015</option>";
print "<option value=\"2015-09-30\" " . (($_GET['tanggal']=='2015-09-30') ? "selected" : "") . ">September 2015</option>";
print "<option value=\"2015-10-31\" " . (($_GET['tanggal']=='2015-10-31') ? "selected" : "") . ">Oktober 2015</option>";
print "<option value=\"2015-11-30\" " . (($_GET['tanggal']=='2015-11-30') ? "selected" : "") . ">November 2015</option>";
print "<option value=\"2015-12-31\" " . (($_GET['tanggal']=='2015-12-31') ? "selected" : "") . ">Desember 2015</option>";


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
		
		$c = 0;
		print "<tr>";
		foreach ($COLNAMES as $data)
		{
		 	$colname = $data[0];
		 
			print "<td>";
			print $rs->fields[$colname];
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