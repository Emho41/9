<?php
session_start();
$html = file_get_contents("login.html");

echo $html;


$servername = "localhost";
$usernameDB = "root";
$dbname = "9";

//Connect till databas
$mysqli = new mysqli($servername, $usernameDB, '', $dbname);

//Logga in-knappen hantering
if(isset($_POST['login_button'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	//Kontrollerar så att användarnamn och lösenord finns
	if($username && $password){
		$result = $mysqli->query("SELECT * FROM users WHERE fldUser = '$username'");
		if(mysqli_num_rows($result) != 0){
			//Går igenom databasen och sparar det hittade anv-namnet och pw i variabler
			while($row = $result->fetch_assoc()){
				$databasepw = $row['fldPass'];
				$databaseUser = $row['fldUser'];
			}
			//Jämför det hittade anv-namn och pw mot användarens input
			if($username == $databaseUser && $password == $databasepw){
				$_SESSION['username'] = $username;
				//Sänder vidare användaren till forumet vid lyckad inloggning
				echo "Inloggning godkänd". header("refresh:3; url=index.php");
			} else echo "Fel lösenord";
		} else echo "Användare ej hittad";
	} else echo "Fyll i alla fält ovan";
	
}




?>