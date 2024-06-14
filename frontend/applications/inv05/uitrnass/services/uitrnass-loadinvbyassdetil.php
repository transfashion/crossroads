<?
if (!defined('__SERVICE__')) {
	die("access denied");
}

	$username 	= $_SESSION["username"];
	$limit 		= $_POST['limit'];
	$start 		= $_POST['start'];
	$criteria	= $_POST['criteria'];


	$param = "";
	$SQL_CRITERIA = "";
	$objCriteria = json_decode(stripslashes($criteria));

	if (is_array($objCriteria)) {
		$CRITERIA_DB = array();
		while (list($name, $value) = each($objCriteria)) {
			$CRITERIA_DB[$value->name] = $value;
 
			//seharusnya amankan criteria di sini, cegat di $criteria[$value->name]->value
		}		
		$AMid = SQLUTIL::BuildCriteria($SQL_CRITERIA, $CRITERIA_DB, 'AMid', 'hemoving_id', "A.hemoving_id = '{criteria_value}'");
		//$heinv_id = SQLUTIL::BuildCriteria($SQL_CRITERIA, $CRITERIA_DB, 'heinv_id', 'heinv_id', "B.heinv_id = '{criteria_value}'");
	}

 
	$data = array();
	//$sql = "SELECT * from transaksi_hemovingdetil where hemoving_id = (SELECT hemoving_id FROM transaksi_hemovingdetil WHERE $SQL_CRITERIA AND left(hemoving_id,2) ='AM')";
 
 
	$sql = "select 
A.hemoving_id,  
B.hemovingdetil_line,
B.heinv_id,
B.heinv_art,
B.heinv_mat,
B.heinv_col,
B.heinv_name,
A.hemoving_descr,
A.region_id,
C.heinvgro_id,
C.heinvctg_id,
A01,A02,A03,A04,A05,A06,A07,A08,A09,A10,A11,A12,A13,A14,A15,A16,A17,A18,A19,A20,A21,A22,A23,A24,A25
FROM transaksi_hemoving A inner join transaksi_hemovingdetil B on A.hemoving_id = B.hemoving_id
left join master_heinv C ON  B.heinv_id = C.heinv_id
WHERE $SQL_CRITERIA AND left(A.hemoving_id,2)='AM'
GROUP BY 
A.hemoving_id,  
B.hemovingdetil_line,
B.heinv_id,
B.heinv_art,
B.heinv_mat,
B.heinv_col,
B.heinv_name,
A.hemoving_descr,
A.region_id,
C.heinvgro_id,
C.heinvctg_id,
A01,A02,A03,A04,A05,A06,A07,A08,A09,A10,A11,A12,A13,A14,A15,A16,A17,A18,A19,A20,A21,A22,A23,A24,A25
HAVING SUM(A01+A02+A03+A04+A05+A06+A01+A07+A08+A09+A10+A11+A12+A13+A14+A15+A16+A17+A18+A19+A20+A21+A22+A23+A24+A25) < 0";

	$rs  = $conn->Execute($sql);
	
	while (!$rs->EOF) {
	
		unset($obj);
		$region_id =trim($rs->fields['region_id']); 
		$A01 =  1*trim($rs->fields['A01']);
		$A02 =  1*trim($rs->fields['A02']);
		$A03 =  1*trim($rs->fields['A03']);
		$A04 =  1*trim($rs->fields['A04']);
		$A05 =  1*trim($rs->fields['A05']);
		$A06 =  1*trim($rs->fields['A06']);
		$A07 =  1*trim($rs->fields['A07']);
		$A08 =  1*trim($rs->fields['A08']);
		$A09 =  1*trim($rs->fields['A09']);
		$A10 =  1*trim($rs->fields['A10']);
		$A11 =  1*trim($rs->fields['A11']);
		$A12 =  1*trim($rs->fields['A12']);
		$A13 =  1*trim($rs->fields['A13']);
		$A14 =  1*trim($rs->fields['A14']);
		$A15 =  1*trim($rs->fields['A15']);
		$A16 =  1*trim($rs->fields['A16']);
		$A17 =  1*trim($rs->fields['A17']);
		$A18 =  1*trim($rs->fields['A18']);
		$A19 =  1*trim($rs->fields['A19']);
		$A20 =  1*trim($rs->fields['A20']);
		$A21 =  1*trim($rs->fields['A21']);
		$A22 =  1*trim($rs->fields['A22']);
		$A23 =  1*trim($rs->fields['A23']);
		$A24 =  1*trim($rs->fields['A24']);
		$A25 =  1*trim($rs->fields['A25']);
		
		
		//$jml = 	$A01+$A02+$A03+$A04+$A05+$A06+$A07+$A08+$A09+$A10+$A11+$A12+$A13+$A14+$A15+$A16+$A17+$A18+$A19+$A20+$A21+$A22+$A23+$A24+$A25;
		
		
		$obj->hemoving_id = trim($rs->fields['hemoving_id']);
		$obj->hemovingdetil_line = 1*trim($rs->fields['hemovingdetil_line']);
		$obj->heinv_id = trim($rs->fields['heinv_id']);
		$obj->heinv_art	= trim($rs->fields['heinv_art']); 
		$obj->heinv_mat	= trim($rs->fields['heinv_mat']); 
		$obj->heinv_col	= trim($rs->fields['heinv_col']); 
		$obj->heinv_name	= trim($rs->fields['heinv_name']);
	 	$obj->hemoving_descr= trim($rs->fields['hemoving_descr']);		 
	 	
	 	$obj->heinvgro_id= trim($rs->fields['heinvgro_id']);		 
	 	$obj->heinvctg_id= trim($rs->fields['heinvctg_id']);		 
	 	
		$obj->A01 = (int) $rs->fields['A01'];
		$obj->A02 = (int) $rs->fields['A02'];
		$obj->A03 = (int) $rs->fields['A03'];
		$obj->A04 = (int) $rs->fields['A04'];
		$obj->A05 = (int) $rs->fields['A05'];
		$obj->A06 = (int) $rs->fields['A06'];
		$obj->A07 = (int) $rs->fields['A07'];
		$obj->A08 = (int) $rs->fields['A08'];
		$obj->A09 = (int) $rs->fields['A09'];
		$obj->A10 = (int) $rs->fields['A10'];
		$obj->A11 = (int) $rs->fields['A11'];
		$obj->A12 = (int) $rs->fields['A12'];
		$obj->A13 = (int) $rs->fields['A13'];
		$obj->A14 = (int) $rs->fields['A14'];
		$obj->A15 = (int) $rs->fields['A15'];
		$obj->A16 = (int) $rs->fields['A16'];
		$obj->A17 = (int) $rs->fields['A17'];
		$obj->A18 = (int) $rs->fields['A18'];
		$obj->A19 = (int) $rs->fields['A19'];
		$obj->A20 = (int) $rs->fields['A20'];
		$obj->A21 = (int) $rs->fields['A21'];
		$obj->A22 = (int) $rs->fields['A22'];
		$obj->A23 = (int) $rs->fields['A23'];
		$obj->A24 = (int) $rs->fields['A24'];
		$obj->A25 = (int) $rs->fields['A25'];	
		
		$heinv_id = trim($rs->fields['heinv_id']);
		$CTG = "SELECT heinvctg_id FROM master_heinv WHERE heinv_id = '$heinv_id'";
		$rsCtg = $conn->execute($CTG);
							
		$heinvctg_id = $rsCtg->fields['heinvctg_id'];
		

		
		$SIZE ="SELECT heinvctg_sizetag FROM master_heinvctg WHERE region_id = '$region_id' and heinvctg_id = '$heinvctg_id'";
		$rsSize = $conn->execute($SIZE);
		$obj->heinv_sizetag = (int) $rsSize->fields['heinvctg_sizetag'];	
	

	
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