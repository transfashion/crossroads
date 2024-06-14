<?php
require_once '_mheader.php';
?>

<script>
$(document).ready(function() {
	$("form").submit(function(e){

		var username = $('#loginform').find('input[name="fusername"]').val();
		var password = $('#loginform').find('input[name="fpassword"]').val();

		if (username=='michael' && password=='m121')
		{
		 	return true;
		}
		else
		{
			e.preventDefault();
		 	alert('Username dan password salah!');
			return false;
		}

/*

		if (username=='michael' && password='m121')
		{
		 	alert('ok');
			return true;
		}
		else
		{
		 	e.preventDefault();
		 	alert('Username dan password salah!');
			return false;	
		}
*/			     
	});
});	         
</script>         


<div data-role="page">
  
  <div data-role="header">
		<h1>Login</h1>
  </div>
	
  <div data-role="main" class="ui-content" id="loginform">
    <form method="post" action="bazarval.php">
      
      <label for="fusername">Username</label>
      <input type="text" name="fusername" id="fusername" placeholder="username..">
      
      <label for="fpassword">Password</label>
      <input type="password" name="fpassword" id="fpassword" placeholder="Password...">
      
	  <div class="ui-grid-solo">
      <div class="ui-block-a">
      <button class="ui-btn">Login</button>
      </div>
      </div>
         
    </form>
  </div>
</div>

<?php 
require_once '_mfooter.php';

