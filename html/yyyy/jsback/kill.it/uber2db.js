// 16.07.2020
// denis.kudriakov@gmail.com
//

if (!process.argv[2])  {console.log("No file! Usge uber2db FILENAME.CSV START END"); return(0);}
const filename=process.argv[2];
var start=process.argv[3];
var end=process.argv[4];

console.log("filename:"+filename);
//filename='';
var dstart=process.argv[3];
var dend=process.argv[4];

//const filename="test.csv";
const csv = require("csv-parser");
//const fs = require("fs");  
const mysql = require("mysql2");
const connection = mysql.createConnection({
  host: "localhost",
  user: "zmey",
  charset: "utf8",
  database: "taxi",
  password: "kalina"
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

var fs=require('fs')
var buffer=fs.readFileSync(filename)
var filename2=filename;
fs.writeFileSync(filename,buffer.slice(3,buffer.length) )
//cutting without trash in start
const results = [];
/////////////////// translante CSV 2 obj
  console.log ("читаем CSV :"+filename2);
  fs.createReadStream(filename2)
  .pipe(csv())
  .on('data', (data) => {
    results.push(data);
   })
  .on('end', () => {
    for (var i=0;i<results.length;i++){
      cell=results[i];
      itogo=cell['Итого'];
      gotivka=cell['Готівку отримано']; //"Стягнення"
      if (!gotivka) gotivka=cell['Стягнення'];        //
      if (!gotivka) gotivka=cell['Готівкові розрахунки'];//
//      gotivka=cell['Готівкові розрахунки'];//
      if (!gotivka) gotivka='0';
      itogo-=gotivka;
      id1=cell['Идентификатор водителя'];
      id2=cell['Имя'];
      id3=cell['Фамилия'];
      poezdok=cell['Поездки'];
      prof60=itogo*0.6;
      prof40=itogo*0.4;
      balans=(prof60*1+1*gotivka);
//      console.log (id1+" "+id2+" "+id3+" p:"+poezdok+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
 sqlq="INSERT INTO uber (iduber, nameuber, famuber, poezdok, itogo, pro40, pro60, gotivka,balans,start,end) VALUES('" +id1+"', " + "'"+id2+"'," + "'"+id3+"', "+poezdok+", " + itogo + ", " + prof40 + ", "+ prof60 + ", "+ gotivka + ", " + balans + ", '"+ start + "', '"+end  +"');"
//      console.log(sqlq);
      
      if (itogo){
        console.log("Добавляется запись -"+id1+" "+id2+" "+id3+" p:"+
          poezdok+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+
          " g:"+gotivka+" b:"+ balans.toFixed(2));
        

        connection.execute(sqlq, function(err, sqlresults, fields) {
          if (err) console.log(err);
//        console.log(sqlresults); // data
        });


      }
    }
    connection.end();
    console.log("----------------------------------------------");
    console.log("Всего добавлено "+results.length+"строк");
  });


