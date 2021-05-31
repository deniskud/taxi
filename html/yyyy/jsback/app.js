var wialon = require( 'wialon' );
var moment = require( 'moment/moment' );
const mysql = require("mysql2");
/////////////////////
var fs = require('fs');
var debug=1;
function consolelog(msg){
  if (debug) {
    ts = new Date();
    var textlog=(ts.toString().substr(4, 24)+'. ' + msg); 
    console.log(textlog);
    if (debug>2) textlog=textlog+' <br>';
    if (debug>1) fs.appendFileSync("./server.log", textlog+'\n');
  }
}
////////////////////

var timeres = 60000; //время обновления координат


const connection = mysql.createConnection({
    host: "localhost",
    user: "zlodey",
    database: "taxo",
    password: "zeratul"
});

//var cars={}


connection.connect(function(err){
  if (err) return console.error("Ошибка: " + err.message);
  else consolelog("Подключение к серверу MySQL успешно установлено");
});


var opts = { // authz params
      authz : {token : '6df4368868b7c804c5f08acf199dff64A65AFB56CE9CCA14590B07B1706ACFF8D1DB90F7',
      operateAs : 'PLC1' }
};

var session = wialon( opts ).session;

// grab a new search instance
var search = wialon( opts ).search();
//var cars=[];

// search for units
setInterval(()=> {
  search.search('avl_unit', '*', '1025', '1')
  .then(function (data) {
    consolelog('recive '+data.length+' obects from wialon');
    data.forEach((user,idx) => {
      const {id, pos} = user;
      const {t: time, x: pos_x, y: pos_y, z: pos_z, s: pos_s} = pos;
      consolelog("time="+ time+" x="+ pos_x+" y="+ pos_y+" z="+ pos_z+" s="+ pos_s+" id="+ id);
      connection.query('SELECT * FROM cars WHERE  wi_id = ?',id , function(err, results) {
        var newdate =  moment.utc(new Date(time*1000), 'YYYY-MM-DD hh:mm:ss').format('YYYY-MM-DD hh:mm:ss');
        if(results.id){
          consolelog('data='+results.id); // собственно данные
          const getpoit = { dt: newdate, cid: results.id, x: pos_x, y: pos_y, z: pos_z, s: pos_s };
          consolelog('set to DB: '+getpoit);
          connection.query("INSERT INTO vialonrp SET ?", getpoit,  function(err, results) {
            if(err) throw err;
            consolelog('Last insert ID:', results.insertId);
          });
        }
//        else consolelog('not found in base id='+id);
                     //if (idx===data.length-1) consolelog('end');
      });
    });
  })
  .catch(function (err) { consolelog(err) });
},timeres);
