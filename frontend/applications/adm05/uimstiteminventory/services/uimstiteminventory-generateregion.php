<?
  
$region_id= $_GET['region_id'];
 



			/* Ambil semua region yang parent nya region_id*/
			$sql = "select * from master_region where region_id='$region_id'";
			$rs  = $conn->Execute($sql);
			$region_path = substr($rs->fields['region_path'],0,5);

			$sql = "select * from master_region where region_path like '$region_path%'";
			$rsR  = $conn->Execute($sql);
					
					
					
						
						
			$arrregions="";	
			while (!$rsR->EOF) {
					
				$arrregions .= "'" . $rsR->fields['region_id'] . "',";
				$rsR->MoveNext();
			}
			

 			 $arrregions = substr($arrregions,0,strlen( $arrregion) - 1);

			$sql =  "SELECT 
					region_id,
					region_name
					FROM master_region WHERE  region_id in (" . $arrregions . " ) ";

		
			$rs = $conn->Execute($sql);
			$totalCount = $rs->recordCount();

 		
			$data = array();
			while (!$rs->EOF) {
				unset($obj);
				$obj->region_id 	= $rs->fields['region_id'];
				$obj->region_name 	= $rs->fields['region_name'];
				$data[] = $obj;
				$rs->MoveNext();
			}


 
$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 
		
print(stripslashes(json_encode($objResult)));

?>
