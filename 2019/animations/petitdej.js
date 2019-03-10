var payutc = require('payutc');
var express = require('express');
var db = require('mysql');
var cors = require('cors');
var app= express();
var bodyParser = require('body-parser');
var PixlMail = require('pixl-mail');
var request = require('request');


payutc.config.setAppKey("a25ec5855d88dedbf5b03eec51ff5dbe");


app.use(cors());
app.options('*', cors());


app.get("/petitdej/:baguetteQtty/:croissantQtty/:chocoQtty/:login", function(req, res, err){
	var result;

  var baguette_id = 14037;
	var baguette_qtty = req.params.baguetteQtty;
	var croissant_id = 14036;
	var croissant_qtty = req.params.croissantQtty;
  var choco_id = 14035;
	var choco_qtty = req.params.chocoQtty;

	var mail = req.params.login;
	var retour = " ";
	if(mail.indexOf("@tremplin-utc.net") != -1)
		retour = "http://assos.utc.fr/skiutc/";
	else {
		retour = "https://payutc.nemopay.net/";
	}
	console.log("mail", mail);
	console.log("return url ",retour);

	if(baguette_qtty > 0 && baguette_qtty < 35 && croissant_qtty >= 0 && croissant_qtty < 35 && choco_qtty >= 0 && choco_qtty < 35||
		baguette_qtty >= 0  && baguette_qtty < 35 && croissant_qtty > 0 && croissant_qtty < 35 && choco_qtty >= 0 && choco_qtty < 35||
		baguette_qtty >= 0  && baguette_qtty < 35 && croissant_qtty >= 0 && croissant_qtty < 35 && choco_qtty > 0 && choco_qtty < 35)
	payutc.websale.createTransaction({
		itemsArray: [[baguette_id, baguette_qtty],[croissant_id, croissant_qtty],[choco_id, choco_qtty]],
		funId: 6,
		mail: mail,
		returnUrl : retour,
		callbackUrl : "http://51.75.200.68:8080/petitdej/retour/transaction",
		callback: function(data){
			data = JSON.parse(data);
			if("error" in  data){ /*ERROR */
				console.log(data.error);

			}

			result = JSON.stringify(data.url);
			console.log(result);
			res.setHeader("Content-Type", "application/json");
			res.json(result);
		}
	});



});


app.get("/petitdej/retour/transaction",function(req,res,err){
	console.log("Request on payment return");
	var trad = req.query;
	var quantite = 0;
	var prix = 0;
	var animation = " ";
	var articleid = 0;
	var trans = trad['tra_id'];
	console.log("trans id");
	console.log(parseInt(trans));
	// { tra_id }
	payutc.websale.getTransactionInfo({funId:6,traId:parseInt(trans), callback : function(data){
					data = JSON.parse(data);
					console.log( data);
					console.log("status");
					console.log(data.status);
					var connection = db.createConnection({
					  host     : 'localhost',
					  user     : 'root',
					  password : 'skiutc2019',
					  database : 'skiut'
					});
					connection.connect(function(err) {
						  if (err) {
						    console.error('error connecting: ' + err.stack);
						    return;

						 }
             			quantiteBaguette =0
						 quantiteCroissant=0
             			quantiteChoco=0
						 price = 0
						 for(var pur in data.purchases){
								prix = data.purchases[pur].pur_price;
								prix = prix/100; //Price in cents
								price+=prix;
								console.log("Apprends a ecrire purchases connard: ", data.purchases[pur]);
								if(data.purchases[pur].obj_id==14037){
									quantiteBaguette += data.purchases[pur].pur_qte
								}
								else if(data.purchases[pur].obj_id==14036){
									quantiteCroissant += data.purchases[pur].pur_qte
								}
                else {
                  quantiteChoco += data.purchases[pur].pur_qte
                }
								console.log("Baguettes: "+quantiteBaguette+" | Croissants: "+quantiteCroissant+" | Choco: "+quantiteChoco);
						}


						/*  connection.query("insert into tombola (mail,trans) values('" + data.email + "'," + trans + ")", function(err, rows) {
							  // connected! (unless `err` is set)
							  	connexion.release();
							}); */
						if(data.status == 'V'){
						connection.query("INSERT INTO `petitdej`(`login`, `trans`) values('" + data.email + "'," + trans + " )", function(err, rows) {
					 			  //connected! (unless `err` is set)
									console.log(err);
						});
						console.log('Hey, we did  query nigga  !');
						var login = data.email;
						console.log("email ",data.email);
							//var login = login.substring(0,8);
							console.log(login);
							var mail = new PixlMail( 'smtps.utc.fr', 465 );
							console.log("hi");
							var message =
									"To: " + data.email + "\n" +
									"From: skiutc@assos.utc.fr\n" +
									"Subject: Achat Petit Dej Ski\'UTC !\n" +
									"\n Coucou  ! \n" +
									"\n Félicitations pour ton achat !  \n" +
									"\n Tu as donc acheté "+ quantiteBaguette + "baguettes, " + quantiteCroissant + "croissants et" + quantiteChoco + "pains au chocolat pour chaque matin ! \n" +
									"En cas de problème, voici ton numéro de transaction : " + trans +"  ! Garde le pour nous contacter si tu as eu un problème (skiutc-info@assos.utc.fr) !\n"+
									"\n" +
									"L'équipe de Ski'UTC impatiente de te voir !.\n";
									console.log(message);
								  mail.setOption( 'secure', true ); // use ssl
								  console.log("here 1");
								mail.setOption( 'auth', { user: 'elejeail', pass: 'qwxVBF77' } );
								console.log("here");
								mail.send( message, function(err) {
									console.log("here2");
									if (err) console.log( "Mail Error: " + err );
								});
								console.log("here 3");
							connection.end();
						var login = data.email.split("@");
						login=login[0];
						console.log("num de trans: "+trans+" prix: "+price+" login: "+login)
						request("https://assos.utc.fr/skiutc/2019/animations/api_validatepetitdej.php?login="+login+"&trans="+trans+"&baguette="+quantiteBaguette+"&croissant="+quantiteCroissant+"&choco="+quantiteChoco, function(error, response, body) {
							 /*body = JSON.parse(body);
							  console.log(body.good);*/
							  console.log("api error :",error);
						});
						}
					});

				 }


	});
	res.sendStatus(200);


});
app.listen(8080);
console.log("server up on port 8080");
