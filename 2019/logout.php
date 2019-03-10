<?php
	session_start();
	unset($_SESSION['login-etu']);
	unset($_SESSION['user']);
	unset($_SESSION['login-tremplin']);
	unset($_SESSION['login_tremplin']);
	session_unset();
	header('Location: ../index.php');
?>
