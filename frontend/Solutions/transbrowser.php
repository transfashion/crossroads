<?php
require_once dirname(__FILE__).'/start.inc.php';

#[parameter]
$username = $_GET['username'];

/* PROCESS */
print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
print "<TransBrowser xmlns=\"http://tempuri.org/Template.xsd\">\n";
print "\t<Solutions>\n";

$sqlProgramType = "SELECT programtype_id, programtype_name, programtype_icon FROM master_programtype";
$rs = $conn->Execute($sqlProgramType);
while (!$rs->EOF) {

	$group_id = str_pad($rs->fields['programtype_id'], 3, "0", STR_PAD_LEFT);

	print "\t\t<".$rs->fields['programtype_name'].">\n";
	print "\t\t\t<Id>".$group_id."00000"."</Id>\n";
	print "\t\t\t<Icon>".$rs->fields['programtype_icon']."</Icon>\n";
	print "\t\t\t<Programs>\n";
	
	$sqlProgram = "EXEC sp_auth_userprogram '".$username."', ".$rs->fields['programtype_id'];
	$rsProgram  = $conn->Execute($sqlProgram);
	
	while (!$rsProgram->EOF) {

		$program_id 			= $group_id.str_pad($rsProgram->fields['program_id'], 5, "0", STR_PAD_LEFT);
      $program_title 		= $rsProgram->fields['program_title'];
      $program_icon 			= $rsProgram->fields['program_icon'];
      $program_ns       	= $rsProgram->fields['program_ns'];
      $program_dll      	= $rsProgram->fields['program_dll'];
      $program_instance 	= $rsProgram->fields['program_instance'];
      $program_parameter	= $rsProgram->fields['program_parameter'];
      $program_description	= $rsProgram->fields['program_description'];
      $program_isdisabled	= $rsProgram->fields['program_isdisabled'];

		print "\t\t\t\t<Program>\n";
		print "\t\t\t\t\t<Id>$program_id</Id>\n";
		print "\t\t\t\t\t<Title>$program_title</Title>\n";
		print "\t\t\t\t\t<Icon>$program_icon</Icon>\n";
		print "\t\t\t\t\t<Ns>$program_ns</Ns>\n";
		print "\t\t\t\t\t<Dll>$program_dll</Dll>\n";
		print "\t\t\t\t\t<Instance>$program_instance</Instance>\n";
		print "\t\t\t\t\t<Parameter>$program_parameter</Parameter>\n";
		print "\t\t\t\t\t<Description>$program_description</Description>\n";
		print "\t\t\t\t\t<Disabled>$program_isdisabled</Disabled>\n";
		print "\t\t\t\t</Program>\n";
      $rsProgram->MoveNext();
	}
	
	
	print "\t\t\t</Programs>\n";
	print "\t\t</".$rs->fields['programtype_name'].">\n";
	$rs->MoveNext();
}
print "\t</Solutions>\n";
print "\t<Groups>\n";

$sqlGroup = "EXEC sp_auth_usergroup '$username'";
$rsGroup  = $conn->Execute($sqlGroup);
while (!$rsGroup->EOF) {

	$group_id 				= str_pad($rsGroup->fields['group_id'], 5, "0", STR_PAD_LEFT);
	$group_name 			= $rsGroup->fields['group_name'];
	$group_description 	= $rsGroup->fields['group_description'];
	$group_isdisabled 	= $rsGroup->fields['group_isdisabled'] ? 0 : 1;

	print "\t\t<Group>\n";
	print "\t\t\t<Id>$group_id</Id>\n";
	print "\t\t\t<Name>$group_name</Name>\n";
	print "\t\t\t<Description>$group_description</Description>\n";
	print "\t\t\t<Disabled>0</Disabled>\n";
	print "\t\t\t<ShowAllPrograms>0</ShowAllPrograms>\n";
	print "\t\t\t<Programs>\n";
	
	$sqlGroupProgram = "EXEC sp_auth_groupprogram $group_id";
	$rsGroupProgram  = $conn->Execute($sqlGroupProgram);
	while (!$rsGroupProgram->EOF) {
		$program_id     = str_pad($rsGroupProgram->fields['program_id'], 5, "0", STR_PAD_LEFT);
		$programtype_id = str_pad($rsGroupProgram->fields['programtype_id'], 3, "0", STR_PAD_LEFT);
	   print "\t\t\t\t<Program><Id>".$programtype_id.$program_id."</Id></Program>\n";
      $rsGroupProgram->MoveNext();
	}
	print "\t\t\t</Programs>\n";
	print "\t\t</Group>\n";
	$rsGroup->MoveNext();
}
print "	</Groups>\n";
print "</TransBrowser>\n";
?>
