<?php


/*
$file_name_with_full_path = realpath('./images/header.gif');

if (!is_file($file_name_with_full_path)) {
	die ("file '$file_name_with_full_path' does not exist");
}


echo "testing upload\n";

$target_url = 'http://127.0.0.1/crossroads/frontend/testupload-recv.php';

$post = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$result=curl_exec ($ch);
curl_close ($ch);
echo $result;

*/


$docid = 'PC/05/HBS/HO/180000034';
$target_url = "http://172.18.10.20/putdocqueue.php?docid=$docid";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$target_url);
$result=curl_exec ($ch);
curl_close ($ch);
echo $result;




