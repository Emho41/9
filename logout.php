<?php
session_start();
//Raderar info i sessionsvariabler
session_unset();
//avslutar sessionen
session_destroy();
//Skickar användaren till login sidan
header('Location: login.php');

?>