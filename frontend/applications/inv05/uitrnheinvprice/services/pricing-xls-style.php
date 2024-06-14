<?

class XlsDocStyles {

	public static $DATA = array(
		'TITLE1' => array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 15,
				'name'  => 'Verdana'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			),
		),


		'TITLE2' => array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 12,
				'name'  => 'Verdana'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			),
		),


		'HEAD1' => array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ffff00')
			),

			'borders' => array(
				'bottom' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' =>  array('rgb' => '000000')
				)
			),

			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Verdana'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			),
		),


		'NORMAL' => array(
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Verdana'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			),
		),
		
		'NORMAL_DEC_0' => array(
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Verdana'
			),

			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			),

			'numberformat' => array(
				'code' => '#,##0'   //PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3
			)

		),

		'NORMAL_DEC_2' => array(
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Verdana'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			),

			'numberformat' => array(
				'code' => PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
			)			
		),

		'NORMAL_PERCENT' => array(
			'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Verdana'
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
			),

			'numberformat' => array(
				'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
			)			
		)		

	);		


	public static function get($stylename) {
		$HEAD2 = self::$DATA['HEAD1'];
		$HEAD2['fill']['color']['rgb'] = '3465a4';

		$HEAD3 = self::$DATA['HEAD1'];
		$HEAD3['fill']['color']['rgb'] = '8d1d75';
		$HEAD3['font']['color']['rgb'] = 'ffffff';

		$HEAD4 = self::$DATA['HEAD1'];
		$HEAD4['fill']['color']['rgb'] = '4e102d';
		$HEAD4['font']['color']['rgb'] = 'ffffff';

		$HEAD5 = self::$DATA['HEAD1'];
		$HEAD5['fill']['color']['rgb'] = '468a1a';

		self::$DATA['HEAD2'] = $HEAD2;
		self::$DATA['HEAD3'] = $HEAD3;
		self::$DATA['HEAD4'] = $HEAD4;
		self::$DATA['HEAD5'] = $HEAD5;
		
		return self::$DATA[$stylename];
	}


}

?>