<?
	session_start();
	
	if(isset($_POST["status"]) && !empty($_POST["status"]))
		$status = $_POST["status"];
	else
		$status = "menu";
	
	if(isset($_POST["user-selected"]) && !empty($_POST["user-selected"]))
		$userSelected = $_POST["user-selected"];

	if($status == "createuser") {
		$newUsername = strtoupper($_POST["new-username"]);
		$newPassword = strtoupper($_POST["new-password"]);
		$newUserType = strtoupper($_POST["new-user-type"]);
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
			$newUserMessage = '<table class="summary-table"><tr><td>Username</td><td>'.$newUsername.'</td></tr><tr><td>Password</td><td>'.$newPassword.'</td></tr><tr><td>Tipo utente</td><td>'.$userTypes[$newUserType].'</td></tr></table>';
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
		<link rel="stylesheet" href="css/style.css">
		<script>
			function updateStatus(status){
				document.getElementById("status").value = status;
				document.getElementById("form").submit();
			}
			function setUser(user){
				document.getElementById("user-selected").value = user;
			}
		</script>
	</head>
	<body class="align">
	
	<div class="card">
		<h3>Pannello amministratore</h3>
		<?
			echo '<form method="POST" name="form" id="form">';
			echo '<input type="hidden" id="status" name="status"/>';
			echo '<input type="hidden" id="user-selected" name="user-selected"/>';
			// echo 'Stato '.$status;
			switch ($status) {
				case 'menu':
					echo '<h4>Benvenuto '.$_SESSION["username"].'</h4>';
					echo '<div class="form-field"><input type="button" value="lista utenti" onclick="updateStatus(\'read\')"/></div>';
					echo '<div class="logout-button"><a href="logout.php">Logout</a></div>';
					break;
				case 'read':
					echo '<h4>Lista utenti</h4>';
					echo '<table class="user-table"><thead><tr><th>Username</th><th>Password</th><th>Tipo</th><th colspan="2">Aggiorna</th></tr></thead><tbody>';
					if ($handle = fopen("../utenti.csv", "r")){
						while ($line = fgetcsv($handle, 1000, ";")) {
							echo '<tr><td>'.$line[0].'</td><td>'.$line[1].'</td><td>'.$line[2].'</td><td onclick="setUser(\''.$line[0].'\'); updateStatus(\'edit\')"><div class="updater">Modifica</div></td><td onclick="setUser(\''.$line[0].'\'); updateStatus(\'delete\')"><div class="updater">Elimina</div></td></tr>';
						}
						fclose($handle);
					}
					echo '</tbody></table>';
					echo '<div class="form-field"><input type="button" value="aggiungi un utente" onclick="updateStatus(\'create\')"/></div>';
					echo '<div class="form-field"><input type="button" value="torna al menu\'" onclick="updateStatus(\'menu\')"/></div>';
					break;
				case 'create':
					echo '<h4>Aggiungi utente</h4>';
					echo '<div class="form-field"><input type="text" name="new-username" placeholder="username" required/></div>';
					echo '<div class="form-field"><input type="text" name="new-password" placeholder="password" required/></div>';
					echo '<p>Tipo utente</p>';
					echo '<select name="new-user-type" id="user-type">';
					echo '<option value="U">Utente</option>';
					echo '<option value="A">Amministratore</option>';
					echo '</select>';
					echo "<div><p class='error'>".$newUserError."</p></div>";
					echo '<div class="form-field"><input type="submit" value="crea utente" onclick="updateStatus(\'createuser\')"/></div>';
					echo '<div class="form-field"><input type="button" value="torna indietro" onclick="updateStatus(\'read\')"/></div>';
					break;
				case 'created':
					echo '<h4>Utente creato</h4>';
					echo $newUserMessage;
					echo '<div class="form-field"><input type="button" value="torna al menu\'" onclick="updateStatus(\'menu\')"/></div>';
					break;
				case 'edit':
					echo '<h4>Modifica utente</h4>';
					if ($userSelected == $_SESSION["username"])
						echo "<div><p class='error'>Impossibile modificare se stessi</p></div>";
					else{
						if (($handle = fopen("../utenti.csv", "r"))){
							while ($line = fgetcsv($handle, 1000, ";")) {
								if($userSelected == $line[0]){
									$username = $line[0];
									$password = $line[1];
									$userType = $line[2];
									break;
								}
							}
							fclose($handle);
						}
						echo '<div class="form-field"><input type="text" name="edit-username" value="'.$username.'" disabled/></div>';
						echo '<div class="form-field"><input type="text" name="edit-password" value="'.$password.'" required/></div>';
						echo '<p>Tipo utente</p>';
						echo '<select name="edit-user-type">';
						echo '<option value="U"'.($userType == "U" ? ' selected' : '').'>Utente</option>';
						echo '<option value="A"'.($userType == "A" ? ' selected' : '').'>Amministratore</option>';
						echo '</select>';
						echo '<div class="form-field"><input type="submit" value="conferma modifiche" onclick="setUser(\''.$userSelected.'\'); updateStatus(\'edited\')"/></div>';
					}
					echo '<div class="form-field"><input type="button" value="torna indietro" onclick="updateStatus(\'read\')"/></div>';
					break;
				case 'edited':
					echo '<h4>Utente modificato</h4>';
					echo '<p>'.$userSelected.' modificato</p>';
					if (($handle = fopen("../utenti.csv", "r")) && ($temp = fopen("utenti.tmp", "w"))){					
						$editUsername = $userSelected;
						$editPassword = strtoupper($_POST["edit-password"]);
						$editUserType = strtoupper($_POST["edit-user-type"]);
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
					echo '<div class="form-field"><input type="button" value="torna al menu\'" onclick="updateStatus(\'menu\')"/></div>';
					break;				
				case 'delete':
					echo '<h4>Elimina utente</h4>';
					if ($userSelected == $_SESSION["username"])
						echo "<div><p class='error'>Impossibile eliminare se stessi</p></div>";
					else{
						echo '<p>'.$userSelected.' eliminato</p>';
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
					echo '<div class="form-field"><input type="button" value="torna indietro" onclick="updateStatus(\'read\')"/></div>';
					break;
			}
		?>
		<form>
	</div>
	</body>
</html>