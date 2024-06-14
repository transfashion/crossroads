<?
if (!defined('__SERVICE__')) {
	die("access denied");
}




$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];



$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria  */
	 SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_RV_id', 'hemoving_id', "refParser");
	 SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_region_id', 'region_id', " %s = '%s' ");
 
}



 
$sql = "

				SET NOCOUNT ON
					
				SELECT 
				A.hemoving_id,
				A.hemovingdetil_line,
				A.heinv_id,
				A.heinv_art,
				A.heinv_mat,
				A.heinv_col,
				A.heinv_name,
				B.season_id,
				B.heinvgro_id,
				B.heinvctg_id,
				B.region_id,
				heinv_firstprice = B.heinv_priceori,
				lastprice = B.heinv_price01,
				lastdisc = B.heinv_pricedisc01
				FROM transaksi_hemovingdetil A inner join master_heinv B on A.heinv_id = B.heinv_id
				WHERE $SQL_CRITERIA
	 

";



$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
 
 $i = 10;
while (!$rs->EOF) {
	unset($obj);
	$i = $i+10;
	
	$obj->hemoving_id = $rs->fields['hemoving_id'];
	$obj->hemovingdetil_line = $rs->fields['hemovingdetil_line'];
	$obj->pricedetil_line = $i;
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
	$obj->heinvgro_id = $rs->fields['heinvgro_id'];
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->season_id = $rs->fields['season_id'];
	
	$region_id = $rs->fields['region_id'];
	$heinvgro_id = $rs->fields['heinvgro_id'];
	$heinvctg_id = $rs->fields['heinvctg_id'];
	
 
	$SQLG = "SELECT heinvgro_name FROM master_heinvgro where region_id = '$region_id' and heinvgro_id ='$heinvgro_id'";
	$rsgro = $conn->execute($SQLG);
	$obj->heinvgro_name = $rsgro->fields['heinvgro_name'];
	

 
	$SQLC = "SELECT heinvctg_name FROM master_heinvctg where region_id = '$region_id' and heinvctg_id ='$heinvctg_id'";
	$rsctg = $conn->execute($SQLC);
	$obj->heinvctg_name = $rsctg->fields['heinvctg_name'];
	
 
		
	$obj->heinv_firstprice = 1*$rs->fields['heinv_firstprice'];
	$obj->heinv_lastprice = 1*$rs->fields['lastprice'];
	$obj->heinv_lastdisc = 1*$rs->fields['lastdisc'];
	$obj->heinv_price01 = 0;
	$obj->heinv_pricedisc01 = 0;
	
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