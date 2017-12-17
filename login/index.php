<?
	session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = strtoupper($_POST["username"]);
		$password = strtoupper($_POST["password"]);
		if ($handle = fopen("../utenti.csv", "r")){
			while ($line = fgetcsv($handle, 1000, ";")) {
				if ($username == $line[0] && $password  == $line[1]){ 
					$_SESSION["username"] = $username;
					$_SESSION["usertype"] = $line[2][0];
					if ($line[2][0]=='A')
						header("Location: ../admin/");
					else
						header("Location: ../user/");
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
		<title>CRUD su file</title>
		
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel="stylesheet" href="../css/style.css">
		
  </head>
	<body class="login-page">
		<div class="container">
        <form method='POST' class="form-signin">
						<h2 class="form-signin-heading">Accesso alla piattaforma</h2>
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
						<?
							echo "<p class='error'>$error</p>";
						?>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Accedi</button>
				</form>
				<div class="message"> 
					<p><i>Accedere come utente <b>ROOT</b> (password: <b>ROOT</b>) per testare le funzionalità da amministratore</i></p>
				</div>
		</div>
		<footer class="container">
			<hr>
			<p>&copy; Franceso Bognini - I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-crud-file" target="_blank">GitHub</a></p>
		</footer>

	</body>
</html>
