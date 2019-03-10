var payutc = require('payutc');
var express = require('express');
var db = require('mysql');
var cors = require('cors');
var app= express();
var bodyParser = require('body-parser')
var PixlMail = require('pixl-mail');
var request = require('request');


payutc.config.setAppKey("a25ec5855d88dedbf5b03eec51ff5dbe");


app.use(cors());
app.options('*', cors());

app.get("/tombola/:ticket1Id/:ticket1Qtty/:ticket10Id/:ticket10Qtty/:login", function(req, res, err){
	var result;

	var article_id1 = req.params.ticket1Id;
	var article_qtty1 = req.params.ticket1Qtty;
	var article_id10 = req.params.ticket10Id;
	var article_qtty10 = req.params.ticket10Qtty;

	var mail = req.params.login;
	var retour = " ";
	if(mail.indexOf("@tremplin-utc.net") != -1)
		retour = "http://assos.utc.fr/skiutc/";
	else {
		retour = "https://payutc.nemopay.net/";
		}
	console.log("mail", mail)
	console.log("return url ",retour);
	console.log("Article ID:" + article_id1 + " |   Article Qtty: " + article_qtty1 + "||		Article ID:"+ article_id10 + " | 		Article Qtty: "+ article_qtty10 +" mail " + mail);

	if(article_qtty1 > 0 && article_qtty10 >= 0 || article_qtty1 >= 0 && article_qtty10 > 0)
	payutc.websale.createTransaction({
		itemsArray: [[article_id1, article_qtty1],[article_id10, article_qtty10]],
		funId: 6,
		mail: mail,
		returnUrl : retour,
		callbackUrl : "http://51.75.200.68:8080/tombola/retour/transaction",
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


app.get("/tombola/retour/transaction",function(req,res,err){
	console.log("Request on tombola return");
	var trad = req.query;
	var quantite = 0;
	var prix = 0;
	var trans = trad['tra_id'];
	console.log("trans id");
	console.log(parseInt(trans));
	// { tra_id }
	payutc.websale.getTransactionInfo({funId:6,traId:parseInt(trans), callback : function(data){
					data = JSON.parse(data);
					console.log( data);
					console.log("statuts");
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
						 quantiteT1 =0
						 quantiteT10=0
						 for(var pur in data.purchases){
								prix = data.purchases[pur].pur_price;
								prix = prix/100; //Price in cents
								console.log("Apprends a ecrire purchases connard: ", data.purchases[pur]);
								if(data.purchases[pur].obj_id==13713){
									quantiteT10 += data.purchases[pur].pur_qte
								}
								else{
									quantiteT1 += data.purchases[pur].pur_qte
								}
								console.log("1 ticket: "+quantiteT1+" | 10 tickets: "+quantiteT10);
						}

						/*  connection.query("insert into tombola (mail,trans) values('" + data.email + "'," + trans + ")", function(err, rows) {
							  // connected! (unless `err` is set)
							  	connexion.release();
							}); */
						if(data.status == 'V'){
						connection.query("INSERT INTO `tombola`(`login`, `trans`, `ticket1`, `ticket10`) values('" + data.email + "'," + trans + "," + quantiteT1 + "," + quantiteT10 + " )", function(err, rows) {
					 			 // connected! (unless `err` is set)
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
									"Subject:Achat tombola Skiutc !\n" +
									"\n Coucou  ! \n" +
									"\n Félicitations pour ton achat !  \n" +
									"\n Tu as donc pris " + quantiteT1 + " tickets de tombola à l'unité et " + quantiteT10 + " carnets de 10 tickets !  \n" +

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
						request("https://assos.utc.fr/skiutc/2019/tombola/api_validatetombola.php?login="+data.email+"&pack1="+quantiteT1+"&trans="+trans+"&pack2="+quantiteT10, function(error, response, body) {
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
