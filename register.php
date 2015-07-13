<?php

$errors = [];

session_start();

if(isset($_SESSION['username'])) {
	header("Location: index.php");
	die;
}

function isEmpty()
{
		foreach(func_get_args() as $argument)
				if(empty($argument)) return true;
		return false;
}

if(isset($_POST['username'], $_POST['password'], $_POST['password_again'], $_POST['first_name'], $_POST['last_name'])) {
	if(isEmpty($_POST['username'], $_POST['password'], $_POST['password_again'], $_POST['first_name'], $_POST['last_name'])) {
		$errors[] = "Please fill in all fields.";
	} else {
		if($_POST['password_again'] === $_POST['password']) {
			include_once 'includes/dbconnection.php';

			$query = $pdo->prepare("SELECT * FROM `users` WHERE `username` = ?");
			$query->execute([$_POST['username']]);

			if($query->rowCount() === 0) {
				$query = $pdo->prepare("INSERT INTO `users` VALUES(NULL, ?, ?, ?)");

				if(!$query->execute([$_POST['username'], hash("sha512", $_POST['password']), $_POST['first_name'] . " " . $_POST['last_name']])) {
						die("Failed to create the user, please check the MySQL syntax.");
				}

				die(header("Location: login"));
			} else {
				$errors[] = "A user by that name already exists, please pick another.";
			}
		} else {
			$errors[] = "Passwords do not match!";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login System | Register</title>

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
	        <li><a href="login">Login</a></li>
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
		    <legend>Register</legend>
		    <div class="form-group">
		      <label for="username" class="col-lg-2 control-label">Username</label>
		      <div class="col-lg-10">
		        <input value="<?= isset($_POST['username']) ? htmlentities($_POST['username'], ENT_QUOTES) : '' ?>" class="form-control" name="username" id="username" placeholder="Username" type="text">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="first_name" class="col-lg-2 control-label">First name</label>
		      <div class="col-lg-10">
		        <input value="<?= isset($_POST['first_name']) ? htmlentities($_POST['first_name'], ENT_QUOTES) : '' ?>" class="form-control" name="first_name" id="first_name" placeholder="First name" type="text">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="last_name" class="col-lg-2 control-label">Last name</label>
		      <div class="col-lg-10">
		        <input value="<?= isset($_POST['last_name']) ? htmlentities($_POST['last_name'], ENT_QUOTES) : '' ?>" class="form-control" name="last_name" id="last_name" placeholder="Last name" type="text">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="password" class="col-lg-2 control-label">Password</label>
		      <div class="col-lg-10">
		        <input class="form-control" id="password" name="password" placeholder="Password" type="password">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="password_again" class="col-lg-2 control-label">Password again</label>
		      <div class="col-lg-10">
		        <input class="form-control" id="password_again" name="password_again" placeholder="Password again" type="password">
		      </div>
		    </div>
		    <div class="form-group">
      		<div class="col-lg-10 col-lg-offset-2">
        		<button type="submit" class="btn btn-primary">Register</button>
      		</div>
    		</div>
		  </fieldset>
		</form>
	</div>
	<script src="assets/vendor/js/jquery.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
</body>
</html>
