// 16.07.2020
// denis.kudriakov@gmail.com
//


function normal(txt){
  var cnt=0;
  var outtxt='';
  while (cnt<txt.length) {
    if (txt[cnt]==',') outtxt+='.';
    else outtxt+=txt[cnt]; 
    cnt++;
  }
  return outtxt;
}


const filename="bolt.csv";
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
txt = fs.readFileSync(filename, "utf8");
const results = [];
var max=0;
/////////////////// translante CSV 2 obj
console.log ("читаем CSV :"+filename);
fs.createReadStream(filename)
  .pipe(csv())
  .on('data', (data) => results.push(data))
  .on('end', () => {
  max=0;
//  cell=results[max];
  while (cell['Водій']!=''){
//    if (cell['Загальний тариф']!='0,00') console.log(max+"-"+cell['Водій']);
    cell=results[max];
    max++;
  }
  console.log(max+"   ------------------------");
  max--;
  var cntadd=0;
  for (var i=1; i<max;i++){
    cell=results[i];
    gotivka=normal(cell['Поїздки за готівку (зібрана готівка)']);
    itogo=normal(cell['Тижневий баланс']) - normal(gotivka);
    id1=cell['Водій'];
    prof60=itogo*0.6;
    prof40=itogo*0.4;
    balans=(prof60*1+1*gotivka);
    id2=cell['Телефон водія'];
    id3='';
    if (itogo) {
//      console.log (id1+" "+id2+" "+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
      cntadd++;
      sqlq="INSERT INTO bolt (namebolt, telbolt, itogo, pro40, pro60, gotivka,balans) VALUES('" +id1+"', " + "'"+id2+"',"  + itogo + ", " + prof40 + ", "+ prof60 + ", "+ gotivka + ", " + balans + ");"
      console.log(sqlq);
      connection.execute(sqlq, function(err, sqlresults, fields) {
      if (err) console.log(err);
        console.log(sqlresults); // data
      });
    }
  }

    connection.end();
    console.log("----------------------------------------------");
    console.log("Всего добавлено "+cntadd+" из "+max+"строк");
//console.log(results[2]);
  });


