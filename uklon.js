// 16.07.2020
// denis.kudriakov@gmail.com
//

function normal(txt){
  if (!txt) return 0;
  var cnt=0;
  var outtxt='';
console.log(cnt+" txt:"+txt+" :")//+txt.length+"-------------------------------------------------");
//  txt='';
  while (cnt < txt.length) {
    if (txt[cnt]==',') outtxt+='.';
    else outtxt+=txt[cnt]; 
    cnt++;
  }
  return outtxt;
}


const filename="test.csv";
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
  max=results.length;
//  console.log(results);
//  console.log(max+"   ------------------------");
  max--;
  var cntadd=0;
  for (var i=0; i<max;i++){
    cell=results[i];
    gotivka=cell['Наличные '];
    itogo=cell['Заработок'];
    id1=cell['Позывной'];
    id2=cell['Гос. номер'];
    prof60=itogo*0.6;
    prof40=itogo*0.4;
    balans=(prof60*1-1*gotivka);
    poezdok=cell['К-во поездок'];
//    if (itogo) {
//      console.log ("["+i+"]"+id1+" "+id2+" "+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
      cntadd++;
      sqlq="INSERT INTO bolt (pozivnoy, gosnomer, itogo, pro40, pro60, gotivka,balans) VALUES('" +id1+"', " + "'"+id2+"',"  + itogo + ", " + prof40.toFixed(2) + ", "+ prof60.toFixed(2) + ", "+ gotivka + ", " + balans.toFixed(2) + ");"
      console.log(sqlq);
//      connection.execute(";", function(err, sqlresults, fields) {
//      if (err) console.log(err);
//        console.log(sqlresults); // data
//      });
//    }
  }

    connection.end();
    console.log("----------------------------------------------");
    console.log("Всего добавлено "+cntadd+" из "+max+"строк");
//console.log(results[2]);
  });




/*


//"use strict";
exports.__esModule = true;
var fs = require("fs");
var path = require("path");
var xlsx_1 = require("xlsx");
var usage = "usage: xls2csv filename";
var args = process.argv;
if (args.length !== 3) {
    console.error(usage);
    process.exit(1);
}
console.log("arg1="+args[1]+" arg2="+args[2]);

var filename = args[2];
var baseOutFilename = filename.replace(/\.[^/.]+$/, "");
var workbook = xlsx_1.readFile(path.join(process.cwd(), filename));
for (var _i = 0, _a = workbook.SheetNames; _i < _a.length; _i++) {
    var sheetName = _a[_i];
    var sheet = workbook.Sheets[sheetName];
    var csv = xlsx_1.utils.sheet_to_csv(sheet).replace(/[,]{2,}/gm, "");
    var outFilename = workbook.SheetNames.length === 1
        ? baseOutFilename + ".csv"
        : baseOutFilename + "_" + sheetName + ".csv";
    fs.writeFile(path.join(process.cwd(), outFilename), csv, undefined, function () { });
}



*/
