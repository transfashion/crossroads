<?

//ob_start();
//date_default_timezone_set('Asia/Jakarta');
print 'starting ... ' . "\r\n";
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

try
{
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection($db_local[type]);
	$DSN_LOCAL  = "PROVIDER=MSDASQL; DRIVER={SQL Server}; SERVER=".$db_local[host]."; DATABASE=".$db_local[name]."; UID=".$db_local[user]."; PWD=".$db_local[pass].";";
	$conn->Connect($DSN_LOCAL);


} catch (exception $e) {
	print $e->GetMessage();	
}

    $tanggal = date('d-m-Y', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
    $tanggal_db = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));

	$SQL_REGION = "SELECT region_id,branch_id FROM master_regionbranch WHERE regionbranch_disabledentry=0 AND region_id='00500'";	
	$rs_region = $conn->execute($SQL_REGION);
	print 'Looping Region Branch ... ' ."\n" ;
	WHILE (!$rs_region->EOF)
	{
	
				 $region_id = $rs_region->fields['region_id'];
				 $branch_id = $rs_region->fields['branch_id'];


				 $rsReg = $conn->Execute("select region_name from master_region where region_id='$region_id '");
				 $region_name  = $rsReg->fields['region_name'];
				 
				 $rsReg = $conn->Execute("select branch_name from master_branch where branch_id='$branch_id '");
				 $branch_name  = $rsReg->fields['branch_name'];
				 
				 
											
//											$bataswaktu_malam = mktime(00,00,00,date("n"),date("j") ,date("Y"));
//											$bataswaktu_siang = mktime(12,00,00,date("n"),date("j") ,date("Y"));
											
//											if (time() >= $bataswaktu_malam && time() < $bataswaktu_siang) {
											                // mundurin waktu kemaren

//											} else {
											                // waktu normal
//											                $id = date("Ymd", time()).".".$region_id;
//											                $tanggal = date("d-M-Y");
//											                $tanggal_db = date("Y-M-d");

//											}              

							                $id =   date('Ymd', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y"))).".".$region_id;
							 	 		    print "Executing [$region_id, $branch_id]: $region_name - $branch_name\n";

											$SQL = " EXEC saleslogdetil_M '$id','$region_id','$branch_id','$tanggal_db'";
											$conn->execute($SQL);

											$SQL = " EXEC saleslogdetil_MTOUCH '$id','$region_id','$branch_id','$tanggal_db'";
											$conn->execute($SQL);

											
											//$SQL = " EXEC saleslogdetil '$id','$region_id','$branch_id','$tanggal_db'";
											//$conn->execute($SQL);
											
											print "$SQL\n\n";
		 //print '.';
		$rs_region->MoveNext();
	}
	


	/*	
	$id =   date('Ymd', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));

	print "\n\n";
	print "Executing sales Global...\n\n";

	print "Hi-end ($id, $tanggal_db)..."; 
	$SQL = " EXEC saleslogregion '$id','$tanggal_db'";                                                                   
	$conn->execute($SQL);
	print "done.\n";
	
	
	print "Mango ($id, $tanggal_db)..."; 
	$SQL = " EXEC saleslogregion_M '$id','$tanggal_db'";                                                                   
	$conn->execute($SQL);
	print "done.\n";

	print "Mango Touch ($id, $tanggal_db)..."; 
	$SQL = " EXEC saleslogregion_MTOUCH '$id','$tanggal_db'";                                                                   
	$conn->execute($SQL);
	print "done.\n";
	*/

	//$SQL = " EXEC saleslogALL '$id','$tanggal_db'";
	//$conn->execute($SQL);

		
   
 
?>
 