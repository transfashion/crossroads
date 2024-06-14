<?
ob_start();
//var_dump($_FILES);
//---- STARTING -----------


if ($_FILES) {
	$file 		= $_FILES["file"];
	$name 		= $file["name"];
	$tmp_name 	= $file["tmp_name"];
	$size 		= $file["size"];
	
	
 
	try {
 
		copy( $tmp_name,  dirname(__FILE__)."/../../../../../../imghost/$name");

	} catch (exception $e) {
	 	print $e->GetMessage();	
	}	

}

//---- ENDING --------------
$cnts = ob_get_contents();
ob_end_clean();
$outputfile = dirname(__FILE__)."/output.txt";
$fp = fopen($outputfile, "w");
fputs($fp, $cnts);
fclose($fp);
?>