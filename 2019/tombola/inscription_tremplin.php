<?php 
	session_start();
		
	if(!isset($_SESSION['signup-status']))
		$_SESSION['signup-status'] = -1;
		
	$status = $_SESSION['signup-status'];
        include_once('db.php');

    if(isset($_POST['mail']) && isset($_POST['password']))
    {       
        $mail = $_POST['mail'];
        $tremp='@tremplin-utc.net';
        if (strstr($mail, $tremp))
        {
            $password = $_POST['password'];
			$passwordc = md5($_POST['password']);
			echo $passwordc;
            $user = $db->prepare('
                SELECT *
                FROM users_tremplin_2017
                WHERE mailtremplin = :mail
                ');

            $user->bindParam(':mail',$mail,PDO::PARAM_STR);
            $user->execute();
            $user = $user->fetch();

            $tremplinId = $user['login_tremplin'];
                
            if (strlen($password) > 5)
            {
                if(empty($user))
                {
                    $user = $db->prepare('
                        INSERT INTO users_tremplin_2017(mailtremplin,password,position)
                        VALUES(:mail,:passwordc,"-1")
                        ');
                    $user->bindParam(':mail',$mail,PDO::PARAM_STR);
                    $user->bindParam(':passwordc',$passwordc,PDO::PARAM_STR);
                    $user->execute();
                    
                    $_SESSION['login_tremplin'] = $mail;

                    if(isset($_POST['invite'])){
                    $stmt = $db->prepare("UPDATE users_tremplin_2017 SET ext=1 WHERE mailtremplin like :mail");
                    $stmt->bindParam(':mail', $mail);
                    $stmt->execute();              
                    }

                    // Préparation du mail contenant le lien d'activation
                    $destinataire = $mail;
                    $sujet = "Création de votre compte" ;
                    
                    //Headers pour faire passer du html en utf-8 + spécifier envoyeur
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                    $headers .= 'From: SkiUTC <skiutc@assos.utc.fr>' . "\r\n";
         
                    // Le lien d'activation est composé du login(log) et de la clé(cle)
                    $message = 'Bienvenue à SkiUTC,<br /><br />

                    Vous vous etes inscrit sur le site de Ski UTC avec les informations ci dessous : <br />
                    login : '.$_POST['mail'].'<br />
                    MDP   : '.$_POST['password'].'<br />
          
                    ---------------<br />
                    Ceci est un mail automatique, Merci de ne pas y répondre.';
         
         
                    mail($destinataire, $sujet, $message, $headers) ; // Envoi du mail

                    $_SESSION['signup-status'] = 0; // validating
                    
                    
                }
                else
                {
                    $_SESSION['signup-status'] = 1; // Error : account already exists
                }
            }
            else
            {
                $_SESSION['signup-status'] = 5; // Error : minimum 6 caractere
            }
        }
        else
        {
            $_SESSION['signup-status'] = 2; // Error : email is invalid
        }
        header("Refresh:0");    
    }
    else 
    {
        $_SESSION['signup-status'] = 3; // Error : some fields are missing
    }

?>
<!doctype html>
<html>
<!--<?php include_once("head.html"); ?>!-->
<head>
<meta charset="utf-8">
<title>Ski'UTC 2017 - Inscription Tremplin</title>
<link rel="icon" href="img/logo2.png" />
<link rel="stylesheet" href="css/form.css">
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
<div id="inscription" >
<div class="overlay">   
	<div class="container">
        <div class="row">
        <?php 
			if($status == 0)
			{ ?>
            <h3>Inscription done</h3>
            <p>
            	Vous allez être redirigé vers la tombola !
            </p>
            <?php 			header ("refresh:2;url = /skiutc/tombola.php"); }
			else 
			{ 
				switch($status)
				{
					case 1 : ?> <p><em>Le compte associé à cette adresse mail existe déjà.</em></p><br>  <?php ; 
						break;
					case 2 : ?> <p><em>L'adresse mail renseignée n'est pas valide. Veuillez entrer une adresse tremplin.</em></p><br> <?php ;
						break;
					case 3 : ?> <!--<p><em>Veuillez remplir tous les champs.</em></p><br>!-->  <?php ;
						break;
					case 4 : ?> <p><em>Connexion impossible. Votre compte n'est pas encore actif. Veuillez vérifier votre boîte mail et vos spams.</em></p> <?php ;
						break;
					case 5 : ?><p><em>Votre mot de passe doit contenir entre 6 et 12 caractères.</em></p><?php
						break;
				}
		?>
    	
    		<form class="col-sm-6 col-xs-12" method="post" action="inscription_tremplin.php">
                <div class="titre">
				<h2>Créer un compte</h2>
				</div>
                <div class="control-group">
                    <label for="mail">Email (Tremplin utc obligatoire !) : </label>
                    <div class="controls">
                        <input size="30" type="email" name="mail" id="mail" required>
                    </div>
                </div>
                <div class="control-group">
                    <label for="password">Mot de passe (entre 6 et 15 caractères): </label>
                    <div class="controls">
                        <input size="30" pattern=".{6,}" maxlength="15" type="password" name="password" required>
                    </div>
                </div><br>
                <div class="control-group">
                    <label for="checkbox">Comptez-vous venir avec un exterieur si vous reussissez à shotgun ? (Modifiable par la suite) </label>
                    <div class="controls">
                        <input type="checkbox" name="invite">
                    </div>
                </div><br>
                <div class="control-group">
                    <div class="controls">
                        <input type="submit" value="Inscription">
                    </div>
                </div>
        	</form> 
		<form class="col-sm-6 col-xs-12"  action = "../2018/login_tremplin.php" method = "post">
		       <input type="submit" value="Déjà un compte ?">
		</form>
        <?php } ?>     
        </div>
		</div>
    </div>
    </div>
	</div> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
</body>
</html>