<?php
session_start();
$html=file_get_contents("welcome.html");
$html_pieces = explode("<!--===entries===-->", $html);


$servername = "localhost";
$usernameDB = "root";
$dbname = "9";

//Connect till databas
$mysqli = new mysqli($servername, $usernameDB, '', $dbname);

//Kolla connection till databas
if($mysqli ->connect_error){
	die("Connection failed: ". $mysqli->connect_error);
} 

//Välkomnar användaren med hjälp av SESSION och skriver ut övre delen av html-dokumentet welcome.html
echo str_replace("---user---", $_SESSION['username'], $html_pieces[0]);


//Hanterar ämnes sök knappen i welcome.html
if(isset($_POST['subjects_button'])){
	
	$subjects = $_POST['subjects'];
	if($subjects){
		//Skriver ut alla inlägg i forumet som stämmer med användarens önskade ämne
		if ($result = $mysqli->query("SELECT * FROM `post` WHERE fldSubject = '$subjects'")) {
			while($row = $result->fetch_assoc()){
			$id = $row["id"];
			$fldTime = $row["fldTime"];
			$fldName = $row["fldName"];
			$fldText = $row["fldText"];
			$fldSubject = $row["fldSubject"];
		
		echo str_replace(array("---no---", "---time---", "---name---", "---subject---", "---text---"), array($id, $fldTime, $fldName, $fldSubject, $fldText,), $html_pieces[1]);
		
		//Loop för att skriva ut kommentarer till varje post
		if ($resultComment = $mysqli->query("SELECT * FROM `comments` WHERE fldPostId=$id")) 
			while($rowComment = $resultComment->fetch_assoc()){
				$commentText = $rowComment["fldComment"];
				echo str_replace("---comment---", $commentText, $html_pieces[2]);
			}
		}
	} 
	} else echo "Ämne måste vara valt!";
}


?>