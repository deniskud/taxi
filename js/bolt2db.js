// 16.07.2020
// denis.kudriakov@gmail.com
//
//time-ok

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


if (!process.argv[2])  {console.log("No file! Usge bolt2db FILENAME.CSV"); return(0);}

const filename=process.argv[2];
const csv = require("csv-parser");
const fs = require("fs");  
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
var start;
var stop;

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
//console.log("rezult size: "+results.length);

  while (cell['Водій']!=''){
//    if (cell['Загальний тариф']!='0,00') console.log(max+"-"+cell['Водій']);
    cell=results[max];
    max++;
  }
  console.log(max+"   ------------------------");
  max--;
  var cntadd=0;
  var tmp='';
//////
  function minepoezd(id){
    var counter=0;
    for(var i=max; i<results.length; i++){ if (results[i]['Телефон водія']==id) counter++;}
//  console.log ("id="+id+" -"+counter+"raz");
    return counter;
  };
////////////////////////////////////////////////
  for (var i=1; i<max;i++){
    tmp=cell['Період'];
    start='';
    stop='';
//    for (var j=8;j<18;j++) start+=tmp[j];
//    for (var j=21;j<31;j++) stop+=tmp[j];

//console.log ("'"+start+"'-------------------------------------:"+stop+":");
    cell=results[i];
    gotivka=normal(cell['Поїздки за готівку (зібрана готівка)']);
    itogo=normal(cell['Тижневий баланс']) - normal(gotivka);
    id1=cell['Водій'];
    prof60=itogo*0.6;
    prof40=itogo*0.4;
    balans=(prof60*1+1*gotivka);
    id2=cell['Телефон водія'];
    id3='';
    poezdok=minepoezd(id2);
    if (itogo) {
//      console.log (id1+" "+id2+" "+" i:"+itogo+" 40%:"+prof40.toFixed(2)+" 60%:"+prof60.toFixed(2)+" g:"+gotivka+" b:"+ balans.toFixed(2));
      cntadd++;
      sqlq="INSERT INTO bolt (poezdok, namebolt, telbolt, itogo, pro40, pro60, gotivka, balans) VALUES('"+poezdok+"', '" +id1+"', '" +id2+"',"  + itogo + ", " + prof40.toFixed(2) + ", "+ prof60.toFixed(2) + ", "+ gotivka + ", " + balans.toFixed(2) + ");"
//      console.log(sqlq);
      connection.execute(sqlq, function(err, sqlresults, fields) {
      if (err) console.log(err);
        console.log(sqlresults); // data
      });
    }
  }
//////////////////////////////////////////////
  connection.end();
  console.log("----------------------------------------------");
  console.log("Всего добавлено "+cntadd+" из "+max+"строк");
//  console.log(results[2]);
});


