<?
ob_start();
//var_dump($_FILES);
//---- STARTING -----------


if ($_FILES) {
	$file 		= $_FILES["file"];
	$name 		= $file["name"];
	$tmp_name 	= $file["tmp_name"];
	$size 		= $file["size"];
	

	print "uploading <u>$name</u> into <u>$tmp_name</u><br>\r\n";

	try {
		copy( $tmp_name,  dirname(__FILE__)."/../../data/test/$name");
	} catch (exception $e) {
	 	print $e->GetMessage();	
	}	

}

//---- ENDING --------------
$cnts = ob_get_contents();
ob_end_clean();
$outputfile = dirname(__FILE__)."/outputtest.html";
$fp = fopen($outputfile, "w");
fputs($fp, $cnts);
fclose($fp);
?>