<!DOCTYPE html>
<html lang="en">
  <head>
  <?php session_start();
    include_once("../user.php");
    include_once('../db.php');
  ?>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Envoi de mail</title>

      <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Main Stylesheet -->
      <link href="../css/form.css" rel="stylesheet">
      <link rel="shortcut icon" href="../images/logo.png">
      <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

      <script src="../../controllers/ckeditor/ckeditor.js"></script>
  </head>

<body>
  <nav class="navbar" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019 | Envoyer un mail</b></a>
    </div>
  </nav>

  <?php
      header('Content-Type: text/html; charset=utf-8');
  ?>


	<?php
		$user = $db->prepare('SELECT * FROM `users_2019` WHERE `payment-first-received`=1');
		$user->execute();

		if(isset($_POST['editor1'])){
		if(isset($_POST['objet']))
      $sujet = $_POST['objet'];
		else
			$sujet = "SkiUTC";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";


    //si le mail existe (= n'a pas �t� d�j� trait�)
    $message = $_POST['editor1'];
	  // $i = 1;
        while($result = $user->fetch(PDO::FETCH_ASSOC)){
       	  mail($result['email'], $sujet, $message, $headers);
        	 // mail($destinataire, $i, $message, $headers);
		// $i  = $i + 1;
		}
		echo "Le daily mail a bien été envoyé";
		}
		else{
	?>
  <form action = "send_mail.php" method = "post">
    <div class="form-group">
      <label> Objet du mail <input type="text" name="objet" id ="objet" value = "[Ski'UTC] Mail" class="form-control"> </label>
    </div>
    <div class="form-group">
      <textarea name="editor1" id="editor1" rows="10" cols="80">

      </textarea>
    </div>

    <script>
  		CKEDITOR.config.skin = 'office2013';

                  // Replace the <textarea id="editor1"> with a CKEditor
                  // instance, using default configuration.
                  CKEDITOR.replace( 'editor1' );
    </script>

    <input type = "Submit" name = "Submit1" value = "Envoyer le mail" class="btn btn-info">

  </form>

	<?php } ?>

  </body>
</html>
