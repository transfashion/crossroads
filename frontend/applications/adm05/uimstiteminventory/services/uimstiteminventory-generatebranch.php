<?
  
$region_id= $_GET['region_id'];
 



			/* Ambil semua region yang parent nya region_id*/
			$sql = "select * from master_region where region_id='$region_id'";
			$rs  = $conn->Execute($sql);
			$region_path = substr($rs->fields['region_path'],0,5);
			$sql = "select * from master_region where region_path like '$region_path%'";
			$rs  = $conn->Execute($sql);
			
			$arrregions="";
			while (!$rs->EOF) {
			
				IF ($arrregions=="")
					{
						$arrregions= "region_id='".$rs->fields['region_id']."'";
				 	}
				else
				 	{
					    $arrregions += " OR region_id='".$rs->fields['region_id']."'";
					}
			
				
				$rs->MoveNext();
			}
 

			$sql =  "SELECT 
					branch_id,
					branch_name
					FROM master_branch ";



			$rs = $conn->Execute($sql);
			$totalCount = $rs->recordCount();
			
 
			$data = array();
			while (!$rs->EOF) {
				unset($obj);
				$obj->branch_id 	= $rs->fields['branch_id'];
				$obj->branch_name 	= $rs->fields['branch_name'];
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
