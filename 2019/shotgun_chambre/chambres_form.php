<?php

session_start();

include("db.php");

$req = $db->query('SELECT * FROM users_2019 WHERE login = "' . $_SESSION['login'] . '" AND `payment-first-received` = 1');
if (!$req->fetch())
{
    header('location : ../../index.php');
    exit();
}

if (!$_SESSION['choix'])
{
    header("Location: chambres.php");
    exit();
}

//si on a bien choisi une des options de la page précédente, on définit le user
if(isset($_POST['taille_'])) {
    $user = $_SESSION['login'];

?>

<div class="container">
    <div class="chambres">

    <?php
    $_SESSION['choix'] = false;
    $_SESSION['recap'] = false;
    $_SESSION['heure'] = date('Y-m-d H:i:s');

    $_SESSION['taille']=$_POST['taille_'];
    $_SESSION['traitement'] = false;

    if ($_SESSION['form'] == true)
    {
        header('Location: chambres.php');
        exit();
    }

    $_SESSION['form'] = true;

    if (($_POST['taille_'] != "Autre") && ($_POST['taille_'] != "Seul")) {

        if ($_SERVER['HTTP_REFERER'] == 'http://assos.utc.fr/skiutc/2019/shotgun_chambre/chambres.php' or $_SERVER['HTTP_REFERER'] == 'http://assos.utc.fr/skiutc/2019/shotgun_chambre/chambres_recap.php')
        {
            //récupérer le nombre de chambres libres pour la taille choisie
            $dispo = $db->query('SELECT nb_libres FROM chambres_libres WHERE taille_chambre = ' . $_POST['taille_'])->fetch();
            if ($dispo['nb_libres'] <= 0) // si toutes les chambres de cette taille ont été prises entre temps
            {
                header('Location: chambres.php');
                exit();
            }
            // on bloque la chambre (pour 5min)
            $db->query('UPDATE chambres_libres SET nb_libres = nb_libres-1 WHERE taille_chambre = ' . $_POST['taille_']);
        }

        ?>
        <!DOCTYPE html>
        <html>
          <head>
            <title>Shotgun Chambres Skiutc 2019</title>
        	<link rel="stylesheet" href="chambres.css">
          </head>
          <body>
        <!-- SAISIE DES MEMBRES DE LA CHAMBRE -->

        <h1>Saisissez les <b>LOGINS</b> des membres de la chambre</h1>

        <h2>La chambre est réservée. Il te reste <span id="time"><?php echo $_SESSION['minutes'] . ':' . $_SESSION['secondes']; ?></span> minutes pour la remplir avant l'annulation automatique.</h2>

        <p>Le numéro de téléphone est indispensable au cas où un problème serait rencontré.</p>


        <form method="post" action="chambres_recap.php">
            <input name="taille_" type="hidden" value="<?php echo $_POST['taille_']; ?>" />
            <input name="responsable_" id="responsable_" type="hidden" value="<?php echo $user ?>" />
			<table>
   <tr>
       <td>
			<fieldset class="a">
			<legend> Informations générales : </legend>
            <label for="nom_">Nom de la chambre</label>
            <input name="nom_" type="text" value = "<?php if(isset($_POST['nom_'])) echo $_POST['nom_']; ?>" required />
            <br />

            <label for="soirees_">Soirées ?</label>
            <br><input name="soirees_" type="radio" value="Grosse chouille"  required /> Grosse chouille
            <br><input name="soirees_" type="radio" value="Chouille, mais pas que"  required /> Chouille, mais pas que
            <br><input name="soirees_" type="radio" value="Tranquille"  required /> Tranquille
            <br />

            <label for="voisins_">Voisins (login)</label>
            <input name="voisins_" type="text" value = "<?php if(isset($_POST['voisins_'])) echo $_POST['voisins_']; ?>" />
            <br />

            <label for="demande_">Demandes, remarques ou n'importe quoi d'autre, lâche toi !</label>
            <textarea name="demande_" rows="3" cols="50"><?php if(isset($_POST['demande_'])) echo $_POST['demande_']; ?></textarea>
            <br />

            <label for="telephone_">Téléphone du responsable</label>
            <input name="telephone_" type="tel" value="<?php if(isset($_POST['telephone_'])) echo $_POST['telephone_']; ?>" required />

			</fieldset>
			</td>
			<td>
			<fieldset class="b">
			<legend> Composition de la chambre : </legend>
			<p>Responsable de la chambre : <b><?php echo $user ?></b></p>

         <?php
            for($i=1;$i<$_POST['taille_'];$i++){
               $j = $i + 1;
               echo "<label for='membre${i}_'>Membre $j</label>
               <input name='membre${i}_' id='membre${i}_' type='text'";
               if(isset($_POST["membre${i}_"]))
                  echo " value=" . $_POST["membre${i}_"];
               echo " required />
               <span class='erreur erreurMembre${i}'></span>
               <br />";
            }?>
			</td><td>
            <input id="envoiForm" class="btn" name="saisie" type="submit" value="Envoyer" onclick="clicbouton();" />
            <a class="btn" href="" onclick="clicbouton(); retour(); " id="retour">Retour</a>
<td>
        <script type="text/javascript">
            function retour(){
                $.ajax({
                    type: "POST",
                    url: "deblocage_chambre.php",
                    data: "taille="+<?PHP echo $_POST['taille_']; ?>
                });
                };
        </script>

            <br />
			</td></tr></table>
			</fieldset>
        </form>
		<?php echo '<script language="javascript" type=\'text/javascript\'>setTimeout("window.location=\'deblocage_chambre.php\'",300000);</script>';?>

        <script type="text/javascript">
            var secondes = <?php echo $_SESSION['secondes'] ?>;
            var minutes = <?php echo $_SESSION['minutes'] ?>;
            var interval = setInterval(function(){
                secondes--;
                if (secondes < 0) {
                    minutes--;
                    secondes = 59;
                }

                var time = document.getElementById("time");

                time.textContent = this.minutes + ':' + this.secondes;

                if (this.minutes == 0 && this.secondes == 0) {
                    clearInterval(interval);
                    $.ajax({
                        type: "POST",
                        url: "deblocage_chambre.php",
                        data: "taille="+<?PHP echo $_POST['taille_']; ?>,
                        async: false
                    });
                    document.location.href="chambres.php";
                }
            },1000);
        </script>
		        <script type="text/javascript">
            function session(){
                        type: "POST",
                        url: "deblocage_chambre.php",
                        data: "taille="+<?PHP echo $_POST['taille_']; ?>,
                        async: false;
                    window.location=\'chambres.php\\';
                }

        </script>

        <script type="text/javascript">
            window.onbeforeunload = close; //à la sortie ou fermeture de la page

            var quitte = true; //permet de tester si la page est quittée via bouton ou non

            function close() {
                if (quitte){ //fenêtre fermée : on libère la chambre
                    $.ajax({
                        type: "POST",
                        url: "deblocage_chambre.php",
                        data: "taille="+<?PHP echo $_POST['taille_']; ?>,
                        success: function(retour) {},
                           async: false
                           });
                } else { // clic sur bouton : on màj le temps de saisie
                    $.ajax({
                        type: "POST",
                        url: "deblocage_chambre.php",
                        data: "secondes=" + secondes + "&minutes=" + minutes,
                        success: function(retour) {},
                        async: false
                    });
                }
            }

            function clicbouton(){
                quitte = false;
            }
        </script>


        <?php
    } elseif($_POST['taille_'] == "Autre") {
		$taille=1;
        ?>
        <link rel="stylesheet" href="chambres.css">

        <!-- SAISIE DES MEMBRES DU GROUPE -->

        <h1>Saisissez les <b>LOGINS</b> des membres du groupe</h1>

        <p>Le numéro de téléphone est indispensable au cas où un problème serait rencontré.</p>
        <p>Le responsable est compris d'office dans le groupe ; inutile de le ressaisir comme membre.</p>
        <p>Responsable du groupe : <b><?php echo $user ?></b></p>

        <form method="post" action="chambres_recap.php">
		<table>
   <tr>
       <td>
			<fieldset class="a">
			<legend> Informations générales : </legend>
            <input name="taille_" type="hidden" value="<?php echo $_POST['taille_']; ?>" />

            <label for="soirees_">Soirées ?</label>
            <br><input name="soirees_" type="radio" value="Grosse chouille" <?php if ($_POST['soirees_'] == "Grosse chouille") echo 'checked'; ?> required /> Grosse chouille
            <br><input name="soirees_" type="radio" value="Chouille, mais pas que" <?php if ($_POST['soirees_'] == "Chouille, mais pas que") echo 'checked'; ?> required /> Chouille, mais pas que
            <br><input name="soirees_" type="radio" value="Tranquille" <?php if ($_POST['soirees_'] == "Tranquille") echo 'checked'; ?> required /> Tranquille
            <br />

            <label for="voisins_">Voisins (login)</label>
            <input name="voisins_" type="text" value = "<?php if(isset($_POST['voisins_'])) echo $_POST['voisins_']; ?>" style="width: 400px;" />
            <br />

            <label for="demande_">Demandes, remarques ou n'importe quoi d'autre, lâche toi !</label>
            <textarea name="demande_" rows="3" cols="50"><?php if(isset($_POST['demande_'])) echo $_POST['demande_']; ?></textarea>
            <br />

            <label for="telephone_">Téléphone du responsable</label>
            <input name="telephone_" type="tel" value="<?php if(isset($_POST['telephone_'])) echo $_POST['telephone_']; ?>" required /><br />
            </fieldset>
        </td>
        <td>
			<fieldset class="b">
			<legend> Composition de la chambre : </legend>
            <input name="responsable_" id="responsable" type="hidden" value="<?php echo $user ?>" />
            <span class="erreur erreurResponsable"></span>
            <br />
            <div id="membres">
                <label for="membre1_">Membre 2</label>
                <input name="membre1_" id="membre1_" type="text" value="<?php if(isset($_POST['membre1_'])) echo $_POST['membre1_']; ?>" required />
                <span class="erreur erreurMembre1"></span>
                <br />

            </div>
			Remplissez le nombre de personnes qui seront dans votre groupe. Laissez les autres champs vides.
			<div id="membres">
                <label for="membre2_">Membre 3</label>
                <input name="membre2_" id="membre2_" type="text" value="<?php if(isset($_POST['membre2_'])) echo $_POST['membre2_']; ?>"  />
                <span class="erreur erreurMembre2"></span>
                <br />


            </div>
</td>
<td>


            <input id="envoiForm" class="btn" name="saisie" type="submit" value="Envoyer" />
            <a class="btn " href="chambres.php">Retour</a>
			</td></tr></table></fieldset>
        </form>

        <?php
    } elseif($_POST['taille_'] == "Seul") {
        ?>
        <link rel="stylesheet" href="chambres.css">
        <h2>Si tu es seul, tu complèteras une chambre.</h2>
        <form method="post" action="chambres_traitement.php">
			<table>
   <tr>
       <td>
			<fieldset class="a">
			<legend> Informations générales : </legend>
            <!--<label for="nom_">Nom de la chambre</label> -->
            <input type="hidden" name="taille_" value="Seul" />
            <input type="hidden" name="responsable_" value="<?php echo $user; ?>" />

            <label for="soirees_">Soirées ?</label>
            <br><input name="soirees_" type="radio" value="Grosse chouille" <?php if ($_POST['soirees_'] == "Grosse chouille") echo 'checked'; ?> required /> Grosse chouille
            <br><input name="soirees_" type="radio" value="Chouille, mais pas que" <?php if ($_POST['soirees_'] == "Chouille, mais pas que") echo 'checked'; ?> required /> Chouille, mais pas que
            <br><input name="soirees_" type="radio" value="Tranquille" <?php if ($_POST['soirees_'] == "Tranquille") echo 'checked'; ?> required /> Tranquille
            <br />

            <label for="demande_">Demandes, remarques ou n'importe quoi d'autre, lâche toi !</label>
            <textarea name="demande_" rows="3" cols="50"><?php if(isset($_POST['demande_'])) echo $_POST['demande_']; ?></textarea>
            <br />

            <input class="btn " name="saisie" type="submit" value="Envoyer" style="margin-top: 100px;" />
        </form>

        <a class="btn " href="chambres.php" >Retour</a>
		</fieldset>
</td></tr></table>


        <?php
    } else {
        header("Location: chambres.php");
    }
} else {
    header("Location: chambres.php");
}
        ?>
    </div>
</div>
</body>
<script type="text/javascript" src="js2/jquery.js"></script>
<script type="text/javascript" src="js2/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="js2/main_.js"></script>
</html>
