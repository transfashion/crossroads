<?php

$webpage = $_POST['webpage'];
$id = $_POST['id'];
$line = $_POST['line'];
$datestart = $_POST['datestart'];
$dateend = $_POST['dateend'];
$coverage = $_POST['coverage'];
$region_id = $_POST['region_id'];
$branch_id = $_POST['branch_id'];


$sql = "select * from transaksi_inventorymoving where inventorymoving_id='$id'";
$rs  = $conn->Execute($sql);
$descr = $rs->fields['inventorymoving_descr'];

$style = "padding:'0 0 0 10'; border-bottom:'1px #CCCCCC solid';";
print "<table width=\"100%\" cellspacing=0 style=\"font-family: Verdana; font-size: 8pt \">";
print "<tr>";
print "<td width:\"60\" align=right><b>ID</b></td>  <td style=\"$style\"><b>".$id."</b></td>";
print "</tr>\n";

print "<tr>";
print "<td align=right><b>Tanggal</b></td>  <td style=\"$style\">&nbsp;</td>";
print "</tr>\n";

print "<tr>";
print "<td align=right><b>Descr</b></td>  <td style=\"$style\"><i>$descr</i></td>";
print "</tr>\n";

print "<tr>";
print "<td>&nbsp;</td>  <td></td>";
print "</tr>\n";


print "</table>";
print "<br>";



$sql = "select * from transaksi_inventorymovingdetil where inventorymoving_id='$id'";
$rs  = $conn->Execute($sql);
print "<table width=\"100%\" cellspacing=0 style=\"font-family: Verdana; font-size: 8pt \">";
$style = "style=\"border-bottom: '1px #EFEFEF solid'; padding:'0 0 0 10'; vertical-align: top; color: '#FFFFFF'; background-color: '#666666'\"";
print "<tr>";
print "<td $style align=right><b>Line</b></td>";
print "<td $style><b>ItemID</b></td>";
print "<td $style><b>Descr</b></td>";
print "<td $style><b>Qty</b></td>";
print "</tr>";

while (!$rs->EOF) {
	if ($rs->fields['inventorymovingdetil_line']==$line) {
		$backcol = "#CCCCCC";
	} else {
		$backcol = "#FFFFFF";

	}

	$style = "style=\"border-bottom: '1px #EFEFEF solid'; padding:'0 0 0 10'; vertical-align: top; background-color: '$backcol'\"";
	print "<tr>";
	print "<td $style align=right>".$rs->fields['inventorymovingdetil_line'].".</td>";
	print "<td $style>".$rs->fields['iteminventory_id']."</td>";
	print "<td $style>".$rs->fields['inventorymovingdetil_descr']."</td>";
	print "<td $style align=right>".$rs->fields['inventorymovingdetil_qty']."</td>";
	print "</tr>";
	$rs->MoveNext();
}
print "</table>";




?>