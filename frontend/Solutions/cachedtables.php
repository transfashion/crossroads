<?php
require_once dirname(__FILE__).'/start.inc.php';

$CachedTables = array(
	array("master_rekanan", "ms_MstRekanan_Select", ""),  			
	array("master_acc", "cp_MstAcc_Select", ""),  			
); 


print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
print "<TransBrowser xmlns=\"http://tempuri.org/Template.xsd\">\n";
print "\t<CachedTables>\n";

for ($i=0; $i<count($CachedTables); $i++) {
	$table = $CachedTables[$i];
	print "\t\t<Table>\n";
	print "\t\t\t<Name>".$table[0]."</Name>\n";
	print "\t\t\t<Procedure>".$table[1]."</Procedure>\n";
	print "\t\t\t<Criteria>".$table[2]."</Criteria>\n";
	print "\t\t</Table>\n";
}

print "\t</CachedTables>\n";
print "</TransBrowser>\n";
?>