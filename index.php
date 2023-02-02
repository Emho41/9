<?php
session_start();
$html = file_get_contents("welcome.html");
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

//Variabler från html form
if(isset($_POST['new_button'])){
	$name = $_SESSION['username'];
	$text = $_POST['text'];
	$subject = $_POST['subject'];
	
	//tid
	$time = date("Y/m/d "). date("h:i:sa");
	
	//Insert med rollback 
	$mysqli -> autocommit(FALSE);
		
	$mysqli->query("INSERT INTO post (fldName, fldText, fldTime, fldSubject) VALUES ('$name', '$text', '$time', '$subject')");
		
	// Commit transaction
	if (!$mysqli -> commit()) {
		echo "Commit transaction failed";
		exit();
	}
	
	// Rollback transaction
	$mysqli -> rollback();
	
}

//Kommentarsknappen
if(isset($_POST['comment_button'])){
	$postID = $_POST['postID'];
	$comment = $_POST['comment'];
	if($postID && $comment){
		$postID = $_POST['postID'];
		$comment = $_POST['comment'];
		$mysqli->query("INSERT INTO comments (fldComment, fldPostId) VALUES ('$comment', '$postID')");
	}else echo "Kommentar och inläggsrutan får inte vara tom!";
	
	
	
}

//Välkomnar användaren med hjälp av SESSION och skriver ut övre delen av html-dokumentet welcome.html
echo str_replace("---user---", $_SESSION['username'], $html_pieces[0]);

//Skriver ut alla inlägg i forumet
if ($result = $mysqli->query("SELECT * FROM `post`")) {
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



?>