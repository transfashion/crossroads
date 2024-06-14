<?php
require_once dirname(__FILE__).'/start.inc.php';

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
print "<TransBrowser xmlns=\"http://tempuri.org/Template.xsd\">\n";
print "\t<Configurations>\n";
print "\t\t<Datastore>\n";
print "\t\t\t<Server>".$db[host]."</Server>\n";
print "\t\t\t<Catalog>".$db[name]."</Catalog>\n";
print "\t\t\t<UID>".$db[user]."</UID>\n";
print "\t\t\t<PWD>".$db[pass]."</PWD>\n";
print "\t\t</Datastore>\n";
print "\t\t<Debug>\n";
print "\t\t\t<InstanceDirect>".$InstanceDirect."</InstanceDirect>\n";
print "\t\t</Debug>\n";
print "\t</Configurations>\n";
print "</TransBrowser>\n";
?>