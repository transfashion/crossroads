<?
ob_start();
date_default_timezone_set('Asia/Jakarta');


$db_local[type] = 'ado_mssql';
$db_local[host] = '172.16.10.21';
$db_local[name] = 'E_FRM2_MGP';
$db_local[user] = 'sa';
$db_local[pass] = 'Modul@Oblongata';

/*
$db_local[type] = 'ado_mssql';
$db_local[host] = 'IGUN-PC\SQLEXPRESS';
$db_local[name] = 'E_FRM2_MGP';
$db_local[user] = 'sa';
$db_local[pass] = 'rahasia';
*/

define('ADODB_DIR', 'adodb');
require_once ADODB_DIR.'/adodb-exceptions.inc.php';
require_once ADODB_DIR.'/adodb.class.php';

	require("inc/smtp/smtp.php");
	require("inc/sasl/sasl.php");
//require_once 'inc/sqlutil.inc.php';



try {
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection($db_local[type]);
	$DSN_LOCAL  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db_local[host]."; DATABASE=".$db_local[name]."; UID=".$db_local[user]."; PWD=".$db_local[pass].";";
	$conn->Connect($DSN_LOCAL);

} catch (exception $e) {
	print $e->GetMessage();	
}


 
 
 // REPORT SALES LOG GLOBAL ( transaksi_saleslog )
  
 
	 $sql = "SELECT 
saleslog_id, 
saleslog_date,
region_id,
saleslog_gross = SUM(saleslog_gross),
saleslog_nett = SUM(saleslog_nett),
saleslog_qty = SUM(saleslog_qty),
saleslog_jmlbon = SUM(saleslog_jmlbon),
saleslog_issent

FROM transaksi_saleslogregionEP 
	WHERE convert(varchar(10),saleslog_date,120)=convert(varchar(10),getdate()-1,120) AND saleslog_issent = 0
GROUP by saleslog_id,saleslog_date, region_id, saleslog_issent
"; 
 	 $rs = $conn->Execute($sql);
	 

		$Logfile = dirname(__FILE__)."/" . "Log_REGION_EP___ " . $ID. ".txt";
		$fLog = fopen($Logfile, "w");
	 

 
  	 while (!$rs->EOF)
   	 {
	   	$id = $rs->fields["saleslog_id"];
		$tgl = explode(" ",$rs->fields["saleslog_date"]);
		$tgl = explode("-",$tgl[0]);	
		$tanggal = $tgl[2] . "-" . $tgl[1] . "-"  . $tgl[0];
		
		

  		$rs->MoveNext();
   	  }
   	  

   	   $rs = $conn->Execute($sql);
   	   
   	   
$strLog = "======= START LOOPING=======  \r\n";
fwrite($fLog, $strLog);
$strLog = "EXECUTING $sql .. \r\n";
fwrite($fLog, $strLog);

	    $line = "";
		$line .= str_pad("PT. TRANS FASHION INDONESIA",35," " ,STR_PAD_RIGHT) . "\n";
		$line .= str_pad("Daily Region Sales",35," " , STR_PAD_RIGHT) . "\n";
		$line .= str_pad($tanggal,35," " , STR_PAD_RIGHT) . "\n";
		$line .= str_pad("in million rupiah",35," " , STR_PAD_RIGHT) . "\n";
	   	$line .= "\r\n";
	   	$line .= "\r\n";
		
		 
  	 while (!$rs->EOF)
   	 {

	   	$TOTAL = 0;

	
		$region_id = $rs->fields['region_id'];
	
	   	$sqlR = "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
   		$rsR = $conn->Execute($sqlR);
	   	$region_name = $rsR->fields["region_name"];
		 	
   			$TOTAL_GROSS =  (float)($rs->fields["saleslog_gross"])/1000000;
  			$TOTAL_NETT =  (float)($rs->fields["saleslog_nett"])/1000000;

				
			$TOTAL_GROSS = floor($TOTAL_GROSS);
			$TOTAL_NETT = floor($TOTAL_NETT); 

		 	$TOTAL_QTY = 1*$rs->fields["saleslog_qty"];
		 	$TOTAL_BON= 1*$rs->fields["saleslog_jmlbon"];


		 	$line .= str_pad($region_name,20);
	 		$line .= "\r\n";	  
			$line .= str_pad("Q : " . $TOTAL_QTY . " PCS",8," " , STR_PAD_RIGHT);		 	
	 		$line .= "\r\n";	  
			$line .= str_pad("G : " . $TOTAL_GROSS . " jt",8," " , STR_PAD_RIGHT);
	 		$line .= "\r\n";	  			
			$line .= str_pad("N : " . $TOTAL_NETT . " jt",8," " , STR_PAD_RIGHT);
	 		$line .= "\r\n";	  
			$line .= str_pad("B : " . $TOTAL_BON . " ",8," " , STR_PAD_RIGHT);
	 		$line .= "\r\n";
	 		$line .= "\r\n";
	 		
 
	 		
		 	$_TOTAL_GROSS += $TOTAL_GROSS;
		 	$_TOTAL_NETT += $TOTAL_NETT;
		 	$_TOTAL_QTY += $TOTAL_QTY;
		 	$_TOTAL_TRAFFIC += $TOTAL_TRAFFIC;
		 	$_TOTAL_BON += $TOTAL_BON;
		 	

   	$rs->MoveNext();	
   }
 

			$line .= str_pad("====",4," " , STR_PAD_LEFT);
		   	$line .= "\r\n";
			$line .= str_pad("TOTAL Q : " . $_TOTAL_QTY . " PCS",8," " , STR_PAD_LEFT);
		   	$line .= "\r\n";
			$line .= str_pad("TOTAL G : " . $_TOTAL_GROSS  . " jt",4," " , STR_PAD_LEFT);
		   	$line .= "\r\n";
			$line .= str_pad("TOTAL N :" . $_TOTAL_NETT  . " jt",8," " , STR_PAD_LEFT);
		   	$line .= "\r\n";
			$line .= str_pad("TOTAL B :" . $_TOTAL_BON  . " ",8," " , STR_PAD_LEFT);
		   	$line .= "\r\n";
		   	$line .= "\r\n";
			$line .= "Source : TransBrowser 2009i";

			$sqlImel = "select * FROM master_saleslogrecipientEP where recipient_reportregion=1 and recipient_isDisabled=0";

			$rsImel = $conn->Execute($sqlImel);
			$totalCount = $rsImel->recordcount();
	
			$strLog = "***RECIPIENT QUERY*** .. \r\n";
			fwrite($fLog, $strLog);
			$strLog = "EXECUTING $sqlImel .. \r\n";
			fwrite($fLog, $strLog);
			$strLog = "Record Count : '$totalCount' \r\n";
			fwrite($fLog, $strLog);
			$strLog = "*************** .. \r\n";
			fwrite($fLog, $strLog);
			
			
			
		 while (!$rsImel->EOF)		
		 {
 		    $recipient_to = $rsImel->fields["recipient_to"];
 		    $from = "it@transfashionindonesia.com";
 		  	 		 		 
		 		 	$strLog = "***SENDING IMEL*** .. \r\n";
					fwrite($fLog, $strLog);
 
	  				$subject = "TRANS FASHION INDONESIA - REGION SALES $tanggal";
	  			 
					if (send_imel($recipient_to,$from,$subject,$line))
					{
						$sqlInsert = "
						INSERT INTO transaksi_saleslogsentto (saleslog_id, saleslog_emailto, saleslog_issent)
						VALUES ('$ID','$recipient_to',1)
						";
						$conn->Execute($sqlInsert);
						$strLog = "SENT TO :  $recipient_to SUKSES \r\n";
						fwrite($fLog, $strLog);
					}
					else
					{
 						$sqlInsert = "
						INSERT INTO transaksi_saleslogsentto (saleslog_id, saleslog_emailto, saleslog_issent)
						VALUES ('$ID','$recipient_to',0)
						";
						$conn->Execute($sqlInsert);
						$strLog = "SENT TO :  $recipient_to FAILED \r\n";
						fwrite($fLog, $strLog);
		 			 }
		 			 
		 			 
		 			$strLog = "************** .. \r\n";
					fwrite($fLog, $strLog);
 
			$rsImel->MoveNext(); 
		 }
	 

					$strLog = "Updating  transaksi_saleslogregionEP.. \r\n";
					fwrite($fLog, $strLog);
					
					
	 
		   	$sqlInsertGlobal="
			UPDATE transaksi_saleslogregionEP 
			SET saleslog_issent = 1
			WHERE saleslog_id =  '$ID'";
			
	
	 		$conn->execute($sqlInsertGlobal);
	 		$strLog = "Executing  $sqlInsertGlobal \r\n";
			fwrite($fLog, $strLog);	
 


 //---- ENDING --------------
 $strLog = "======= END LOOPING=======  \n";
fwrite($fLog, $strLog);

$cnts = ob_get_contents();
ob_end_clean();
$outputfile = dirname(__FILE__)."/" . "GLOBAL__" . time() . ".txt";
$fp = fopen($outputfile, "w");
fputs($fp, $cnts);
fclose($fp);
fclose($fLog );
print $cnts ;
?>

<?
function send_imel($to,$from,$subject,$isi)
{
	 
	 print "--->send email to $to...\n\n";
	 
	if(strlen($from)==0)
		die("Please set the messages sender address in line ".$sender_line." of the script ".basename(__FILE__)."\n");
	if(strlen($to)==0)
		die("Please set the messages recipient address in line ".$recipient_line." of the script ".basename(__FILE__)."\n");

	$smtp=new smtp_class;

	$smtp->host_name="172.16.10.25";       /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
	$smtp->host_port=25;                /* Change this variable to the port of the SMTP server to use, like 465 */
	$smtp->ssl=0;                       /* Change this variable if the SMTP server requires an secure connection using SSL */
	$smtp->start_tls=0;                 /* Change this variable if the SMTP server requires security by starting TLS during the connection */
	$smtp->localhost="172.16.10.20";       /* Your computer address */
	$smtp->direct_delivery=0;           /* Set to 1 to deliver directly to the recepient SMTP server */
	$smtp->timeout=10;                  /* Set to the number of seconds wait for a successful connection to the SMTP server */
	$smtp->data_timeout=0;              /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
	                                       Set to 0 to use the same defined in the timeout variable */
	$smtp->debug=1;                     /* Set to 1 to output the communication with the SMTP server */
	$smtp->html_debug=1;                /* Set to 1 to format the debug output as HTML */
	$smtp->pop3_auth_host="";           /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
	$smtp->user="it";                     /* Set to the user name if the server requires authetication */
	$smtp->realm="";                    /* Set to the authetication realm, usually the authentication user e-mail domain */
	$smtp->password="FlexibleTable123";                 /* Set to the authetication password */
	$smtp->workstation="";              /* Workstation name for NTLM authentication */
	$smtp->authentication_mechanism=""; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
	                                       Leave it empty to make the class negotiate if necessary */

	/*
	 * If you need to use the direct delivery mode and this is running under
	 * Windows or any other platform that does not have enabled the MX
	 * resolution function GetMXRR() , you need to include code that emulates
	 * that function so the class knows which SMTP server it should connect
	 * to deliver the message directly to the recipient SMTP server.
	 */
	if($smtp->direct_delivery)
	{
		if(!function_exists("GetMXRR"))
		{
			/*
			* If possible specify in this array the address of at least on local
			* DNS that may be queried from your network.
			*/
			$_NAMESERVERS=array();
			include("getmxrr.php");
		}
		/*
		* If GetMXRR function is available but it is not functional, to use
		* the direct delivery mode, you may use a replacement function.
		*/
		/*
		else
		{
			$_NAMESERVERS=array();
			if(count($_NAMESERVERS)==0)
				Unset($_NAMESERVERS);
			include("rrcompat.php");
			$smtp->getmxrr="_getmxrr";
		}
		*/
	}

	if($smtp->SendMessage(
		$from,
		array(
			$to
		),
		array(
			"From: \"TransBrowser\"<$from>",
			"To: $to",
			"Subject: $subject",
			"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z") 
			//"Content-Type: plain/text;\n\t"."charset=\"us-ascii\""
			//"Content-Transfer-Encoding: quoted-printable"
		),
		$isi)) 
		
		{	 
		 return true;
		 } else { return false; }
	
}

?>