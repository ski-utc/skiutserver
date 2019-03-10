<?php

include_once('db.php');
$etus = $db->query('SELECT login,firstName,lastName,isAdult,etumail FROM `shotgun-etu_2019` LIMIT 300');

while ($donnees = $etus->fetch()){
	$req = $db->prepare('INSERT INTO `users_2019` (`login`,`firstName`,`lastName`,`isAdult`,`etumail`) VALUES (:login, :firstName, :lastName, :isAdult, :etumail)');
	$req->bindParam(':login',$donnees[0],PDO::PARAM_STR);
	$req->bindParam(':firstName',$donnees[1],PDO::PARAM_STR);
	$req->bindParam(':lastName',$donnees[2],PDO::PARAM_STR);
	$req->bindParam(':firstName',$donnees[1],PDO::PARAM_STR);
	$req->bindParam(':isAdult',$donnees[3],PDO::PARAM_STR);
	$req->bindParam(':etumail',$donnees[4],PDO::PARAM_STR);
	$req->execute();
}
echo "<br>END";
