var mysql = require('mysql');

var con = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'skiutc2019',
  database : 'skiut'
});

// con.connect(function(err) {
//   if (err) throw err;
//   console.log("Connected!");
//   var sql = "CREATE TABLE `petitdej`(`login` VARCHAR(40), `trans` INTEGER)";
//   con.query(sql, function (err, result) {
//     if (err) throw err;
//     console.log("Table created");
//   });
// });

con.connect(function(err) {
  if (err) throw err;
  con.query("SELECT * FROM anim_user", function (err, result, fields) {
    if (err) throw err;
    console.log(result);
  });
});


// con.connect(function(err) {
//   if (err) throw err;
//   con.query("SELECT * FROM petitdej", function (err, result, fields) {
//     if (err) throw err;
//     console.log(result);
//   });
// });
