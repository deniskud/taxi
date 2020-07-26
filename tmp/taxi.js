// 16.07.2020
// denis.kudriakov@gmail.com
//

const csv = require("csv-parser");
const fs = require("fs");  
const mysql = require("mysql2");
const connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  database: "zmey",
  password: "zeratul@)))"
});1

txt = fs.readFileSync("test.csv", "utf8");
//console.log(txt);
var i=0;

var cell = [];

//var allcell = [];

//allcell[0]=cell;
//console.log(txt.length);

//const results = [];
//var onestr = [];
/////////////////// translante CSV 2 obj
//fs.createReadStream('test.csv')
//  .pipe(csv())
//  .on('data', (data) => results.push(data))
//  .on('end', () => {
//  onestr = csv('Тариф')
//  cell=results[1];
//console.log (cell);    

//  });
var tmpstr='';
var counter=0;

var x=0;
var y=0;
while (i<txt.length){
  if (txt[i]=='"') {
console.log("START>>>> i="+i );
    i++;
    while (txt[i]!='"'){
//console.log("i="+i+"  "+txt[i]);
      tmpstr+=txt[i];
      i++;
    }
    if (tmpstr=="") tmpstr=0;
    if (tmpstr!=",") {
console.log("[" + x + "," + y + "] " + tmpstr);
      if (!cell[x]) cell[x]=[];
      cell[x][y]=tmpstr;
//console.log(cell[x][y]);
      if (x<15) x++;
      else {
        x=0;
//        allcell[y]=cell;
        y++;
console.log(">>>>>>>>>>>>>>>>>>>end of X & Y++ >>>>>>>>>>>>>>>");
console.log(cell);
//console.log(allcell);
      }
    }
//console.log(counter++ + " " + tmpstr);
    tmpstr='';
 }
  i++;
}

//console.log(--y+" string found");
//console.log(cell);
//console.log("start add cell");
//for (;y>0;y--){
//  for (x=0;x<16;x++){ 
//    console.log (allcell[x,y]+ "[" +x + "," +y+"]");
//  }
//}
//  console.log (cell[4][i]);
//}


    console.log("----------------------------------------------");
connection.execute("SHOW DATABASES;",
  function(err, results, fields) {
    console.log("----------------------------------------------");
    console.log(err);
    console.log(results); // data
    console.log(fields); // meta-data 
});
connection.end();
