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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_price_id', 'price_id', " %s = '%s' ");
	
}




$sql = "

		BEGIN
		
				SET NOCOUNT ON
					
			 select heinv_id,heinv_art,heinv_mat,heinv_col,heinv_name,heinvctg_id,heinv_lastprice,heinv_lastdisc,
heinv_price01,heinv_pricedisc01,
heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=transaksi_heinvpricedetil.heinvctg_id) 
 
from transaksi_heinvpricedetil WHERE $SQL_CRITERIA
		
		END

";
$i=0;


$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {

	unset($obj);
	$i++;
	$obj->pricedetil_line = $i;
	$obj->heinv_id = $rs->fields['heinv_id'];
	$obj->heinv_art = $rs->fields['heinv_art'];
	$obj->heinv_mat = $rs->fields['heinv_mat'];
	$obj->heinv_col = $rs->fields['heinv_col'];
	$obj->heinv_name = $rs->fields['heinv_name'];
	$obj->heinvctg_id = $rs->fields['heinvctg_id'];
	$obj->heinv_lastprice = 1*$rs->fields['heinv_lastprice'];
	$obj->heinv_lastdisc = 1*$rs->fields['heinv_lastdisc'];
	$obj->heinv_price01 = 1*$rs->fields['heinv_price01'];
	$obj->heinv_pricedisc01 = 1*$rs->fields['heinv_pricedisc01'];
	$obj->heinv_isSP = 1*$rs->fields['heinv_isSP'];
	$obj->heinv_sizetag = 1*$rs->fields['heinv_sizetag'];
	$sqlS = "SELECT season_id FROM master_heinv WHERE heinv_id = '$obj->heinv_id'";
	$rsS = $conn->Execute($sqlS);
	$obj->season_id = $rsS->fields['season_id'];
	
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