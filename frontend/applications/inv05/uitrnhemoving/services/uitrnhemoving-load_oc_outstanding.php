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
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_id', 'heorder_id', " A.%s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_chk_masterdata_name', 'heorder_descr', " (A.heorder_id='{criteria_value}' OR A.{db_field} LIKE '%{criteria_value}%') ");

	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_region_id', 'region_id', " A.%s = '%s' ");
	SQLUTIL::BuildCriteria(&$SQL_CRITERIA, $DB_CRITERIA, 'obj_search_chk_rekanan_id', 'rekanan_id', " A.%s = '%s' ");
	
	
}



$sql = "

		BEGIN
		
				SET NOCOUNT ON
		
				SELECT 
				A.heorder_id, A.heorder_descr, A.heorder_date, A.season_id, A.rekanan_id, A.currency_id, B.price_id, B.price_isgenerated
				INTO #TEMP_ORDER_1
				FROM transaksi_heorder A left join transaksi_heinvprice B on B.heorder_id = A.heorder_id
				WHERE A.heorder_isposted=1 AND A.heorder_isclosed=0 
					AND $SQL_CRITERIA
				ORDER BY A.heorder_date DESC
				
				

				SELECT 
				A.heorder_id,
				B.heorderdetil_line,
				qty_order = SUM(B.C01+B.C02+B.C03+B.C04+B.C05+B.C06+B.C07+B.C08+B.C09+B.C10+B.C11+B.C12+B.C13+B.C14+B.C15+B.C16+B.C17+B.C18+B.C19+B.C20+B.C21+B.C22+B.C23+B.C24+B.C25),
				qty_recv  = isnull((SELECT SUM(C01+C02+C03+C04+C05+C06+C07+C08+C09+C10+C11+C12+C13+C14+C15+C16+C17+C18+C19+C20+C21+C22+C23+C24+C25) FROM transaksi_hemovingdetil WHERE ref_id=A.heorder_id AND ref_line=B.heorderdetil_line ), 0)
				INTO #TEMP_ORDER_2
				FROM
				#TEMP_ORDER_1 A INNER JOIN transaksi_heorderdetil B ON A.heorder_id=B.heorder_id
				GROUP BY A.heorder_id, B.heorderdetil_line
		
				SELECT 
				* ,
				qty_outstd = (qty_order-qty_recv)
				INTO #TEMP_ORDER_3
				FROM
				#TEMP_ORDER_2 
				WHERE (qty_order-qty_recv)>0
		
		
				SELECT 
				A.heorder_id,
				qty_order = (SELECT SUM(qty_order) FROM #TEMP_ORDER_2 WHERE heorder_id = A.heorder_id),
				qty_recv = (SELECT SUM(qty_recv) FROM #TEMP_ORDER_2 WHERE heorder_id = A.heorder_id),
				qty_outstd = SUM(A.qty_outstd)
				INTO #TEMP_ORDER_4
				FROM #TEMP_ORDER_3 A 
				GROUP BY 
				A.heorder_id
		
		
		
				SET NOCOUNT OFF
		
				SELECT
				heorder_id=A.heorder_id,
				heorder_descr=B.heorder_descr,
				heorder_date=B.heorder_date,
				season_id=B.season_id,
				currency_id=B.currency_id,
				rekanan_id=B.rekanan_id,
				rekanan_name = (SELECT rekanan_name FROM master_rekanan WHERE rekanan_id = B.rekanan_id),
				qty_order=A.qty_order,
				qty_recv=A.qty_recv,
				qty_outstd=A.qty_outstd,
				B.price_id,
				isnull(B.price_isgenerated, 0) as price_isgenerated
				FROM #TEMP_ORDER_4 A INNER JOIN #TEMP_ORDER_1 B ON A.heorder_id = B.heorder_id
		


				SET NOCOUNT ON;
		
				DROP TABLE #TEMP_ORDER_1;
				DROP TABLE #TEMP_ORDER_2;
				DROP TABLE #TEMP_ORDER_3;
				DROP TABLE #TEMP_ORDER_4;
		
		
		END

";




$data = array();
$rs = $conn->Execute($sql);
$totalCount = $rs->recordCount();
while (!$rs->EOF) {
	unset($obj);
	$obj->heorder_id = $rs->fields['heorder_id'];
	$obj->heorder_descr = str_replace('"', "", $rs->fields['heorder_descr']);
	$obj->heorder_date = SQLUTIL::SQLDateParseToStringdatesmall(trim($rs->fields['heorder_date']));
	$obj->season_id = trim($rs->fields['season_id']);
	$obj->currency_id = trim($rs->fields['currency_id']);
	$obj->rekanan_id = trim($rs->fields['rekanan_id']);	
	$obj->rekanan_name = trim($rs->fields['rekanan_name']);
	$obj->price_id = trim($rs->fields['price_id']);
	$obj->price_isgenerated = trim($rs->fields['price_isgenerated']);

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