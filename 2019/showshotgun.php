<?php session_start();
include_once("2019/db.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="skiutc website">
   <meta name="author" content="teaminfooo">
   <!--CSS-->

   <!-- <link rel="stylesheet" type="text/css" href="2019/font-awesome-4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" type="text/css" href="2019/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="2019/css/style.css">
   <link rel="stylesheet" type="text/css" href="2019/css/animate.css"> -->

   <!-- Custom styles for this template -->
   <link href="2019/css/index.css" rel="stylesheet">

   <!-- Bootstrap core CSS -->
   <link href="2019/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

   <!-- Custom fonts for this template -->
   <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

   <!--Google Fonts-->
   <link rel="shortcut icon" href="2019/images/logo.png">
</head>
<body>
   <h1 style='margin: 0px auto;text-align:center;'>Résultat shotgun SkiUTC 2k19</h1>
   <div class='row align-items-center no-gutters'>
<?php
   $num=0;
   $shotgun_reussi = $db->query('SELECT login FROM `shotgun-etu_2019` LIMIT 300');
   for($i=1;$i<6;$i++){
      echo "
      <div class='col align-self-center'>
         <table style='margin: 0px auto;text-align:center;'>
            <th align='center'>Classement</th>
            <th align='center'>Login</th>";
      while ($num<$i*60 && $donnees = $shotgun_reussi->fetch())
      {
         $num=$num+1;
         $login=$donnees[0];
      echo  "<tr><td>".$num."</td><td>".$login."</td></tr>";
      }
      echo "
         </table>
      </div>";

   }
?>
   </div>
   <!--echo"      <div class='col-xl-4 col-lg-4 align-self-center'>
            <table style='margin: 0px auto;text-align:center;'>
               <th>Classement</th>
               <th>Login</th>";
   while ($num<200 && $donnees = $shotgun_reussi->fetch())
   {
      $num=$num+1;
      $login=$donnees[0];
            echo "<tr><td>".$num."</td><td>".$login."</td></tr>";
   }
      echo "</table>
         </div>
         <div class='col-xl-4 col-lg-4 align-self-center'>
            <table style='margin: 0px auto;text-align:center;'>
               <th>Classement</th>
               <th>Login</th>";
   while ($num<300 && $donnees = $shotgun_reussi->fetch())
   {
      $num=$num+1;
      $login=$donnees[0];
         echo "<tr><td>".$num."</td><td>".$login."</td></tr>";
   }
      echo '</table>
         </div>
      </div>';
   ?> -->
</body>
