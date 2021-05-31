/**
 * Created by No Fear on 04.03.2021.
 * E-mail: g0th1c097@gmail.com
 */



// $.post("/api/path/", { "cars": getSelectedCars(), "time": $('#map-time :selected').attr('data-time') }, function (response) {
//
//     response.status;
//
// });




// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     maxZoom: 18,
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
//     "layers": {
//         "id": "building",
//         "type": "fill",
//         "source": "openmaptiles",
//         "source-layer": "building",
//         "minzoom": 4,
//         "paint": {
//             "fill-color": "#cc000e",
//             "fill-outline-color": "#08ff00",
//             "fill-opacity": 0.5
//         }
//     }
// }).addTo(mainMap);




// $(pointsForJson).each(function (i) {
//     if(i === pointsForJson.length - 1) {return;}
//     L.polyline([pointsForJson[i],pointsForJson[i+1]], {
//         weight: 5,
//         lineJoin: 'round',
//         color: '#0089ff',
//         opacity: 0.5
//     }).addTo(mainMap);
// });

// var pointsForJson = [
//     [50.449877, 30.523868],
//     [50.448877, 30.522868],
//     [50.447877, 30.520868],
//     [50.443877, 30.520868],
//     [50.440877, 30.515868]
// ];
//
// $(pointsForJson).each(function (i) {
//
//     if(i === pointsForJson.length - 1) {return;}
//
//     L.hotline([[pointsForJson[i][0],pointsForJson[i][1],1],[pointsForJson[i+1][0],pointsForJson[i+1][1],2]], {
//         min: 1,
//         max: 2,
//         palette: {
//             0.0: 'rgba(0, 137, 255, 0.5)',
//             1.0: 'rgba(0, 137, 255, 0.5)'
//         },
//         weight: 8,
//         outlineWidth: 0
//     }).addTo(mainMap);
//
// });




// 6df4368868b7c804c5f08acf199dff649D206261825B155A545C2A7190C7A71EC831C8F4




// function init() { // Execute after login succeed
//     var sess = wialon.core.Session.getInstance(); // get instance of current Session
//     console.log(wialon.item);
// flags to specify what kind of data should be returned
// var flags = wialon.item.Item.dataFlag.base | wialon.item.Unit.dataFlag.lastMessage;
//
// sess.loadLibrary("itemIcon"); // load Icon Library
// sess.updateDataFlags( // load items to current session
//     [{type: "type", data: "avl_unit", flags: flags, mode: 0}], // Items specification
//     function (code) { // updateDataFlags callback
//         if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
//
//         // get loaded 'avl_unit's items
//         var units = sess.getItems("avl_unit");
//         if (!units || !units.length){ msg("Units not found"); return; } // check if units found
//
//         for (var i = 0; i< units.length; i++){ // construct Select object using found units
//             var u = units[i]; // current unit in cycle
//             // append option to select
//             $("#units").append("<option value='"+ u.getId() +"'>"+ u.getName()+ "</option>");
//         }
//         // bind action to select change event
//         $("#units").change( getSelectedUnitInfo );
//     }
// );
// }

// function getSelectedUnitInfo(){ // print information about selected Unit
//
//     var val = $("#units").val(); // get selected unit id
//     if(!val) return; // exit if no unit selected
//
//     var unit = wialon.core.Session.getInstance().getItem(val); // get unit by id
//     if(!unit){ msg("Unit not found");return; } // exit if unit not found
//
//     // construct message with unit information
//     var text = "<div>'"+unit.getName()+"' selected. "; // get unit name
//     var icon = unit.getIconUrl(32); // get unit Icon url
//     if(icon) text = "<img class='icon' src='"+ icon +"' alt='icon'/>"+ text; // add icon to message
//     var pos = unit.getPosition(); // get unit position
//     if(pos){ // check if position data exists
//         var time = wialon.util.DateTime.formatTime(pos.t);
//         text += "<b>Last message</b> "+ time +"<br/>"+ // add last message time
//             "<b>Position</b> "+ pos.x+", "+pos.y +"<br/>"+ // add info about unit position
//             "<b>Speed</b> "+ pos.s; // add info about unit speed
//         // try to find unit location using coordinates
//         wialon.util.Gis.getLocations([{lon:pos.x, lat:pos.y}], function(code, address){
//             if (code) { msg(wialon.core.Errors.getErrorText(code)); return; } // exit if error code
//             msg(text + "<br/><b>Location of unit</b>: "+ address+"</div>"); // print message to log
//         });
//     } else // position data not exists, print message
//         msg(text + "<br/><b>Location of unit</b>: Unknown</div>");
// }

// execute when DOM ready
// $(document).ready(function () {
//     wialon.core.Session.getInstance().initSession("https://hst-api.wialon.com"); // init session
//     // For more info about how to generate token check
//     // http://sdk.wialon.com/playground/demo/app_auth_token
//     wialon.core.Session.getInstance().loginToken("6df4368868b7c804c5f08acf199dff64A65AFB56CE9CCA14590B07B1706ACFF8D1DB90F7", "", // try to login
//     function (code) { // login callback
//             // if error code - print error message
//             // if (code){ msg(wialon.core.Errors.getErrorText(code)); return; }
//             // msg("Logged successfully"); init(); // when login suceed then run init() function
//             if (code){ console.log(code); return; }
//             init();
//         });
// });




// $.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + e.latlng['lat']+ '&lon=' + e.latlng['lng'], function(data){
//     var message = data.address.country;
//     data.address.city !== undefined && data.address.city.length && (message += ', ' + data.address.city);
//     data.address.borough !== undefined && data.address.borough.length && (message += ', ' + data.address.borough);
//     data.address.road !== undefined && data.address.road.length && (message += ', ' + data.address.road);
//     data.address.house_number !== undefined && data.address.house_number.length && (message += ', ' + data.address.house_number);
//     $('.map-popup-list-address strong').text(message);
// });

// https://docs.mapbox.com/api/search/geocoding/

// https://developer.mapquest.com/documentation/open/geocoding-api/

// https://sdk.wialon.com/playground/demo/get_geofences

// https://esri.github.io/esri-leaflet/examples/reverse-geocoding.html




// var $th = $('.tableFixHead').find('thead th')
// $('.tableFixHead').on('scroll', function() {
//     $th.css('transform', 'translateY('+ this.scrollTop +'px)');
// });




// let compareChartWater = new Chart($('.compare-chart-water'), {
//     type: 'line',
//     data: {
//         labels: [],
//         datasets: []
//     },
//     options: {
//         responsive: true,
//         maintainAspectRatio: false,
//         scales: {
//             xAxes: [{
//                 ticks: {
//                     fontColor: "rgba(0,0,0,0.54)"
//                 }
//             }],
//             yAxes: [{
//                 ticks: {
//                     beginAtZero: true,
//                     fontColor: "rgba(0,0,0,0.54)"
//                 }
//             }]
//         },
//         tooltips: {
//             mode: 'index'
//         }
//     }
// });





// var socket = io(); //load socket.io-client and connect to the host that serves the page
// //io.connect('/socket.io-client');
// socket.on('connect',function(data){
//     console.log("Connected");
//     socket.emit("data","carlist");    //array of all cars
// //    socket.emit("data","caronline");  //array on-line cars
// //    socket.emit("data","ofline");     //array off-line cars
//     console.log('Connected');
// });
// var carlist=[];
//
// //  socket.on('che-nebud',(data)=>{console.log(data);});
// socket.on('carlist',(data)=>{carlist=data; console.log(carlist);});





// // start info
// var mapDataFormat = [{
//     id: '101',
//     driverName: 'Afanasii',
//     driverPhone: '+380974562135',
//     speed: '93',
//     workTime: '3:35',
//     lastCoords: [[50.451505, 30.522443], [50.453256, 30.521894], [50.455041, 30.520526], [50.455247, 30.521183], [50.455223, 30.521921], [50.454998, 30.522875]]
// }, {
//     id: '102',
//     driverName: 'Anatolii',
//     driverPhone: '+380637841698',
//     speed: '61',
//     workTime: '5:13',
//     lastCoords: [[50.493446, 30.487027], [50.492780, 30.487075], [50.492786, 30.488286], [50.492795, 30.490105], [50.492789, 30.492337], [50.491386, 30.492343]]
// }, {
//     id: '103',
//     driverName: 'Agafon',
//     driverPhone: '+380663275681',
//     speed: '104',
//     workTime: '12:59',
//     lastCoords: [[50.434355, 30.487965], [50.435147, 30.487889], [50.435367, 30.487667], [50.434065, 30.485202], [50.433395, 30.483763], [50.432780, 30.480480]]
// }];

// // each second info
// var mapDataFormatTimeout = [{
//     id: '101',
//     driverName: 'Afanasii',
//     driverPhone: '+380974562135',
//     speed: '0',
//     workTime: '3:36',
//     lastCoords: [50.450366, 30.524468]
// }, {
//     id: '103',
//     driverName: 'Agafon',
//     driverPhone: '+380663275681',
//     speed: '47',
//     workTime: '13:00',
//     lastCoords: [50.433774, 30.487382]
// }];

// // on time change get tails
// var mapDataFormatTime = [{
//     id: '101',
//     driverName: 'Afanasii',
//     driverPhone: '+380974562135',
//     speed: '93',
//     workTime: '3:35',
//     lastCoords: [[50.451505, 30.522443], [50.453256, 30.521894], [50.455041, 30.520526], [50.455247, 30.521183], [50.455223, 30.521921], [50.454998, 30.522875], [50.493446, 30.487027], [50.492780, 30.487075], [50.492786, 30.488286], [50.492795, 30.490105], [50.492789, 30.492337], [50.491386, 30.492343], [50.434355, 30.487965], [50.435147, 30.487889], [50.435367, 30.487667], [50.434065, 30.485202], [50.433395, 30.483763], [50.432780, 30.480480]]
// }, {
//     id: '102',
//     driverName: 'Anatolii',
//     driverPhone: '+380637841698',
//     speed: '61',
//     workTime: '5:13',
//     lastCoords: [[50.493446, 30.487027], [50.492780, 30.487075], [50.492786, 30.488286], [50.492795, 30.490105], [50.492789, 30.492337], [50.491386, 30.492343], [50.451505, 30.522443], [50.453256, 30.521894], [50.455041, 30.520526], [50.455247, 30.521183], [50.455223, 30.521921], [50.454998, 30.522875], [50.434355, 30.487965], [50.435147, 30.487889], [50.435367, 30.487667], [50.434065, 30.485202], [50.433395, 30.483763], [50.432780, 30.480480]]
// }, {
//     id: '103',
//     driverName: 'Agafon',
//     driverPhone: '+380663275681',
//     speed: '104',
//     workTime: '12:59',
//     lastCoords: [[50.434355, 30.487965], [50.435147, 30.487889], [50.435367, 30.487667], [50.434065, 30.485202], [50.433395, 30.483763], [50.432780, 30.480480], [50.493446, 30.487027], [50.492780, 30.487075], [50.492786, 30.488286], [50.492795, 30.490105], [50.492789, 30.492337], [50.491386, 30.492343], [50.451505, 30.522443], [50.453256, 30.521894], [50.455041, 30.520526], [50.455247, 30.521183], [50.455223, 30.521921], [50.454998, 30.522875]]
// }];

// // non static data
// var nonStaticData = [];
// $(mapDataFormat).each(function () {
//     var _t = this;
//     nonStaticData.push({
//         id: _t.id,
//         speed: _t.speed,
//         workTime: _t.workTime,
//         lastCoords: _t.lastCoords[0]
//     });
// });





// <li data-id="101">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 1</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="102">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 2</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="103">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 3</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="104">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 4</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="105">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 5</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="106">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 6</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="107">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 7</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="108">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 8</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="109">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 9</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>
//     <li data-id="110">
//     <label class="checkbox">
//     <input type="checkbox" checked>
// <span><i class="fas fa-check"></i></span>
// </label>
// <div class="title">Машина 10</div>
// <label class="map-car-color-select-wrap">
//     <input type="text" class="map-car-color-select" value="">
//     </label>
//     </li>