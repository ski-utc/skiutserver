<!DOCTYPE html>
<html>
	<head>
		<title>Tombola Ski'UTC</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="../css/tombola.css"/>
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
	</head>

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="../js/customjs.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function calculate(){
				total = parseInt(document.poulet.qt1.value) + (9 * parseInt(document.poulet.qt2.value));
				document.poulet.sum.value = total +' euros'; }

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
			// 	throw new Error("Status (200, 201, 204...) is required for callback");
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
	<body>

		<nav class="navbar" id="mainNav">
			<div class="container">
					<a class="navbar-brand" href="#page-top"><img src="../images/logo.png" class="img-fluid" alt="" height="42" width="42"><b>Ski'UTC 2019</b> | Tombola</a>
				<div class="navbar-right">
					<div class="container minicart"></div>
				</div>
			</div>
		</nav>

		<div class="container-fluid breadcrumbBox text-center">
			<ol class="breadcrumb">
				<li><a href="../../index.php">Retour sur le site</a></li>
				<li class="active"><a >Panier</a></li>
				<li><a>Paiement !</a></li>
			</ol>
		</div>

		<div class="container text-center">

			<div class="col-md-12 col-sm-12 tombola-text">
				<div class="bigcart"></div>
				<h1>Tombola Ski'UTC!</h1>
				<p>
					Bienvenue sur la tombola de Ski'UTC ! Viens acheter quelques tickets et tu auras peut-être la chance, lors du tirage au sort, de remporter un pack Ski'UTC, ou un week-end pour deux à la montagne ou encore une paire de ski (Ou snow, au choix !) ainsi que de nombreux lots délirants !
				</p>
			</div>

			<?php    	session_start();
		if(!isset($_SESSION['login-etu']) && !isset($_SESSION['login_tremplin'])) {
?>

<div class="connexion">
	Merci de vous connecter

	<div class="connect">
		<form action = "/skiutc/2017/inscription_tremplin.php" method = "post">
			<input type="submit" value="Tremplin ? Clique ici !" class="btn btn-info">
		</form>
	</div>

	<div class="connect">
		<form action = "/skiutc/controllers/login-etu-tombola.php" method = "post">
			<input type="submit" value="Etudiant ? Clique ici !" class="btn btn-info">
		</form>
	</div>
</div>


			<?php } else { ?>
		<form name = "poulet">
			<div class="col-md-12 col-sm-12 text-left">
				<ul>
					<li class="row list-inline columnCaptions">
						<span><b>Quantité</b></span>
						<span><b>Article</b></span>
						<span>Prix</span>
					</li>
					<li class="row">
						<span class="quantity"> <input type = "number" style = " width: 50px" id = "qt1" name="quantity13712" onchange = "calculate()" value = 0 min = 0 onchange = "calculate()"> </span>
						<span class="itemName">&nbsp; &nbsp; &nbsp;Tickets à l'unite !</span>
						<span class="price">1 €</span>
					</li>
					<li class="row">
						<span class="quantity"> <input type = "number" style = " width: 50px" id = "qt2" name="quantity13713" onchange = "calculate()"  value = 0 min = 0 onchange = "calculate()"> </span>
						<span class="itemName"> &nbsp; &nbsp; &nbsp;Pack de 10 tickets !</span>
						<span class="price">9 €</span>
					</li>
					<li class="row totals">
						<span class="itemName"><b>Total : </b></span>
						<span class="price"><input name="sum" readonly style="border:none" id = "sum" value="0"></span>
						<span class="order">   <input type="button" id="sbmt" value="Acheter !" class="btn btn-info" onclick="launch()"></span>
					</li>
				</ul>
			</div>

		</div>
</form>

<?php
		if(isset($_SESSION['login-etu'])) $mail = $_SESSION['login-etu'] . "@etu.utc.fr"; else $mail = $_SESSION['login_tremplin']; } ?>

		<!-- The popover content -->

		<div id="popover" style="display: none">
			<a href="#"><span class="glyphicon glyphicon-pencil"></span></a>
			<a href="#"><span class="glyphicon glyphicon-remove"></span></a>
		</div>


<script>

function launch () {
	//4546  4542 = 1
	var quantity13712= document.forms.namedItem("poulet").quantity13712;
	var quantity13713= document.forms.namedItem("poulet").quantity13713;
	var log = '<?php echo $mail; ?>';
	console.log("login",log);
	if (quantity13712.value  > 0 && quantity13713.value >= 0 || quantity13712.value  >= 0 && quantity13713.value > 0)
	AJAXCall({
		url : "http://vps604065.ovh.net:8080/tombola/" + 13712 + "/" + quantity13712.value + "/" + 13713 + "/" + quantity13713.value + "/" + log + "?rand=" + Math.floor(Math.random()*1000),
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
/*
var submit = document.getElementById("sbmt");
submit.addEventListener("click", function(event){
	event.preventDefault();
	launch();
}); */
</script>

	</body>
</html>
