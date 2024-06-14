<?
	require_once dirname(__FILE__)."/../../../../PHPExcel-1.8/Classes/PHPExcel.php";
	require_once dirname(__FILE__)."/pricing-xls-style.php";


	class XlsDoc {
		function __construct($id) {
			$this->pricing_id = $id;

			$this->doc = new PHPExcel();
			$this->doc->getProperties()->setCreator("TransBrowser")
						->setLastModifiedBy("TransBrowser")
						->setTitle("Pricing $id")
						->setSubject("Pricing $id");

			$this->doc->setActiveSheetIndex(0);	
			
			$this->data = array();
		}


		public function getColumns() {
			return array(
				'price_id' => array('headertext'=>'price_id', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'A', 'formula'=>''),
				'pricedetil_line' => array('headertext'=>'pricedetil_line', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'B', 'formula'=>''),
				'price_isnewitemprice' => array('headertext'=>'NewItemPricing', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'C', 'formula'=>''),
				'ref_id' => array('headertext'=>'ref_id', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'D', 'formula'=>''),
				'heinvgro_id' => array('headertext'=>'heinvgro_id', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'E', 'formula'=>''),
				'heinvctg_id' => array('headertext'=>'heinvctg_id', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'F', 'formula'=>''),
				'heinvctg_name' => array('headertext'=>'heinvctg_name', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'G', 'formula'=>''),
				'season_id' => array('headertext'=>'season_id', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'H', 'formula'=>''),
				'heinv_id' => array('headertext'=>'heinv_id', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'I', 'formula'=>''),
				'heinv_art' => array('headertext'=>'heinv_art', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'J', 'formula'=>''),
				'heinv_mat' => array('headertext'=>'heinv_mat', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'K', 'formula'=>''),
				'heinv_col' => array('headertext'=>'heinv_col', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'L', 'formula'=>''),
				'heinv_lastqty' => array('headertext'=>'heinv_lastqty', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'M', 'formula'=>''),
				'heinv_age' => array('headertext'=>'heinv_age', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'N', 'formula'=>''),
				'heinv_currentpricegross' => array('headertext'=>'current pricegross', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'O', 'formula'=>''),
				'heinv_currentprice' => array('headertext'=>'Current Label Price', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'P', 'formula'=>''),
				'heinv_currentpricedisc' => array('headertext'=>'Current Label Disc', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'Q', 'formula'=>''),
				'heinv_currentpricenett' => array('headertext'=>'Current Price Nett', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'R', 'formula'=>''),
				'heinv_ordered' => array('headertext'=>'Ordered', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'S', 'formula'=>''),
				'currency_id' => array('headertext'=>'Currency', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL',  'column'=>'T', 'formula'=>''),
				'heinv_fob' => array('headertext'=>'heinv_fob', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_2',  'column'=>'U', 'formula'=>''),
				'heinv_rate' => array('headertext'=>'heinv_rate', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'V', 'formula'=>''),
				'heinv_fobidr' => array('headertext'=>'heinv_fobidr', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'W', 'formula'=>''),
				'heinv_extracost' => array('headertext'=>'heinv_extracost', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_2',  'column'=>'X', 'formula'=>''),
				'heinv_cost' => array('headertext'=>'Landed Cost', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'Y', 'formula'=>''),
				'heinv_minmf' => array('headertext'=>'Minimum MF', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_2',  'column'=>'Z', 'formula'=>''),
				'calcmfprice' => array('headertext'=>'CalcMFPrice', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AA', 'formula'=>''),
				'heinv_price_hk' => array('headertext'=>'HK Retail in IDR', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AB', 'formula'=>''),
				'heinv_price_sin' => array('headertext'=>'SIN Retail in IDR', 'HeaderStyle'=>'HEAD1', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AC', 'formula'=>''),
				'proposed_price' => array('headertext'=>'Price', 'HeaderStyle'=>'HEAD2', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AD', 'formula'=>''),
				'proposed_pricedisc' => array('headertext'=>'Disc', 'HeaderStyle'=>'HEAD2', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AE', 'formula'=>''),
				'calc_nett' => array('headertext'=>'Nett', 'HeaderStyle'=>'HEAD5', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AF', 'formula'=>'=((1-(AE{row}/100))*AD{row})'),
				'calc_mfonfob' => array('headertext'=>'MF on FOB', 'HeaderStyle'=>'HEAD3', 'RowStyle'=>'NORMAL_DEC_2',  'column'=>'AG', 'formula'=>'=AD{row}/W{row}'),
				'calc_mfonlandedcost' => array('headertext'=>'MF on Landed Cost', 'HeaderStyle'=>'HEAD3', 'RowStyle'=>'NORMAL_DEC_2',  'column'=>'AH', 'formula'=>'=AD{row}/Y{row}'),
				'calc_varcalc' => array('headertext'=>'Var  Calc vs Proposed', 'HeaderStyle'=>'HEAD3', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AI', 'formula'=>'=AD{row}-AA{row}'),
				'calc_varhk' => array('headertext'=>'%var Propesed vs HK', 'HeaderStyle'=>'HEAD3', 'RowStyle'=>'NORMAL_PERCENT',  'column'=>'AJ', 'formula'=>'=(AB{row}-AD{row})/AD{row}'),
				'calc_varsin' => array('headertext'=>'%var Propesed vs SIN', 'HeaderStyle'=>'HEAD3', 'RowStyle'=>'NORMAL_PERCENT',  'column'=>'AK', 'formula'=>'=(AC{row}-AD{row})/AD{row}'),
				'calc_vat' => array('headertext'=>'VAT', 'HeaderStyle'=>'HEAD4', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AL', 'formula'=>'=(AF{row}-(AF{row}/1.11))'),
				'calc_gp' => array('headertext'=>'Gross Margin per Unit', 'HeaderStyle'=>'HEAD4', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AM', 'formula'=>'=(AF{row}/1.11)-Y{row}'),
				'calc_gptotal' => array('headertext'=>'Total Gross Margin', 'HeaderStyle'=>'HEAD4', 'RowStyle'=>'NORMAL_DEC_0',  'column'=>'AN', 'formula'=>'=AM{row}*(S{row}+M{row})'),
				'calc_gppercent' => array('headertext'=>'% Gross Margin per unit (nett)', 'HeaderStyle'=>'HEAD4', 'RowStyle'=>'NORMAL_PERCENT',  'column'=>'AO', 'formula'=>'=((AF{row}/1.11)-Y{row})/(AF{row}/1.11)'),
				'calc_gppercentgross' => array('headertext'=>'% Gross Margin per unit (gross)', 'HeaderStyle'=>'HEAD4', 'RowStyle'=>'NORMAL_PERCENT',  'column'=>'AP', 'formula'=>'=((AD{row}/1.11)-Y{row})/(AD{row}/1.11)'),
			);
		}



		public function Compose() {
			$sheet = $this->doc->getActiveSheet();
			$sheet->setTitle("Pricing");

			$this->WriteCell_DocTitle($sheet, $this->pricing_id);
			$this->WriteCell_DocHeader($sheet);
			$this->WriteCell_RowData($sheet);

			$this->WriteCell_AutoSize($sheet);
		}

		public function AddRecord($rs) {
			$columns = $this->getColumns();
			$row = array();
			foreach ($columns as $colid=>$col) {
				if ($col['formula']=='') {
					$row[$colid] = $rs->fields[$colid];
				}
			}
			$this->data[] = $row;
		}

		public function WriteCell_DocTitle($sheet, $text) {
			$sheet->getStyle('A1')->applyFromArray(XlsDocStyles::get('TITLE1'));
			$sheet->getRowDimension(1)->setRowHeight(22);
			$sheet->setCellValue('A1', $text);	
		}


		public function WriteCell_DocHeader($sheet) {
			$rownum = 3;
			$columns = $this->getColumns();

			$sheet->getRowDimension($rownum)->setRowHeight(22);
			foreach ($columns as $col) {
				$colname = $col['column'].$rownum;
				$headerstyle = $col['HeaderStyle'];
				$headertext = $col['headertext'];
				$sheet->getStyle($colname )->applyFromArray(XlsDocStyles::get($headerstyle));
				$sheet->setCellValue($colname, $headertext);
			}
		}


		public function WriteCell_RowData($sheet) {
			$rownum = 4;

			foreach ($this->data as $row) {
				$columns = $this->getColumns();
				foreach ($columns as $colid=>$col) {
					$colname = $col['column'].$rownum;
					$style = $col['RowStyle'];
					$formula = $col['formula'];
					$text = $row[$colid];					
					
					
					$sheet->getStyle($colname )->applyFromArray(XlsDocStyles::get($style));

					if ($formula!='') {
						$rc11 = str_replace("{row}", $rownum, $formula);
						$sheet->setCellValue($colname, $rc11);
					} else {
						$sheet->setCellValue($colname, $text);
					}					
				}


				$rownum++;
			}
		}

		public function WriteCell_AutoSize($sheet) {
			$columns = $this->getColumns();
			foreach ($columns as $col) {
				$sheet->getColumnDimension($col['column'])->setAutoSize(true);
			}
		}

		public function Save($filepath) {
			$objWriter = PHPExcel_IOFactory::createWriter($this->doc, 'Excel2007');
			$objWriter->setPreCalculateFormulas();
			$objWriter->save($filepath);
		}

		
	}


?>