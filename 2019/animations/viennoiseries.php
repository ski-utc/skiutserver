<?php
session_start();

include("../db.php");
include("../user.php");

if(!isset($_SESSION['login-etu']) && !isset($_SESSION['login-tremplin']))
{
    echo "Merci de vous connecter, redirection vers l'accueil";
    header("refresh: 3;url= /skiutc/index.php");
    exit();
}
else{
    $user = isset($_SESSION['login-etu']) ? getEtuFromLogin($db,$_SESSION['login-etu']) : getEtuFromLogin($db,$_SESSION['login-tremplin']);
    $_SESSION['login'] = $user['login'];
    // $mail = $user['email'];
    if(isset($_SESSION['login-etu'])) {
      $mail = $_SESSION['login-etu']."@etu.utc.fr";
    }  else {
        $mail = $_SESSION['login-tremplin'];
    }

    $login = strpos($mail, "@") ? substr($mail, 0, strpos($mail, "@")) : $mail;

    $req = $db->query('SELECT * FROM users_2019 WHERE login = "' . $_SESSION['login'] . '" AND `payment-first-received` = 1');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Ski'UTC 2019 - Boulangerie</title>

      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="skiutc website">
      <meta name="author" content="teaminfooo">
      <!-- Bootstrap core CSS -->
      <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Custom style -->
      <link href="../css/form.css" rel="stylesheet">
      <!-- Custom fonts for this template -->
      <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
      <!-- Logo -->
      <link rel="shortcut icon" href="../images/logo.png">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </head>
  <body>
    <!-- Navigation -->
    <nav class="navbar" id="mainNav">
      <div class="container">
          <a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019 | Viennoiseries</b></a>
      </div>
    </nav>

    <?php
    if (!$req->fetch()){
          echo "Vous ne participez pas au voyage";
          header("refresh: 3;url= http://www.watching-grass-grow.com/");
      } else {
      // $id1=$db->query('SELECT id_user as idd FROM users_2019 WHERE login = "' . $_SESSION['login'] . '"');
      // $id1 = $id1->fetch();
      // $id = $id1['idd'];
      $id = $_SESSION['login'];
      ?>
      <h1> Bonjour <?php echo $_SESSION['login']; ?> </h1>
      <?php

      // $nombre = $db->prepare('SELECT count(*) as nb FROM `anim_user` WHERE id_anim = 1 group by id_anim ');
      // $nombre = $db->prepare('SELECT count(*) as nb FROM `rel_options_users` WHERE id_option = 14011 group by id_option ');

      $idgroupe = $db->query('SELECT `id_groupe` FROM `rel_groupe_user` WHERE `login_user`= "' . $_SESSION['login'] .  '"')->fetch();

      $payed = $db->prepare('SELECT `id_groupe` FROM `petitdej` WHERE `id_groupe` = ' . $idgroupe['id_groupe']);
      $payed->execute();

      // if($payed->fetch())
      //     Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> Viennoiseries déjà réservées ! ! Vous êtes bien enregistré ! </font> </b> ";
      // else
      // { ?>
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                  <img src="../images/viennoiseries.jpg" width="100%">
              </div>
              <div class="col-md-6">
                <form id="petitdej" name="petitdej">
                    <fieldset>
                        <h2>Petit dej ! </h2>
                        <p>Les quantités à choisir sont à choisir pour un matin. Exemple : si vous choisissez une baguette alors vous aurez une baguette tous les matins. </p>
                        <br>
                        <br>
                        <p>Nombre de baguettes par jour pour la chambre : (9,1 € pour les 5 jours)</p>
                        <input type = "number" style = " width: 50px" id = "qtBaguette" name="baguette" value = 0 min = 0 max = 35 onchange = "calculate()">
                        <p>Nombre de croissants par jour pour la chambre : (5 € pour les 5 jours)</p>
                        <input type = "number" style = " width: 50px" id = "qtCroissant" name="croissant" value = 0 min = 0 max = 35 onchange = "calculate()">
                        <p>Nombre de pains au chocolat (NON PAS CHOCOLATINE) par jour pour la chambre : (9,1 € pour les 5 jours)</p>
                        <input type = "number" style = " width: 50px" id = "qtChoco" name="choco" value = 0 min = 0 max = 35 onchange = "calculate()">
                        <br>
              					<span class="itemName"><b>Total : </b></span>
              					<span class="price"><input name="sum" readonly style="border:none" id = "sum" value="0"></span>
                        <input class="btn btn-success" type="button" value="Payer" onclick = "launch()"/>
                    </fieldset>
                </form>
              </div>
            </div>
          </div>
        <?php //} ?>

        <!-- Footer -->
        <footer class="text-center">
          <div class="container">
            <p>Copyright &copy; Ski'UTC 2019</p>
            <p>Made with &#10084; by Pierre, Manu, Sevan & Elodie</p>
          </div>
        </footer>

      <?php } ?>


  <script type="text/javascript">
      function calculate(){
              total = 9.1 * parseInt(document.petitdej.qtBaguette.value) +
                      (9.1 * parseInt(document.petitdej.qtChoco.value)) +
                      (5 * parseInt(document.petitdej.qtCroissant.value));
              document.petitdej.sum.value = total.toFixed(2) +' euros'; }

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
                  for (var key in params.data){
                      url += key + "=" + params.data[key] + "&";
                  }
                  url = url.substring(0,url.length-1);
                  params.url += url;
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

  <script>

  function launch () {

    var qteBaguette = document.forms.namedItem("petitdej").baguette;
  	var qteCroissant = document.forms.namedItem("petitdej").croissant;
    var qteChoco = document.forms.namedItem("petitdej").choco;

  	var log = '<?php echo $mail; ?>';
  	console.log("login",log);
    console.log("baguette", qteBaguette.value);

  	if (qteBaguette.value > 0 && qteCroissant.value >= 0 && qteChoco.value >= 0 || qteBaguette.value >= 0 && qteCroissant.value > 0 && qteChoco.value >= 0 || qteBaguette.value >= 0 && qteCroissant.value >= 0 && qteChoco.value > 0)
    AJAXCall({
        url : "http://vps604065.ovh.net:8080/petitdej/" + qteBaguette.value + "/" + qteCroissant.value + "/" + qteChoco.value + "/" + log + "?rand=" + Math.floor(Math.random()*1000),
        method : "GET",
        data: {},
        async: true,
        callback: function(data){
            data=JSON.parse(data);
            console.log("Reponse recue", data.toString());

            document.location = data.replace(/\"/g, '');
        }
    });
  }

  // var submit = document.getElementById("sbmt");
  // submit.addEventListener("click", function(event){
  // event.preventDefault();
  // launch();
  // });
  </script>
 </body>
</html>

<?php
 } ?>
