<?php

include_once("db.php");

$req1 = $db->prepare("SELECT login FROM users_2019 WHERE login = '" . $_POST['login'] . "'");
$result1 = $req1->execute();

$req2 = $db->prepare("SELECT login_user FROM rel_groupe_user WHERE login_user = '" . $_POST['login'] . "'");
$result2 = $req2->execute();

if (!$req1->fetch(PDO::FETCH_ASSOC))
{
    echo 1;
} else if ($req2->fetch(PDO::FETCH_ASSOC))
{
    echo 2;
}

?>
