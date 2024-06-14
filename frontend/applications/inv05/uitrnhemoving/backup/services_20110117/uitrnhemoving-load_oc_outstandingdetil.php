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
		
		
				SELECT 
				heorder_id, heorder_descr, heorder_date, season_id, rekanan_id, currency_id
				INTO #TEMP_ORDER_1
				FROM transaksi_heorder A 
				WHERE $SQL_CRITERIA 
				ORDER BY heorder_date DESC
		
			
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
		
		
				
				-- ORDER YANG SUDAH DITERIMA
				SELECT 
				heorder_id=A.ref_id,
				heorderdetil_line=A.ref_line,
				A.heinv_id,
				heinv_price=0,
				C01=-SUM(C01),C02=-SUM(C02),C03=-SUM(C03),C04=-SUM(C04),C05=-SUM(C05),C06=-SUM(C06),C07=-SUM(C07),C08=-SUM(C08),C09=-SUM(C09),C10=-SUM(C10),C11=-SUM(C11),C12=-SUM(C12),C13=-SUM(C13),C14=-SUM(C14),C15=-SUM(C15),C16=-SUM(C16),C17=-SUM(C17),C18=-SUM(C18),C19=-SUM(C19),C20=-SUM(C20),C21=-SUM(C21),C22=-SUM(C22),C23=-SUM(C23),C24=-SUM(C24),C25=-SUM(C25) 
				INTO #TEMP_ORDER_4
				FROM transaksi_hemovingdetil A INNER JOIN #TEMP_ORDER_3 B 
				ON A.ref_id=B.heorder_id AND A.ref_line=B.heorderdetil_line
				GROUP BY A.ref_id,A.ref_line,A.heinv_id
				UNION
				-- ORDER YANG MASIH OUTSTANDING
				SELECT
				A.heorder_id,
				A.heorderdetil_line,
				A.heinv_id,
				A.heinv_price,
				C01, C02, C03, C04, C05, C06, C07, C08, C09, C10, C11, C12, C13, C14, C15, C16, C17, C18, C19, C20, C21, C22, C23, C24, C25 
				FROM transaksi_heorderdetil A INNER JOIN #TEMP_ORDER_3 B
				ON A.heorder_id=B.heorder_id AND A.heorderdetil_line=B.heorderdetil_line
		
					
				SELECT 
				heorder_id,
				heorderdetil_line,
				heinv_id,
				heinv_price=SUM(heinv_price),
				C01=SUM(C01), C02=SUM(C02), C03=SUM(C03), C04=SUM(C04), C05=SUM(C05), C06=SUM(C06), C07=SUM(C07), C08=SUM(C08), C09=SUM(C09), C10=SUM(C10), C11=SUM(C11), C12=SUM(C12), C13=SUM(C13), C14=SUM(C14), C15=SUM(C15), C16=SUM(C16), C17=SUM(C17), C18=SUM(C18), C19=SUM(C19), C20=SUM(C20), C21=SUM(C21), C22=SUM(C22), C23=SUM(C23), C24=SUM(C24), C25=SUM(C25)
				INTO #TEMP_ORDER_5
				FROM #TEMP_ORDER_4
				GROUP BY heorder_id,heorderdetil_line,heinv_id
			
		
				SET NOCOUNT OFF
		
				SELECT 
				A.heorder_id,
				A.heorderdetil_line,
				A.heinv_id,
				B.heinv_art,
				B.heinv_mat,
				B.heinv_col,
				B.heinv_name, 
				heinvgro_id=B.heinvgro_id, heinvctg_id=B.heinvctg_id, heinv_sizetag=(SELECT heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id=B.heinvctg_id), 
				A.heinv_price,
				A.C01, A.C02, A.C03, A.C04, A.C05, A.C06, A.C07, A.C08, A.C09, A.C10, A.C11, A.C12, A.C13, A.C14, A.C15, A.C16, A.C17, A.C18, A.C19, A.C20, A.C21, A.C22, A.C23, A.C24, A.C25
				FROM
				#TEMP_ORDER_5 A left join master_heinv B on A.heinv_id=B.heinv_id 
				ORDER BY A.heorderdetil_line 
		
				SET NOCOUNT ON
		
				DROP TABLE #TEMP_ORDER_1
				DROP TABLE #TEMP_ORDER_2
				DROP TABLE #TEMP_ORDER_3
				DROP TABLE #TEMP_ORDER_4
				DROP TABLE #TEMP_ORDER_5
		
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
	$obj->heinv_sizetag = $rs->fields['heinv_sizetag'];

	$obj->heinv_price = 1*$rs->fields['heinv_price'];
	
	
	$obj->C01 = 1*$rs->fields['C01'];
	$obj->C02 = 1*$rs->fields['C02'];
	$obj->C03 = 1*$rs->fields['C03'];
	$obj->C04 = 1*$rs->fields['C04'];
	$obj->C05 = 1*$rs->fields['C05'];
	$obj->C06 = 1*$rs->fields['C06'];
	$obj->C07 = 1*$rs->fields['C07'];
	$obj->C08 = 1*$rs->fields['C08'];
	$obj->C09 = 1*$rs->fields['C09'];
	$obj->C10 = 1*$rs->fields['C10'];
	$obj->C11 = 1*$rs->fields['C11'];
	$obj->C12 = 1*$rs->fields['C12'];
	$obj->C13 = 1*$rs->fields['C13'];
	$obj->C14 = 1*$rs->fields['C14'];
	$obj->C15 = 1*$rs->fields['C15'];
	$obj->C16 = 1*$rs->fields['C16'];
	$obj->C17 = 1*$rs->fields['C17'];
	$obj->C18 = 1*$rs->fields['C18'];
	$obj->C19 = 1*$rs->fields['C19'];
	$obj->C20 = 1*$rs->fields['C20'];
	$obj->C21 = 1*$rs->fields['C21'];
	$obj->C22 = 1*$rs->fields['C22'];
	$obj->C23 = 1*$rs->fields['C23'];
	$obj->C24 = 1*$rs->fields['C24'];
	$obj->C25 = 1*$rs->fields['C25'];
	
	$qty = 0;
	for ($i=1; $i<=25; $i++) {
		$fname = str_pad($i, 2, "0", STR_PAD_LEFT);  
		$qty += 1*$rs->fields['C'.$fname];
	} 
	
	$obj->heinv_qty = 1*$qty;
	$obj->heinv_pricesubtotal = 1*$qty*$obj->heinv_price;
	
	$obj->heinv_received = 0;
	$obj->heinv_outstanding = 1*($obj->heinv_qty - $obj->heinv_received);
		


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