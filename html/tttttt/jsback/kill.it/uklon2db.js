// 16.07.2020
// denis.kudriakov@gmail.com
//
//time -ok
"use strict";
exports.__esModule = true;
var path = require("path");
var xlsx_1 = require("xlsx");
const csvfilename="tmp.csv";
const csv = require("csv-parser");
const fs = require("fs");  
const mysql = require("mysql2");

if (!process.argv[2]) {
  console.log("No file! Usge uklon2db FILENAME.XLSX"); 
  return(0);
}
var xlsfilename=process.argv[2];
var start=process.argv[3];
var end=process.argv[4];

//var xlsfilename = 'test.xlsx';
console.log(xlsfilename);
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
const results = [];
var max=0;
//for (var i=0;i<)

var workbook = xlsx_1.readFile(path.join('', xlsfilename));

for (var _i = 0, _a = workbook.SheetNames; _i < _a.length; _i++) {
  var sheetName = _a[_i];
  var sheet = workbook.Sheets[sheetName];
  var csvout = xlsx_1.utils.sheet_to_csv(sheet).replace(/[,]{2,}/gm, "");
  fs.writeFile(path.join(process.cwd(), csvfilename), csvout, undefined, function () { 
    /////////////////// translante CSV 2 obj
    var txt = fs.readFileSync(csvfilename, "utf8");
    console.log ("читаем CSV :"+csvfilename);
    fs.createReadStream(csvfilename)
    .pipe(csv())
    .on('data', (data) => results.push(data))
    .on('end', () => {
      max=results.length;
      max--;
      var cntadd=0;
      for (var i=0; i<max;i++){
        cell=results[i];
        gotivka=cell['Наличные '];
        itogo=cell['Заработок'];
        itogo*=.99;
        id1=cell['Позывной'];
        id2=cell['Гос. номер'];
        prof60=itogo*0.6;
        prof40=itogo*0.4;
        balans=(prof60*1-1*gotivka);
        poezdok=cell['К-во поездок'];
        if (itogo) {
//        console.log ("["+i+"]"+id1+" "+id2+" "+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
          cntadd++;
          sqlq="INSERT INTO uklon (poezdok, pozivnoy, gosnomer, itogo, pro40, pro60, gotivka,balans,start,end) VALUES('"+poezdok+"','" +id1+"', " + "'"+id2+"',"  + itogo + ", " + prof40.toFixed(2) + ", "+ prof60.toFixed(2) + ", "+ gotivka + ", " + balans.toFixed(2)+ ", '" +start+ "', '" +end + "');"
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
    });
  });
}


