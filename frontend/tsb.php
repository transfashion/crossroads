<?php
require_once dirname(__FILE__).'/start.inc.php';

set_time_limit(100);

$bulan   = $_GET['bulan']; 
if (!$bulan) {
	$bulan = date("m");
	$_GET['bulan'] = $bulan;
}


$tanggal = "2014-$bulan-".($_GET['tanggal']);

print "<b>LAPORAN SALES TSS BDG.</b><br>";

if ($_GET['tanggal']) {
	print "per $tanggal ".date("H:i:s");
	print "<br><Br>";
} else {
	$_GET['tanggal'] = date("d");
}


print "<form>";
print "<input type=\"hidden\" name=\"t\" value=\"".time()."\">";
print "<table>";
print "<tr>";
print "<td>";
print "<select name='bulan'>";
print "<option value=\"01\" " . (($_GET['bulan']=='01') ? "selected" : "") . ">JAN</option>";
print "<option value=\"02\" " . (($_GET['bulan']=='02') ? "selected" : "") . ">FEB</option>";
print "<option value=\"03\" " . (($_GET['bulan']=='03') ? "selected" : "") . ">MAR</option>";
print "<option value=\"04\" " . (($_GET['bulan']=='04') ? "selected" : "") . ">APR</option>";
print "<option value=\"05\" " . (($_GET['bulan']=='05') ? "selected" : "") . ">MEI</option>";
print "<option value=\"06\" " . (($_GET['bulan']=='06') ? "selected" : "") .
">JUN</option>";
print "<option value=\"07\" " . (($_GET['bulan']=='07') ? "selected" : "") .
">JUL</option>";
print "<option value=\"08\" " . (($_GET['bulan']=='08') ? "selected" : "") .
">AGT</option>";
print "<option value=\"09\" " . (($_GET['bulan']=='09') ? "selected" : "") .
">SEP</option>";
print "<option value=\"10\" " . (($_GET['bulan']=='10') ? "selected" : "") .
">OKT</option>";
print "<option value=\"11\" " . (($_GET['bulan']=='11') ? "selected" : "") .
">NOV</option>";
print "<option value=\"12\" " . (($_GET['bulan']=='12') ? "selected" : "") .
">DES</option>";
print "</select>";
print "</td>";

print "<td>";
print "<select name='tanggal'>";
print "<option " . (($_GET['tanggal']=='01') ? "selected" : "") . ">1</option>";
print "<option " . (($_GET['tanggal']=='02') ? "selected" : "") . ">2</option>";
print "<option " . (($_GET['tanggal']=='03') ? "selected" : "") . ">3</option>";
print "<option " . (($_GET['tanggal']=='04') ? "selected" : "") . ">4</option>";
print "<option " . (($_GET['tanggal']=='05') ? "selected" : "") . ">5</option>";
print "<option " . (($_GET['tanggal']=='06') ? "selected" : "") . ">6</option>";
print "<option " . (($_GET['tanggal']=='07') ? "selected" : "") . ">7</option>";
print "<option " . (($_GET['tanggal']=='08') ? "selected" : "") . ">8</option>";
print "<option " . (($_GET['tanggal']=='09') ? "selected" : "") . ">9</option>";
print "<option " . (($_GET['tanggal']=='10') ? "selected" : "") . ">10</option>";
print "<option " . (($_GET['tanggal']=='11') ? "selected" : "") . ">11</option>";
print "<option " . (($_GET['tanggal']=='12') ? "selected" : "") . ">12</option>";
print "<option " . (($_GET['tanggal']=='13') ? "selected" : "") . ">13</option>";
print "<option " . (($_GET['tanggal']=='14') ? "selected" : "") . ">14</option>";
print "<option " . (($_GET['tanggal']=='15') ? "selected" : "") . ">15</option>";
print "<option " . (($_GET['tanggal']=='16') ? "selected" : "") . ">16</option>";
print "<option " . (($_GET['tanggal']=='17') ? "selected" : "") . ">17</option>";
print "<option " . (($_GET['tanggal']=='18') ? "selected" : "") . ">18</option>";
print "<option " . (($_GET['tanggal']=='19') ? "selected" : "") . ">19</option>";
print "<option " . (($_GET['tanggal']=='20') ? "selected" : "") . ">20</option>";
print "<option " . (($_GET['tanggal']=='21') ? "selected" : "") . ">21</option>";
print "<option " . (($_GET['tanggal']=='22') ? "selected" : "") . ">22</option>";
print "<option " . (($_GET['tanggal']=='23') ? "selected" : "") . ">23</option>";
print "<option " . (($_GET['tanggal']=='24') ? "selected" : "") . ">24</option>";
print "<option " . (($_GET['tanggal']=='25') ? "selected" : "") . ">25</option>";
print "<option " . (($_GET['tanggal']=='26') ? "selected" : "") . ">26</option>";
print "<option " . (($_GET['tanggal']=='27') ? "selected" : "") . ">27</option>";
print "<option " . (($_GET['tanggal']=='28') ? "selected" : "") . ">28</option>";
print "<option " . (($_GET['tanggal']=='29') ? "selected" : "") . ">29</option>";
print "<option " . (($_GET['tanggal']=='30') ? "selected" : "") . ">30</option>";
print "<option " . (($_GET['tanggal']=='31') ? "selected" : "") . ">31</option>";
print "</select>";
print "</td>";
print "<td>";
print "<input type='submit' name='sub' value='ok'>";
print "</td>";
print "</form>";





$sql = "

DECLARE @region_id as varchar(5);
DECLARE @datestart as smalldatetime;
DECLARE @dateend as smalldatetime;


SET @region_id = '01700';
SET @datestart = '$tanggal';
SET @dateend = '$tanggal';


BEGIN
			
					set nocount on;
			
	
					select 
						 A.branch_id, 
						 A.bon_id, 
						 A.bon_date, 
						 A.bon_createby, 
						 A.heinv_id,
						 A.bon_region_id, 
						 A.bondetil_qty, 
						 itemgrossori,
						 itemgross, 
						 itemnett,
						 nett		 
					INTO #TEMP_BONLIST_134665
					from dbo.view_hepos_bonlist_1 A
					where
					A.bon_isvoid = 0
					AND convert(varchar(10),A.bon_date,120)>=convert(varchar(10),@datestart,120)
					AND convert(varchar(10),A.bon_date,120)<=convert(varchar(10),@dateend,120)
					AND A.region_id = @region_id
					ORDER BY A.branch_id, A.bon_date

 

					SELECT A.branch_id, A.branch_name 
					INTO #TEMP_BRANCH_134665
					FROM master_branch A inner join master_regionbranch B
					ON A.branch_id = B.branch_id
					WHERE B.region_id = @region_id
					--AND A.branch_id NOT IN ('0000200', '0000210');
					AND A.branch_id IN (
					'0002601',
					'0002604',
					'0002613',
					'0002615',
					'0002616',
					'0002617',
                                        '0002618' 
					);
 
					SELECT 
					branch_id,
					sales_qty = SUM(bondetil_qty),
					sales_gross = SUM(itemgrossori),
					sales_nett = SUM(nett)
					INTO  #TEMP_BONSUMBS_134665
					FROM  #TEMP_BONLIST_134665
					GROUP BY branch_id
 
					set nocount off;
			
					SELECT
					A.branch_id,
					A.branch_name,
					sales_qty = isnull(B.sales_qty, 0),
					sales_gross = isnull(B.sales_gross, 0),
					sales_nett = isnull(B.sales_nett, 0)
					FROM #TEMP_BRANCH_134665 A LEFT JOIN #TEMP_BONSUMBS_134665 B
					ON A.branch_id = B.branch_id

			
					set nocount on;
			
					DROP TABLE #TEMP_BONSUMBS_134665;
					DROP TABLE #TEMP_BONLIST_134665;	
					DROP TABLE #TEMP_BRANCH_134665;			 
			
END	

";



if ($_GET['sub']=='ok') {


		$rs = $conn->Execute($sql);
		
		print "<table border=1>";
		while (!$rs->EOF) {
			$branch_id = $rs->fields['branch_id'];
			$branch_name = $rs->fields['branch_name'];
			$sales_qty = $rs->fields['sales_qty'];
			$sales_gross = $rs->fields['sales_gross'];
			$sales_nett = $rs->fields['sales_nett'];
			
		
			print "<tr>";
			print "<td>$branch_id</td>";
			print "<td>$branch_name</td>";
			print "<td align='right'>".number_format($sales_qty)."</td>";
			print "<td align='right'>".number_format($sales_gross)."</td>";
			print "<td align='right'>".number_format($sales_nett)."</td>";
			print "</tr>";	
		
		
			$tot_qty += $sales_qty;
			$tot_gross += $sales_gross;
			$tot_nett += $sales_nett;
		
			$rs->MoveNext();
		}
		
		print "<tr>";
		print "<td bgcolor='#CCCCC'> </td>";
		print "<td bgcolor='#CCCCC'><b>TOTAL</b></td>";
		print "<td bgcolor='#CCCCC' align='right'><b>".number_format($tot_qty)."</b></td>";
		print "<td bgcolor='#CCCCC' align='right'><b>".number_format($tot_gross)."</b></td>";
		print "<td bgcolor='#CCCCC' align='right'><b>".number_format($tot_nett)."</b></td>";
		print "</tr>";	
		

		print "</table>";

}
?>