<?php
$id =  $_GET['id'];
 

IF (is_file(dirname(__FILE__) . "/../../imghost/$id.jpg"))
{
	$str = dirname(__FILE__) . "/images/$id.jpg";
	$url = "http://webservice.transmahagaya.com/imghost/$id.jpg";
}

ELSE

{
	$str = dirname(__FILE__) . "/images/noImage.jpg";
	$url = "http://webservice.transmahagaya.com/imghost/noImage.jpg";
}
?>
<body marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<img src="<?=$url?>" width=168 height=150>
</body>

