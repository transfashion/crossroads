<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<style>
		table { font-family: verdana; font-size: 12px }
		input { background: #E1FFE1; border:1px #CCC solid; padding:5px; }	
		.login { height:180px; width:270px; border:1px #CCC solid; padding:10px; background-color:#E9E9E9; position: fixed; top: 50%; left: 50%; margin-top: -120px; margin-left: -180px;}
	</style>
</head>
<body>
<div class="login">
<table cellmargin="0" cellpadding="0" width="270">
	<tr>
		<td width="65">
		<img src="images/ci_key.png" height="60" width="65">
		</td>
		<td valign="bottom" style="padding-bottom: 10px">
		<b>Login</b>
		</td>
	</tr>
</table>
<table  cellmargin="0" cellpadding="0"  width="270" >
<form method="POST" action="<?=$_GET['page']?>">
	<tr>
		<td  width="65">Username</td>
		<td><input type="text" name="username" id="username"></td>
	</tr>
		<td>Password</td>
		<td><input type="password" name="password" id="password"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="Submit" value="Sign In" id="btn_login"><div class="err" id="add_err"></div></td>
	</tr>
</form>	
</table>
</div>


</body>
</html>
