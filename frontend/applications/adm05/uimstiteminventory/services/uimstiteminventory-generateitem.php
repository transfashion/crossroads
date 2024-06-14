<?
  
$region_id= $_GET['region_id'];
$opnametype= $_GET['opnametype'];


$opnameproject_descr= strtolower(stripslashes(str_replace(array('–', '"', "'", "\\"), array('-', '','',''),$opnametype)));

IF (strtolower($opnameproject_descr)!='daily')
{

			/* Ambil semua region yang parent nya region_id*/
			$sql = "select * from master_region where region_id='$region_id'";
			$rs  = $conn->Execute($sql);
			$region_path = $rs->fields['region_path'];
			$sql = "select * from master_region where region_path like '$region_path%'";
			$rs  = $conn->Execute($sql);
			$totalCount = $rs->recordCount();
			
			$arrregions="";
			while (!$rs->EOF) {
			
				IF ($arrregions=="")
					{

						$arrregions= "region_id='".$rs->fields['region_id']."'";
  					   
				 	}
				else
				 	{
					    $arrregions = $arrregions. " OR region_id='".$rs->fields['region_id']."' ";
					 
					}
			
				
				$rs->MoveNext();
			}
				 

					$sql =  "SELECT 
					item_id = iteminventory_id,
					item_name = iteminventory_name,
					barcode = iteminventory_factorycode,
					article = iteminventory_article,
					material_id =iteminventory_material,
					material_name = '',
					color_id = iteminventory_color,
					color_name = (select iteminventorycolor_name FROM master_iteminventorycolor WHERE region_id = master_iteminventory.region_id AND iteminventorycolor_id = master_iteminventory.iteminventory_color),
					size_id = iteminventory_size,
					size_name =(select iteminventorysize_name FROM master_iteminventorysize WHERE region_id = master_iteminventory.region_id AND iteminventorysize_id = master_iteminventory.iteminventory_size),
					group_id = iteminventorygroup_id,
					group_name =(select iteminventorygroup_name FROM master_iteminventorygroup WHERE region_id = master_iteminventory.region_id AND iteminventorygroup_id = master_iteminventory.iteminventorygroup_id),
					subgroup_id = iteminventorysubgroup_id,
					subgroup_name = (select iteminventorysubgroup_name FROM master_iteminventorysubgroup WHERE region_id = master_iteminventory.region_id AND iteminventorygroup_id = master_iteminventory.iteminventorygroup_id AND iteminventorysubgroup_id = master_iteminventory.iteminventorysubgroup_id),
					item_price = iteminventory_sellpricedefault
					FROM master_iteminventory WHERE  $arrregions ";
			 
}

else

{					
					$sql =  "SELECT 
					item_id = B.iteminventory_id,
					item_name = iteminventory_name,
					barcode = iteminventory_factorycode,
					article = iteminventory_article,
					material_id =iteminventory_material,
					material_name = '',
					color_id = iteminventory_color,
					color_name = (select iteminventorycolor_name FROM master_iteminventorycolor WHERE region_id = B.region_id AND iteminventorycolor_id = B.iteminventory_color),
					size_id = iteminventory_size,
					size_name =(select iteminventorysize_name FROM master_iteminventorysize WHERE region_id = B.region_id AND iteminventorysize_id = B.iteminventory_size),
					group_id = iteminventorygroup_id,
					group_name =(select iteminventorygroup_name FROM master_iteminventorygroup WHERE region_id = B.region_id AND iteminventorygroup_id = B.iteminventorygroup_id),
					subgroup_id = iteminventorysubgroup_id,
					subgroup_name = (select iteminventorysubgroup_name FROM master_iteminventorysubgroup WHERE region_id = B.region_id AND iteminventorygroup_id = B.iteminventorygroup_id AND iteminventorysubgroup_id = B.iteminventorysubgroup_id),
					item_price = iteminventory_sellpricedefault
					FROM E_FRM2_MGP_OPNAME.dbo.master_dailyopname A 
					INNER JOIN master_iteminventory B ON A.iteminventory_id = B.iteminventory_id 
					WHERE A.iteminventory_isdisabled = 0	";

}


			$rs = $conn->Execute($sql);
			$totalCount = $rs->recordCount();
			
 			$data = array();
			while (!$rs->EOF) {
				unset($obj);
				$obj->item_id 				= $rs->fields['item_id'];
				$obj->item_name 			= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['item_name']);
				$obj->barcode 				= $rs->fields['barcode'];
				$obj->article 				= $rs->fields['article'];				
				$obj->material_id 			= $rs->fields['material_id'];
				$obj->material_name 		= $rs->fields['material_name'] ? $rs->fields['material_name'] : $rs->fields['material_id'];
				$obj->color_id 				= $rs->fields['color_id'];
				$obj->color_name 			= $rs->fields['color_name'] ? $rs->fields['color_name'] : $rs->fields['color_id'];
				$obj->size_id 				= $rs->fields['size_id'];
				$obj->size_name 			= $rs->fields['size_name'] ? $rs->fields['size_name'] : $rs->fields['size_id'];
				$obj->group_id 				= $rs->fields['group_id'];
				$obj->group_name 			= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['group_name'] ? $rs->fields['group_name'] : $rs->fields['group_id']); 
				$obj->subgroup_id 			= $rs->fields['subgroup_id'];
				$obj->subgroup_name 		= str_replace(array('–', '"', "'", "\\"), array('-', '','',''), $rs->fields['subgroup_name'] ? $rs->fields['subgroup_name'] : $rs->fields['subgroup_id']);
				$obj->item_price			= 1*$rs->fields['item_price'];
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
