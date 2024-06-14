<?php

if (!defined('__SERVICE__')) {
	die("access denied");
}

$username 	= $_SESSION["username"];
$id 		= $_POST['id'];
$doc        = $_GET['doc'];


$sql = 
"SELECT A.region_id,A.batch_isean,B.*,
sizetag = (select heinvctg_sizetag FROM master_heinvctg WHERE heinvctg_id = B.heinvctg_id AND region_id=A.region_id)
FROM transaksi_heinvprintbarcode A inner join transaksi_heinvprintbarcodedetil B on
A.batch_id = B.batch_id
where B.batch_id = '$id'";
$data = array();
$rs = $conn->Execute($sql);


$isEan = $rs->fields['batch_isean'];


while (!$rs->EOF)
{
		$head_heinv_id = $rs->fields['heinv_id'];


		// ditambahkan agar bisa print pricing tanpa generate
		$pricingdoc_price     = (float)$rs->fields['price'];
		$pricingdoc_pricedisc = (float)$rs->fields['pricedisc'];
		 // ----------------------
		
			 
			 
		$region_id = $rs->fields['region_id'];
		$SIZETAG = $rs->fields['sizetag'];
		$sqlSize = "SELECT * FROM master_heinvsizetag WHERE region_id ='$region_id' AND SIZETAG = '$SIZETAG'"; 
		$rsSize = $conn->Execute($sqlSize);
			 
	 
	 for ($i=1;$i<=25;$i++)
		{
			$columName = 'C'.str_pad($i,2,"0",STR_PAD_LEFT);
			$QTY = $rs->fields[$columName];
			

			IF ($QTY>0)
			{
			 
			  unset($obj);
				$obj->batch_id = $rs->fields['batch_id'];
				$obj->batchdetil_line = 1*$rs->fields['batchdetil_line'];
				
				
				$heinv_id = substr($rs->fields['heinv_id'],0,11) . '00';
				$colnum = str_pad($i,2,"0",STR_PAD_LEFT);
				
				$sqlEAN = " 
				SELECT B.* FROM master_heinv A inner join master_heinvitem B on A.heinv_id = B.heinv_id AND A.region_id ='$region_id' 
				AND B.heinv_id = '$heinv_id' and heinvitem_colnum = '$colnum' and heinvitem_colnum is not null";
				
				$rsEAN = $conn->execute($sqlEAN);
				$heinvitem_barcode = $rsEAN->fields['heinvitem_barcode'];				

				IF ($isEan==1)
				{
					if ($heinvitem_barcode)
					{
					 	$heinvitem_id = $heinvitem_barcode;
	 					$obj->heinv_id =$heinvitem_id;
						
						$SQLID = "SELECT A.heinv_id FROM master_heinv A inner join master_heinvitem B on A.heinv_id = B.heinv_id  WHERE B.heinvitem_barcode = '$heinvitem_barcode' and B.heinvitem_colnum is not null and A.heinv_isdisabled=0";
						$rsID = $conn->execute($SQLID);
						$heinv_id = $rsID->fields['heinv_id'];						
						
						$sqlCtg = "select heinvctg_id FROM master_heinv where heinv_id = '$head_heinv_id'";
						$rsCtg = $conn->execute($sqlCtg);
						$obj->heinvctg_id = $rsCtg->fields['heinvctg_id'];
						
		 
				
				
					 }
					 else
					 {
						$heinv_id = substr($rs->fields['heinv_id'],0,11) . '00';//str_pad($i,2,"0",STR_PAD_LEFT);	  


						$sqlCtg = "select heinvctg_id FROM master_heinv where heinv_id = '$head_heinv_id'";
						$rsCtg = $conn->execute($sqlCtg);
						$obj->heinvctg_id = $rsCtg->fields['heinvctg_id'];



						$obj->heinv_id =$heinv_id;
					  }
					 
										 	
				 }
				 else
				 {
	  				$heinvitem_id = substr($rs->fields['heinv_id'],0,11) . str_pad($i,2,"0",STR_PAD_LEFT);
  					$obj->heinv_id =$heinvitem_id;
  	
	  
  					$sqlCtg = "select heinvctg_id FROM master_heinv where heinv_id = '$heinv_id'";
					$rsCtg = $conn->execute($sqlCtg);
					$obj->heinvctg_id = $rsCtg->fields['heinvctg_id'];

				  }
				

				
				//$obj->heinv_id = substr($rs->fields['heinv_id'],0,11) . str_pad($i,2,"0",STR_PAD_LEFT);
 

				$obj->heinv_art = $rs->fields['heinv_art'];
				$obj->heinv_mat = $rs->fields['heinv_mat'];
				$obj->heinv_col = $rs->fields['heinv_col'];
				$obj->heinv_name = $rs->fields['heinv_name'];
				$obj->season_id = $rs->fields['season_id'];
				
				
				//$heinv_id = substr($obj->heinv_id,0,11) . '00';
		
		

	
				$heinvctg_id  = $obj->heinvctg_id;
				$sqlCat = "SELECT heinvctg_name FROM master_heinvctg WHERE heinvctg_id = '$heinvctg_id'";
				$rsC = $conn->execute($sqlCat);
				$heinvctg_name = $rsC->fields['heinvctg_name'];
				$obj->heinvctg_name = $rsC->fields['heinvctg_name'];
		
		
				$obj->heinv_isSP = $rs->fields['heinv_isSP'];
				$obj->qtyPrint = $QTY;
				$obj->Size = $rsSize->fields[$columName];
				
				$heinv_id = $rs->fields['heinv_id'];
				$sqlPrice = "SELECT heinv_priceori,heinv_price01,heinv_pricedisc01 FROM master_heinv WHERE heinv_id ='$heinv_id'";
				$rsR = $conn->Execute($sqlPrice);
				
				$sqlLog	= "SELECT heinvprice_id from master_heinvpricelog where heinv_id='$heinv_id' order by heinvpricelog_gendate desc";
				$rsLog = $conn->Execute($sqlLog);
				
				$obj->heinvprice_id = $rsLog->fields['heinvprice_id'];				



				/* ambil data adjustment price */
				/* -- BEGIN ADJUSTMENT PRICE */
				$sqlAdj = " SELECT TOP 1 heinvpriceadj_value, heinvpriceadj_line 
							FROM dbo.master_heinvpriceadj A
							WHERE
							heinv_id = '".$heinv_id."'
							AND convert(varchar(10),A.heinvpriceadj_date,120)<=convert(varchar(10),getdate(),120)
							order by A.heinvpriceadj_date desc ";
				$rsAdj  = $conn->Execute($sqlAdj);
				$heinvpriceadj_value = (float) $rsAdj->fields['heinvpriceadj_value'];
				$heinvprice_ori      = (float) $rsR->fields['heinv_priceori'];
				$heinvprice_gross    = (float) ($heinvprice_ori + $heinvpriceadj_value);
				/*--- END ADJUSTMENT PRICE*/

				//$obj->heinv_priceori = 1*$rsR->fields['heinv_priceori'];
				$obj->heinv_priceori = $heinvprice_gross;
				
				
				//$obj->heinv_price01 = 1*$rsR->fields['heinv_price01'];
				//$obj->heinv_pricedisc01 = 1*$rsR->fields['heinv_pricedisc01'];
				
				// ditambahkan agar bisa print pricing tanpa generate
				if ($pricingdoc_price>0) {
					$obj->heinv_price01 = $pricingdoc_price;
					$obj->heinv_pricedisc01 = $pricingdoc_pricedisc;
				} else { 
					$obj->heinv_price01 = 1*$rsR->fields['heinv_price01'];
					$obj->heinv_pricedisc01 = 1*$rsR->fields['heinv_pricedisc01'];
				}				
				
				if ($obj->heinvctg_id==null) {
					$obj->heinvctg_id="";
				}

				if ($obj->heinvctg_name==null) {
					$obj->heinvctg_name="";
				}
	
				$data[] = $obj;

			}
 			
			
		}

	// PB/05/HBS/HO/230000095	
 	$rs->MoveNext();
 }
 
  

$objResult = new WebResultObject("objResult");
$objResult->totalCount = $totalCount;
$objResult->success = true;
$objResult->data = $data;
unset($objResult->errors); 

	
print(stripslashes(json_encode($objResult)));

?>