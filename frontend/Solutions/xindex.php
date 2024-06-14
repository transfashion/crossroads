<?
	$transbrowser = $_GET['transbrowser'];
	$current_version = "1.0.2.6";
	$user_version = trim($_GET['version']);
	$download_url = "http://workshop/download/Transbrowser-1.0.2.6-debug.zip";
	
	$user_version = $user_version ? $user_version : "Unknown";

	if ($user_version != $current_version) {
		$update_reminder = true;
	} else {
		$update_reminder = false;
	}


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.welcome {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-style: normal;
	line-height: normal;
	font-weight: bold;
	color: #333333;
}
.normaltext {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
}
-->
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td bgcolor="#000000">&nbsp;</td>
    <td align="right" bgcolor="#000000">&nbsp;&nbsp;</td>
  </tr>
</table>
<table width="100%" height="96%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="images/header.gif" width="467" height="65"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="20">
        <tr> 
          <td height="93" class="normaltext"><? if ($update_reminder) { ?><table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr> 
                <td bgcolor="#FF0000"><table width="100%" border="0" cellspacing="0" cellpadding="15">
                    <tr> 
                      <td bgcolor="#FFCCCC"><span class="normaltext"><strong> 
                        Version Update!!</strong></span><span class="normaltext"><strong> 
                        <img src="images/new.gif" width="25" height="9"></strong></span><br> <br>
                        <span class="normaltext">
						<? if ($transbrowser) { ?>
						Your browser current version 
                        is <strong> 
                        <?=$user_version?>
                        </strong> <br><? } ?>
                        There's new version <strong> 
                        <?=$current_version?>
                        </strong> of TransBrowser, that can be downloaded at<br>
                        <a href="<?=$download_url?>"><?=$download_url?></a></span><br> </td>
                    </tr>
                  </table></td>
              </tr>
            </table>
            <br>
            <br>
            <? } ?><span class="welcome">Welcome, </span><br> <br>
            <br>
            <? if ($transbrowser) { ?>You've loged in as <strong> 
            <?=$_GET['username']?>
            </strong><br><br>
            <? } ?>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
			<table width="100%" cellpadding="0" cellspacing="0" class="normaltext">
              <tr><td><div align="center"><img src="images/magay.jpg"> </div></td>
              </tr></table>
            <br>
            <br>
            <? if (!$transbrowser) { ?>
			<table width="100%" cellpadding="0" cellspacing="0" class="normaltext">
              <tr><td><hr>
            <strong><big>Developer Center</big></strong><br><br>
                  <table width="100%" border="1" cellpadding="2" cellspacing="0" class="normaltext">
                    <tr bgcolor="#99CC00"> 
                      <td><strong>Download </strong></td>
                      <td><strong>Binary</strong></td>
                      <td><strong>Source</strong></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Flat Tab Control</td>
                      <td><a href="http://workshop/download/FlatTabControl.dll">FlatTabControl.dll</a></td>
                      <td>-</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Modul Basic</td>
                      <td>-</td>
                      <td><a href="http://workshop/download/uiBase.vb">uiBase</a>, 
                        <a href="http://workshop/download/uiBase.zip">uiBase.zip</a></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">General Ledger</td>
                      <td>gl.dll</td>
                      <td><a href="http://workshop/download/gl.src.zip">gl.src.zip</a></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Budget</td>
                      <td>budget.dll</td>
                      <td><a href="http://workshop/download/budget.src.zip">budget.src.zip</a></td>
                    </tr>
                    <tr>
                      <td bgcolor="#CCCCCC">Payable</td>
                      <td>payable.dll</td>
                      <td><a href="http://workshop/download/payable.src.zip">payable.src.zip</a></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Payment Request</td>
                      <td>pr.dll</td>
                      <td><a href="http://workshop/download/pr.src.zip">pr.src.zip</a></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Procurement</td>
                      <td>proc.dll</td>
                      <td><a href="http://workshop/download/proc.src.zip">proc.src.zip</a></td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Payment Voucher</td>
                      <td>pv.dll</td>
                      <td>pv.src.zip</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Sales</td>
                      <td>sales.dll</td>
                      <td>sales.src.zip</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">Programming</td>
                      <td>prg.dll</td>
                      <td>prg.src.zip</td>
                    </tr>
                    <tr> 
                      <td bgcolor="#CCCCCC">HR</td>
                      <td>hr.dll</td>
                      <td>hr.src.zip</td>
                    </tr>
                  </table>
                  <br>
                  <br>
			</td></tr></table>
            <? } ?>
          </td>
        </tr>
      </table>
      <br>
      <br>
      <br>
    </td>
    <td width="250" valign="top" bgcolor="#CCCCCC" class="normaltext" style="padding: 15"><strong>Pengumuman</strong><br>
      <br>
      <br>
      <em>(no item)</em>       </td>
  </tr>
</table>
</body>
</html>
