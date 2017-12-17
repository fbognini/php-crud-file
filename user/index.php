<?
	session_start();
	if(!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['usertype']=='A') 
		header("location: ../");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Utente</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    	<link rel="stylesheet" href="../css/style.css">

	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<div class="navbar-brand">Pannello utente</div>
				<a href="../logout.php" class="btn btn-outline-danger my-2 my-sm-0" role="button">Logout</a>
			</div>
		</nav>
        <div class="jumbotron">
			<div class="container">
				<h1 class="display-4">
				<?
					echo "Benvenuto ".$_SESSION["username"];
				?>
				</h1>
				<p>Pasta alla carbonara</p>
			</div>
        </div>
		<div class="container">
			<p>Pasta al pesto</p>
		</div>
		<footer class="container">
			<hr>
			<p>&copy; Franceso Bognini - I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-crud-file" target="_blank">GitHub</a></p>
		</footer>
	</body>
</html>