<?php
/*
Generated by TransBrowser Generator
Created by dhewe, 21/03/2011 15:55
Program untuk registrasi item
Filename: uitrnheinvregister-save.php
*/

	if (!defined('__SERVICE__')) {
		die("access denied");
	}


	$__USERNAME	= $_SESSION["username"];
	$__ID		= $_POST["__ID"];
    $__PO		= $_POST["__PO"];
    $__PC		= $_POST["__PC"];
	$__JSONDATA	= $_POST['JSONDATA'];
	$__POSTDATA = json_decode(stripslashes($__JSONDATA));
	$__POSTDATA = $__POSTDATA[0];
	$__RESULT = array("");	
	$__RESULT[0]->__ID = $__ID;
	

	if (empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD'])=='post') {
		$poidsMax = ini_get('post_max_size');
		$dbErrors = new WebResultErrorObject("0x00000001", "Data Overload, your data is too big, maximum allowed size here is $poidsMax");
		$objResult = new WebResultObject("objResult");
		$objResult->totalCount = 1;
		$objResult->success = false;
		$objResult->data = $__RESULT;
		$objResult->errors = $dbErrors;
		die(stripslashes(json_encode($objResult)));		
	}
     
    $register_id = $__ID;
    $po_id =$__PO;
    $pc_id =$__PC;
    

    try {
        $conn->BeginTrans();

        // cek no PO
        $sql = "SELECT * FROM transaksi_heorder WHERE heorder_id='$po_id'";
        $rs = $conn->Execute($sql);
        if ($rs->recordCount()==0) {
            throw new Exception("No Order '$po_id' tidak ditemukan!");
        }

        $heorder_isposted = $rs->fields['heorder_isposted'];
        $heorder_isclosed = $rs->fields['heorder_isclosed'];
        
        if ($heorder_isposted!=0 || $heorder_isclosed!=0) {
            throw new Exception("No Order '$po_id' sudah di posting/closed. PO tidak dapat diproses!");
        }



        // cek no Pricing
        $sql = "SELECT * FROM transaksi_heinvprice WHERE price_id='$pc_id'";
        $rs = $conn->Execute($sql);
        if ($rs->recordCount()==0) {
            throw new Exception("No Pricing '$pc_id' tidak ditemukan!");
        }

        $price_isposted = $rs->fields['price_isposted'];
        $price_isgenerated = $rs->fields['price_isgenerated'];
        
        if ($price_isposted!=0 || $price_isgenerated!=0) {
            throw new Exception("No Pricing '$pc_id' sudah di posting/generate. Pricing tidak dapat diproses!");
        }



        $sql = "
            EXEC inv05he_TrnRegister_ToPOPC '$register_id', '$po_id', '$pc_id'
        ";
        $rs  = $conn->Execute($sql);

		$success = true;
		$conn->CommitTrans();
    } catch (Exception $e) {
	       
		$conn->RollbackTrans();
		$msg = $e->getMessage();
		$success = false;
		$dbErrors = new WebResultErrorObject("0x00000001", str_replace('"','',$msg));
	}
 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success =  $success;;
	$objResult->data =  $__RESULT;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
	
?>