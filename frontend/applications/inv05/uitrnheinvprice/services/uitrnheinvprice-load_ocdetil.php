<?
if (!defined('__SERVICE__')) {
	die("access denied");
}




$username 	= $_SESSION["username"];
$criteria	= $_POST['criteria'];



$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$DB_CRITERIA = array();
	while (list($name, $value) = each($objCriteria)) {
		$DB_CRITERIA[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
	}
	
	/* Default Criteria  */
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_heorder_id', 'A.heorder_id', " %s = '%s' ");
	
}




$sql = "

		BEGIN
		
				SET NOCOUNT ON
					
				SELECT A.* 
				INTO #TEMP1 
				FROM transaksi_heorder A
				 WHERE $SQL_CRITERIA
				
				
				SELECT 
				A.heorder_id,
				B.heorderdetil_line,
				A.region_id,
				B.heinv_id,
				B.heinv_art,
				B.heinv_mat,
				B.heinv_col,
				B.heinv_name
				INTO #TEMP2
				FROM #TEMP1 A inner join transaksi_heorderdetil B on A.heorder_id = B.heorder_id
				

				SELECT A.heorder_id,A.heorderdetil_line,A.region_id,A.heinv_id,A.heinv_art,A.heinv_mat,A.heinv_col,A.heinv_name,
				B.heinvgro_id,
				B.heinvctg_id,
				B.heinv_priceori,
				last_price = (B.heinv_price01),
				last_disc = (B.heinv_pricedisc01)
				INTO #TEMP3 
				FROM #TEMP2 A INNER JOIN master_heinv B on A.heinv_id = B.heinv_id
				
				
				SELECT 
				heorder_id,heorderdetil_line,region_id,heinv_id,heinv_art,heinv_mat,heinv_col,heinv_name,
				heinvgro_id,
				heinvctg_id,
				group_name = (select heinvgro_name FROM master_heinvgro WHERE heinvgro_id=#TEMP3.heinvgro_id AND region_id = #TEMP3.region_id),
				ctg_name = (select heinvctg_name FROM master_heinvctg WHERE heinvctg_id=#TEMP3.heinvctg_id AND region_id = #TEMP3.region_id),
				heinv_priceori,
				last_price,
				last_disc,
				heinv_price01=0,
				heinv_pricedisc01=0
				FROM #TEMP3
				
				
				DROP TABLE #TEMP1
				DROP TABLE #TEMP2
				DROP TABLE #TEMP3
		
		END

";



$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->heorder_id = $rs->fields['heorder_id'];
	$obj->heorderdetil_line = $rs->fields['heorderdetil_line'];
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
	$obj->heinvgro_id = $rs->fields['heinvgro_id'];
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->group_name = $rs->fields['group_name'];
	$obj->ctg_name = $rs->fields['ctg_name'];
	$obj->heinv_lastprice = 1*$rs->fields['last_price'];
	$obj->heinv_lastdisc = 1*$rs->fields['last_disc'];
	$obj->heinv_price01 = 1*$rs->fields['heinv_price01'];
	$obj->heinv_pricedisc01 = 1*$rs->fields['heinv_pricedisc01'];
	$obj->heinv_firstprice = 1*$rs->fields['heinv_priceori'];
	
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