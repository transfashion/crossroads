 <?
ob_start();
date_default_timezone_set('Asia/Jakarta');

$db_local[type] = 'ado_mssql';
$db_local[host] = '172.16.10.20';
$db_local[name] = 'E_FRM2_MGP';
$db_local[user] = 'sa';
$db_local[pass] = 'meg@tower';

/* 
$db_local[type] = 'ado_mssql';
$db_local[host] = 'IGUN-PC\SQLEXPRESS';
$db_local[name] = 'E_FRM2_MGP';
$db_local[user] = 'sa';
$db_local[pass] = 'rahasia';


*/
SET_TIME_LIMIT(6000);


define('ADODB_DIR', 'adodb');
require_once ADODB_DIR.'/adodb-exceptions.inc.php';
require_once ADODB_DIR.'/adodb.class.php';

//	require("inc/smtp/smtp.php");
//	require("inc/sasl/sasl.php");
 	include dirname(__FILE__)."/inc/smtp/smtp.php";
 	include dirname(__FILE__)."/inc/sasl/sasl.php";

//require_once 'inc/sqlutil.inc.php';


/*

try {
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection($db_local[type]);
	$DSN_LOCAL  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db_local[host]."; DATABASE=".$db_local[name]."; UID=".$db_local[user]."; PWD=".$db_local[pass].";";
	$conn->Connect($DSN_LOCAL);
} catch (exception $e) {
	print $e->GetMessage();	
}
 */


function SendNotificationEmail($type,$__ID,$region_id,$conn)
{

		    $from = "it@transfashionindonesia.com";
 		    $subject = "PT. Trans Mahagaya, Inventory Moving - $__ID";
   

 
 	$SQL = "SELECT * FROM master_hemovingnotifier WHERE region_id = '$region_id' and notifier_isdisabled=0";
 	$rs = $conn->execute($SQL);
 	
 	$totalCount = $rs->recordcount();

		  while (!$rs->EOF)
		  {
		    
		   $recipient_to = $rs->fields['email'];

		   	$SQLR = "SELECT region_name FROM master_region WHERE region_id = '$region_id'";
 			$rsR = $conn->execute($SQLR);
 			
 			$region_name = $rsR->fields['region_name'];
 			
		 	$line = str_pad("PT. TRANS MAHAGAYA",35," " ,STR_PAD_RIGHT) . "\n";
			$line .= str_pad("Inventory Transaction",35," " , STR_PAD_RIGHT) . "\n";
			$line .= str_pad($region_name,35," " , STR_PAD_RIGHT) . "\n";
		 	$line .= "\r\n";
		   	$line .= "\r\n";
		   	
		   	switch ($type)
		   	{
		   		case "RVRECV" :
					
		 
					//$SQLID = "SELECT Qty = SUM(C01+C02+C03+C04+C05+C06+C07+C08+C09+C10+C11+C12+C13+C14+C15+C16+C17+C18+C19+C20+C21+C22+C23+C24+C25) from transaksi_hemovingdetil WHERE hemoving_id = '$__ID'";
					$SQLID = "SELECT A.hemoving_descr,
						   Qty = SUM(C01+C02+C03+C04+C05+C06+C07+C08+C09+C10+C11+C12+C13+C14+C15+C16+C17+C18+C19+C20+C21+C22+C23+C24+C25) from transaksi_hemoving A
						   inner join  transaksi_hemovingdetil B on A.hemoving_id = B.hemoving_id WHERE
						    A.hemoving_id = '$__ID' GROUP BY A.hemoving_descr";
		 			$rsID = $conn->Execute($SQLID);
 			}   	
 
 			
			

		   	$QTY = $rsID ->fields['Qty'];
			$DESCR = $rsID ->fields['hemoving_descr'];

		   	$line .= str_pad("ID : " . $__ID,8," " , STR_PAD_RIGHT);
			$line .= "\r\n";

		   	$line .= str_pad("DESCR  : " . $DESCR ,30," " , STR_PAD_RIGHT);
			$line .= "\r\n";

		   	$line .= str_pad("Qty : " . $QTY . " PCS",8," " , STR_PAD_RIGHT);	
		   	$line .= "\r\n";
		   	$line .= "Has Been Successfully Posted";
		   	$line .= "\r\n";
		   	$line .= "============================================";
		   		

		   	send_imel($recipient_to,$from,$subject,$line);
			

		   
		    	$rs->MoveNext();
		   }
 
 }

  ob_end_clean();
function send_imel($to,$from,$subject,$isi)
{
	 
	if(strlen($from)==0)
		die("Please set the messages sender address in line ".$sender_line." of the script ".basename(__FILE__)."\n");
	if(strlen($to)==0)
		die("Please set the messages recipient address in line ".$recipient_line." of the script ".basename(__FILE__)."\n");

	$smtp=new smtp_class;

	$smtp->host_name="172.16.10.25";       /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
	$smtp->host_port=25;                /* Change this variable to the port of the SMTP server to use, like 465 */
	$smtp->ssl=0;                       /* Change this variable if the SMTP server requires an secure connection using SSL */
	$smtp->start_tls=0;                 /* Change this variable if the SMTP server requires security by starting TLS during the connection */
	$smtp->localhost="172.16.3.19";       /* Your computer address */
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