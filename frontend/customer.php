<?php
require_once dirname(__FILE__).'/start.inc.php';


session_start();
if ($_GET['logout'])
{
	$_SESSION['weblogin'] = false;	 
	$_SESSION['fullname'] = ""; 
	print "you've logout.<br>";
	print "return to <a href=\"customer.php\">customer information</a>.";
	die(""); 
}


if ($_POST['username'])
{
	$username = trim($_POST['username']);
	$password = md5(trim($_POST['password']));
	
	$sql = "SELECT * FROM master_user WHERE username='$username' AND user_md5password='$password'"; 
	$rs  = $conn->Execute($sql);
	if ($rs->recordCount())
	{
		$_SESSION['weblogin'] = true;	 
		$_SESSION['fullname'] = $rs->fields['user_fullname'];
	}
	else
	{
	}
}

if (!$_SESSION['weblogin'])
{
	die("<html>
	<head>
	<script>
	location.href = '/crossroads/frontend/loginform.php?page=/crossroads/frontend/customer.php';
	</script>
	</head>			
	</html>"); 
}


$fullname = $_SESSION['fullname'];


$searchname = trim($_POST['searchcustomer']);
if ($searchname)
{
	die("<html>
	<head>
	<script>
	location.href = '/crossroads/frontend/customer.php?searchname=$searchname';
	</script>
	</head>			
	</html>");  
} 



?><html>
<head>
	<title>Customer Info</title>
	<style>
		table { font-family: verdana; font-size: 12px }
		input { background: #E1FFE1; border:1px #CCC solid; padding:5px; }	
		.rescolumn { border: 1px solid #EEEEEE; padding-top: 8px }
	</style>
</head>
<body>
<table width="100%">
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<tr>
<td width="150">Cari Nama Customer</td>
<td width="100"><input name="searchcustomer" value="<?=$_GET['searchname']?>"></td>
<td width="60"><input type="submit" value="search">
<td></td>
<td width="400" align="right"><b><?=$fullname?></b> | <a href="?logout=1">Log Out</a></td>
</tr>
</form>
</table>
<hr>
<? if ($_GET['searchname']) { ?> 
	<table cellpadding="0" cellspacing="0" style="border: 1px #DDDDDD solid">
		<tr>
			<td width="300" style="background-color: #DDDDDD"><b>Nama</b></td>
			<td width="500" style="background-color: #DDDDDD"><b>Alamat</b></td>
			<td width="200" style="background-color: #DDDDDD"><b>Telp</b></td>
		<tr>
		<?
		$searchname = trim($_GET['searchname']);
		if ($searchname) { 
			$sql = "select top 100 * from master_customer where customer_namefull like '%$searchname%' or customer_phone like '%$searchname%'";
			$rs  = $conn->Execute($sql);
			
			?>
			<? if (!$rs->recordCount()) { ?>
			<tr>
				<td colspan="3">Data tidak ditemukan</td>
			<tr>		
			<? 
			} 
			else 
			{ 
				while (!$rs->EOF) {
					$nama   = $rs->fields['customer_namefull'];
					$alamat = $rs->fields['customer_address'];
					$telp   = $rs->fields['customer_id']; 
					$id     = base64_encode($telp);
					
					if (strlen($telp)>8)
					{
						$telp_f = substr($telp, 0, 3);
						$telp_b = substr($telp, strlen($telp)-4, 4);
					}
					print "<tr>";
					print "<td><a href=\"?id=$id\">$nama</a></td>";
					print "<td>$alamat</td>";
					print "<td>$telp_f******$telp_b</td>";
					print "</tr>";
				
					$rs->MoveNext();
				}
		
			}
		}
		?>
	</table>
<? } ?>
<?
if ($id=$_GET['id']) {

	$id = base64_decode($id);
	$sql = "select * from master_customer where customer_id = '$id' ";	
	$rs  = $conn->Execute($sql);
	
	$nama   = $rs->fields['customer_namefull'];
	$alamat = $rs->fields['customer_address'];
	$telp   = $rs->fields['customer_id']; 
	
	print "<h2>$nama</h2>";
	print "Belanja";
	$sql = "select top 30 A.*, B.*,
			region_name = (select region_name from master_region where region_id=A.region_id),
	 		branch_name = (select branch_name from master_branch where branch_id=A.branch_id)
	        from transaksi_hepos A inner join transaksi_heposdetil B on A.bon_id = B.bon_id 
			where A.bon_isvoid=0 AND A.customer_id='$id' or A.customer_telp='$id' and B.bondetil_art not like 'TMCG%'
			order by A.bon_date DESC
			";
	
	$rs  = $conn->Execute($sql);
	print "<table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px #DDDDDD solid\"> ";
	print "<tr>";
	print "<td width=\"120\" style=\"background-color: #DDDDDD\"><b>Tanggal</b></td>";
	print "<td width=\"250\" style=\"background-color: #DDDDDD\"><b>Lokasi</b></td>";
	print "<td width=\"200\" style=\"background-color: #DDDDDD\"><b>ART</b></td>";
	print "<td width=\"100\" style=\"background-color: #DDDDDD\"><b>MAT</b></td>";
	print "<td width=\"100\" style=\"background-color: #DDDDDD\"><b>COL</b></td>";
	print "<td width=\"70\" style=\"background-color: #DDDDDD\" align=\"center\"><b>SIZE</b></td>";
	print "<td width=\"60\" style=\"background-color: #DDDDDD\" align=\"right\"><b>QTY</b></td>";
	print "<td width=\"100\" style=\"background-color: #DDDDDD\" align=\"right\"><b>GROSS</b></td>";
	print "<td width=\"100\" style=\"background-color: #DDDDDD\" align=\"right\"><b>NETT</b></td>";
	print "</tr>";	
	while (!$rs->EOF)
	{
		$bon_id = $rs->fields['bon_id'];
		$bon_date = $rs->fields['bon_date'];
		$region_name = $rs->fields['region_name'];
		$branch_name = $rs->fields['branch_name'];
		
		$art = $rs->fields['bondetil_art'];
		$mat = $rs->fields['bondetil_mat'];
		$col = $rs->fields['bondetil_col'];
		$size = $rs->fields['bondetil_size'];
		$qty = $rs->fields['bondetil_qty'];
		$gross = number_format($rs->fields['bondetil_mpricegross']);
		$nett = number_format($rs->fields['bondetil_mpricenett']);
		
		
		$time = strtotime($bon_date);
		$date = date("d/m/y h:i", $time);
		
		if (substr($art, 0, 5)!="TMCG-")
		{
			print "<tr>"; 
			print "<td class=\"rescolumn\">$date</td>"; 
			print "<td class=\"rescolumn\">$region_name - $branch_name</td>"; 
			print "<td class=\"rescolumn\">$art</td>"; 
			print "<td class=\"rescolumn\">$mat</td>"; 
			print "<td class=\"rescolumn\">$col</td>"; 
			print "<td class=\"rescolumn\">$size</td>"; 
			print "<td class=\"rescolumn\" align=\"right\">$qty</td>"; 
			print "<td class=\"rescolumn\" align=\"right\">$gross</td>"; 
			print "<td class=\"rescolumn\" align=\"right\">$nett</td>"; 
			print "</tr>";
		}
		
		$rs->MoveNext(); 
	}
	print "</table>";



} ?>
</body>
</html>

