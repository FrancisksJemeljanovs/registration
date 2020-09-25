<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Register</h2>
  </div>
	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Firstname</label>
  	  <input type="text" name="firstname" value="<?php echo $firstname; ?>">
  	</div>
    <div class="input-group">
  	  <label>Lastname</label>
  	  <input type="text" name="lastname" value="<?php echo $lastname; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input id="email" type="email" name="email" onchange="checkEmail(this.value)" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>

<script type="text/javascript">

  function checkEmail(email) {
    let data = {
      data: email
    }
    if (email == "") {
      return
    } else {
      
      let xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "server.php?q="+email,true);
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          alert(`${this.responseText}`)
          document.getElementById("email").value = ""
        }
      }
      xmlhttp.send();
    }
  }

</script>
</html>