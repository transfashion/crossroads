<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$__NEWFILENAME 	 = $_POST['__NEWFILENAME'];
	$__OLDFILENAME 	 = $_POST['__OLDFILENAME'];
	

	try{


		$newFile = dirname(__FILE__)."/../../../../../../imghost/$__NEWFILENAME";
		$oldFile = dirname(__FILE__)."/../../../../../../imghost/$__OLDFILENAME";
		if(is_file($newFile))
		{                    
            
		 	unlink($newFile);
		}
		 
rename(dirname(__FILE__)."/../../../../../../imghost/$__OLDFILENAME",dirname(__FILE__)."/../../../../../../imghost/$__NEWFILENAME"); 		 	

 


		
		unset($obj);
		$obj->sukses = 1; 
		$data[] = $obj;
		
		
	} catch (exception $e) {
	 	$data=0;
	 	print $e->GetMessage();	
	 	
	}	
	
	
	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data = $data;
	unset($objResult->errors); 
			
	print(stripslashes(json_encode($objResult)));

?>