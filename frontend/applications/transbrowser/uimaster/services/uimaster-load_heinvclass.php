<?
if (!defined('__SERVICE__')) {
	die("access denied");
}


$data = array(
	(object) array('heinvctg_class'=>'ACCESSORIES', 'heinvctg_classname'=>'ACCESSORIES'), 
	(object) array('heinvctg_class'=>'BAGS', 'heinvctg_classname'=>'BAGS'), 
	(object) array('heinvctg_class'=>'BELTS', 'heinvctg_classname'=>'BELTS'), 
	(object) array('heinvctg_class'=>'BLOUSE', 'heinvctg_classname'=>'BLOUSE'), 
	(object) array('heinvctg_class'=>'CONSUMABLE GOOD', 'heinvctg_classname'=>'CONSUMABLE GOOD'), 
	(object) array('heinvctg_class'=>'DRESS', 'heinvctg_classname'=>'DRESS'), 
	(object) array('heinvctg_class'=>'FRAGRANCE', 'heinvctg_classname'=>'FRAGRANCE'), 
	(object) array('heinvctg_class'=>'JEANS', 'heinvctg_classname'=>'JEANS'), 
	(object) array('heinvctg_class'=>'KNITWEAR', 'heinvctg_classname'=>'KNITWEAR'), 
	(object) array('heinvctg_class'=>'LUGGAGE', 'heinvctg_classname'=>'LUGGAGE'), 
	(object) array('heinvctg_class'=>'OUTWEAR', 'heinvctg_classname'=>'OUTWEAR'), 
	(object) array('heinvctg_class'=>'PANTS', 'heinvctg_classname'=>'PANTS'), 
	(object) array('heinvctg_class'=>'POLO', 'heinvctg_classname'=>'POLO'), 
	(object) array('heinvctg_class'=>'SHIRT', 'heinvctg_classname'=>'SHIRT'), 
	(object) array('heinvctg_class'=>'SHOES', 'heinvctg_classname'=>'SHOES'), 
	(object) array('heinvctg_class'=>'SKIRT', 'heinvctg_classname'=>'SKIRT'), 
	(object) array('heinvctg_class'=>'SLG', 'heinvctg_classname'=>'SLG'), 
	(object) array('heinvctg_class'=>'SUIT', 'heinvctg_classname'=>'SUIT'), 
	(object) array('heinvctg_class'=>'SPAREPART', 'heinvctg_classname'=>'SPAREPART'), 
	(object) array('heinvctg_class'=>'SWEATER', 'heinvctg_classname'=>'SWEATER'), 
	(object) array('heinvctg_class'=>'TROUSER', 'heinvctg_classname'=>'TROUSER'), 
	(object) array('heinvctg_class'=>'TSHIRT', 'heinvctg_classname'=>'TSHIRT'), 
	(object) array('heinvctg_class'=>'VDC', 'heinvctg_classname'=>'VDC'), 
	(object) array('heinvctg_class'=>'WATCH', 'heinvctg_classname'=>'WATCH'), 
	(object) array('heinvctg_class'=>'OTHER', 'heinvctg_classname'=>'OTHER'), 

);


/*
$sql = "SELECT * FROM master_region ORDER BY region_name";
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	
	unset($obj);
	$obj->region_id = $rs->fields['region_id'];
	$obj->region_name = $rs->fields['region_name'];
	$obj->region_nameshort = $rs->fields['region_nameshort'];
	$data[] = $obj;

	$rs->MoveNext();
}
*/


$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>
