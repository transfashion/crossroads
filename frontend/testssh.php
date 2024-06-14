<?php

//include('./phpseclib1.0.12/Net/SFTP.php');


$connection = ssh2_connect('172.18.10.20', 22);
ssh2_auth_password($connection, 'user', 'syariah2018!');


//echo "send data... ";
//ssh2_scp_send($connection, dirname(__FILE__).'/header.gif', '/home/user', 0644);




