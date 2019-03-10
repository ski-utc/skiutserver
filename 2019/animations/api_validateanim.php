<?php
include_once("../db.php");

if((isset($_GET['login'])) && (isset($_GET['anim'])) && (isset($_GET['trans']))){
	$login = $_GET['login'];
	$trans = $_GET['trans'];
	$anim = $_GET['anim'];
	$quantity = $_GET['quantity'];
	echo $login_only;
	// $idlogin = $db->prepare('SELECT id_user FROM `users_2019` WHERE `etumail`=:login');
	// $idlogin->bindParam(':login',$login,PDO::PARAM_STR);
	// $idlogin->execute();
	// $idlogin1 = $idlogin->fetch();
	// echo $idlogin1['id_user'];
	$pay = $db->prepare('
		INSERT INTO `rel_options_users`(`id_user`,`tra_id`,`id_option`, `quantity`)
		VALUES (:id_user, :trans, :anim, :quantity)
		');
	$pay->bindParam(':anim',$anim,PDO::PARAM_INT);
	/*$pay->bindParam(':idlogin1',$login1,PDO::PARAM_STR);*/
	$pay->bindParam(':trans',$trans,PDO::PARAM_INT);
	// $pay->bindParam(':id_user',$idlogin1[0],PDO::PARAM_INT);
	$pay->bindParam(':id_user',$login,PDO::PARAM_STR);
	$pay->bindParam(':quantity',$quantity,PDO::PARAM_INT);
	$pay->execute();
}
?>
