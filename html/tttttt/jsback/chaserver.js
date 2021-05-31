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

var outcar = new Object;
outcar = { id: 0, name: 'number'};
var outcarlist = new Array;
tmp=[];
tmpid=[];
sqlq="select id, txt2 from leliki;"
connection.execute(sqlq, function(err, sqlresults, fields) {
  if (err) console.log(err);
  cl(' len='+sqlresults.length);
  for (var i=0; i<sqlresults.length;i++) {
    tmp[i]=sqlresults[i].txt2;// .substr(0,4);
    tmpid[i]=sqlresults[i].id;
  }
});

// Навешиваем обработчик на подключение нового клиента
io.sockets.on('connection', function (socket) {
  var handshake = socket.handshake;
  var ID =handshake.address.toString().substr(7,19)+':['+(socket.id).toString().substr(0, 20)+']';

//cl("---");
  var time = (new Date).toLocaleTimeString();
  cl('< new connection ID='+ID)
//  cl('= '+ID+' ip:'+handshake.address);

  // Посылаем клиенту сообщение о том, что он успешно подключился и его id такойто
  socket.json.send({'event': 'connected', 'name': ID, 'time': time});
  // Посылаем всем остальным пользователям, что подключился новый клиент и его id такойто
  socket.broadcast.json.send({'event': 'userJoined', 'name': ID, 'time': time});
  // Навешиваем обработчик на входящее сообщение
  socket.on('messageSend', function (msg) {
    var time = (new Date).toLocaleTimeString();
    // Уведомляем клиента, что его сообщение успешно дошло до сервера и что появилось сообщение новое
    socket.json.send({'event': 'messageSent', 'name': ID, 'text': msg, 'time': time});
    sqlq="INSERT INTO msg(msg, uto, ufrom) VALUES('"+msg.message+"' ,"+msg.user+" ,"+msg.forUsers+");";

    cl('msg='+msg.message+", from="+msg.user+",to="+msg.forUsers);
    cl('sql='+sqlq);
//    sqlq="INSERT INTO msg (msg,uto,ufrom) VALUES('testik222333',1,0);";
//    cl('sql='+sqld);
    connection.execute(sqlq, function(err, sqlresults) {
      if (err) console.log(err);
      if (sqlresults) cl (": sql results:",sqlresults);
      // Посылаем всем остальным пользователям, что появилось новое сообщение.
      socket.broadcast.emit('CheckNewMessageForUser', msg.forUsers);
      cl (">> broadcast msg:CheckNewMessageForUser,"+msg.forUsers);
      // посылаем отправителю.
      socket.emit('CheckNewMessageForUser', msg.forUsers);
      cl (">> send for socid:"+ID+" msg:CheckNewMessageForUser,"+msg.forUsers);
    });
   //socket.broadcast.json.send({'event': 'messageReceived', 'name': ID, 'text': msg, 'time': time})
  }); //end messageSend
// запрос 

  socket.on('carData',function(data) {
    cl("<< carData time="+data.time+'sec listID:'+data.carar);    
  });
  //////
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
  socket.on('carList',function(data) {
    cl('< recive carlist'); //TODO PEREDELAT`!!!!
    for (i=0; i<tmp.length; i++) {
      outcarlist[i]={ id: 0, name: 'number'};
      outcarlist[i].name=tmp[i]+'';
      outcarlist[i].id=tmpid[i];
    }//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    socket.emit("carList",outcarlist);
//    cl('> sended data :'+ outcarlist);
  });
  // При отключении клиента - уведомляем остальных
  socket.on('disconnect', function() {
    var time = (new Date).toLocaleTimeString();
    io.sockets.json.send({'event': 'userSplit', 'name': ID, 'time': time});
    console.log(time,'user',ID, 'disconnection');
  });
}); //end io .connection