<?php

//---- STARTING -----------
 
     
if (!defined('__SERVICE__')) 
{
	die("access denied");
}


$__year = $_POST['__year'];
$__branch_name = $_POST['__branch_name'];

if ($_FILES) {
	$file 		= $_FILES["file"];
	$name 		= $file["name"];
	$tmp_name 	= $file["tmp_name"];
	$size 		= $file["size"];
	
 
 
	try {
      
		copy( $tmp_name,  dirname(__FILE__). "/../../../../../../data/test/mango/$__year/$__branch_name/$name");

		unset($obj);
		$obj->sukses = 1; 
		$data[] = $obj;

	} catch (exception $e) {
		$data=0;
	 	print $e->GetMessage();	
	}	
 
}

	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

 
 

/*
//---- ENDING --------------
$cnts = ob_get_contents();

$outputfile = dirname(__FILE__)."/output.txt";
$fp = fopen($outputfile, "w");
fputs($fp, $cnts);
fclose($fp);
*/



?>