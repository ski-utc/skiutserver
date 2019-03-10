<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Ski'UTC 2019</title>
    <!-- core css -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Main Stylesheet -->
    <link href="../css/form.css" rel="stylesheet">
    <link rel="shortcut icon" href="../images/logo.png">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
    <script type="text/javascript">
function AJAXCall (params){
            //params = url, method, callback, data, async, status
            if (typeof params == 'undefined'){
                throw new Error("Parameters are required");
            }

            if (typeof params.url == 'undefined'){
                throw new Error("URL is required for AJAXCall");
            }

            if (typeof params.method == 'undefined' || (params.method != "GET" && params.method != "POST" && params.method != "DELETE" && params.method != "PUT")){
                throw new Error("method for AJAXCall is required and must be GET, POST, DELETE or PUT only");
            }

            // if (typeof params.status == 'undefined'){
            //  throw new Error("Status (200, 201, 204...) is required for callback");
            // }

            var xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if (xml.readyState == 4 && xml.status == 200){
                    if (typeof params.callback != 'undefined') params.callback(xml.responseText);
                    console.log("Response Arrived");
                    return xml.responseText;
                }
            };

            var async;
            if (typeof params.async != 'undefined'){
                async = params.async;
            }else{
                async = true;
            }

            if (params.method == "GET" || params.method == "DELETE"){

                if (typeof params.data == 'undefined'){
                    xml.open(params.method, params.url, async);
                    xml.setRequestHeader("Content-Type", "application/json");
                    xml.send();
                }else{
                    var url = (params.url.substring(params.url.length-1, params.url.length) == "/")? "?" : "/?";
                    for (var key=0; key<params.data.length; key++){
                        url += "art"+ key + "=" + params.data[key] + "&";
                    }
                    url = url.substring(0,url.length-1);
                    params.url += url+"&rand=" + parseInt(Math.random() *1000);
                    xml.open(params.method, params.url, async);
                    xml.setRequestHeader("Content-Type", "application/json");

                    xml.send();
                    console.log("Request Sent");
                }

            }else if(params.method == "POST" || params.method == "PUT"){
                xml.open(params.method, params.url, async);
                    xml.setRequestHeader("Content-Type", "application/json");

                if (typeof params.data != 'undefined'){
                    xml.send(params.data);
                }else{
                    xml.send();
                }
            }
        }



</script>

<body cz-shortcut-listen="true">

        <!-- Navbar ==================================== -->
<?php

	if(!isset($_SESSION['login-tremplin']))
		header("Location: ../../index.php");
	// if(!in_array('options',$_SESSION['services']))
  //   header('location : ../../index.php');

	include_once("db.php");

	// if(isset($_POST['address'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `address`=:address
   //                            WHERE `login`=:login');
   //    $update->bindParam(':address',htmlentities($_POST['address'],ENT_QUOTES, "UTF-8"),PDO::PARAM_STR);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['zipcode'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `zipcode`=:zipcode
   //                            WHERE `login`=:login');
   //    $update->bindParam(':zipcode',htmlentities($_POST['zipcode']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['city'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `city`=:city
   //                            WHERE `login`=:login');
   //    $update->bindParam(':city',htmlentities($_POST['city'],ENT_QUOTES, "UTF-8"),PDO::PARAM_STR);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['tel'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `tel`=:tel
   //                            WHERE `login`=:login');
   //    $update->bindParam(':tel',htmlentities($_POST['tel']),PDO::PARAM_STR);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['size'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `size`=:size
   //                            WHERE `login`=:login');
   //    $update->bindParam(':size',htmlentities($_POST['size']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['weight'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `weight`=:weight
   //                            WHERE `login`=:login');
   //    $update->bindParam(':weight',htmlentities($_POST['weight']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['shoesize'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `shoesize`=:shoesize
   //                            WHERE `login`=:login');
   //    $update->bindParam(':shoesize',htmlentities($_POST['shoesize']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['transport'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `transport`=:transport
   //                            WHERE `login`=:login');
   //    $update->bindParam(':transport',htmlentities($_POST['transport']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }
   // if(isset($_POST['transport-back'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `transport-back`=:transport_back
   //                            WHERE `login`=:login');
   //    $update->bindParam(':transport_back',htmlentities($_POST['transport-back']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }

   // if(isset($_POST['email'])){
   //    $update = $db->prepare('UPDATE `users_2019`
   //                            SET `email`=:email
   //                            WHERE `login`=:login');
   //    $update->bindParam(':email',htmlentities($_POST['email']),PDO::PARAM_INT);
   //    $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
   //    $update->execute();
   // }


   if(isset($_POST['address'])
		&&isset($_POST['zipcode'])
		&&isset($_POST['city'])
		&&isset($_POST['size'])
		&&isset($_POST['weight'])
		&&isset($_POST['shoesize'])
		&&isset($_POST['transport'])
		&&isset($_POST['transport-back'])
		&&isset($_POST['email'])
		&&isset($_POST['tel'])
		)
	{
		$update = $db->prepare('UPDATE `users_2019`
            SET `address`=:address,`zipcode`=:zipcode,`tel`=:tel,`city`=:city,`size`=:size,`weight`=:weight,`shoesize`=:shoesize,`transport`=:transport,`transport-back`=:transport_back,`email`=:email
            WHERE `login`=:login');
		$update->bindParam(':address',htmlentities($_POST['address'],ENT_QUOTES, "UTF-8"),PDO::PARAM_STR);
      $update->bindParam(':zipcode',htmlentities($_POST['zipcode']),PDO::PARAM_INT);
      $update->bindParam(':size',htmlentities($_POST['size']),PDO::PARAM_INT);
      $update->bindParam(':city',htmlentities($_POST['city'],ENT_QUOTES, "UTF-8"),PDO::PARAM_STR);
      $update->bindParam(':weight',htmlentities($_POST['weight']),PDO::PARAM_INT);
      $update->bindParam(':shoesize',htmlentities($_POST['shoesize']),PDO::PARAM_INT);
      $update->bindParam(':transport',htmlentities($_POST['transport']),PDO::PARAM_INT);
      $update->bindParam(':transport_back',htmlentities($_POST['transport-back']),PDO::PARAM_INT);
      $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
      $update->bindParam(':email',htmlentities($_POST['email']),PDO::PARAM_STR);
      $update->bindParam(':tel',htmlentities($_POST['tel']),PDO::PARAM_STR);
		$update->execute();
	}

   if(isset($_POST['lastName'])){
      $update = $db->prepare('UPDATE `users_2019`
                              SET `lastName`=:lastName
                              WHERE `login`=:login');
      $update->bindParam(':lastName',htmlentities($_POST['lastName']),PDO::PARAM_STR);
      $update->bindParam(':login',htmlentities($_SESSION["login-tremplin"]),PDO::PARAM_STR);
      $update->execute();
   }


   $user = $db->query('
   	SELECT *
   	FROM `users_2019`
   	WHERE `login`="'.$_SESSION['login-tremplin'].'"
   	');
   $user = $user->fetch();
?>

  <nav class="navbar" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019</b></a>
    </div>
  </nav>
    <div class="container">
        <table class="recap">
            <thead><h2>Récapitulatif</h2></thead>
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
        </table>

        <form class="col-sm-12 col-xs-12"  action = "options_tremplin.php" method = "post">
          <input type="submit" class="btn btn-info" value="Modification">
        </form>
    </div>

    <footer class="text-center">
      <div class="container">
        <p>Copyright &copy; Ski'UTC 2019</p>
        <p>Made with &#10084; by Pierre, Manu, Sevan & Elodie</p>
      </div>
    </footer>

</body>

<script>

function launch () {
    var log = "<?php echo $mail; ?>";
    var articles = '<?php echo json_encode($list_article);?>';
    console.log("login",log);
    console.log("articles", articles);
    AJAXCall({
        url : "http://vps604065.ovh.net:8081/pack/" + log,// + "?rand=" + parseInt(Math.random() *1000),
        method : "GET",
        data: JSON.parse(articles),
        async: true,
        callback: function(data){
            data=JSON.parse(data);
            console.log("Reponse recue", data.toString());

            document.location = data.replace(/\"/g, '');
        }
    });
}
</script>

</html>
