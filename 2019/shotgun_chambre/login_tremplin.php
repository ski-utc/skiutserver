<?php
	session_start();
?>
<!doctype html>
<html>
<?php include_once("db.php"); ?>
<head>
<meta charset="utf-8">
<title>Ski'UTC 2018 - Connection Tremplin</title>
<link rel="shortcut icon" href="images/logo.png">
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
<?php
	 if(isset($_SESSION['login-notuser'])){
		 echo '<script>alert("Vous ne faites pas (encore?) partie du voyage !")</script>';
		 unset($_SESSION['login-notuser']);
	 }
?>
    <div class="container">
        <div class="row">
    		<form  method="post" action="login_tremplin_act.php">
                <div class="titre">
				<h2>Se connecter</h2>
				</div>
                <div class="control-group">
                    <label for="mail">Email Tremplin : </label>
                    <div class="controls">
                        <input size="300" type="text" name="mail" id="mail" required>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input type="submit" value="Connexion">
                    </div>
                </div>
        	</form>
        </div>
    </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
</body>
</html>
