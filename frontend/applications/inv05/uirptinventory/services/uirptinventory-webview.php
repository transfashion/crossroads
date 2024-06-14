<?php

$webpage = $_POST['webpage'];
$iteminventory_id = $_POST['iteminventory_id'];
$datestart = $_POST['datestart'];
$dateend = $_POST['dateend'];
$coverage = $_POST['coverage'];
$region_id = $_POST['region_id'];
$branch_id = $_POST['branch_id'];


//print "$iteminventory_id  - $datestart - $dateend - $coverage - $region_id - $branch_id";

if ($coverage=='REGION') {
	$sql = "EXEC inv05_RptSummaryInvPerItem_SelectAllBranchDetil '$iteminventory_id', '|', '$datestart', '$dateend', 0 ";
} else {
	$sql = "EXEC inv05_RptSummaryInvPerItem_SelectMultiByBranchDetil '$iteminventory_id', '|', '$datestart', '$dateend'";
}


$rs = $conn->Execute($sql);
$total = 0;


print "<table width=\"100%\" cellspacing=0 style=\"font-family: arial; font-size: 10pt \">";
print "<tr>";
print "<td>";
print $rs->fields['iteminventory_id']."<br>\n";
print "<b>".$rs->fields['iteminventory_name']."</b><br>\n";
print "Color: ".$rs->fields['color_name']."&nbsp;&nbsp;&nbsp;Size:".$rs->fields['size_name']."<br>\n";
print "</td>";
print "</tr>";
print "</table>";
print "<br>";

print "<table width=\"100%\" cellspacing=0 style=\"font-family: arial; font-size: 8pt \">";
while (!$rs->EOF) {
	$sql = "select branch_name from master_branch where branch_id='".$rs->fields['branch_id']."'";
	$rsB = $conn->Execute($sql);

	$style = "style=\"border-bottom: 1px #CCCCCC solid\"";
	print "<tr $style>";
	print "<td $style>&nbsp;".$rs->fields['name']."</td>";
	print "<td $style>".$rsB->fields['branch_name']."</td>";
	print "<td $style>&nbsp;<a href='open:".$rs->fields['id'].",".$rs->fields['line']."'>".$rs->fields['id']."</a></td>";
	print "<td $style>&nbsp;".$rs->fields['line']."</td>";
	print "<td $style align=right widht=\"75\">".$rs->fields['QTY']."</td>";
	print "<tr>\n";
	
	$total += 1* $rs->fields['QTY'];
	$rs->MoveNext();
}


print "<tr>";
print "<td>&nbsp;</td>";
print "<td>&nbsp;</td>";
print "<td>&nbsp;</td>";
print "<td><B>TOTAL</B></td>";
print "<td align=right style=\"border-top: 1px black solid\"><b>".$total."<b></td>";
print "<tr>\n";
	
print "</table>";


?>