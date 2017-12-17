<?
	session_start();
	if(!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['usertype']=='U') 
		header("location: ../");

	
	if(isset($_POST["status"]) && !empty($_POST["status"]))
		$status = $_POST["status"];
	else
		$status = "menu";
	
	if(isset($_POST["userSelected"]) && !empty($_POST["userSelected"]))
		$userSelected = $_POST["userSelected"];

	if($status == "createuser") {
		$newUsername = strtoupper($_POST["newUsername"]);
		$newPassword = strtoupper($_POST["newPassword"]);
		$newUserType = strtoupper($_POST["newUsertype"]);
		if ($handle = fopen("../utenti.csv", "r")){
			$exist = false;
			while ($line = fgetcsv($handle, 1000, ";")) {
				if($newUsername == $line[0]){
					$exist = true;
					break;
				}
			}
			fclose($handle);
		}
		if ($exist) {
			$newUserError = "Impossibile creare l'utente ".$newUsername;
			$status = "create";
		}
		else {
			$userTypes = array("A" => "AMMINISTRATORE", "U" => "UTENTE");
			$newUserLine = $newUsername.";".$newPassword.";".$newUserType."\r\n";
			$newUserMessage = '<div class="table-responsive"><table class="table table-striped table-bordered text-center"><thead><tr><th>Username</th><th>Password</th><th>Tipo utente</th></tr></thead><tbody><tr><td>'.$newUsername.'</td><td>'.$newPassword.'</td><td>'.$userTypes[$newUserType].'</td></tr></tbody></table></div>';
			$status = "created";

			if ($handle = fopen("../utenti.csv", "a")){
				fputs($handle,$newUserLine);
				fclose($handle);
			}	
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Amministratore</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    	<link rel="stylesheet" href="../css/style.css">

		<script>
			function updateStatus(status){
				document.getElementById("status").value = status;
				document.getElementById("form").submit();
			}
			function setUser(user){
				document.getElementById("userSelected").value = user;
			}
</script>
<style>

	</style>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<div class="navbar-brand">Pannello amministratore</div>
				<a href="../logout.php" class="btn btn-outline-danger my-2 my-sm-0" role="button">Logout</a>
			</div>
		</nav>
		<main role="main">
        <div class="jumbotron">
			<div class="container">
				<h1 class="display-4">
				<?
					echo "Benvenuto ".$_SESSION["username"];
				?>
				</h1>
			</div>
        </div>
		<div class="container">
		<?
			// echo $status."<br>";
			echo '<form method="POST" id="form" class="form-horizontal">';
			echo '<input type="hidden" id="status" name="status"/>';
			echo '<input type="hidden" id="userSelected" name="userSelected"/>';
			switch ($status) {
				case 'menu':
				case 'read':
					echo '<h4>Lista utenti</h4>';
					echo '<p>Tabella contente tutti gli utenti presenti del dabatase</p>';
					echo '<div class="table-responsive">';
					echo '<table class="table table-striped table-bordered text-center">';
					echo '<thead><tr><th>Username</th><th>Password</th><th>Tipo</th><th colspan="2">Aggiorna</th></tr></thead>';
					echo '<tbody>';
					if ($handle = fopen("../utenti.csv", "r")){
						while ($line = fgetcsv($handle, 1000, ";")) {							
							echo '<tr><td>'.$line[0].'</td><td>'.$line[1].'</td><td>'.$line[2].'</td>';
							if($line[0] == "ROOT")
								echo '<td colspan="2">Non è possibile modificare l\'utente ROOT</td>';
							else
								echo '<td onclick="setUser(\''.$line[0].'\'); updateStatus(\'edit\')" class="updater">Modifica</td><td onclick="setUser(\''.$line[0].'\'); updateStatus(\'delete\')" class="updater">Elimina</td>';
							echo '</tr>';
						}
						fclose($handle);
					}
					echo '</tbody></table></div>';
					echo '<button type="button" class="btn btn-primary btn-lg btn-block" onclick="updateStatus(\'create\')">AGGIUNGI UN UTENTE</button>';
					break;
				case 'create':
					echo '<script type="text/javascript">document.getElementById("status").value = "createuser";</script>';
					echo '<h4>Aggiungi un utente</h4>';
					echo '<p>Compila la form per aggiungere un nuovo utente</p>';
					echo '<input type="text" name="newUsername" class="form-control" placeholder="Username" required autofocus>';
					echo '<input type="text" name="newPassword" class="form-control" placeholder="Password" required>';
					echo '<label>Tipo utente</label>';
					echo '<select class="form-control" name="newUsertype">';
					echo '<option value="U">Utente</option>';
					echo '<option value="A">Amministratore</option>';
					echo '</select>';
					echo "<div><p class='error'>".$newUserError."</p></div>";
					echo '<button type="submit" class="btn btn-primary btn-lg btn-block">AGGIUNGI UTENTE</button>';
					echo '<button type="button" class="btn btn-default btn-lg btn-block" onclick="updateStatus(\'read\')">TORNA INDIETRO</button>';
					break;
				case 'created':
					echo '<h4>Aggiungi un utente</h4>';
					echo '<p>E\' stato aggiunto un nuovo utente</p>';
					echo $newUserMessage;
					echo '<button type="submit" class="btn btn-primary btn-lg btn-block" onclick="updateStatus(\'menu\')">TORNA AL MENU</button>';
					break;
				case 'edit':
					echo '<script type="text/javascript">document.getElementById("status").value = "edited";</script>';
					echo '<h4>Modifica un utente</h4>';
					if ($userSelected == $_SESSION["username"]) {
						echo "<p class='error'>Impossibile modificare se stessi</p>";
						echo '<button type="button" class="btn btn-primary btn-lg btn-block" onclick="updateStatus(\'read\')">TORNA AL MENU</button>';
					}
					else{
						if (($handle = fopen("../utenti.csv", "r"))){
							while ($line = fgetcsv($handle, 1000, ";")) {
								if($userSelected == $line[0]){
									$username = $line[0];
									$password = $line[1];
									$usertype = $line[2];
									break;
								}
							}
							fclose($handle);
						}
						echo '<p>Compila la form per modificare l\'utente '.$username.'</p>';
						echo '<input type="text" name="editUsername" class="form-control" value="'.$username.'" disabled>';
						echo '<input type="text" name="editPassword" class="form-control" value="'.$password.'" required autofocus>';
						echo '<label>Tipo utente</label>';
						echo '<select class="form-control" name="editUsertype">';
						echo '<option value="U"'.($userType == "U" ? ' selected' : '').'>Utente</option>';
						echo '<option value="A"'.($userType == "A" ? ' selected' : '').'>Amministratore</option>';
						echo '</select>';
						echo '<button type="submit" class="btn btn-primary btn-lg btn-block" onclick="setUser(\''.$username.'\')">CONFERMA MODIFICHE</button>';
						echo '<button type="button" class="btn btn-default btn-lg btn-block" onclick="updateStatus(\'read\')">TORNA INDIETRO</button>';
					}
					break;
				case 'edited':
					echo '<h4>Modifica un utente</h4>';
					echo '<p>'.$userSelected.' è stato modificato</p>';
					if (($handle = fopen("../utenti.csv", "r")) && ($temp = fopen("utenti.tmp", "w"))){					
						$editUsername = $userSelected;
						$editPassword = strtoupper($_POST["editPassword"]);
						$editUserType = strtoupper($_POST["editUsertype"]);
						while ($line = fgetcsv($handle, 1000, ";")) {
							$userLine = $line[0].';'.$line[1].';'.$line[2]."\r\n";
							if($userSelected == $line[0]){
								$userLine = $editUsername.";".$editPassword.";".$editUserType."\r\n";					
							}
							fputs($temp, $userLine);
						}
						fclose($handle);
						fclose($temp);
						rename("utenti.tmp","../utenti.csv");
					}
					echo '<button type="submit" class="btn btn-primary btn-lg btn-block" onclick="updateStatus(\'menu\')">TORNA AL MENU</button>';
					break;				
				case 'delete':
					echo '<h4>Elimina un utente</h4>';
					if ($userSelected == $_SESSION["username"])
						echo "<p class='error'>Impossibile eliminare se stessi</p>";
					else{
						echo '<p>'.$userSelected.' è stato eliminato</p>';
						if (($handle = fopen("../utenti.csv", "r")) && ($temp = fopen("utenti.tmp", "w"))){
							while ($line = fgetcsv($handle, 1000, ";")) {
								if($userSelected != $line[0]){
									$userLine = $line[0].';'.$line[1].';'.$line[2]."\r\n";
									fputs($temp, $userLine);
								}
							}
							fclose($handle);
							fclose($temp);
							rename("utenti.tmp","../utenti.csv");
						}
					}
					echo '<button type="submit" class="btn btn-primary btn-lg btn-block" onclick="updateStatus(\'menu\')">TORNA AL MENU</button>';
					break;
			}
			echo "</form>";
		?>
		</div>
		</main>
		<footer class="container">
			<hr>
			<p>&copy; Franceso Bognini - I sorgenti sono disponibili su <a href="https://github.com/fbognini/php-crud-file" target="_blank">GitHub</a></p>
		</footer>
	</body>
</html>