<?php
include_once('db.php');

if($_POST['payment-type'] == 1)
{
	$pay = $db->prepare('
		UPDATE `users_2019`
		SET `payment-type`=:type,`payment-first-received`=1,`price`=:price
		WHERE `login`=:login
		');
	$pay->bindParam(':type',$_POST['payment-type'],PDO::PARAM_INT);
	$pay->bindParam(':price',$_POST['payment-price'],PDO::PARAM_INT);
	$pay->bindParam(':login',$_POST['login'],PDO::PARAM_STR);
	$pay->execute();
}
if($_POST['payment-type'] == 2)
{
	$pay = $db->prepare('
		UPDATE `users_2019`
		SET `payment-type`=:type,`payment-first-received`=1,`price`=:price,`cheq2`=:cheq2
		WHERE `login`=:login
		');
	$pay->bindParam(':type',$_POST['payment-type'],PDO::PARAM_INT);
	$pay->bindParam(':price',$_POST['payment-price'],PDO::PARAM_INT);
	$pay->bindParam(':login',$_POST['login'],PDO::PARAM_STR);
	$pay->bindParam(':cheq2',$_POST['mont-cheq2'],PDO::PARAM_INT);
	$pay->execute();
}
print_r($_POST);
echo "montant = ".$_POST['mont-cheq2'];
header('Location: recap_encaissement.php?login='.$_POST['login']);
