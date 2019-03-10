<?php
include_once("../db.php");

if((isset($_GET['login'])) && (isset($_GET['pack1'])) && (isset($_GET['trans']))  && (isset($_GET['pack2']))){
	$login = $_GET['login'];
	$trans = $_GET['trans'];
	$pack2 = $_GET['pack2'];
	$pack1 = $_GET['pack1'];

	$pay = $db->prepare('
		INSERT INTO `tombola_2019`(`login`,`trans`,`pack10`,`pack1`)
		VALUES (:login, :trans,:pack10,:pack1)
		');
	$pay->bindParam(':pack10',$pack2,PDO::PARAM_INT);
	$pay->bindParam(':login',$login,PDO::PARAM_STR);
	$pay->bindParam(':trans',$trans,PDO::PARAM_INT);
	$pay->bindParam(':pack1',$pack1,PDO::PARAM_INT);
	$pay->execute();

	for($i=1;$i<=$pack1;$i++){
	$pay = $db->prepare('
		INSERT INTO `ticket_tombola_2019`(`login`,`trans`)
		VALUES (:login, :trans)
		');
	$pay->bindParam(':login',$login,PDO::PARAM_STR);
	$pay->bindParam(':trans',$trans,PDO::PARAM_INT);
	$pay->execute();
	}

	for($i=1;$i<=($pack2*10);$i++){
	$pay = $db->prepare('
		INSERT INTO `ticket_tombola_2019`(`login`,`trans`)
		VALUES (:login, :trans)
		');
	$pay->bindParam(':login',$login,PDO::PARAM_STR);
	$pay->bindParam(':trans',$trans,PDO::PARAM_INT);
	$pay->execute();
	}
	echo '{ "good"  : "good" }';

}
?>
