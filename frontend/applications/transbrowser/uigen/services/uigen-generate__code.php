<?

$datatypes = array(
    'datetime'  => array('datatabletype'=>'DateTime', 'defaultvalue'=>'Now()'),
    'int'       => array('datatabletype'=>'Int32', 'defaultvalue'=>'0'),
    'nchar'     => array('datatabletype'=>'String', 'defaultvalue'=>'""'),
    'numeric'   => array('datatabletype'=>'Int64', 'defaultvalue'=>'0'),
    'nvarchar'  => array('datatabletype'=>'String', 'defaultvalue'=>'""'),
    'smalldatetime' => array('datatabletype'=>'DateTime', 'defaultvalue'=>'Now()'),
    'tinyint' => array('datatabletype'=>'Boolean', 'defaultvalue'=>'False'),
    'varchar' => array('datatabletype'=>'String', 'defaultvalue'=>'""'),
    'decimal' => array('datatabletype'=>'Decimal', 'defaultvalue'=>'0'),
);






/* MUALI COMPOSE CODENYA
/********************************************************************************************************************************/

	$sql = "SELECT * FROM transbrowser_uigen WHERE uigen_id='$id' ";
	$rs  = $conn->Execute($sql);
    $uigen_name = trim($rs->fields['uigen_name']);
	$uigen_text = trim($rs->fields['uigen_text']);
	$uigen_descr = trim($rs->fields['uigen_descr']);
	$uigen_type = trim($rs->fields['uigen_type']);
	$uigen_header = trim($rs->fields['uigen_header']);
	$uigen_namespace = trim($rs->fields['uigen_namespace']);
	$uigen_objectname = trim($rs->fields['uigen_objectname']);
	$uigen_dll = trim($rs->fields['uigen_dll']);
	$uigen_issingleinstance = trim($rs->fields['uigen_issingleinstance']);
	$uigen_islocaldb = trim($rs->fields['uigen_islocaldb']);
	$uigen_wsns = trim($rs->fields['uigen_wsns']);
	$uigen_wsobject = trim($rs->fields['uigen_wsobject']);
	$uigen_dataheadertable = trim($rs->fields['uigen_dataheadertable']);
	$uigen_dataheaderfpk = trim($rs->fields['uigen_dataheaderfpk']);
	$uigen_dataheaderfcb = trim($rs->fields['uigen_dataheaderfcb']);
	$uigen_dataheaderfcd = trim($rs->fields['uigen_dataheaderfcd']);
	$uigen_dataheaderfmb = trim($rs->fields['uigen_dataheaderfmb']);
	$uigen_dataheaderfmd = trim($rs->fields['uigen_dataheaderfmd']);
	$uigen_datadetil1use = trim($rs->fields['uigen_datadetil1use']);
	$uigen_datadetil1name = trim($rs->fields['uigen_datadetil1name']);
	$uigen_datadetil1table = trim($rs->fields['uigen_datadetil1table']);
	$uigen_datadetil1fpk1 = trim($rs->fields['uigen_datadetil1fpk1']);
	$uigen_datadetil1fpk2 = trim($rs->fields['uigen_datadetil1fpk2']);
	$uigen_datadetil1text = trim($rs->fields['uigen_datadetil1text']);
	$uigen_datadetil2use = trim($rs->fields['uigen_datadetil2use']);
	$uigen_datadetil2name = trim($rs->fields['uigen_datadetil2name']);
	$uigen_datadetil2table = trim($rs->fields['uigen_datadetil2table']);
	$uigen_datadetil2fpk1 = trim($rs->fields['uigen_datadetil2fpk1']);
	$uigen_datadetil2fpk2 = trim($rs->fields['uigen_datadetil2fpk2']);
	$uigen_datadetil2text = trim($rs->fields['uigen_datadetil2text']);
	$uigen_datadetil3use = trim($rs->fields['uigen_datadetil3use']);
	$uigen_datadetil3name = trim($rs->fields['uigen_datadetil3name']);
	$uigen_datadetil3table = trim($rs->fields['uigen_datadetil3table']);
	$uigen_datadetil3fpk1 = trim($rs->fields['uigen_datadetil3fpk1']);
	$uigen_datadetil3fpk2 = trim($rs->fields['uigen_datadetil3fpk2']);
	$uigen_datadetil3text = trim($rs->fields['uigen_datadetil3text']);
	$uigen_datadetil4use = trim($rs->fields['uigen_datadetil4use']);
	$uigen_datadetil4name = trim($rs->fields['uigen_datadetil4name']);
	$uigen_datadetil4table = trim($rs->fields['uigen_datadetil4table']);
	$uigen_datadetil4fpk1 = trim($rs->fields['uigen_datadetil4fpk1']);
	$uigen_datadetil4fpk2 = trim($rs->fields['uigen_datadetil4fpk2']);
	$uigen_datadetil4text = trim($rs->fields['uigen_datadetil4text']);
	$uigen_datadetil5use = trim($rs->fields['uigen_datadetil5use']);
	$uigen_datadetil5name = trim($rs->fields['uigen_datadetil5name']);
	$uigen_datadetil5table = trim($rs->fields['uigen_datadetil5table']);
	$uigen_datadetil5fpk1 = trim($rs->fields['uigen_datadetil5fpk1']);
	$uigen_datadetil5fpk2 = trim($rs->fields['uigen_datadetil5fpk2']);
	$uigen_datadetil5text = trim($rs->fields['uigen_datadetil5text']);
	$uigen_createby = trim($rs->fields['uigen_createby']);
	$uigen_createdate = trim($rs->fields['uigen_createdate']);
	$uigen_modifyby = trim($rs->fields['uigen_modifyby']);
	$uigen_modifydate = trim($rs->fields['uigen_modifydate']);

    
	



	unset($i);
	//$conn->debug = true;
	$sql = "SELECT * FROM transbrowser_uigendetil WHERE uigen_id='$id' AND uigen_datadetilname='DetilH' ORDER BY uigendetil_seq";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$i++;

        unset($obj);
        $obj->i                       = $i;  
		$obj->uigendetil_name 		  = trim($rs->fields['uigendetil_name']);
		$obj->uigendetil_text         = trim($rs->fields['uigendetil_text']);
		$obj->uigendetil_datatype     = trim($rs->fields['uigendetil_datatype']);
		$obj->uigendetil_datalen      = trim($rs->fields['uigendetil_datalen']);
		$obj->uigendetil_dataprec     = trim($rs->fields['uigendetil_dataprec']);
		$obj->uigendetil_isgenerate   = trim($rs->fields['uigendetil_isgenerate']);
		$obj->uigendetil_type     	  = trim($rs->fields['uigendetil_type']);
		$obj->uigendetil_objectwidth  = trim($rs->fields['uigendetil_objectwidth']);
		$obj->uigendetil_objectcolor  = trim($rs->fields['uigendetil_objectcolor']);
		$obj->uigendetil_islisted     = trim($rs->fields['uigendetil_islisted']);
		$obj->uigendetil_issearch     = trim($rs->fields['uigendetil_issearch']);
		$obj->uigendetil_isvisible    = trim($rs->fields['uigendetil_isvisible']);
		$obj->uigendetil_isenabled    = trim($rs->fields['uigendetil_isenabled']);
		$obj->uigendetil_textalign    = trim($rs->fields['uigendetil_textalign']);
		$DH[] = CreateResultSet("H", $obj);
	
		$rs->MoveNext();
	}


	unset($i);
	//$conn->debug = true;
	$sql = "SELECT * FROM transbrowser_uigendetil WHERE uigen_id='$id' AND uigen_datadetilname='Detil1' ORDER BY uigendetil_seq";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$i++;

        unset($obj);
        $obj->i                       = $i;  
		$obj->uigendetil_name 		  = trim($rs->fields['uigendetil_name']);
		$obj->uigendetil_text         = trim($rs->fields['uigendetil_text']);
		$obj->uigendetil_datatype     = trim($rs->fields['uigendetil_datatype']);
		$obj->uigendetil_datalen      = trim($rs->fields['uigendetil_datalen']);
		$obj->uigendetil_dataprec     = trim($rs->fields['uigendetil_dataprec']);
		$obj->uigendetil_isgenerate   = trim($rs->fields['uigendetil_isgenerate']);
		$obj->uigendetil_type     	  = trim($rs->fields['uigendetil_type']);
		$obj->uigendetil_objectwidth  = trim($rs->fields['uigendetil_objectwidth']);
		$obj->uigendetil_objectcolor  = trim($rs->fields['uigendetil_objectcolor']);
		$obj->uigendetil_islisted     = trim($rs->fields['uigendetil_islisted']);
		$obj->uigendetil_issearch     = trim($rs->fields['uigendetil_issearch']);
		$obj->uigendetil_isvisible    = trim($rs->fields['uigendetil_isvisible']);
		$obj->uigendetil_isenabled    = trim($rs->fields['uigendetil_isenabled']);
		$obj->uigendetil_textalign    = trim($rs->fields['uigendetil_textalign']);
		
		$D1[] = CreateResultSet("D", $obj);
	
		$rs->MoveNext();
	}

	
	unset($i);
	//$conn->debug = true;
	$sql = "SELECT * FROM transbrowser_uigendetil WHERE uigen_id='$id' AND uigen_datadetilname='Detil2' ORDER BY uigendetil_seq";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$i++;

        unset($obj);
        $obj->i                       = $i;  
		$obj->uigendetil_name 		  = trim($rs->fields['uigendetil_name']);
		$obj->uigendetil_text         = trim($rs->fields['uigendetil_text']);
		$obj->uigendetil_datatype     = trim($rs->fields['uigendetil_datatype']);
		$obj->uigendetil_datalen      = trim($rs->fields['uigendetil_datalen']);
		$obj->uigendetil_dataprec     = trim($rs->fields['uigendetil_dataprec']);
		$obj->uigendetil_isgenerate   = trim($rs->fields['uigendetil_isgenerate']);
		$obj->uigendetil_type     	  = trim($rs->fields['uigendetil_type']);
		$obj->uigendetil_objectwidth  = trim($rs->fields['uigendetil_objectwidth']);
		$obj->uigendetil_objectcolor  = trim($rs->fields['uigendetil_objectcolor']);
		$obj->uigendetil_islisted     = trim($rs->fields['uigendetil_islisted']);
		$obj->uigendetil_issearch     = trim($rs->fields['uigendetil_issearch']);
		$obj->uigendetil_isvisible    = trim($rs->fields['uigendetil_isvisible']);
		$obj->uigendetil_isenabled    = trim($rs->fields['uigendetil_isenabled']);
		$obj->uigendetil_textalign    = trim($rs->fields['uigendetil_textalign']);
		$D2[] = CreateResultSet("D", $obj);
	
		$rs->MoveNext();
	}


	unset($i);
	//$conn->debug = true;
	$sql = "SELECT * FROM transbrowser_uigendetil WHERE uigen_id='$id' AND uigen_datadetilname='Detil3' ORDER BY uigendetil_seq";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$i++;

        unset($obj);
        $obj->i                       = $i;  
		$obj->uigendetil_name 		  = trim($rs->fields['uigendetil_name']);
		$obj->uigendetil_text         = trim($rs->fields['uigendetil_text']);
		$obj->uigendetil_datatype     = trim($rs->fields['uigendetil_datatype']);
		$obj->uigendetil_datalen      = trim($rs->fields['uigendetil_datalen']);
		$obj->uigendetil_dataprec     = trim($rs->fields['uigendetil_dataprec']);
		$obj->uigendetil_isgenerate   = trim($rs->fields['uigendetil_isgenerate']);
		$obj->uigendetil_type     	  = trim($rs->fields['uigendetil_type']);
		$obj->uigendetil_objectwidth  = trim($rs->fields['uigendetil_objectwidth']);
		$obj->uigendetil_objectcolor  = trim($rs->fields['uigendetil_objectcolor']);
		$obj->uigendetil_islisted     = trim($rs->fields['uigendetil_islisted']);
		$obj->uigendetil_issearch     = trim($rs->fields['uigendetil_issearch']);
		$obj->uigendetil_isvisible    = trim($rs->fields['uigendetil_isvisible']);
		$obj->uigendetil_isenabled    = trim($rs->fields['uigendetil_isenabled']);
		$obj->uigendetil_textalign    = trim($rs->fields['uigendetil_textalign']);
		$D3[] = CreateResultSet("D", $obj);
	
		$rs->MoveNext();
	}


	unset($i);
	//$conn->debug = true;
	$sql = "SELECT * FROM transbrowser_uigendetil WHERE uigen_id='$id' AND uigen_datadetilname='Detil4' ORDER BY uigendetil_seq";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$i++;

        unset($obj);
        $obj->i                       = $i;  
		$obj->uigendetil_name 		  = trim($rs->fields['uigendetil_name']);
		$obj->uigendetil_text         = trim($rs->fields['uigendetil_text']);
		$obj->uigendetil_datatype     = trim($rs->fields['uigendetil_datatype']);
		$obj->uigendetil_datalen      = trim($rs->fields['uigendetil_datalen']);
		$obj->uigendetil_dataprec     = trim($rs->fields['uigendetil_dataprec']);
		$obj->uigendetil_isgenerate   = trim($rs->fields['uigendetil_isgenerate']);
		$obj->uigendetil_type     	  = trim($rs->fields['uigendetil_type']);
		$obj->uigendetil_objectwidth  = trim($rs->fields['uigendetil_objectwidth']);
		$obj->uigendetil_objectcolor  = trim($rs->fields['uigendetil_objectcolor']);
		$obj->uigendetil_islisted     = trim($rs->fields['uigendetil_islisted']);
		$obj->uigendetil_issearch     = trim($rs->fields['uigendetil_issearch']);
		$obj->uigendetil_isvisible    = trim($rs->fields['uigendetil_isvisible']);
		$obj->uigendetil_isenabled    = trim($rs->fields['uigendetil_isenabled']);
		$obj->uigendetil_textalign    = trim($rs->fields['uigendetil_textalign']);
		$D4[] = CreateResultSet("D", $obj);
	
		$rs->MoveNext();
	}


	unset($i);
	//$conn->debug = true;
	$sql = "SELECT * FROM transbrowser_uigendetil WHERE uigen_id='$id' AND uigen_datadetilname='Detil5' ORDER BY uigendetil_seq";
	$rs  = $conn->Execute($sql);
	while (!$rs->EOF) {
		$i++;

        unset($obj);
        $obj->i                       = $i;  
		$obj->uigendetil_name 		  = trim($rs->fields['uigendetil_name']);
		$obj->uigendetil_text         = trim($rs->fields['uigendetil_text']);
		$obj->uigendetil_datatype     = trim($rs->fields['uigendetil_datatype']);
		$obj->uigendetil_datalen      = trim($rs->fields['uigendetil_datalen']);
		$obj->uigendetil_dataprec     = trim($rs->fields['uigendetil_dataprec']);
		$obj->uigendetil_isgenerate   = trim($rs->fields['uigendetil_isgenerate']);
		$obj->uigendetil_type     	  = trim($rs->fields['uigendetil_type']);
		$obj->uigendetil_objectwidth  = trim($rs->fields['uigendetil_objectwidth']);
		$obj->uigendetil_objectcolor  = trim($rs->fields['uigendetil_objectcolor']);
		$obj->uigendetil_islisted     = trim($rs->fields['uigendetil_islisted']);
		$obj->uigendetil_issearch     = trim($rs->fields['uigendetil_issearch']);
		$obj->uigendetil_isvisible    = trim($rs->fields['uigendetil_isvisible']);
		$obj->uigendetil_isenabled    = trim($rs->fields['uigendetil_isenabled']);
		$obj->uigendetil_textalign    = trim($rs->fields['uigendetil_textalign']);
		$D5[] = CreateResultSet("D", $obj);
	
		$rs->MoveNext();
	}





	if ($uigen_datadetil1use) {
		$DETILS[] = array(
			Name	=> $uigen_datadetil1name,
			SaveHnd => strtolower($uigen_datadetil1name),
			Text	=> $uigen_datadetil1text,
			Table	=> $uigen_datadetil1table,
			PK1		=> $uigen_datadetil1fpk1,
			PK2		=> $uigen_datadetil1fpk2
		);
	}

	if ($uigen_datadetil2use) {
		$DETILS[] = array(
			Name	=> $uigen_datadetil2name,
			SaveHnd => strtolower($uigen_datadetil2name),			
			Text	=> $uigen_datadetil2text,
			Table	=> $uigen_datadetil2table,
			PK1		=> $uigen_datadetil2fpk1,
			PK2		=> $uigen_datadetil2fpk2			
		);
	}

	if ($uigen_datadetil3use) {
		$DETILS[] = array(
			Name	=> $uigen_datadetil3name,
			SaveHnd => strtolower($uigen_datadetil3name),			
			Text	=> $uigen_datadetil3text,
			Table	=> $uigen_datadetil3table,
			PK1		=> $uigen_datadetil3fpk1,
			PK2		=> $uigen_datadetil3fpk2			
		);
	}

	if ($uigen_datadetil4use) {
		$DETILS[] = array(
			Name	=> $uigen_datadetil4name,
			SaveHnd => strtolower($uigen_datadetil4name),			
			Text	=> $uigen_datadetil4text,
			Table	=> $uigen_datadetil4table,
			PK1		=> $uigen_datadetil4fpk1,
			PK2		=> $uigen_datadetil4fpk2			
		);
	}

	if ($uigen_datadetil5use) {
		$DETILS[] = array(
			Name	=> $uigen_datadetil5name,
			SaveHnd => strtolower($uigen_datadetil5name),			
			Text	=> $uigen_datadetil5text,
			Table	=> $uigen_datadetil5table,
			PK1		=> $uigen_datadetil5fpk1,
			PK2		=> $uigen_datadetil5fpk2			
		);
	}

	/* assign data */
	$objPage->assign("DH", $DH);
	$objPage->assign("D1", $D1);
	$objPage->assign("D2", $D2);
	$objPage->assign("D3", $D3);
	$objPage->assign("D4", $D4);
	$objPage->assign("D5", $D5);
	$objPage->assign("DETILS", $DETILS);
	$objPage->assign('index', 0);		
	$objPage->assign('uigen_id', $uigen_id);
	$objPage->assign('uigen_name', $uigen_name);
	$objPage->assign('uigen_text', $uigen_text);
	$objPage->assign('uigen_descr', $uigen_descr);
	$objPage->assign('uigen_type', $uigen_type);
	$objPage->assign('uigen_header', $uigen_header);
	$objPage->assign('uigen_namespace', $uigen_namespace);
	$objPage->assign('uigen_namespacelow', strtolower($uigen_namespace));
	$objPage->assign('uigen_objectname', $uigen_objectname);
	$objPage->assign('uigen_dll', $uigen_dll);
	$objPage->assign('uigen_issingleinstance', $uigen_issingleinstance);
	$objPage->assign('uigen_islocaldb', $uigen_islocaldb);
	$objPage->assign('uigen_wsns', $uigen_wsns);
	$objPage->assign('uigen_wsobject', $uigen_wsobject);
	$objPage->assign('uigen_dataheadertable', $uigen_dataheadertable);
	$objPage->assign('uigen_dataheaderfpk', $uigen_dataheaderfpk);
	$objPage->assign('uigen_dataheaderfcb', $uigen_dataheaderfcb);
	$objPage->assign('uigen_dataheaderfcd', $uigen_dataheaderfcd);
	$objPage->assign('uigen_dataheaderfmb', $uigen_dataheaderfmb);
	$objPage->assign('uigen_dataheaderfmd', $uigen_dataheaderfmd);

	$objPage->assign('uigen_datadetil1use', $uigen_datadetil1use);
	$objPage->assign('uigen_datadetil1name', $uigen_datadetil1name);
	$objPage->assign('uigen_datadetil1namelow', strtolower($uigen_datadetil1name));
	$objPage->assign('uigen_datadetil1table', $uigen_datadetil1table);
	$objPage->assign('uigen_datadetil1fpk1', $uigen_datadetil1fpk1);
	$objPage->assign('uigen_datadetil1fpk2', $uigen_datadetil1fpk2);
	$objPage->assign('uigen_datadetil1text', $uigen_datadetil1text);

	$objPage->assign('uigen_datadetil2use', $uigen_datadetil2use);
	$objPage->assign('uigen_datadetil2name', $uigen_datadetil2name);
	$objPage->assign('uigen_datadetil2namelow', strtolower($uigen_datadetil2name));
	$objPage->assign('uigen_datadetil2table', $uigen_datadetil2table);
	$objPage->assign('uigen_datadetil2fpk1', $uigen_datadetil2fpk1);
	$objPage->assign('uigen_datadetil2fpk2', $uigen_datadetil2fpk2);
	$objPage->assign('uigen_datadetil2text', $uigen_datadetil2text);
	
	$objPage->assign('uigen_datadetil3use', $uigen_datadetil3use);
	$objPage->assign('uigen_datadetil3name', $uigen_datadetil3name);
	$objPage->assign('uigen_datadetil3namelow', strtolower($uigen_datadetil3name));	
	$objPage->assign('uigen_datadetil3table', $uigen_datadetil3table);
	$objPage->assign('uigen_datadetil3fpk1', $uigen_datadetil3fpk1);
	$objPage->assign('uigen_datadetil3fpk2', $uigen_datadetil3fpk2);
	$objPage->assign('uigen_datadetil3text', $uigen_datadetil3text);
	
	$objPage->assign('uigen_datadetil4use', $uigen_datadetil4use);
	$objPage->assign('uigen_datadetil4name', $uigen_datadetil4name);
	$objPage->assign('uigen_datadetil4namelow', strtolower($uigen_datadetil4name));		
	$objPage->assign('uigen_datadetil4table', $uigen_datadetil4table);
	$objPage->assign('uigen_datadetil4fpk1', $uigen_datadetil4fpk1);
	$objPage->assign('uigen_datadetil4fpk2', $uigen_datadetil4fpk2);
	$objPage->assign('uigen_datadetil4text', $uigen_datadetil4text);
	
	$objPage->assign('uigen_datadetil5use', $uigen_datadetil5use);
	$objPage->assign('uigen_datadetil5name', $uigen_datadetil5name);
	$objPage->assign('uigen_datadetil5namelow', strtolower($uigen_datadetil5name));		
	$objPage->assign('uigen_datadetil5table', $uigen_datadetil5table);
	$objPage->assign('uigen_datadetil5fpk1', $uigen_datadetil5fpk1);
	$objPage->assign('uigen_datadetil5fpk2', $uigen_datadetil5fpk2);
	$objPage->assign('uigen_datadetil5text', $uigen_datadetil5text);
	$objPage->assign('uigen_createby', $uigen_createby);
	$objPage->assign('uigen_createdate', $uigen_createdate);
	$objPage->assign('uigen_modifyby', $uigen_modifyby);
	$objPage->assign('uigen_modifydate', $uigen_modifydate);
























function CreateResultSet($DType, $obj) {
        global $datatypes;
        

		if ($DType="H") {
			$uigendetil_type  = $obj->uigendetil_type;
		} else {
			$uigendetil_type  = ($obj->uigendetil_type=='CheckBox') ? 'CheckBox' : 'TextBox'; 
		}

		$uigendetil_bindingproperty  = ($uigendetil_type=='CheckBox') ? 'Checked' : 'Text';
		$uigendetil_gridtype  = ($obj->uigendetil_type=='CheckBox') ? 'CheckBox' : 'TextBox'; 

		return array(
			i     					=> $obj->i - 1,
			uigendetil_name 		=> $obj->uigendetil_name,
			uigendetil_text        	=> $obj->uigendetil_text,
			uigendetil_datatype     => $obj->uigendetil_datatype,
			uigendetil_datalen      => $obj->uigendetil_datalen,
			uigendetil_dataprec     => $obj->uigendetil_dataprec,
			uigendetil_isgenerate   => $obj->uigendetil_isgenerate,
			uigendetil_type			=> $uigendetil_type,
			uigendetil_gridtype		=> $uigendetil_gridtype,			
			uigendetil_objectwidth  => $obj->uigendetil_objectwidth,
			uigendetil_objectcolor  => $obj->uigendetil_objectcolor,
			uigendetil_islisted     => $obj->uigendetil_islisted,
			uigendetil_issearch     => $obj->uigendetil_issearch,
			uigendetil_isvisible    => $obj->uigendetil_isvisible,
			uigendetil_isenabled    => $obj->uigendetil_isenabled,
			uigendetil_isreadonly   => $obj->uigendetil_isenabled ? 'False' : 'True', 
			uigendetil_isnumeric    => ($obj->uigendetil_datatype=='decimal'||$obj->uigendetil_datatype=='int') ? 1 : 0,
			uigendetil_textalign    => $obj->uigendetil_textalign,
			
			uigendetil_datatabletype => $datatypes[$obj->uigendetil_datatype]['datatabletype'] ,
			uigendetil_datatabledefault => $datatypes[$obj->uigendetil_datatype]['defaultvalue'],
			
			uigendetil_bindingproperty => $uigendetil_bindingproperty, 
			uigendetil_numericconv  => ($obj->uigendetil_datatype=='decimal'||$obj->uigendetil_datatype=='int') ? '(float) ' : ''
			
		);	

}







?>