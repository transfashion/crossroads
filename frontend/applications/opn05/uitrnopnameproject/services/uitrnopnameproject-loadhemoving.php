<?
if (!defined('__SERVICE__')) {
	die("access denied");
}


$username 	= $_SESSION["username"];
$criteria 	= $_POST['criteria'];


$SQL_CRITERIA = "";
$objCriteria = json_decode(stripslashes($criteria));
if (is_array($objCriteria)) {
	$criteria = array();
	while (list($name, $value) = each($objCriteria)) {
		$criteria[$value->name] = $value;
		//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}

		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_startdate', 'A.hemoving_date_fr', " convert(varchar(10),hemoving_date,120) >=convert(varchar(10), '{criteria_value}',120) ");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_chk_enddate', 'A.hemoving_date_fr', " convert(varchar(10),hemoving_date,120) <=convert(varchar(10), '{criteria_value}',120) ");
		SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'obj_search_txt_region_id', 'A.region_id', " %s = '%s' ");
		$tipeMoving = SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $criteria, 'cmbMovingType', 'A.hemovingtype_id', " %s = '%s' ");
 	


}

if (trim(substr($tipeMoving,22,2))=='TR');
{
			if ($SQL_CRITERIA) {
				$sql = "SELECT distinct A.hemoving_id,A.hemoving_date,A.hemoving_descr from transaksi_hemoving A inner join transaksi_hemovingdetil B on A.hemoving_id = B.hemoving_id WHERE   $SQL_CRITERIA ORDER BY hemoving_id DESC";
			} else {
				$sql = "SELECT distinct A.hemoving_id,A.hemoving_date,A.hemoving_descr from transaksi_hemoving A inner join transaksi_hemovingdetil B on A.hemoving_id = B.hemoving_id  ORDER BY hemoving_id DESC";
			}
	
}
if (trim(substr($tipeMoving,22,2))=='RV');
{
  
			if ($SQL_CRITERIA) {
				$sql = "SELECT distinct A.hemoving_id,A.hemoving_date,A.hemoving_descr from transaksi_hemoving A inner join transaksi_hemovingdetil B on A.hemoving_id = B.hemoving_id WHERE A.hemoving_issend=1  AND A.hemoving_isrecv=0 AND $SQL_CRITERIA ORDER BY hemoving_id DESC";
			} else {
				$sql = "SELECT distinct A.hemoving_id,A.hemoving_date,A.hemoving_descr from transaksi_hemoving A inner join transaksi_hemovingdetil B on A.hemoving_id = B.hemoving_id WHERE A.hemoving_issend=1 AND A.hemoving_isrecv=0 ORDER BY hemoving_id DESC";
			}

  }




$rs = $conn->Execute($sql);

 

$totalCount = $rs->recordCount();

$data = array();
while (!$rs->EOF) {
	unset($obj);

	$obj->selected = 0;
	$obj->hemoving_id = $rs->fields['hemoving_id'];

	$hemoving_id = $rs->fields['hemoving_id'];
	$obj->hemoving_date = $rs->fields['hemoving_date'];
	$obj->hemoving_descr = $rs->fields['hemoving_descr'];
	
	$SQLQTY = "
	SELECT total_qty  = SUM(B01+B02+B03+B04+B05+B06+B07+B08+B09+B10+B11+B12+B13+B14+B15+B16+B17+B18+B19+B20+B21+B22+B23+B24+B25)
	FROM transaksi_hemovingdetil WHERE hemoving_id = '$hemoving_id'";
	$rsQty = $conn->execute($SQLQTY);
	
	$obj->total_qty = $rsQty->fields['total_qty'];
	
//print $rsQty->fields['total_qty'];;
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