// 16.07.2020
// denis.kudriakov@gmail.com
//

const fileame="test.csv";
const csv = require("csv-parser");
const fs = require("fs");  
const mysql = require("mysql2");
const connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  charset: "utf8",
  database: "zmey",
  password: "zeratul@)))"
});
var id1=0;
var id2=0;
var id3=0;
var itogo=0;
var poezdok=0;
var prof60=0;
var prof40=0;
var gotivka=0;
var balans=0;
var sqlq="";
var cell = [];
txt = fs.readFileSync(fileame, "utf8");
const results = [];
/////////////////// translante CSV 2 obj
console.log ("читаем CSV :"+filename);
fs.createReadStream('test.csv');
  .pipe(csv());
  .on('data', (data) => results.push(data));
  .on('end', () => {
    for (var i=0;i<results.length;i++){
      cell=results[i];
      itogo=cell['Итого'];
      gotivka=cell['Готівку отримано'];
      id1=cell['Идентификатор водителя'];
      id2=cell['Имя'];
      id3=cell['Фамилия'];
      poezdok=cell['Поездки'];
      prof60=itogo*0.6;
      prof40=itogo*0.4;
      balans=(prof60*1+1*gotivka);
//      console.log (id1+" "+id2+" "+id3+" p:"+poezdok+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
      sqlq="INSERT INTO uber (iduber, nameuber, famuber, poezdok, itogo, pro40, pro60, gotivka,balans) VALUES('" +id1+"', " + "'"+id2+"'," + "'"+id3+"', "+poezdok+", " + itogo + ", " + prof40 + ", "+ prof60 + ", "+ gotivka + ", " + balans + ");"
//      console.log(sqlq);
        console.log("Добавляется запись -"+id1+" "+id2+" "+id3+" p:"+poezdok+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
      connection.execute(sqlq, function(err, sqlresults, fields) {
        if (err) console.log(err);
//        console.log(sqlresults); // data
      });
    }
    connection.end();
    console.log("----------------------------------------------");
    console.log("Всего добавлено "+results.length+"строк");
  });


