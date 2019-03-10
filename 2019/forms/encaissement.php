<!DOCTYPE html>
<html lang="en">
<head>
<?php session_start();?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Ski'UTC 2019</title>

    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../images/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

</head>



<?php if(!in_array('Encaissement', $_SESSION['services']))
              header('location : ../../index.php');
?>

  <body>

    <nav class="navbar" id="mainNav">
      <div class="container">
          <a class="navbar-brand" href="../../index.php">
            <img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42">
            <b>Ski'UTC 2019 | Encaissement</b>
          </a>
          <span class="navbar-text">
            FLR les chèques samer
          </span>
      </div>
    </nav>


    <div class="container">
      <form action="recap_encaissement.php">
        Login de l'étudiant à encaisser :<br>
        <input type="text" name="login" value="">
        <br><br>
        <input type="submit" value="Envoyer" class="btn btn-info">
      </form>

      <img src="../images/nazim.png" alt="prez">

      <?php
        include_once("db.php");
        $req = $db->prepare('SELECT count(*) FROM `users_2019` WHERE `payment-first-received`=1');
  			$req->execute();
  			$req = $req->fetch();
  			$total=$req[0]+13;
        // +3+4+18;
  			if ($total==400){ echo "STOOOOOOP ON VA TOUT PETERRRRRR"; }
      ?>
      <p>Nombre de places vendues : <?php echo $total; ?> / 400</p>
    </div>
  </body>
</html>
