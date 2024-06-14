<?php
 
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
	

        $summarydate   = SQLUTIL::BuildCriteria($param, $CRITERIA_DB, 'summarydate', '', "{criteria_value}");
		
		
}
 
$SQL = "
 
 
 	declare @periode_id as nvarchar(4);
	declare @begbalance_periode_id as nvarchar(4);
	declare @year as int;
	declare @yearstr as nvarchar(2);
	declare @month as int;
	declare @begbalance_enddate as smalldatetime;

	set nocount on	

	set @year       = year('$summarydate');	
	set @yearstr    = dbo.f_zerofill(@year-2000, 2);	
	set @month      = month('$summarydate');	
	set @periode_id = dbo.f_zerofill(@year-2000, 2) + dbo.f_zerofill(@month, 2);	
	set @begbalance_periode_id = @yearstr + '01';	
	set @begbalance_enddate    = cast(cast(@year as char(4))+'-'+cast(@month as char(4))+'-'+'1' as smalldatetime);	
 
 delete from cache_temp_jurnal
 delete from cache_temp_jurnalsaldo
 delete from cache_temp_jurnalsummary
 
INSERT INTO  cache_temp_jurnal
SELECT 	acc_id  = isnull(acc_id,''), 
		jurnalsaldoawal_idr=sum(jurnalsaldo_idr),
		jurnalsaldodebet_idr=0,
		jurnalsaldokredit_idr=0,
		jurnalsaldomutasi_idr=0
	from transaksi_jurnalsaldo	
	where channel_id='MGP' AND periode_id=@begbalance_periode_id	
	group by acc_id	
    

    
    
	INSERT INTO cache_temp_jurnalsaldo
	SELECT	
	acc_id = isnull(B.acc_id,''),	
	jurnalsaldoawal_idr   = isnull(sum(A.jurnalsaldoawal_idr), 0),	
	jurnalsaldodebet_idr  = isnull(sum(A.jurnalsaldodebet_idr), 0),	
	jurnalsaldokredit_idr = isnull(sum(A.jurnalsaldokredit_idr), 0),	
	jurnalsaldomutasi_idr = isnull(sum(A.jurnalsaldomutasi_idr), 0),	
	jurnalsaldoakhir_idr  = isnull(sum(A.jurnalsaldoawal_idr),0) + isnull(sum(A.jurnalsaldomutasi_idr), 0)	
	from master_acc B left join cache_temp_jurnal A  on A.acc_id = B.acc_id	
	group by B.acc_id;	
    




 
 insert into cache_temp_jurnalsummary
 SELECT 	
		A.acc_id,
		jurnal_id = '',
		jurnal_descr = 'SALDO_AWAL',
		jurnal_bookdate = '',
		jurnal_source = '',
		rekanan_name = '',
		region_name = '',
		branch_name = '',
		strukturunit_name = '',
		jurnalsaldoawal_idr = A.jurnalsaldoakhir_idr,
		jurnalsaldodebet_idr = 0,
		jurnalsaldokredit_idr = 0,
		jurnalsaldomutasi_idr = 0
 
 	FROM cache_temp_jurnalsaldo A	
	UNION	
	SELECT 	
		A.acc_id,
		A.jurnal_id,
		jurnal_descr =     A.jurnaldetil_descr,
		jurnal_bookdate =  isnull(B.jurnal_bookdate,''),
		jurnal_source = B.jurnal_source,
		rekanan_name =     isnull((select rekanan_name from master_rekanan where rekanan_id =A.rekanan_id), ''),
		region_name =        ltrim(isnull((select region_name from master_region where region_id =A.region_id), '')),  
		branch_name =        ltrim(isnull((select branch_name from master_branch where branch_id =A.branch_id), '')),  
		strukturunit_name =  isnull((select strukturunit_name from master_strukturunit where strukturunit_id =A.strukturunit_id), ''),
		jurnalsaldoawal_idr = 0,
		jurnalsaldodebet_idr=sum(case when A.jurnaldetil_idr > 0 then A.jurnaldetil_idr else 0 end),
		jurnalsaldokredit_idr=sum(case when A.jurnaldetil_idr < 0 then A.jurnaldetil_idr else 0 end),
		jurnalsaldomutasi_idr=sum(A.jurnaldetil_idr)
	from 	
	transaksi_jurnaldetil A inner join transaksi_jurnal B on A.jurnal_id = B.jurnal_id	
	where B.channel_id='MGP' 	
		--AND B.periode_id=@periode_id
		AND YEAR(B.jurnal_bookdate)=YEAR('$summarydate') 
		AND B.jurnal_bookdate<='$summarydate' 
		AND jurnal_isposted=1
	group by A.acc_id, A.jurnal_id, A.jurnaldetil_descr,B.jurnal_bookdate,B.jurnal_source,A.rekanan_id,A.region_id,A.branch_id,A.strukturunit_id	
    
    
    
       

	SELECT	
	channel_id = 'MGP',	
	periode_id = @periode_id,	
	B.acc_id,	
	B.acc_name,	
	A.jurnal_id,	
	A.jurnal_descr,	A.jurnal_bookdate,
	A.jurnal_source,	
	A.rekanan_name,	
	A.region_name,	
	A.branch_name,	
	A.strukturunit_name,	
	jurnalsaldoawal_idr = isnull(A.jurnalsaldoawal_idr, 0),	
	jurnalsaldodebet_idr = isnull(A.jurnalsaldodebet_idr, 0),	
	jurnalsaldokredit_idr = isnull(A.jurnalsaldokredit_idr, 0),	
	jurnalsaldomutasi_idr = isnull(A.jurnalsaldomutasi_idr, 0),	
	jurnalsaldoakhir_idr = isnull(A.jurnalsaldoawal_idr,0) + isnull(A.jurnalsaldomutasi_idr, 0)
 
	from master_acc B left join cache_temp_jurnalsummary A   on A.acc_id = B.acc_id	
	where B.acc_isgroup=0 AND (B.acc_id >= '3010000' AND B.acc_id <= '7010012')
	order by B.acc_id, A.jurnal_id
" ;
 
 
$rs = $conn->Execute($SQL);
 
 $data = array(); 
 $dt = explode("-",$summarydate);
 $year = substr ($dt[0],2,2);
 $bln = str_pad($dt[1],2,"0",STR_PAD_LEFT);

$periode_id = $year . $bln;  
 
 
 
 WHILE (!$rs->EOF)
 {
    unset($obj);
        $obj->channel_id = 'MGP';
        $obj->periode_id =$rs->fields['periode_id'];
        $obj->acc_id = $rs->fields['acc_id'];
        $obj->acc_name = $rs->fields['acc_name'];
        $obj->jurnal_id = $rs->fields['jurnal_id'];
        
        
        $rekanan_name = $rs->fields['rekanan_name'];
        $rekanan_name = str_replace("'", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace(";", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace(",", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace("/", "",$rs->fields['rekanan_name']);
        $rekanan_name = str_replace('"', "",$rs->fields['rekanan_name']);
        
        
           $obj->rekanan_name = $rekanan_name;
           
           $jurnal_descr = str_replace("'", "",$rs->fields['jurnal_descr']);
           $jurnal_descr = str_replace(";", "",$jurnal_descr);
           $jurnal_descr = str_replace(",", "",$jurnal_descr);
           $jurnal_descr = str_replace("&", "",$jurnal_descr);
           $jurnal_descr = str_replace("/", "",$jurnal_descr);
           $jurnal_descr = str_replace('"', "",$jurnal_descr);
           
         $obj->jurnal_descr = $jurnal_descr; 
        $obj->jurnal_bookdate = $rs->fields['jurnal_bookdate'];
        $obj->jurnal_source = $rs->fields['jurnal_source'];
        $obj->region_name = $rs->fields['region_name'];
        $obj->branch_name = $rs->fields['branch_name'];
        
        $obj->strukturunit_name = $rs->fields['strukturunit_name'];
        
        $obj->jurnalsaldoawal_idr = (float) $rs->fields['jurnalsaldoawal_idr'];
        $obj->jurnalsaldodebet_idr = (float) $rs->fields['jurnalsaldodebet_idr'];
        $obj->jurnalsaldokredit_idr = (float) $rs->fields['jurnalsaldokredit_idr'];
        $obj->jurnalsaldomutasi_idr = (float) $rs->fields['jurnalsaldomutasi_idr'];
        $obj->jurnalsaldoakhir_idr = (float) $rs->fields['jurnalsaldoakhir_idr'];
       
    $data[] = $obj;
    $rs->MoveNext();
    
 }
 
 
 
 

	$objResult = new WebResultObject("objResult");
	$objResult->totalCount = 1;
	$objResult->success = true;
	$objResult->data =  $data;
	$objResult->errors = $dbErrors;
	if (!$dbErrors) unset($objResult->errors);
	
	print(stripslashes(json_encode($objResult)));
 
?> 