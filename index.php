<?php

session_start();

if(!isset($_SESSION['username'])) {
	header("Location: login");
	die;
}

include_once 'includes/dbconnection.php';

$query = $pdo->prepare("SELECT * FROM `users` WHERE `username` = ?");
$query->execute([$_SESSION['username']]);

$user = $query->fetchObject();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login System | Home</title>

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
	        <li><a href="logout">Logout</a></li>
	      </ul>
	    </div>
	  </div>
	</nav>
	<div class="container">
		<div class="row">
			<div class="jumbotron">
			  <h1>Hello <?= htmlentities(explode(" ", $user->full_name)[0], ENT_QUOTES) ?>!</h1>
			  <p>Welcome to this simple website made by Jack Chappell.</p>
			  <p><a href="logout" class="btn btn-primary btn-lg">Logout</a></p>
			</div>
		</div>
	</div>

	<script src="assets/vendor/js/jquery.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
</body>
</html>
