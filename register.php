<?php
$html = file_get_contents("register.html");

echo $html;

$servername = "localhost";
$username = "root";
$dbname = "9";

//Connect till databas
$mysqli = new mysqli($servername, $username, '', $dbname);

//Registrera-knappen hantering
if(isset($_POST['register_button'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirmPass = $_POST['confirmPassword'];
	$email = $_POST['email'];
	
	//Kollar så att alla fält är ifyllda
	if($username && $password && $confirmPass && $email){
		//kollar så att lösenordet är samma båda gångerna
		if($password == $confirmPass){
			if($mysqli->query("INSERT INTO users (fldUser, fldPass, fldEmail) VALUES ('$username', '$password', '$email')")){
				echo "$username är nu registrerad, vänligen använd knappen ovan för att logga in!";
			} else echo "Fel inträffade";
		} else echo "Lösenord måste vara samma i båda fälten";
	} else echo "Fyll i alla fält ovan";
	
}

//Kolla om mail redan är registrerad-knappen
if(isset($_POST['checkEmail_button'])){
	$email = $_POST['checkEmail'];
	$result = $mysqli->query("SELECT * FROM users WHERE fldEmail = '$email'");
	$matchFound = mysqli_num_rows($result) > 0 ? "Det finns redan en användare med e-postaddress: $email, vänligen logga in eller registrera med en annan e-postaddress" : "E-postaddressen används inte, vänligen registrera dig";
	echo $matchFound;
	  
}




?>