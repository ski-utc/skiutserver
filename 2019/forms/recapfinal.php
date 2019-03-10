<!DOCTYPE html>
<html lang="en">
<head>
<?php session_start();
    include_once("db.php");
    include_once("user.php");
    $user = isset($_SESSION['login-etu']) ? getEtuFromLogin($db,$_SESSION['login-etu']) : getEtuFromLogin($db,$_SESSION['login-tremplin']);
    // $etu = getInfoFromLogin($db, $_GET['login']);
?>
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
</head><!--/head-->

<body>

  <nav class="navbar" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019 | Récapitulatif</b></a>
    </div>
  </nav>

<?php
    header('Content-Type: text/html; charset=utf-8');
?>


<?php

  if($user['payment-first-received'] == 1){
        ?>

    <div class="container">
        <table class="recap">
            <thead><h2>Récapitulatif :</h2></thead>
            <tr>
                <td><strong>Nom</strong></td>
                <td><?php echo $user['lastName'];?></td>
            </tr>
            <tr>
                <td><strong>Prenom</strong></td>
                <td><?php echo $user['firstName'];?></td>
            </tr>
            <tr>
                <td><strong>Adresse</strong></td>
                <td><?php echo $user['address'];?></td>
            </tr>
            <tr>
                <td><strong>Code postal</strong></td>
                <td><?php echo $user['zipcode'];?></td>
            </tr>
            <tr>
                <td><strong>Ville</strong></td>
                <td><?php echo $user['city'];?></td>
            </tr>
            <tr>
                <td><strong>Telephone</strong></td>
                <td><?php echo $user['tel'];?></td>
            </tr>
            <tr>
                <td><strong>Mail</strong></td>
                <td><?php echo $user['email'];?></td>
            </tr>
            <tr>
                <td><strong>Taille</strong></td>
                <td><?php echo $user['size'];?></td>
            </tr>
            <tr>
                <td><strong>Poids</strong></td>
                <td><?php echo $user['weight'];?></td>
            </tr>
            <tr>
                <td><strong>Pointure</strong></td>
                <td><?php echo $user['shoesize'];?></td>
            </tr>
            <tr>
                <td><strong>Navette Aller Retour : </strong></td>
                <td>
                    <?php switch($user['transport'])
                    {
                        case 0: echo "Compiègne    </td> </tr>"; ?> <tr>
                <td><strong>Transport retour</strong></td>
                <td>
                    <?php switch($user['transport-back'])
                    {
                        case 0: echo "Avec"; break;
                        case 2: echo "Sans"; break;
                    } ?>
                </td>
            </tr><?php break;
                        case 1: echo "Paris </td> </tr> "; ?> <tr>
                <td><strong>Transport retour</strong></td>
                <td>
                    <?php switch($user['transport-back'])
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
                    <?php switch($user['food'])
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
                    <?php switch($user['equipment'])
                    {
                        case 0: echo "Aucun"; break;
                        case 1:
                            switch($user['pack'])
                            {
                                case 0: echo "Pas de Pack";
                                  break;
                                case 1: echo "Pack Bronze ";
                                 switch($user['items'])
                                    {
                                        case 0: echo "Chaussures de ski"; break;
                                        case 1: echo "Skis seuls"; break;
                                        case 3: echo "Chaussures + skis"; break;
                                    }
                                    break;
                                case 2: echo "Pack Argent ";
                                    switch($user['items'])
                                        {
                                            case 0: echo "Chaussures de ski"; break;
                                            case 1: echo "Skis seuls"; break;
                                            case 3: echo "Chaussures + skis"; break;
                                        }
                                        break;
                                case 3: echo "Pack Or ";
                                    switch($user['items'])
                                        {
                                            case 0: echo "Chaussures de ski"; break;
                                            case 1: echo "Skis seuls"; break;
                                            case 3: echo "Chaussures + skis"; break;
                                        }
                                        break;
                            }

                            break;
                        case 2:
                            switch($user['pack'])
                                {
                                    case 0: echo "Aucun";  break;
                                    case 1: echo "Pas de pack"; break;
                                    case 2: echo "Pack Argent ";
                                        switch($user['items'])
                                            {
                                                case 0: echo "Chaussures de snowboard"; break;
                                                case 2: echo "Snowboard seul"; break;
                                                case 4: echo "Chaussures + snowboard"; break;
                                            }
                                             break;
                                    case 3: echo "Pack Or ";
                                        switch($user['items'])
                                            {
                                                case 0: echo "Chaussures de snowboard"; break;
                                                case 2: echo "Snowboard seul"; break;
                                                case 4: echo "Chaussures + snowboard"; break;
                                            }
                                    break;
                                }

                            break;
                    }?>
                </td>
            </tr>
            <tr>
              <td><strong> Assurance Annulation <a href="assurance-annulation.pdf" target="_blank">Clauses du contrat</a></strong> </td>
              <td>
                <?php switch($user['assurance_annulation'])
                {
                    case 0: echo " Non"; break;
                    case 1: echo " Oui"; break;
                }?>
              </td>
            </tr>
            <tr>
              <td><strong> Assurance Rapatriement </td>
              <td>
                <?php switch($user['assurance_rapa'])
                {
                    case 0: echo " Non"; break;
                    case 1: echo " Oui"; break;
                }?>
              </td>
            </tr>
            <tr>
                <td><strong>Caution</strong></td>
                <td>
                    <?php switch($user['caution'])
                    {
                        case 0: echo "pas donné"; break;
                        case 1: echo "donné"; break;
                    } ?>
                </td>
            </tr>
            <tr>
                <td><strong>Pack Tize</strong></td>
                <td><?php echo $user['packtize'];?></td>
            </tr>
            <tr>
                <td><h3>Prix total</h3></td>
                <td><strong><?php echo $user['price'];?>€</strong></td>
            </tr>
          </table>

<?php
    };

?>

 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  </body>
</html>
