<?php
require_once dirname(__FILE__).'/start.inc.php';

print "<HTML>\r\n";
print "<HEAD>\r\n";
print "<meta http-equiv=\"refresh\" content=\"60\" >\r\n";
print "<TITLE>DAILY SALES REPORT</TITLE>\r\n";
print "<style>\r\n";
print ".bgheader { font-family: verdana; font-size: 12px; color: #FFFFFF;}\r\n";
print ".bgdata { font-family: verdana; font-size: 12px; color: #000000;}\r\n";
print "\r\n";
print "</style>\r\n";
print "</HEAD>\r\n";
print "<BODY>\r\n";
print "<h2>Daily Sales Report</h2>";


$tanggal = date("d M Y");
$jam     = date("H:i");
$pilihtanggal = date("Y-m-d");



$pilihtanggal = date("Y-m-d");

$sql = "
			declare @startdate as smalldatetime;
			declare @enddate as smalldatetime;
			
			SET @startdate = '$pilihtanggal'
			SET @enddate   = '$pilihtanggal'

			SELECT 
			  region_id=A.region_id,
			  region_name=(select region_name FROM master_region where region_id=A.region_id), 
			  qty   = SUM(A.bon_itemqty),
			  gross = SUM(A.bon_msubtotal), 
			  nett = SUM(A.bon_mtotal) 
			FROM transaksi_hepos A 
			WHERE
				   A.bon_isvoid = 0
				AND convert(varchar(10),A.bon_date,120)>=convert(varchar(10),@startdate,120)
				AND convert(varchar(10),A.bon_date,120)<=convert(varchar(10),@enddate,120)
			GROUP BY  A.region_id  
";




try 
{
	$rs = $conn->Execute($sql);
}
catch (exception $e)
{
 	die('koneksi sibuk, coba lagi beberapa saat...');
}


print "Per Tanggal $tanggal jam $jam<br>";

print "<table cellpadding=\"4\" cellspacing=\"0\" style=\"border: 1px solid #666666\">";
print "<tr>";
print "<td bgcolor=\"#000000\"             class=\"bgheader\" width=\"150\"><b>Region</b></td>";
print "<td bgcolor=\"#000000\" align=right class=\"bgheader\" width=\"50\"><b>Qty</b></td>";
//print "<td bgcolor=\"#000000\" align=right class=\"bgheader\" width=\"100\"><b>Gross</b></td>";
print "<td bgcolor=\"#000000\" align=right class=\"bgheader\" width=\"100\"><b>Nett</b></td>";
print "</tr>";


while (!$rs->EOF)
{
 	$region_id   = $rs->fields['region_id'];
 	$region_name = $rs->fields['region_name'];
 	$qty         = (float)$rs->fields['qty'];
	$gross       = (float)$rs->fields['gross'];
 	$nett        = (float)$rs->fields['nett'];
 
 	$TOTAL_QTY  += $qty;
 	$TOTAL_GROSS+= $gross;
 	$TOTAL_NETT += $nett;
 
 	$i++;
 	$bgcolor = ($i%2==0) ? "#FFFFFF" : "#DDDDDD";
 
 	if ($_GET['detil']=="1" && $_GET['region_id']==$region_id)
 	{
		$detil = "0";
 	}
	else
	{
		$detil = "1";	
	}
	
	
	print "<tr>";
	print "<td bgcolor=$bgcolor align=left  class=\"bgdata\"><a href=\"?detil=$detil&region_id=$region_id\">$region_name</a></td>";
	print "<td bgcolor=$bgcolor align=right class=\"bgdata\">".number_format($qty)."</td>";
	//print "<td bgcolor=$bgcolor align=right class=\"bgdata\">".number_format($gross)."</td>";
	print "<td bgcolor=$bgcolor align=right class=\"bgdata\">".number_format($nett)."</td>";
	print "</tr>";	 

	if ($_GET['detil']=="1" && $_GET['region_id']==$region_id)
	{




		$sqlbranch = "
					declare @startdate as smalldatetime;
					declare @enddate as smalldatetime;
					
					SET @startdate = '$pilihtanggal'
					SET @enddate   = '$pilihtanggal'
		
					SELECT 
					  branch_name=(select branch_name FROM master_branch where branch_id=A.branch_id), 
					  qty   = SUM(A.bon_itemqty),
					  gross = SUM(A.bon_msubtotal), 
					  nett = SUM(A.bon_mtotal) 
					FROM transaksi_hepos A 
					WHERE
						   A.bon_isvoid = 0
						AND region_id='$region_id'   
						AND convert(varchar(10),A.bon_date,120)>=convert(varchar(10),@startdate,120)
						AND convert(varchar(10),A.bon_date,120)<=convert(varchar(10),@enddate,120)
					GROUP BY  A.branch_id  
		";

	 
	 	try 
		{
			$rsBranch = $conn->Execute($sqlbranch);
		}
		catch (exception $e)
		{
		 	die('koneksi sibuk, coba lagi beberapa saat...');
		}
	 
	 
	 	while (!$rsBranch->EOF)
	 	{
		 	$branch_name  = $rsBranch->fields['branch_name'];
		 	$bqty         = (float)$rsBranch->fields['qty'];
			$bgross       = (float)$rsBranch->fields['gross'];
		 	$bnett        = (float)$rsBranch->fields['nett'];	 	 
			print "<tr>";
			print "<td bgcolor=#FFFFCC align=right class=\"bgdata\">$branch_name</td>";
			print "<td bgcolor=#FFFFCC align=right class=\"bgdata\">".number_format($bqty)."</td>";
			//print "<td bgcolor=#FFFFCC align=right class=\"bgdata\">".number_format($bgross)."</td>";
			print "<td bgcolor=#FFFFCC align=right class=\"bgdata\">".number_format($bnett)."</td>";
			print "</tr>";	
			$rsBranch->MoveNext();
	 	}
	}
	


	$rs->MoveNext(); 
}



print "<tr>";
print "<td bgcolor=\"#000000\" align=left  class=\"bgheader\" ><b>TOTAL</b></td>";
print "<td bgcolor=\"#000000\" align=right class=\"bgheader\" >".number_format($TOTAL_QTY)."</td>";
//print "<td bgcolor=\"#000000\" align=right class=\"bgheader\" >".number_format($TOTAL_GROSS)."</td>";
print "<td bgcolor=\"#000000\" align=right class=\"bgheader\" >".number_format($TOTAL_NETT)."</td>";
print "</tr>";


print "</table>";


print "</BODY>";
print "</HTML>";

?>