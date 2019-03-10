<?php
	session_start();
	ini_set('display_errors', 'on');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ski-UTC 2019 Shotgun</title>
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

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55527513-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

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
	// Charger ginger (pas nécessaire si vous utilisez Composer)
	require("../autoload.php");
	include_once("../db.php");


	if(isset($_POST['login']))
	{
		$ginger = new Ginger\Client\GingerClient("yE27aq9cV2Xdm79j85eNCEg3TJaEBZ8v");
		try {
			$gingerUser = $ginger->getUser(htmlentities($_POST['login']));
			$req = $db->prepare('SELECT COUNT(*) FROM `users_2019` WHERE `login`=:login');
			$req->bindParam(':login',$_POST['login'],PDO::PARAM_STR);
			$req->execute();
			$req = $req->fetch();
			if($req[0] == 0)
			{
				if($gingerUser->is_cotisant == 1){
					$adult = $gingerUser->is_adulte ? 1 : 0;
					$req = $db->prepare('INSERT INTO `users_2019`(`login`, `firstName`, `lastName`,`isAdult`, `email`, `etumail`) VALUES (:login, :firstName, :lastName, :isAdult, :email, :etumail)');
					$req->bindParam(':login',$_POST['login'],PDO::PARAM_STR);
					$req->bindParam(':firstName',$gingerUser->prenom,PDO::PARAM_STR);
					$req->bindParam(':lastName',$gingerUser->nom,PDO::PARAM_STR);
					$req->bindParam(':isAdult',$adult,PDO::PARAM_STR);
					$req->bindParam(':email',$gingerUser->mail,PDO::PARAM_STR);
					$req->bindParam(':etumail',$gingerUser->mail,PDO::PARAM_STR);
					$req->execute();

				}
				else {
					echo "Utilisateur non cotisant";
					header("Location: ../shotgun_physique.html");
				}
			} else {
				$etu = $db->prepare('SELECT * FROM `users_2019` WHERE `login`=:login');
				$etu->bindParam(':login',$_POST['login']);
				$etu->execute();
				$etu = $etu->fetch();

				if($etu['payment-first-received'])
					header("Location: ../forms/recap_encaissement.php?login=".$etu['login']);
			}


 					?>
						<div class="container">
							<form method="post" action="../forms/recap-etu.php">
	                <div class="info">
	                    <div class="row">
	                        <fieldset class="col-sm-6 col-xs-12">
	                            <legend>Coordonnées</legend>
	                            <div class="control-group">
	                            <?php    if(isset($_SESSION['login-etu'])) {
	                                        echo ' <label for="lastName" class="control-label">Nom : '.$etu['lastName'].'</label>';
	                            }else{      ?>
	                                <label for="lastName" class="control-label">Nom</label>
	                                <?php echo $_POST['login'];?>
	                                <div class="controls">
	                                    <input size="30" type="text" name="lastName" id="lastName" value="<?php if(isset($etu['lastName'])) echo $etu['lastName'];?>"required/>
	                                </div>
	                         <?php   }  ?>
	                            </div>
	                            <div class="control-group">
	                                 <?php    if(isset($_SESSION['login-etu'])) {
	                                        echo ' <label for="firstName" class="control-label"> Prenom : '.$etu['firstName'].'</label>';
	                            }else{      ?>
	                                <label for="firstName" class="control-label">Prénom</label>
	                                <div class="controls">
	                                    <input size="30" type="text" name="firstName" id="firstName" value="<?php if(isset($etu['firstName'])) echo $etu['firstName'];?>"required/>
	                                </div>
	                                <?php   }  ?>
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
	                            <legend>Matériel</legend>
	                            <table>
	                                <tr>
	                                    <td><label id="packLabel">Equipement</label></td>
	                                    <td>
	                                    <select id="equipment" name="equipment">
	                                        <option id="equipment-none" value="0" <?php if($etu['equipment'] == 0) echo "selected"; ?>>Aucun</option>
	                                        <option id="ski" value="1" <?php if($etu['equipment'] == 1) echo "selected"; ?>>Ski</option>
	                                        <option id="snow" value="2" <?php if($etu['equipment'] == 2) echo "selected"; ?>>Snowboard</option>
	                                    </select>
	                                    </td>
	                                </tr>
	                                <tr class="equipRow">
	                                    <td><label>Pack</label></td>
	                                    <td>
	                                    <select id="pack" name="pack">
	                                        <p>Attention pas de snow en pack Bronze</p>
	                                        <option id="or" value="3" <?php if($etu['pack'] == 3) echo "selected"; ?>>Or (Ski et Snow)</option>
	                                        <option id="argent" value="2" <?php if($etu['pack'] == 2) echo "selected"; ?>>Argent (Ski et Snow)</option>
	                                        <option id="bronze" value="1" <?php if($etu['pack'] == 1) echo "selected"; ?>>Bronze (Ski)</option>

	                                    </select><br>
	                                    </td>
	                                </tr>
	                                <tr class="equipRow">
	                                    <td><label>Items</label></td>
	                                    <td>
	                                    <select id="items" name="items">
	                                        <option disabled selected value> -- select an option -- </option>
	                                        <option value="4" <?php if($etu['items'] == 4) echo "selected"; ?>>Chaussures et Snow</option>
	                                        <option value="3" <?php if($etu['items'] == 3) echo "selected"; ?>>Chaussures et Skis</option>
	                                        <option value="2" <?php if($etu['items'] == 2) echo "selected"; ?>>Snow seul</option>
	                                        <option value="1" <?php if($etu['items'] == 1) echo "selected"; ?>>Skis seuls</option>
	                                        <option value="0" <?php if($etu['items'] == 0) echo "selected"; ?>>Chaussures seules</option>
	                                    </select>
	                                    </td>
	                                </tr>
	                            </table>
	                        </fieldset>
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
	                            <label>Food Pack (42 €)</label>
	                            <div>
	                                <select name="foodPack">
	                                    <option value="0" <?php if($etu['food'] == 0) echo "selected"; ?>>Avec porc 42€</option>
	                                    <option value="1" <?php if($etu['food'] == 1) echo "selected"; ?>>Sans porc 42€</option>
	                                    <option value="2" <?php if($etu['food'] == 2) echo "selected"; ?>>Aucun</option>
	                                </select>
	                            </div>
	                            <label>Assurance Annulation et Bagage (20 €) :</label>
	                             <div>
	                                <select name="assurance_annulation">
	                                    <option value="0" <?php if($etu['assurance_annulation'] == 0) echo "selected"; ?>>Non </option>
	                                    <option value="1" <?php if($etu['assurance_annulation'] == 1) echo "selected"; ?>>Oui</option>
	                                </select>
	                            </div>
	                            <label>Assurance Rapatriement (7 €) :</label>
	                             <div>
	                                <select name="assurance_rapa">
	                                    <option value="0" <?php if($etu['assurance_rapa'] == 0) echo "selected"; ?>>Non </option>
	                                    <option value="1" <?php if($etu['assurance_rapa'] == 1) echo "selected"; ?>>Oui</option>
	                                </select>
	                            </div>
	                        </fieldset>
	                    </div>
	                </div>

	                <div class="row">
											<input type="hidden" name="login" value="<?php echo $etu['login'];?>">
	                    <input class="btn btn-info" type="submit" value="Envoyer" id="submitbtn" disabled="disabled">
	                </div>
	            </form>
						</div>

						<!-- Footer -->
						<footer class="text-center">
							<div class="container">
								<p>Copyright &copy; Ski'UTC 2019</p>
								<p>Made with &#10084; by Pierre, Manu, Sevan & Elodie</p>
							</div>
						</footer>

						<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
				    <!-- Bootstrap core JavaScript -->
				    <script src="../vendor/jquery/jquery.min.js"></script>
				    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

				    <!-- Plugin JavaScript -->
				    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

				    <!-- Custom scripts for this template -->
				    <script src="../js/grayscale.min.js"></script>
				    <script>
				      $(function (){
				          // Etu & Tremplin
				          var checkNone = function() {
				            if($('#equipment-none').is(':selected')) {
				                $('#pack').hide();
				                $('#items').hide();
				            } else {
				                $('#pack').show();
				                $('#items').show();

				            }

				          };

				          checkNone();

				            var checkSnow = function() {
				            if($('#snow').is(':selected')) {
				                $('#pack').find ("option[value=1]").hide();
				                $('#items').find ("option[value=1]").hide();
				                $('#items').find ("option[value=3]").hide();
				            } else {
				                $('#pack').find ("option[value=1]").show();
				                $('#items').find ("option[value=1]").show();
				                $('#items').find ("option[value=3]").show();
				                }

				          };

				          checkSnow();

				            var checkSki = function() {
				            if($('#ski').is(':selected')) {
				                $('#items').find ("option[value=2]").hide();
				                $('#items').find ("option[value=4]").hide();
				            } else {
				                $('#items').find ("option[value=2]").show();
				                $('#items').find ("option[value=4]").show();
				                }

				          };

				          checkSki();

				          $('#equipment').change(function(){
				              checkNone();
				              checkSnow();
				              checkSki();
				          });

				          var checkOr = function() {
				            if($('#or').is(':selected')) {
				                $('#items').find ("option[value=0]").hide();
				            } else {
				                $('#items').find ("option[value=0]").show();
				                }
				          };

				          checkOr();

				          $('#pack').change(function(){
				              checkOr();
				          });


				          $('#pack').change(function(){
				            if($(this).val()=='1'){
				                $('#items') .find ("option[value=2]").hide();
				                $('#items') .find ("option[value=4]").hide();
				            }
				            if($(this).val()=='3'){
				                $('#items') .find ("option[value=2]").hide();
				                $('#items') .find ("option[value=4]").hide();
				            }
				            else{
				                $('#items') .find ("option[value=2]").show();
				                $('#items') .find ("option[value=4]").show();
				            }
				          });

				         var transportNone = function() {
				            if($('#sans').is(':selected')) {
				                $('#transport-back').hide();
				            } else {
				                $('#transport-back').show();
				            }
				          };

				          transportNone();

				          $('#transport').change(function(){
				              transportNone();
				          });

				          var checkAberant = function() {
				            if($('#ski').is(':selected') && ($('#items').find("option[value=2]").is(':selected') || $('#items').find("option[value=4]").is(':selected'))) {
				              $("#submitbtn").prop("disabled", true);
				            } else if($('#snow').is(':selected') && ($('#items').find("option[value=1]").is(':selected') || $('#items').find("option[value=3]").is(':selected'))) {
				              $("#submitbtn").prop("disabled", true);
				            } else if ($('#snow').is(':selected') && ($('#bronze').is(':selected'))) {
				              $("#submitbtn").prop("disabled", true);
				            } else {
				              $("#submitbtn").prop("disabled", false);
				            }
				          }

				          checkAberant();

				          $('#equipment').change(function(){
				              checkAberant();
				          });

				          $('#pack').change(function(){
				              checkAberant();
				          });

				          $('#items').change(function(){
				              checkAberant();
				          });

				      });

				     var equip = document.getElementById("equipment");

				        equip.addEventListener("change", function() {
				            if (this.options[this.selectedIndex].value == '2') {
				                document.getElementById('eco').style.display = "none";
				            } else {
				                document.getElementById('eco').style.display = "block";
				            }
				        }, false);



				    </script>

					<?php
						}

		catch (\Ginger\Client\ApiException $ex){
			echo "Erreur, login inexistant \n";
			//print $ex;
		}
		catch(\Ginger\Client\Exception $e){
			echo "Error";
		}
	}
	else {
		echo "Entrez un login";
		// $_SESSION['shotgun'] = 6;
	}

	//header("Location: index.php");
?>
</body>
</html>
