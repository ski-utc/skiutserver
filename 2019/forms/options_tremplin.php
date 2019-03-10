<!DOCTYPE html>
<html lang="en">
<head>
<?php session_start(); ?>

  <title>Ski'UTC 2019</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="skiutc website">
  <meta name="author" content="teaminfooo">
  <!--CSS-->
  <!-- Bootstrap core CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../css/form.css" rel="stylesheet">
  <!-- <link href="../css/form.css" rel="stylesheet"> -->

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

  <!--Google Fonts-->
  <link rel="shortcut icon" href="../images/logo.png">
</head><!--/head-->

<body>
        <!--
        ==================================== -->
        <!-- Navigation -->
        <nav class="navbar" id="mainNav">
    			<div class="container">
    					<a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019</b></a>
    				<div class="navbar-right">
    					<div class="container minicart"></div>
    				</div>
    			</div>
    		</nav>

<?php
    if(!isset($_SESSION['login-tremplin']))
        header("Location: /skiutc/index.php");
   //   if(!in_array('options',$_SESSION['services']))
     //      header('location : ../index.php');
    include_once("db.php");
    include_once("user.php");
    if(isset($_SESSION['login-tremplin']))
    {
        $req = $db->prepare('SELECT * FROM `users_2019` WHERE `login`=:login');
        $req->bindParam(':login',$_SESSION['login-tremplin'],PDO::PARAM_STR);
        $req->execute();
        if(!$req->fetch())
        {
            $req = $db->prepare('INSERT INTO `users_2019`(`login`) VALUES (:login)');
            $req->bindParam(':login',$_SESSION['login-tremplin'],PDO::PARAM_STR);
            $req->execute();
        }

        $etu = $db->prepare('SELECT * FROM `users_2019` WHERE `login`=:login');
        $etu->bindParam(':login',$_SESSION['login-tremplin']);
        $etu->execute();
        $etu = $etu->fetch();

        $displayForm = true;
        $places_etu = 326;
    }

?>

    <?// php include_once("header.php"); ?>
    <div class="container">
        <?php

        if($displayForm)
        { ?>
            <form method="post" action="recap-tremplin.php">
                <div class="info">
                    <div class="row">
                        <fieldset class="col-sm-6 col-xs-12">
                            <legend>Coordonnées</legend>
                            <div class="control-group">
                            <?php
                            if($etu['lastName']!='INVITE') {
                               echo ' <label for="lastName" class="control-label">Nom : '.$etu['lastName'].'</label>';
                            }else{ ?>
                                <label for="lastName" class="control-label">Nom</label>
                                <?php echo $_POST['login'];?>
                                <div class="controls">
                                    <input size="30" type="text" name="lastName" id="lastName" value="<?php if(isset($etu['lastName'])) echo $etu['lastName'];?>"required/>
                                </div>
                         <?php   }  ?>
                            </div>
                            <div class="control-group">
                                 <?php echo '<label for="firstName" class="control-label"> Prenom : '.$etu['firstName'].'</label>';?>
                            </div>
                            <div class="control-group">
                                <label for="adr_rue" class="control-label">Adresse</label>
                                <div class="controls">
                                    <input size="30" type="text" name="address" id="adr_rue" value="<?php if(isset($etu['address'])) echo $etu['address'];?>"required/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="adr_cp" class="control-label">Code Postal</label>
                                <div class="controls">
                                    <input size="30" type="text" name="zipcode" id="adr_cp" value="<?php if(isset($etu['zipcode'])) echo $etu['zipcode'];?>"required/>
                                </div>
                            </div>
                             <div class="control-group">
                                <label for="phone" class="control-label">Telephone</label>
                                <div class="controls">
                                    <input size="12" type="text" name="tel" id="tel" value="<?php if(isset($etu['tel'])) echo $etu['tel'];?>"required/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="adr_ville" class="control-label">Ville</label>
                                <div class="controls">
                                    <input size="30" type="text" name="city" id="adr_ville" value="<?php if(isset($etu['city'])) echo $etu['city'];?>"required/>
                                </div>
                            </div>
                            <div class="control-group">
                            <label for="mail_contact" class="control-label">Email de contact</label>
                            <div class="controls">
                                <input size="30" type="email" name="email" id="mail_contact" value="<?php if(isset($etu['email'])) echo $etu['email'];?>"required/>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-sm-6 col-xs-12">
                            <legend>Mensurations</legend>
                            <div class="control-group">
                                <label for="taille" class="control-label">Taille</label>
                                <div class="input-append control">
                                    <input size=3 type="number" name="size" id="taille" min="110" max="220" value="<?php if(isset($etu['size'])) echo $etu['size'];?>" required/>
                                    <span class="add-on">cm</span>
                                </div>
                            </div>
                            <div class="control-group">
                            <label for="poids" class="control-label">Poids</label>
                                <div class="input-append controls">
                                    <input size=3 type="number" name="weight" id="poids" min="20" max="180" value="<?php if(isset($etu['weight'])) echo $etu['weight'];?>"required/>
                                    <span class="add-on">Kg</span>
                                </div>
                            </div>
                            <div class="control-group">
                            <label for="pointure" class="control-label">Pointure</label>
                                <div class="input-append control">
                                    <input type="number" name="shoesize" id="pointure" min="25" max="50" value="<?php if(isset($etu['shoesize'])) echo $etu['shoesize'];?>"required/>
                                    <span class="add-on">Taille Européenne</span>
                                </div>
                            </div>
                        </fieldset>
                    </div><br>
                    <div class="row">
                        <fieldset class="col-sm-6 col-xs-12">
                            <legend>Options</legend>
                            <label>Transport</label>
                            <div>
                                Voulez vous une navette au départ de Compiègne ou Paris ? :
                                <select name="transport" id = "transport">
                                    <option value="0" <?php if($etu['transport'] == 0) echo "selected"; ?>>Compiègne</option>
                                    <option value="1" <?php if($etu['transport'] == 1) echo "selected"; ?>>Paris</option>
                                    <option id = "sans" value="2" <?php if($etu['transport'] == 2) echo "selected"; ?>>Sans</option>
                                </select>
                            </div>
                            <div>
                                Si oui, avec ou sans navette retour vers votre ville de départ ?:
                                <select name="transport-back" id = "transport-back">
                                    <option value="0" <?php if($etu['transport-back'] == 0) echo "selected"; ?>>Avec</option>
                                    <option value="2" <?php if($etu['transport-back'] == 2) echo "selected"; ?>>Sans</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <input class="btn btn-info" type="submit" value="Envoyer" id="submitbtn">
                </div>
            </form>
        <?php
        }?>
    </div>

    <!-- Footer -->
    <footer class="text-center">
      <div class="container">
        <p>Copyright &copy; Ski'UTC 2019</p>
        <p>Made with &#10084; by Pierre, Manu, Sevan & Elodie</p>
      </div>
    </footer>

    <?php// include_once("footer.php"); ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="../js/grayscale.min.js"></script>
</body>
</html>
