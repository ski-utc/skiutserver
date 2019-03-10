<!DOCTYPE html>
<html lang="en">
<head>
<?php session_start(); ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Ski'UTC 2019</title>

    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Main Stylesheet -->
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../images/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">

</head>

<body>

  <nav class="navbar" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019 | Encaissement</b></a>
    </div>
  </nav>


  <?php
      header('Content-Type: text/html; charset=utf-8');
  ?>


<?php
	include_once("db.php");
	include_once("user.php");

	$etu = getEtuFromLogin($db,$_GET['login']);
    if($etu['payment-first-received'] == 1){ ?>

    <!-- Affichage des options si paiement déjà réalisé -->

    <div class="container">
        <table class="recap">
            <thead><h2>Paiement réalisé, récapitulatif :</h2></thead>
            <tr>
                <td><strong>Nom</strong></td>
                <td><?php echo $etu['lastName'];?></td>
            </tr>
            <tr>
                <td><strong>Prenom</strong></td>
                <td><?php echo $etu['firstName'];?></td>
            </tr>
            <tr>
                <td><strong>Adresse</strong></td>
                <td><?php echo $etu['address'];?></td>
            </tr>
            <tr>
                <td><strong>Code postal</strong></td>
                <td><?php echo $etu['zipcode'];?></td>
            </tr>
            <tr>
                <td><strong>Ville</strong></td>
                <td><?php echo $etu['city'];?></td>
            </tr>
            <tr>
                <td><strong>Telephone</strong></td>
                <td><?php echo $etu['tel'];?></td>
            </tr>
            <tr>
                <td><strong>Mail</strong></td>
                <td><?php echo $etu['email'];?></td>
            </tr>
            <tr>
                <td><strong>Taille</strong></td>
                <td><?php echo $etu['size'];?></td>
            </tr>
            <tr>
                <td><strong>Poids</strong></td>
                <td><?php echo $etu['weight'];?></td>
            </tr>
            <tr>
                <td><strong>Pointure</strong></td>
                <td><?php echo $etu['shoesize'];?></td>
            </tr>
            <tr>
                <td><strong>Navette Aller Retour : </strong></td>
                <td>
                    <?php switch($etu['transport'])
                    {
                        case 0: echo "Compiègne    </td> </tr>"; ?> <tr>
                <td><strong>Transport retour</strong></td>
                <td>
                    <?php switch($etu['transport-back'])
                    {
                        case 0: echo "Avec"; break;
                        case 2: echo "Sans"; break;
                    } ?>
                </td>
            </tr><?php break;
                        case 1: echo "Paris </td> </tr> "; ?> <tr>
                <td><strong>Transport retour</strong></td>
                <td>
                    <?php switch($etu['transport-back'])
                    {
                        case 0: echo "Avec"; break;
                        case 2: echo "Sans"; break;
                    } ?>
                </td>
            </tr><?php break;
                        case 2: echo "Sans </td> </tr> "; break;
                    } ?>

            <tr>
                <td><strong>Food Pack</strong></td>
                <td>
                    <?php switch($etu['food'])
                    {
                        case 0: echo "Avec porc"; break;
                        case 1: echo "Sans porc"; break;
                        case 2: echo "Aucun"; break;
                    } ?>
                </td>
            </tr>
              <tr>
                <td><strong>Location</strong></td>
                <td>
                    <?php switch($etu['equipment'])
                    {
                        case 0: echo "Aucun"; break;
                        case 1:
                            switch($etu['pack'])
                            {
                                case 0: echo "Pas de Pack";
                                  break;
                                case 1: echo "Pack Bronze ";
                                 switch($etu['items'])
                                    {
                                        case 0: echo "Chaussures de ski"; break;
                                        case 1: echo "Skis seuls"; break;
                                        case 2: echo "Erreur, choix impossible"; break;
                                        case 3: echo "Chaussures + skis"; break;
                                        case 4: echo "Erreur, choix impossible"; break;
                                    }
                                    break;
                                case 2: echo "Pack Argent ";
                                    switch($etu['items'])
                                        {
                                            case 0: echo "Chaussures de ski"; break;
                                            case 1: echo "Skis seuls"; break;
                                            case 2: echo "Erreur, choix impossible"; break;
                                            case 3: echo "Chaussures + skis"; break;
                                            case 4: echo "Erreur, choix impossible"; break;
                                        }
                                        break;
                                case 3: echo "Pack Or ";
                                    switch($etu['items'])
                                        {
                                            case 0: echo "Chaussures de ski"; break;
                                            case 1: echo "Skis seuls"; break;
                                            case 2: echo "Erreur, choix impossible"; break;
                                            case 3: echo "Chaussures + skis"; break;
                                            case 4: echo "Erreur, choix impossible"; break;
                                        }
                                        break;
                            }

                            break;
                        case 2:
                            switch($etu['pack'])
                                {
                                    case 0: echo "Aucun";  break;
                                    case 1: echo "Pas de pack"; break;
                                    case 2: echo "Pack Argent ";
                                        switch($etu['items'])
                                            {
                                                case 0: echo "Chaussures de snowboard"; break;
                                                case 1: echo "Erreur, choix impossible"; break;
                                                case 2: echo "Snowboard seul"; break;
                                                case 3: echo "Erreur, choix impossible"; break;
                                                case 4: echo "Chaussures + snowboard"; break;
                                            }
                                             break;
                                    case 3: echo "Pack Or ";
                                        switch($etu['items'])
                                            {
                                                case 0: echo "Chaussures de snowboard"; break;
                                                case 1: echo "Erreur, choix impossible"; break;
                                                case 2: echo "Snowboard seul"; break;
                                                case 3: echo "Erreur, choix impossible"; break;
                                                case 4: echo "Chaussures + snowboard"; break;
                                            }
                                    break;
                                }

                            break;
                    } ?>
                </td>
            </tr>
            <tr>
              <td><strong> Assurance Annulation <a href="assurance-annulation.pdf" target="_blank">Clauses du contrat</a></strong> </td>
              <td>
                <?php switch($etu['assurance_annulation'])
                {
                    case 0: echo " Non"; break;
                    case 1: echo " Oui"; break;
                }?>
              </td>
            </tr>
            <tr>
              <td><strong> Assurance Rapatriement </td>
              <td>
                <?php switch($etu['assurance_rapa'])
                {
                    case 0: echo " Non"; break;
                    case 1: echo " Oui"; break;
                }?>
              </td>
            </tr>
            <tr>
                <td><h3>Prix total</h3></td>
                <td><strong><?php echo $etu['price'];?>€</strong></td>
            </tr>

    <?php
        if($etu['cheq2'] > 0) echo '<tr><td>deux cheques : 200 + '.$etu['cheq2'].'€</td></tr>' ;
        echo '</table>';
      ?>

<!--
    <form class="col-sm-6 col-xs-12"  action = "encaissement.php" method = "post">
      <input type="submit" value="Encaisser un autre étudiant ? ">
    </form> -->

    <a href="encaissement.php">Encaisser un autre étudiant ?</a>
  </div>


  <?php } else { ?>

  <!-- Affichage du prix total et choix du paiement par chèque -->

<!-- <head>
    <style type="text/css">
        @import url('./recap.css');
    </style>
</head> -->
<!-- <body> -->
    <div class="container">

      <form action="payment-validate.php" method="post" id="poulet" name="poulet">
      	<fieldset>
          	<legend class="title">Paiement</legend>
              <table>
              	<tr>
              		<td><label>Prix total</label></td>
                  <td id="pTot"><?php echo getPriceFromLogin($db,$_GET['login']); ?>€</td>
                  <input type="text" id="pTot" value="<?php echo getPriceFromLogin($db,$_GET['login']); ?>€" disabled/>
                  <input type="hidden" name="payment-price" value="<?php echo getPriceFromLogin($db,$_GET['login']); ?>"/>
                </tr>
                <tr>
                  <td><label>Payé en </label></td>
                	<td>
                    	 <input id="payment-count-1" type="radio" name="payment-type" value="1"/><label class="label-plain" for="payment-count-1">1 fois</label>
                	     <input id="payment-count-2" type="radio" name="payment-type" value="2"/><label class="label-plain" for="payment-count-2">2 fois</label>
                  </td>
                </tr>
                <tr>
                  <td><label>Si deux fois, montant du second chèque : </label></td>
                  <td>
                      <input id="mont-cheq2" type="number" name="mont-cheq2" value="0" />
                  </td>
                </tr>
                <tr>
                	<td><input type="submit" value="Payer" class="btn btn-info"/ disabled=disabled id="payButton"></td>
                  <td><input type="hidden" name="login" value="<?php echo $_GET['login'];?>"/></td>
                </tr>
              </table>
          </fieldset>
      </form>
    </div>

    <?php } ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>

        // Pour le calcul du deuxième chèque
        var payementCount1 = document.querySelector("#payment-count-1");
        var payementCount2 = document.querySelector("#payment-count-2");

        var cheque2 = document.querySelector("#mont-cheq2");
        var montant = document.querySelector("#pTot");

        payementCount1.addEventListener("click", function(){
            cheque2.value = 0;
        });

        payementCount2.addEventListener("click", function(){
            cheque2.value = parseInt(montant.value) - 200;
        });

        $("input:radio").change(function () {$("#payButton").prop("disabled", false);});



    /*
    var butP2  = document.getElementById("payment-count-2");
    var flr = "";
    console.log(butP2);
    butP2.addEventListener("click", function(){
       var mont = prompt("Montant du second cheque:");
      if(mont == null || mont == 0 || isNaN(mont)){
        alert("Met le montant ta race de permanencier !");
      }else{
        var hi = document.createElement("input");
        hi.name = "montant";
        hi.type = "number";
        hi.classname="disabled";
        hi.disabled = "true";
        hi.value = mont;
        hi.id = "montant";
        if(flr == "") flr = document.getElementById("pTot").innerHTML;
        document.getElementById("pTot").innerHTML = flr + (" - Montant du deuxième chèque: " + mont + "€");
        document.forms["poulet"].appendChild(hi);
      }
    });

    */
    </script>
  </body>
</html>
