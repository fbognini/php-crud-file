<?
	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Utente</title>
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body class="align">
	<div class="card">
		<h3>
		<?
			echo "Benvenuto ".$_SESSION["username"];
		?>
		</h3>
		<form method='POST' name='form' id='form'>
			<input type='hidden' id='status' name="status"/>
			<div class="logout-button">
				<a href="logout.php">Logout</a>
			</div>
		</form>
	</div>
	</body>
</html>