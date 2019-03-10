<!doctype html>
<html>
  <head>
    <?php session_start();?>
    <meta charset="utf-8">
    <title>Ski-UTC 2019 Shotgun physique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="skiutc website">
    <meta name="author" content="teaminfooo">
    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../css/form.css" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
    <!--Google Fonts-->
    <link rel="shortcut icon" href="../images/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>

  <?php if(!in_array('Encaissement', $_SESSION['services']))
                header('location : ../../index.php');
  ?>

  <body>
  	<!-- Navigation -->
  	<nav class="navbar" id="mainNav">
  		<div class="container">
  				<a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019 | Shotgun physique</b></a>
  			<div class="navbar-right">
  				<div class="container minicart"></div>
  			</div>
  		</div>
  	</nav>
    <!-- ==================================== -->

      <div class="col-sm-5 pull-right">
        <h1>Shotgun physique</h1>
          <form id="form-shotgun" action="shotgun_physique.php" method="post">
              <input name="login" type="text" maxlength="8" placeholder="TON LOGIN PATATE" class="form-control"/>
              <input type="submit" value="SHOTGUN !!!!" class="btn btn-info"/>
          </form>
      </div>
      <?php
        include_once("../db.php");
        $req = $db->prepare('SELECT count(*) FROM `users_2019` WHERE `payment-first-received`=1');
  			$req->execute();
  			$req = $req->fetch();
  			$total=$req[0]+13;
  			if ($total==400){ echo "STOOOOOOP ON VA TOUT PETERRRRRR"; }
      ?>
      <p>Nombre de places vendues : <?php echo $total; ?> / 400</p>

  </body>
</html>
