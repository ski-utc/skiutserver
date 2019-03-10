<?php
include_once("../db.php");

if((isset($_GET['login'])) && (isset($_GET['trans'])) && (isset($_GET['price']))){
	$login = $_GET['login'];
	$trans = $_GET['trans'];
	$price = $_GET['price'];
	echo $login;
	echo $trans;
	echo $price;
	$pay = $db->prepare('UPDATE `users_2019`
		SET `payment-type`=1, `payment-first-received`=1, `price`=:price, `tra_id`=:trans
		WHERE `login`=:login');
	$pay->bindParam(':price',$price,PDO::PARAM_INT);
	$pay->bindParam(':trans',$trans,PDO::PARAM_INT);
	$pay->bindParam(':login',$login,PDO::PARAM_STR);
	$pay->execute();

}
?>
