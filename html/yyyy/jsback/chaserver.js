#!/usr/bin/env node
var io = require('socket.io').listen(8080); 
var fs = require('fs');

var debug=1;
function cl(msg){
  if (debug) {
    ts = new Date();
    var textlog=(ts.toString().substr(0, 24)+'. ' + msg); 
    console.log(textlog);
    if (debug>2) textlog=textlog+' <br>';
    if (debug>1) fs.appendFileSync("./server.log", textlog+'\n');
  }
}
const mysql = require("mysql2");
const connection = mysql.createConnection({
  host: "localhost",
  user: "zlodey",
  charset: "utf8",
  database: "taxo",
  password: "zeratul"
});

/*
var outcar = new Object;
var outcardata = new Object;
outcar = { id: 0, name: 'number'};
outcardataobj = {
    id: '',
    driverName: '',
    driverPhone: '',
    speed: '',
    workTime: '',
    lastCoords: [[50.451505, 30.522443], [50.453256, 30.521894], [50.455041, 30.520526]]
}
outcardataarr =[];
outcardataarr[0]=outcardataobj;
*/





leliki=[];
sqlq="select * from leliki order by id;"
connection.execute(sqlq, function(err, sqlresults, fields) {
  if (err) console.log(err);
  else sqlresults.forEach(res => {leliki[res.id]=res;});
});




// Навешиваем обработчик на подключение нового клиента
io.sockets.on('connection', function (socket) {
  var handshake = socket.handshake;
  var ID =handshake.address.toString().substr(7,19)+':['+(socket.id).toString().substr(0, 20)+']';
  var time = (new Date).toLocaleTimeString();
  cl('< new connection ID='+ID)
  // Посылаем клиенту сообщение о том, что он успешно подключился и его id такойто
  socket.json.send({'event': 'connected', 'name': ID, 'time': time});
  // Посылаем всем остальным пользователям, что подключился новый клиент и его id такойто
  socket.broadcast.json.send({'event': 'userJoined', 'name': ID, 'time': time});
  // Навешиваем обработчик на входящее сообщение
  socket.on('messageSend', function (msg) {
    var time = (new Date).toLocaleTimeString();
    // Уведомляем клиента, что его сообщение успешно дошло до сервера и что появилось сообщение новое
    socket.json.send({'event': 'messageSent', 'name': ID, 'text': msg, 'time': time});
    sqlq="INSERT INTO msg(msg,ufrom, uto) VALUES('"+msg.message+"' ,"+msg.user+" ,"+msg.forUsers+");";
    cl('msg='+msg.message+", from="+msg.user+",to="+msg.forUsers);
    cl('sql='+sqlq);
    connection.execute(sqlq, function(err, sqlresults) {
      if (err) console.log(err);
      if (sqlresults) cl (": sql results:",sqlresults);
      // Посылаем всем остальным пользователям, что появилось новое сообщение.
      var uid = {'id':msg.forUsers};
      socket.broadcast.emit('CheckNewMessageForUser', uid);
      cl (">> broadcast msg:CheckNewMessageForUser,"+uid.id);
      // посылаем отправителю.
      socket.emit('CheckNewMessageForUser', uid);
      cl (">> send for socid:"+ID+" msg:CheckNewMessageForUser,"+uid.id);
    });
   //socket.broadcast.json.send({'event': 'messageReceived', 'name': ID, 'text': msg, 'time': time})
  }); //end messageSend

///////////////////////////////// carData


  var outarray=[];
  var coordarray=[];

  socket.on('carData',(data)=> {
//cl('--------------' +leliki[100].fam);
    data.cars.forEach(carn => {
if (leliki[carn]) cl('*good lelik'); else cl('*bad lelik')
      cl('request wialon for car id='+carn);
      sql="select x,y from vialonrp where cid="+carn+" order by dt desc limit 3;";
      connection.execute(sql, function(err, sqlresults, fields) {
        if (err) console.log(err);
        coordarray=[];
        sqlresults.forEach(sqlres => {coordarray.push([sqlres.y,sqlres.x]);})
//        cl('-> for '+carn+' coord array:'+ coordarray);
        outarray.push({
            id:carn, 
            driverName: leliki[100].fam,
            driverPhone:' leliki[carn].tel',
            speed :'todo',
            workTime :'todo', 
            lastCoords : coordarray
        });
        if (data.cars.length==outarray.length){socket.emit('carData',outarray);outarray=[];}
      });

    })
  });


//  socket.on('carDataMove',(data)=> {socket.emit('carDataMove',minecardata(data));});
//////////////////////////////////////////////////////////
/*
  function minemess(from,to,uid){
    sqlr='select * from msg where (uto=0 or uto='+uid+') order by dt desc limit '+to+';'
    console.log(sqlr);
  }

  socket.on('messagesLoad',(data)=> {
cl('==='+data.to);
    cl('<< recive req for mess fo user'+data.user);    
    minemess(data.from,data.to,data.uid);
//    socket.emit('carData',tmparr);
  });


*/
//////////////////////////////////////////////////////////
  socket.on('messagesUsers',()=> {
    cl('<< messagesUsers');
    sqlq='SELECT id, username from users;';
    connection.execute(sqlq, function(err, sqlresults, fields) {
      if (err) console.log(err);
//      socket.json.send(sqlresults);
      socket.emit('messagesUsers',sqlresults);
      cl('send users:'+sqlresults);
    });
  });
/////////////////////////////////////////////////////////
  socket.on('carList',function(data) {
    var carlist=[];
    cl('< recive carlist');
    sqlq="select id, txt2 from leliki;"
    connection.execute(sqlq, function(err, sqlresults, fields) {
      if (err) console.log(err);
      sqlresults.forEach(sqlres => {
        carlist.push({id:sqlres.id, name:sqlres.txt2})
        if (sqlresults.length==carlist.length) {socket.emit('carList',carlist);}
      })


    });
  });
  // При отключении клиента - уведомляем остальных
//////////////////////////////////////////////////////////
  socket.on('disconnect', function() {
    var time = (new Date).toLocaleTimeString();
    io.sockets.json.send({'event': 'userSplit', 'name': ID, 'time': time});
    console.log(time,'user',ID, 'disconnection');
  });
}); //end io .connection