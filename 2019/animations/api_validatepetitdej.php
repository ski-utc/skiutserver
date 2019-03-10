<?php
include_once("../db.php");
include_once("../user.php");

if((isset($_GET['login'])) && (isset($_GET['baguette'])) && (isset($_GET['croissant'])) && (isset($_GET['choco'])) && (isset($_GET['trans']))){
	$login = $_GET['login'];
	$trans = $_GET['trans'];
	$baguette = $_GET['baguette'];
	$croissant = $_GET['croissant'];
  $choco = $_GET['choco'];
	echo $login_only;


  $idgroupe = $db->prepare('SELECT `id_groupe` FROM `rel_groupe_user` WHERE `login_user` LIKE "' . $login .  '%"');
	$idgroupe->execute();
	$grp = $idgroupe->fetch();


	// $idlogin = $db->prepare('SELECT id_user FROM `users_2019` WHERE `etumail`=:login');
	// $idlogin->bindParam(':login',$login,PDO::PARAM_STR);
	// $idlogin->execute();
	// $idlogin1 = $idlogin->fetch();
	// echo $idlogin1['id_user'];
	$pay = $db->prepare('
		INSERT INTO `petitdej`(`login`, `id_groupe`, `trans`, `baguette`, `croissant`, `chocolat`)
		VALUES (:login, :idgroupe, :trans, :baguette, :croissant, :chocolat)
		');
	$pay->bindParam(':login',$login,PDO::PARAM_STR);
	// $pay->bindParam(':idgroupe',$idgroupe['id_groupe'],PDO::PARAM_STR);
	$pay->bindParam(':idgroupe',$grp['id_groupe'],PDO::PARAM_INT);
	$pay->bindParam(':trans',$trans,PDO::PARAM_INT);
	// $pay->bindParam(':id_user',$idlogin1[0],PDO::PARAM_INT);
	$pay->bindParam(':baguette',$baguette,PDO::PARAM_INT);
	$pay->bindParam(':croissant',$croissant,PDO::PARAM_INT);
  $pay->bindParam(':chocolat',$choco,PDO::PARAM_INT);
	$pay->execute();
}
?>
