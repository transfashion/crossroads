<?php
$id =  $_GET['id'];
$criteria	= $_POST['criteria'];

$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'heinv_id', 'heinv_id', " %s = '%s' ");
}











$SQL = "SELECT heinv_id FROM master_heinv WHERE $SQL_CRITERIA";
$rs=$conn->execute($SQL);

$totalCount = $rs->recordcount();

IF ($totalCount==0)
{
 
 			$url = "http://webservice.transmahagaya.com/imghost/NOIMAGE.JPG";
 	
}
else
{
 

	$id = $rs->fields['heinv_id'];
	$str = dirname(__FILE__) . "/../../../../../../imghost/$id.jpg" ;

	
	if (is_file($str))
	{
	 			$url = "http://webservice.transmahagaya.com/imghost/$id.jpg"; 
	}
	else
	{

				$SQLL = "SELECT heinvitem_barcode FROM master_heinvitem WHERE $SQL_CRITERIA";
			 	$rsL=$conn->execute($SQLL);
				$totalCount = $rsL->recordcount(); 	
			
				if ($totalCount==0)
				{
				 	$url = "http://webservice.transmahagaya.com/imghost/NOIMAGE.JPG";
				 }
				else
				{

				 	 $id = $rsL->fields['heinvitem_barcode'];	 	 	  
                                         $str = dirname(__FILE__) . "/../../../../../../imghost/$id.jpg" ;
			 	 	 

                                         if (is_file($str))
                                        {
                                              $url = "http://webservice.transmahagaya.com/imghost/$id.jpg"; 

                                         }
                                         else
                                         {
$url = "http://webservice.transmahagaya.com/imghost/NOIMAGE.JPG";
                                         }

				}
				 
	}

 

	
	
	 
}

unset($obj);
$obj->url = $url; 
$data[] = $obj;
 

/*

IF (is_file(dirname(__FILE__) . "/../../imghost/$id.jpg"))
{
	$str = dirname(__FILE__) . "/images/$id.jpg";
	$url = "http://webservice.transmahagaya.com/imghost/$id.jpg";
}

ELSE

{
	$str = dirname(__FILE__) . "/images/NOIMAGE.jpg";
	$url = "http://webservice.transmahagaya.com/imghost/NOIMAGE.JPG";
}

<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<img src="<?=$url?>" width=168 height=150>
</body>
*/



$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));
?>

