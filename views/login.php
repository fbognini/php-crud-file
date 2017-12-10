<?
	session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = strtoupper($_POST["username"]);
		$password = strtoupper($_POST["password"]);
		$_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		if ($handle = fopen("../utenti.csv", "r")){
			while ($line = fgetcsv($handle, 1000, ";")) {
				if ($username == $line[0] && $password  == $line[1]){ 
					$_SESSION["user-type"] = $line[2][0];
					header("Location: ../index.php");
				}
			}
			$error = "Credenziali non valide";
			fclose($handle);
		}
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>CRUD su file</title>
    <link rel="stylesheet" href="css/style.css">
  </head>
	<body class="align">
	  <div class="card login-form">
		<h3>Accesso alla piattaforma</h3>
		<form method='POST' name='form' id='form'>
			<input type='hidden' id='status' name="status"/>
			<div class="form-field">
				<input type="text" name="username" placeholder="username" required/>
			</div>
			<div class="form-field">
				<input type="password" name="password" placeholder="password" required/>
			</div>
			<?
				echo "<div><p class='error'>".$error."</p></div>";
			?>
			<div class="form-field">
				<input type="submit" value="accedi"/>
			</div>
		  <p class="message">I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-crud-file">GitHub</a></p>
		</form>
	  </div>

	</body>
</html>
