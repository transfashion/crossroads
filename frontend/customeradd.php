<?php
require_once dirname(__FILE__).'/start.inc.php';


$sql = "
set nocount on

declare @dt datetime;
set @dt = '2014-11-01'



select region_id, branch_id, bon_id, bon_date, customer_id, customer_name, customer_telp, customer_genderid, bon_itemqty, bon_mtotal 
into #temp_bon_cust
from 
transaksi_hepos
where 
convert(varchar(10), bon_date, 120)>=convert(varchar(10), @dt, 120)
and customer_telp like '0%' 
and customer_name <> ''


select distinct customer_telp,
customer_name = case when len(customer_telp)<5 then '' else (select top 1 customer_name from #temp_bon_cust where customer_telp=A.customer_telp) end
into #temp_customer
from #temp_bon_cust A


set nocount off

select 
A.customer_telp, 
A.customer_name, 
B.customer_genderid,
B.region_id,
B.branch_id,
region_name = (select region_name from master_region where region_id=B.region_id),
branch_name = (select branch_name from master_branch where branch_id=B.branch_id),
B.bon_id, B.bon_date, B.bon_itemqty, B.bon_mtotal
from #temp_customer A inner join #temp_bon_cust B on A.customer_telp = B.customer_telp
order by  B.bon_date

set nocount on

drop table #temp_customer
drop table #temp_bon_cust

";



$rs = $conn->Execute($sql);

print "Data Found: " . $rs->recordCount() . "\n";

$total  = $rs->recordCount();

while (!$rs->EOF)
{
 	$customer_id = trim($rs->fields['customer_telp']);
 	$customer_name = trim($rs->fields['customer_name']);
 	$gender_id = trim($rs->fields['customer_genderid']);
	$region_id = trim($rs->fields['region_id']);
	$branch_id = trim($rs->fields['branch_id']);
	$bon_date  = $rs->fields['bon_date']; 
	$bon_id = $rs->fields['bon_id'];
 
 	$sqlcek = "select * from master_customer where customer_id='$customer_id '";
 	$rscek  = $conn->Execute($sqlcek);
 	
 	$i++; 
 	
 	if (!$rscek->recordCount())
 	{

 		unset($obj);
		$obj->customer_id = $customer_id;
		$obj->customer_namefull = $customer_name ? $customer_name  : $customer_id;
		$obj->customertype_id = "C";
		$obj->gender_id =  $gender_id;
		$obj->region_id =  $region_id;
		$obj->branch_id =  $branch_id;
		$obj->date = $bon_date;
		$obj->customer_createby = 'SYSTEM';
		$obj->customer_createdate= $bon_date;
		$obj->bon_creator = $bon_id;
		
		$SQL = SQLUTIL::SQL_InsertFromObject('master_customer', $obj);
		$conn->Execute($SQL);
		
		print "inserting $i of $total...\n";
 	}
	else
	{
	 	unset($obj);
		$obj->customer_modifyby = 'SYSTEM';
		$obj->customer_modifydate = $bon_date;
		$obj->date = $bon_date;
		$obj->bon_updator = $bon_id;
		
		$SQL = SQLUTIL::SQL_UpdateFromObject('master_customer', $obj, "customer_id='$customer_id' ");
		$conn->Execute($SQL);
		
		print "updating $i of $total...\n";		
		
	}
 
 
	$rs->MoveNext(); 
}




?>