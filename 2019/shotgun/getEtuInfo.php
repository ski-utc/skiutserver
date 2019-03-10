<?php

require("autoload.php");
include_once('db.php');
$etus = $db->query('
	SELECT *
	FROM `users_2019`
	');

echo "BEGIN <br>";
foreach($etus as $etu)
{
	try {
		$ginger = new Ginger\Client\GingerClient("yE27aq9cV2Xdm79j85eNCEg3TJaEBZ8v");
		$gingerUser = $ginger->getUser($etu['login']);
		$adult = $gingerUser->is_adulte ? 1 : 0;
		$db->exec('
			UPDATE `users_2019`
			SET `firstName` = \''.$gingerUser->prenom.'\',`lastName` = \''.$gingerUser->nom.'\',`isAdult` = \''.$adult.'\',`etumail` =  \''.$gingerUser->mail.'\'
				WHERE `login` = \''.$etu['login'].'\' ');

	/*		$update = $db->prepare('
            UPDATE `users`
            SET `firstName`=:firstName,`lastName`=:lastName,`etumail`=:etumail,`isAdult`=:assurance
            WHERE `login`=:login AND `type` = :type
            ');
        $update->bindParam(':address',$_POST['address'],PDO::PARAM_STR);
		$update->bindParam(':firstName',$_POST['firstName'],PDO::PARAM_STR);
		$update->bindParam(':lastName',$_POST['lastName'],PDO::PARAM_STR);
		$update->execute(); */

		echo '<br> -> '.$gingerUser->prenom."  ".$gingerUser->nom.' '.$adult;
	}
	catch (Ginger\Client\ApiException $ex){
		echo '<br> Erreur';
	}
}
echo "<br>END";
