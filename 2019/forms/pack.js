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

app.get("/pack/:login", function(req, res, err){
	var result;
	var itemsList=[]
  if (req.query.art0!=undefined){
    itemsList.push([req.query.art0,1]);
		if (req.query.art1!=undefined){
	    itemsList.push([req.query.art1,1]);
			if (req.query.art2!=undefined){
		    itemsList.push([req.query.art2,1]);
				if (req.query.art3!=undefined){
			    itemsList.push([req.query.art3,1]);
					if (req.query.art4!=undefined){
				    itemsList.push([req.query.art4,1]);
						if (req.query.art5!=undefined){
					    itemsList.push([req.query.art5,1]);
							if (req.query.art6!=undefined){
						    itemsList.push([req.query.art6,1]);
						  }
					  }
				  }
			  }
		  }
	  }
  }

	var mail = req.params.login;
	var retour = " ";
	if(mail.indexOf("@tremplin-utc.net") != -1)
		retour = "http://assos.utc.fr/skiutc/";
	else {
		retour = "https://payutc.nemopay.net/";
		}
	console.log("mail", mail)
	console.log("return url ",retour);
	console.log("List: " + itemsList + " |  mail " + mail);

	payutc.websale.createTransaction({
		itemsArray: itemsList,
		funId: 6,
		mail: mail,
		returnUrl : retour,
		callbackUrl : "http://51.75.200.68:8081/pack/retour/transaction",
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


app.get("/pack/retour/transaction",function(req,res,err){
	console.log("Request on payment return");
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
						 price = 0
						 for(var pur in data.purchases){
								prix = data.purchases[pur].pur_price;
								prix = prix/100; //Price in cents
								price+=prix
								console.log("Apprends a ecrire purchases connard: ", data.purchases[pur]);
								console.log("price: "+price);
						}

						/*  connection.query("insert into tombola (mail,trans) values('" + data.email + "'," + trans + ")", function(err, rows) {
							  // connected! (unless `err` is set)
							  	connexion.release();
							}); */
						if(data.status == 'V'){
						//connection.query("INSERT INTO `payment`(`login`, `trans`, `ticket1`, `ticket10`) values('" + data.email + "'," + trans + "," + quantiteT1 + "," + quantiteT10 + " )", function(err, rows) {
					 			 // connected! (unless `err` is set)
								//console.log(err);
						//});
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
									"Subject:Paiment Pack Skiutc !\n" +
									"\n Coucou  ! \n" +
									"\n Félicitations pour ton paiement !  \n" +
									"\n Tu peux consulter à tout moment le contenu de ta commande sur ta page récap dans ton espace sur le site Ski'UTC !\n"+
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
						request("https://assos.utc.fr/skiutc/2019/forms/api_validatepayment.php?login="+login+"&trans="+trans+"&price="+price, function(error, response, body) {
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
app.listen(8081);
console.log("server up on port 8081");
