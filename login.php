<?php

session_start();

if(isset($_SESSION['username'])) {
	die(header("Location: index.php"));
}

$errors = [];

if(isset($_POST['username'], $_POST['password'])) {
		if(!empty($_POST['username']) && !empty($_POST['password'])) {
			include_once 'includes/dbconnection.php';
			
			$query = $pdo->prepare("SELECT * FROM `users` WHERE `username` = ? AND `password` = ?");
			$query->execute([$_POST['username'], hash("sha512", $_POST['password'])]);

			if($query->rowCount() === 1) {
					$_SESSION['username'] = $_POST['username'];
					die(header("Location: index"));
			} else {
					$errors[] = "Incorrect username or password!";
			}
		} else {
			$errors[] = "Please fill in all fields.";
		}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login System | Login</title>

	<link rel="stylesheet" href="assets/vendor/css/bootstrap.css">
</head>
<body>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand">Login System</a>
	    </div>

	    <div class="collapse navbar-collapse">
	      <ul class="nav navbar-nav navbar-right">
	        <li><a href="register">Register</a></li>
	      </ul>	
	    </div>
	  </div>
	</nav>
	<div class="container">
		<?php foreach($errors as $error): ?>
			<div class="alert alert-dismissible alert-danger">
				<p><?= $error ?></p>
			</div>	
		<?php endforeach; ?>
		<form class="form-horizontal" method="post" action="">
		  <fieldset>
		    <legend>Login</legend>
		    <div class="form-group">
		      <label for="username" class="col-lg-2 control-label">Username</label>
		      <div class="col-lg-10">
		        <input value="<?= isset($_POST['username']) ? htmlentities($_POST['username'], ENT_QUOTES) : '' ?>" class="form-control" name="username" id="username" placeholder="Username" type="text">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="password" class="col-lg-2 control-label">Password</label>
		      <div class="col-lg-10">
		        <input class="form-control" id="password" name="password" placeholder="Password" type="password">
		      </div>
		    </div>
		    <div class="form-group">
      		<div class="col-lg-10 col-lg-offset-2">
        		<button type="submit" class="btn btn-primary">Login</button>
      		</div>
    		</div>
		  </fieldset>
		</form>
	</div>
	<script src="vendor/assets/js/jquery.js"></script>
	<script src="vendor/assets/js/bootstrap.js"></script>
</body>
</html>