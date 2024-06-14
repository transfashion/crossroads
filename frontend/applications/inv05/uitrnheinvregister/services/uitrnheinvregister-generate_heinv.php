 <?
   function HeinvUpdate($data, $season_id, $region_id, $username, $OLD_DATA, &$conn) {

		$SQL = "";
		
		try {

   			unset($obj);
			$obj->heinv_isdisabled = 0;
			$obj->heinv_modifyby = $username;
			$obj->heinv_modifydate = SQLUTIL::SQL_GetNowDate();
			$obj->heinv_produk = $data->heinv_produk;
			$obj->heinv_bahan = $data->heinv_bahan;
			$obj->heinv_pemeliharaan = $data->heinv_pemeliharaan;
			$obj->heinv_logo = $data->heinv_logo;
			$obj->heinv_dibuatdi = $data->heinv_dibuatdi;
			$obj->heinv_other1 = $data->heinv_other1;
			$obj->heinv_other2 = $data->heinv_other2;
			$obj->heinv_other3 = $data->heinv_other3;
			$obj->heinv_other4 = $data->heinv_other4;
			$obj->heinv_other5 = $data->heinv_other5;
			$obj->heinv_group1 = $data->heinv_other4;
			$obj->heinv_group2 = $data->heinv_other5;
			$obj->heinv_color1 = $data->heinv_colordescr;
			$obj->heinv_gender = $data->heinv_gender;
			$obj->heinv_tobemap = 9;
			$obj->heinv_hscode_ship = $data->heinv_hscode_ship;
			$obj->heinv_hscode_ina = $data->heinv_hscode_ina;
			$obj->heinv_plbname = $data->heinv_plbname;

			$obj->heinv_isweb 		= $data->heinv_isweb;
			$obj->heinv_weight 		= $data->heinv_weight;
			$obj->heinv_length 		= $data->heinv_length;
			$obj->heinv_width 		= $data->heinv_width;
			$obj->heinv_height 		= $data->heinv_height;
			$obj->heinv_webdescr 	= $data->heinv_webdescr;

			$obj->ref_id = $data->heinvregister_id;


			$sql = "select invcls_id from master_heinvctg where heinvctg_id = '".$data->heinvctg_id."'";
			$rs  = $conn->Execute($sql);
			$invcls_id  = $rs->fields['invcls_id'];		
			$obj->invcls_id = $invcls_id;

			$SQL = SQLUTIL::SQL_UpdateFromObject("master_heinv", $obj, "heinv_id='$data->heinv_id'");
			$conn->Execute($SQL);


			
			/* jika di data sebelumnya disabled */
			if ($OLD_DATA->fields['heinv_isdisabled']) {
				$sql = "SELECT log_line=MAX(log_line) FROM transaksi_tlog WHERE id='$data->heinv_id' ";
				$rs = $conn->Execute($sql);
				$log_line = ((int) $rs->fields['log_line']) + 10;
			
				unset($obj);
				$obj->id = $data->heinv_id;
				$obj->log_line = $log_line;
				$obj->log_date = SQLUTIL::SQL_GetNowDate();
				$obj->log_action = 'SET TO ENABLE';
				$obj->log_table = 'master_heinv';
				$obj->log_descr = 'Updated from register no '.$data->heinvregister_id.", line ".$data->heinvregisteritem_line;
				$obj->log_lastvalue = '';
				$obj->log_username = $username;
				$SQL = SQLUTIL::SQL_InsertFromObject("transaksi_tlog", $obj);
				$conn->Execute($SQL);				
			}
		 
	   	
	   		return true ;
		
		} catch (Exception $e) {
			// echo $e->getMessage();
			// tulis error di file
			$filename = dirname(__FILE__)."/sqllog.txt";
			$fp = fopen($filename, "w");
			fputs($fp, $e->getMessage());
			fputs($fp, "\r\n");
			fputs($fp, implode(",\r\n", explode(",", $SQL)));

			throw $e;
		}		

	}



   
   function HeinvInsert($data, $season_id, $region_id, $username, &$conn) {
		try {
			$sql = "DECLARE @id as nvarchar(13);
			       EXEC sp_sequencer_heinv 'TM', @id;";
			$rs  = $conn->Execute($sql);
			$heinv_id  = $rs->fields['id'];		
			
			$obj->heinv_id 		= $heinv_id;
			$obj->heinv_art		= $data->heinv_art;
			$obj->heinv_mat		= $data->heinv_mat;
			$obj->heinv_col		= $data->heinv_col;
			$obj->heinv_name		= $data->heinv_name;
			$obj->heinv_descr		= $data->heinv_descr;
			$obj->heinv_gtype		= $data->heinv_gtype;
			$obj->heinv_produk	= $data->heinv_produk;
			$obj->heinv_bahan		= $data->heinv_bahan;
			$obj->heinv_pemeliharaan = $data->heinv_pemeliharaan;
			$obj->heinv_logo		= $data->heinv_logo;
			$obj->heinv_dibuatdi	= $data->heinv_dibuatdi;
			$obj->heinv_other1	= $data->heinv_other1;
			// $obj->heinv_other2	= $data->heinv_other2;
			// $obj->heinv_other3	= $data->heinv_other3;
			// $obj->heinv_other4	= $data->heinv_other4;
			// $obj->heinv_other5	= $data->heinv_other5;
			$obj->heinv_other2 = $data->heinv_other2;  // line
			$obj->heinv_other3 = $data->heinv_other3;  // fit
			$obj->heinv_other4 = $data->heinv_other4;
			$obj->heinv_other5 = $data->heinv_other5;
			$obj->heinv_group1 = $data->heinv_other4;
			$obj->heinv_group2 = $data->heinv_other5;
			$obj->heinv_color1 = $data->heinv_colordescr;
			$obj->heinv_gender = $data->heinv_gender;
			$obj->heinv_tobemap = 9;

			$obj->heinv_priceori	= 0;
			$obj->heinv_price01	= 0;
			$obj->heinv_pricedisc01	= 0;
			$obj->heinv_price02	= 0;
			$obj->heinv_pricedisc02	= 0;
			$obj->heinv_price03	= 0;
			$obj->heinv_pricedisc03	= 0;
			$obj->heinv_price04	= 0;
			$obj->heinv_pricedisc04	= 0;
			$obj->heinv_price05	= 0;
			$obj->heinv_pricedisc05	= 0;
			$obj->heinv_createby	= $username;
			$obj->heinvctg_id		= $data->heinvctg_id;
			$obj->heinvgro_id		= $data->heinvgro_id;
			$obj->season_id		= $season_id;
			$obj->region_id		= $region_id;			

			$sql = "select invcls_id from master_heinvctg where heinvctg_id = '".$obj->heinvctg_id."'";
			$rs  = $conn->Execute($sql);
			$invcls_id  = $rs->fields['invcls_id'];		
			$obj->invcls_id = $invcls_id;

			$obj->heinv_hscode_ship = $data->heinv_hscode_ship;
			$obj->heinv_hscode_ina = $data->heinv_hscode_ina;
			$obj->heinv_plbname = $data->heinv_plbname;

			$obj->heinv_isweb 		= $data->heinv_isweb;
			$obj->heinv_weight 		= $data->heinv_weight;
			$obj->heinv_length 		= $data->heinv_length;
			$obj->heinv_width 		= $data->heinv_width;
			$obj->heinv_height 		= $data->heinv_height;
			$obj->heinv_webdescr 	= $data->heinv_webdescr;

			$obj->ref_id = $data->heinvregister_id;
			
			$SQL = SQLUTIL::SQL_InsertFromObject("master_heinv", $obj);
			$conn->Execute($SQL);			
			
			/* Masukkan ke Log */
			$sql = "SELECT log_line=MAX(log_line) FROM transaksi_tlog WHERE id='$heinv_id' ";
			$rs = $conn->Execute($sql);
			$log_line = ((int) $rs->fields['log_line']) + 10;			
		
			unset($obj);
			$obj->id = $heinv_id;
			$obj->log_line = $log_line;
			$obj->log_date = SQLUTIL::SQL_GetNowDate();
			$obj->log_action = 'INSERT';
			$obj->log_table = 'master_heinv';
			$obj->log_descr = 'Generated from register no '.$data->heinvregister_id.", line ".$data->heinvregisteritem_line;
			$obj->log_lastvalue = '';
			$obj->log_username = $username;
			
			$SQL = SQLUTIL::SQL_InsertFromObject("transaksi_tlog", $obj);
			$conn->Execute($SQL);
			
			return $heinv_id;

		} catch (Exception $e) {
			echo $e->getMessage();
			throw $e;			
		}   
   }   
   
   function HeinvUpdateBarcode($heinv_id, $barcode, $size, &$conn) {
   	if ($heinv_id) {
			$sql = "select * from master_heinvitem where heinv_id='$heinv_id' AND heinvitem_barcode='$barcode' AND heinvitem_size='$size' ";   
			$rs  = $conn->Execute($sql);
			$colnumber = $rs->fields['heinvitem_colnum'];
			if (!$rs->recordCount()) {
				/* cari di kolom no berapa */
				$sql = "select * from master_heinv where heinv_id='$heinv_id' ";
				$rs  = $conn->Execute($sql);
				$region_id = $rs->fields['region_id'];
				$heinvctg_id = $rs->fields['heinvctg_id'];
				
				$sql = "select heinvctg_name, heinvctg_sizetag, heinvgro_id from master_heinvctg where heinvctg_id='$heinvctg_id' and region_id='$region_id' ";
				$rs = $conn->Execute($sql);
				$heinvctg_sizetag = $rs->fields['heinvctg_sizetag'];						

				$sql = "SELECT * FROM master_heinvsizetag WHERE SIZETAG='$heinvctg_sizetag' and region_id='$region_id' ";
				$rs = $conn->Execute($sql);	
				
				$sizefound = false;
				for ($iC=1; $iC<=25; $iC++) {
					$colname = "C" . str_pad($iC, 2, "0", STR_PAD_LEFT);
					if (trim($rs->fields[$colname])==trim($size)) {
						$sizefound = true;
						break;					
					}
				}				
			
				if (!$sizefound) {
					$sql = "SELECT * FROM master_heinvsizetagshadow WHERE SIZETAG='$heinvctg_sizetag' and region_id='$region_id' ";
					$rs = $conn->Execute($sql);
					
					$sizefound = false;
					for ($iC=1; $iC<=25; $iC++) {
						$colname = "C" . str_pad($iC, 2, "0", STR_PAD_LEFT);
						if (trim($rs->fields[$colname])==trim($size)) {
							$sizefound = true;
							break;					
						}
					}


					/* cari balik nama sizenya */
					if ($sizefound) {
						$sql = "SELECT * FROM master_heinvsizetag WHERE SIZETAG='$heinvctg_sizetag' and region_id='$region_id' ";
						$rs = $conn->Execute($sql);	
						$size = $rs->fields[$colname];
					}


				}

				if (!$sizefound) {
					$colname = "C00";
				}

				/* buat data barcode baru */
				$sql = " SELECT maxline=MAX(heinvitem_line) FROM master_heinvitem WHERE heinv_id='$heinv_id' ";
				$rs  = $conn->Execute($sql);
				$line = $rs->fields['maxline'];
				$line++;

				unset($obj);
				$obj->heinv_id = $heinv_id;
				$obj->heinvitem_line = $line;
				$obj->heinvitem_size = $size;
				$obj->heinvitem_colnum = substr($colname,1,2);
				$obj->heinvitem_barcode = trim($barcode);
				$obj->region_id = $region_id;
				
				//$sql = "select * from master_heinvitem where heinv_id='".$obj->heinv_id."' and heinvitem_barcode='".$obj->heinvitem_barcode."'";
				//$rsI = $conn->Execute($sql);
				//if (!$rs->recordCount())
				//{
				try {
					if ($obj->heinvitem_barcode) { 
						$SQL = SQLUTIL::SQL_InsertFromObject("master_heinvitem", $obj);
						$conn->Execute($SQL);
					}
					
				} catch (Exception $e) {
				} 	
	
				//}
				return $obj->heinvitem_colnum;
			
			} else {
				return $colnumber;
			} 				
   	}
   }
   
   
?>
