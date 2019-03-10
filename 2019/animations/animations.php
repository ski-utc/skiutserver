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
    $req = $db->query('SELECT * FROM users_2019 WHERE login = "' . $_SESSION['login'] . '" AND `payment-first-received` = 1');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Ski'UTC 2019 - Animations</title>

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
          <a class="navbar-brand" href="../../index.php"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019 | Animations</b></a>
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
      $nombre = $db->prepare('SELECT count(*) as nb FROM `rel_options_users` WHERE id_option = 14011 group by id_option ');
      $nombre->execute();
      $nombre = $nombre->fetch();
      $nbraquettes = $nombre['nb'];

      $nombre = $db->prepare('SELECT count(*) as nb FROM `rel_options_users` WHERE id_option = 14010 group by id_option ');
      $nombre->execute();
      $nombre = $nombre->fetch();
      $nbparapente = $nombre['nb'];

      $nombre = $db->prepare('SELECT count(*) as nb FROM `rel_options_users` WHERE id_option = 14012 group by id_option ');
      $nombre->execute();
      $nombre = $nombre->fetch();
      $nbspa = $nombre['nb'];

        $paye = getCours($db,$id,13998);
        $paye2 = getCours($db,$id,14016);
        $paye3 = getCours($db,$id,14017);
            if($paye['nb'] >= 1 || $paye2['nb'] >=1 || $paye3['nb'] >= 1)
                Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> Cours de ski déjà réservé ! ! Vous êtes bien enregistré ! </font> </b> ";
            else
            { ?>
          <div class="container">
            <div class="row">
              <div class="col-md-6">
                  <img src="../images/cours.jpg" width="100%">
              </div>
              <div class="col-md-6">
                <form id="cours" name="cours" method="post" action="cours.php">
                    <fieldset>
                        <h2>Cours de ski </h2>
                        <p>Apprend à skier avec Dora pendant 3 demi-journées ! </p>
                        <select name="niveau" id="level">
                            <option value="1"> Débutant </option>
                            <option value="2"> Faux Débutant </option>
                            <option value="3"> Intermédiaire </option>
                        </select>
                        <b>33 €</b>
                        <br>
                        <!-- <input id="long" type="hidden" name="cours" value="13998"/> -->
                        <input class="btn btn-success" type="button" value="Payer" onclick = "launch(1, 13998)"/>
                    </fieldset>
                </form>
              </div>
            </div>
          </div>
        <?php }

        $paye4 = getCours($db,$id,14010);
           if($paye4['nb'] >= 1)
               Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> Parapente deja reserve ! ! Vous êtes bien enregistré ! </font> </b> ";
           else
           { ?>
           <br><br><br>
           <div class="container">
             <div class="row">
               <div class="col-md-6">
                 <img src="../images/parapente.jpg" width="100%">
               </div>
               <div class="col-md-6">
                 <form id="parapente" name="parapente">
                   <fieldset>
                       <h2>Parapente !  </h2>
                       <p>Si tu as l’âme d’un papillon, que tu veux t’envoler pour le 7eme ciel ou juste t’essayer au parapente, tout est possible. Notre Prez adoré te propose d'apprendre à voler pour la modique somme de 56€.</p>
                       <p>ATTENTION : pour faire du parapente il faut rajouter l'extension de forfait pour y aller, qui pourra être acheté lorsqu'on arrivera à Veysonnaz.</p>
                       <br>
                       <b>56 €</b>
                       <br>
                       <input id="long" type="hidden" name="parapente" value="14010"/>
                       <?php if($nbparapente < 125) {?>
                       <input class="btn btn-success" type="button" value="Payer" onclick = "launch(2, 14010)"/>
                       <?php } else echo "Vente finie"; ?>
                   </fieldset>
                 </form>
               </div>
             </div>
           </div>
         <?php }

         $paye = getCours($db,$id,14011);
            if($paye['nb'] >= 1)
                Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> Balade en raquette deja reserve ! ! Vous êtes bien enregistré ! </font> </b> ";
            else
            { ?>
            <br><br><br>
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <img src="../images/randonnee-raquette.jpg" width="100%">
                </div>
                <div class="col-md-6">
                  <form id="raquettes" name="raquettes">
                    <fieldset>
                        <h2>Balade en raquettes !  </h2>
                        <p>Dans la forêt, il y a un sapin ...</p>
                        <br>
                        <b>10 €</b>
                        <br>
                        <input id="long" type="hidden" name="raquettes" value="14011"/>
                        <?php if($nbraquettes < 90) {?>
                          <input class="btn btn-success" type="button" value="Payer" onclick = "launch(3, 14011)"/>
                        <?php } else echo "Vente finie"; ?>
                    </fieldset>
                  </form>
                </div>
              </div>
            </div>
          <?php }

          $paye = getCours($db,$id,14012);
             if($paye['nb'] >= 1)
                 Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> SPA deja reserve ! ! Vous êtes bien enregistré ! </font> </b> ";
             else
             { ?>
             <br><br><br>
             <div class="container">
               <div class="row">
                 <div class="col-md-6">
                   <img src="../images/spa.jpg" width="100%">
                 </div>
                 <div class="col-md-6">
                   <form id="spa" name="spa">
                     <fieldset>
                         <h2>SPA !  </h2>
                         <p>Si tu veux te détendre après ta journée de ski intense avec Natacha ...</p>
                         <br>
                         <b>14 €</b>
                         <br>
                         <input id="long" type="hidden" name="spa" value="14012"/>
                         <?php if($nbspa < 180) {?>
                           <input class="btn btn-success" type="button" value="Payer" onclick = "launch(4, 14012)"/>
                        <?php } else echo "Vente finie"; ?>
                     </fieldset>
                   </form>
                 </div>
               </div>
             </div>
           <?php }

           $paye = getCours($db,$id,14013);
             if($paye['nb'] >= 1)
                 Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> Deja ". $paye['nb'] ." RedBull reserve(s) ! </font> </b> ";
                 ?>
             <br><br><br>
             <div class="container">
               <div class="row">
                 <div class="col-md-6">
                   <img src="../images/Redbull_normal.png" width="40%">
                 </div>
                 <div class="col-md-6">
                   <form id="redbull" name="redbull">
                     <fieldset>
                         <h2>Pack de 4x25cl de RedBull !  </h2>
                         <p>Si tu veux lacher des gros tricks comme Jasper Tjäder !! (Un Dieu pour nous tous)</p>
                         <br>
                         <p>Ou simplement pour accompagner ton Jäger !</p>
                         <br>
                         <b>6,12 €</b>
                         <br>
                         <input id="redbullNumber" type="number" name="redbull_nb" min="0" max="100" step="1" value="1">
                         <!-- <input id="long" type="hidden" name="redbull" value="14013"/> -->
                         <input class="btn btn-success" type="button" value="Payer" onclick = "launch(5, 14013)"/>
                     </fieldset>
                   </form>
                 </div>
               </div>
             </div>
           <?php

           $paye = getCours($db,$id,6);
             if($paye['nb'] >= 1)
                 Echo " <font color= 'red'> <b><br> <br> <br> <br> <br> Deja ". $paye['nb'] ." RedBull sans sucre reserve(s) ! </font> </b> ";
                 ?>
             <br><br><br>
             <!-- <div class="container">
               <div class="row">
                 <div class="col-md-6">
                   <img src="../images/Redbull_sugarfree.png" width="40%">
                 </div>
                 <div class="col-md-6">
                   <form id="redbull_sugarfree" name="redbull_sugarfree">
                     <fieldset>
                         <h2>Pack de 4x25cl de RedBull Sans Sucre !  </h2>
                         <p>Si tu veux lacher des gros tricks comme Jasper Tjäder tout en faisant attention à ton cholesterol !! (Un Dieu pour nous tous)</p>
                         <br>
                         <p>Ou simplement pour accompagner ton Jäger (bon là... sugar free ou pas, ca va pas changer grand chose) !</p>
                         <br>
                         <b>5€02</b>
                         <br>
                         <input id="redbullZero" type="number" name="redbull_sugarfree_nb" min="0" max="100" step="1" value="1">
                         <input id="long" type="hidden" name="redbull" value="14014"/>
                         <input class="btn btn-success" type="button" value="Payer" onclick = "launch(6, 14014)"/>
                     </fieldset>
                   </form>
                 </div>
               </div>
             </div> -->

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
              total = 8.6*parseInt(document.shop.qt1.value) + (3 * parseInt(document.shop.qt2.value));
              document.shop.sum.value = total.toFixed(2) +' euro'; }
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

  function launch (idanim, articleID) {

    if (idanim == 1) {
      var element = document.getElementById("level");
      var level = element.options[element.selectedIndex].value;

      console.log("level :", level);

      switch (level) {
        case "1":
          articleID = 13998;
          break;
        case "2":
          var articleID = 14016;
          console.log(articleID);
          break;
        case "3":
          var articleID = 14017;
          break;
        default:
          var articleID = 13998;
      }

    } else {
      var level = 0;
    }

    if (idanim == 5) {
      var quantity = document.getElementById("redbullNumber").value;
    } else if (idanim == 6) {
      var quantity = document.getElementById("redbullZero").value;
    } else {
      var quantity = 1;
    }

    var log = '<?php echo $mail; ?>';
    console.log("login",log);
    AJAXCall({
        url : "http://vps604065.ovh.net:7237/anim/" + articleID + "/" + quantity + "/" + log + "?rand=" + parseInt(Math.random()*1000),
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
