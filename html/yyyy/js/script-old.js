/**
 * Created by No Fear on 23.02.2021.
 * E-mail: g0th1c097@gmail.com
 */

//--------- Necessary variables
var color_picker_active = 0, mainMap, firstMapDraw = 0, mainMapCarsSelected = [], carsMovementTimeout, geocodeService, mainTailsLength = parseInt($('#map-time :selected').attr('data-time'))/1000, mainCarsStop = [], socket = io(), mapStaticData = [];

socket.on('connect',function(data){
    socket.emit("data","carList");
    socket.emit("data","carData",getSelectedCars());
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
    // socket.emit("data","carlist");
});

$(document).ready(function () {

    //--------- Hide loader
    $('#loader').stop().fadeOut();

    //--------- Main nav functionality
    $('#sidebar nav button').click(function () {
        if(!$(this).hasClass('active')) {
            var _t = $(this);
            $('#sidebar nav button').removeClass('active');
            _t.addClass('active');
            $('.tab').removeClass('in');
            setTimeout(function () {
                $('.tab').removeClass('active');
                $('.tab#' + _t.attr('data-target')).addClass('active');
            }, 200);
            setTimeout(function () {
                $('.tab#' + _t.attr('data-target')).addClass('in');
            }, 230);
            if(_t.attr('data-target') === 'map') {
                drawCars();
                carsMovement();
            }
            else {
                clearInterval(carsMovementTimeout);
            }
            if(_t.attr('data-target') === 'messenger') {
                //--------- Scroll to bottom of messages
                setTimeout(function () {
                    $('#messages').scrollTop($("#messages")[0].scrollHeight);
                },250);
            }
        }
    });

    //--------- Map car selection show/hide
    $('.map-car-select > .btn').click(function () {
        $(this).parent().toggleClass('active');
    });

    //--------- Map cars list fill from database
    socket.on('carList',(data)=>{
        $(data).each(function () {
            $('.map-car-select ul').append('<li data-id="' + this.id + '"><label class="checkbox"><input type="checkbox" checked><span><i class="fas fa-check"></i></span></label><div class="title">' + this.name + '</div><label class="map-car-color-select-wrap"><input type="text" class="map-car-color-select" value=""></label></li>');
        });
    });

    //--------- Map cars color rewrite from local storage
    if (typeof localStorage !== 'undefined' && localStorage.getItem('mapCarsColors') !== null) {
        var colors = JSON.parse(localStorage.getItem('mapCarsColors'));
        $.each(colors, function(index, value) {
            $('.map-car-select li[data-id="' + index + '"]').addClass('colored').find('.map-car-color-select').attr('value', value);
        });
    }

    //--------- Map car set random color if no color
    $('.map-car-color-select').each(function () {
        !$(this).attr('value').length && $(this).attr('value', getRandomColor());
    });

    //--------- Map tab active cars colors
    $('.map-car-color-select').spectrum({
        preferredFormat: "hex",
        chooseText: "Выбрать",
        cancelText: "Отмена",
        clickoutFiresChange: false,
        containerClassName: 'spectrum-map-cars',
        change: function(color) {
            $(this).closest('li').addClass('colored');
            mapCarsColors();
            changeCarColor($(this).closest('li').attr('data-id'), $(this).spectrum('get').toRgbString());
        }
    }).on("dragstart.spectrum", function () {
        color_picker_active = 1;
    }).on("dragstop.spectrum", function () {
        setTimeout(function () {
            color_picker_active = 0;
        },50);
    });

    //--------- Map time rewrite from local storage
    if (typeof localStorage !== 'undefined' && localStorage.getItem('mapTime') !== null) {
        $('#map-time option').each(function () {
            if($(this).attr('data-time') === localStorage.getItem('mapTime')) {
                $(this).prop('selected', true);
            }
        });
    }

    //--------- Onchange main map activity time redraw cars and tails
    $('#map-time').change(function () {
        if(!firstMapDraw) {return;}
        localStorage.setItem('mapTime', $('#map-time :selected').attr('data-time'));
        mainTailsLength = parseInt($('#map-time :selected').attr('data-time'))/1000;
        changeMainTails();
        // TODO
    });

    //--------- Map cars unselect from local storage
    if (typeof localStorage !== 'undefined' && localStorage.getItem('mapCarsUnselected') !== null) {
        var unselectedCars = JSON.parse(localStorage.getItem('mapCarsUnselected'));
        $.each(unselectedCars, function(i, v) {
            $('.map-car-select li[data-id="' + v + '"] .checkbox input').prop('checked', false);
        });
    }

    //--------- Redraw cars on selection
    $('.map-car-select li .checkbox input').change(function () {
        if(!firstMapDraw) {return;}
        mapCarsUnselected();
        clearInterval(carsMovementTimeout);
        drawCars();
        carsMovement();
        // TODO
    });

    //--------- Initialize main map
    initMainMap();

    //--------- Draw cars first time
    drawCars();

    //--------- Draw cars positions each second
    carsMovement();







    //--------- Toggle messages smiles
    $('#mess-smiles-btn').click(function () {
        $(this).parent().toggleClass('active');
    });

    //--------- Append messages smiles to message
    $('#mess-smiles div').click(function () {
        $('.mess-smiles-wrap').removeClass('active');
        var cursorPos = $('#mess-input').prop('selectionStart');
        var v = $('#mess-input').val();
        var textBefore = v.substring(0,  cursorPos);
        var textAfter  = v.substring(cursorPos, v.length);
        $('#mess-input').val(textBefore + $(this).text() + textAfter);
    });

    //--------- Send message
    $('#mess-submit').click(function () {
        if($('#mess-input').val().length > 0) {
            var _m = $('#mess-input').val();
            $('#mess-smiles div').each(function () {
                if(_m.indexOf($(this).text()) >= 0) {
                    _m = _m.replace($(this).text(), '<i>' + $(this).text() + '</i>');
                }
            });
            $('#messages').append('<div class="message own"><div class="mess-title">Александр Иванов - <time>' + getFormattedCurrentSavedDate() + '</time></div><div class="mess-body">' + _m + '</div></div>').scrollTop($("#messages")[0].scrollHeight);
            $('#mess-input').val('');
            // TODO user name + send request + get request + select users + loader show
        }
    });

    //--------- Finance start date select
    $('#fin-start-date').val(getFormattedDatepickerDate(-7)).datepicker({
        format: "dd/mm/yyyy",
        weekStart: 1,
        language: "ru",
        todayHighlight: true,
        endDate: getFormattedDatepickerDate(0)
    }).change(function () {
        $('#fin-week-btn').removeAttr('disabled');
        $('#fin-end-date').datepicker('setStartDate', $(this).datepicker('getDate'));
        // TODO request send + loader
    });

    //--------- Finance end date select
    $('#fin-end-date').val(getFormattedDatepickerDate(0)).datepicker({
        format: "dd/mm/yyyy",
        weekStart: 1,
        language: "ru",
        todayHighlight: true,
        startDate: getFormattedDatepickerDate(-7),
        endDate: getFormattedDatepickerDate(0)
    }).change(function () {
        $('#fin-week-btn').removeAttr('disabled');
        $('#fin-start-date').datepicker('setEndDate', $(this).datepicker('getDate'));
        // TODO request send + loader
    });

    //--------- Finance set last seven days view
    $('#fin-week-btn').click(function () {
        $('#fin-start-date').datepicker('setDate', getFormattedDatepickerDate(-7));
        $('#fin-end-date').datepicker('setDate', getFormattedDatepickerDate(0));
        $(this).attr('disabled', 'disabled');
        // TODO request send + prevent double requests + loader
    });

    //--------- Finance change view type
    $('#fin-drivers-cars').change(function () {
        // TODO request send + draw table + loader + sortable + graphs init
    });

    //--------- Finance show single driver/car info
    $('#fin-table tbody tr').click(function () {
        $('#modal-fin-user').modal({
            showClose: false,
            fadeDuration: 300,
            fadeDelay: 0.3
        });
        // TODO request send + show modal
    });

    //--------- Finance click on title
    $('#fin-table th').click(function() {

        var table = $(this).parents('table').eq(0), _users = [], _labels = [], _data = [], _colors = [], _rowsLength = $(table).find('tbody tr').length;

        $('#fin-graphs > *').remove();

        //--------- Sort table
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
        this.asc = !this.asc;
        $('#fin-table th').removeClass('asc desc');
        if (!this.asc) {
            rows = rows.reverse();
            $(this).addClass('desc');
        }
        else {
            $(this).addClass('asc');
        }
        for (var i = 0; i < rows.length; i++) {
            table.append(rows[i])
        }

        //--------- Draw graphs
        if($(this).index() === 0 || $(this).index() === 2 || $(this).index() === 3 || $(this).index() === 4) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(11)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 1) {
            $(table).find('tbody tr td:nth-child(' + ($(this).index()+1) + ')').each(function () {
                var _t = $(this), _inArray = 0;
                $(_users).each(function () {
                    if(_t.text() === this.name) {
                        _inArray = 1;
                        this.quantity = (parseInt(this.quantity) + 1);
                    }
                });
                if(!_inArray) {
                    _users[_users.length] = {'name': _t.text(), 'quantity': '1'};
                    _inArray = 0;
                }
            });
            $('#fin-graphs').append('<div class="fin-canvas-sm"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            roundChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 5) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(6)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 6) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(7)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 7) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(8)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 8) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(9)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 9) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(10)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 10) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(11)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-lg"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);

            _users = [];
            _labels = [];
            _data = [];
            _colors = [];
            _users[_users.length] = {'name': 'Uber', 'quantity': $('#ft-1').text()};
            _users[_users.length] = {'name': 'Bolt', 'quantity': $('#ft-2').text()};
            _users[_users.length] = {'name': 'Uklon', 'quantity': $('#ft-3').text()};
            $('#fin-graphs').append('<div class="fin-canvas-sm"><canvas id="chart-2"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            roundChart($('#chart-2'),_labels,_data,_colors);
        }
        else if($(this).index() === 11) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(12)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 12) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(13)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 13) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(14)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }
        else if($(this).index() === 14) {
            $(table).find('tbody tr').each(function () {
                _users[_users.length] = {'name': ($(this).find('td:nth-child(4)').text() + ' ' + $(this).find('td:nth-child(5)').text()), 'quantity': $(this).find('td:nth-child(15)').text()};
            });
            $('#fin-graphs').append('<div class="fin-canvas-full"><canvas id="chart-1"></canvas></div>');
            $(_users).each(function (i) {
                _labels.push(_users[i].name);
                _data.push(parseInt(_users[i].quantity)); // (parseInt(_users[i].quantity)/_rowsLength*100).toFixed(2)
                _colors.push(getRandomColor());
            });
            barChart($('#chart-1'),_labels,_data,_colors);
        }















    });










    

    //--------- Click on body events
    $('#app').click(function (e) {

        //--------- Hide map tab car selection
        if(!$(e.target).closest('.map-car-select').length && !color_picker_active) {
            $('.map-car-select').removeClass('active');
        }

        //--------- Hide messages smiles
        if(!$(e.target).closest('.mess-smiles-wrap').length) {
            $('.mess-smiles-wrap').removeClass('active');
        }

    });

});

//--------- Function gets random color
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

//--------- Function writes selected from map tab car colors in local storage
function mapCarsColors() {
    var colors = {};
    $('.map-car-select li').each(function () {
        if($(this).hasClass('colored')) {
            colors[$(this).attr('data-id')] = $(this).find('.map-car-color-select').spectrum("get").toString();
        }
    });
    localStorage.setItem('mapCarsColors', JSON.stringify(colors));
}

//--------- Function changes car color on main map
function changeCarColor(carId, color) {

    mainMap.eachLayer(function (layer) {

        //--------- Change marker color
        if ((layer.options.className !== undefined) && layer.options.className.indexOf(carId) >= 0 && layer.options.className.indexOf('custom-marker') >= 0) {
            layer.setStyle({color: color, fillColor: color});
        }

        //--------- Move marker tails
        if ((layer.options.className !== undefined) && layer.options.className.indexOf(carId) >= 0 && layer.options.className.indexOf('custom-tail') >= 0) {
            layer.setStyle({color: color});
        }

    });

}

//--------- Function writes selected from map tab car unselected in local storage
function mapCarsUnselected() {
    var unselected = [];
    $('.map-car-select li .checkbox input').each(function () {
        if(!$(this).is(":checked")) {
            unselected.push($(this).closest('li').attr('data-id'));
        }
    });
    localStorage.setItem('mapCarsUnselected', JSON.stringify(unselected));
}

//--------- Function returns selected cars from main map
function getSelectedCars() {
    mainMapCarsSelected = [];
    $('.map-car-select li .checkbox input').each(function () {
        if($(this).is(":checked")) {
            mainMapCarsSelected.push($(this).closest('li').attr('data-id'));
        }
    });
    return mainMapCarsSelected;
}

//--------- Function returns element from array with non static data for cars
function nonStaticDataFunc(car_id) {
    var elem;
    $(nonStaticData).each(function (i) {
        if(this.id === car_id) {
            elem = this;
        }
    });
    return elem;
}

//--------- Function initializes main map
function initMainMap() {

    //--------- Main init map
    mainMap = L.map('main-map', {
        center: [50.449877, 30.523868],
        zoom: 12
    }).on('zoomstart', function () {
        // $(customMarker._path).removeClass('custom-icon');
    }).on('zoomend', function () {
        // setTimeout(function () {
        //     $(customMarker._path).addClass('custom-icon');
        // }, 30);
    });

    //--------- Add tile layers to main map
    L.tileLayer('https://api.mapbox.com/styles/v1/no-fear/ckljvdhec0qr017puygumdgym/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoibm8tZmVhciIsImEiOiJja2xqYnJmNHcwNWZjMnhwNTlnem9rYWMyIn0.-o1sj1NhV1jmX7F-FkvxqA', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/light-v9',
        tileSize: 512,
        zoomOffset: -1,
        zoom: 15
    }).addTo(mainMap);

    geocodeService = L.esri.Geocoding.geocodeService({
        apikey: 'AAPK4d9bf52941434c77aac1ca332f06ee22_IVxU5orlIOK6lGJunVa-HKCURSE4hu4_FXyxz9XLTn3CBN0QcEggBPSfD5BnoJ1'
    });

}

//--------- function adding points on main map
function drawCars() {

    mainCarsStop = [];

    mainMap.eachLayer(function (layer) {

        if ((layer.options.className !== undefined) && layer.options.className.indexOf('custom-marker') >= 0 && $(layer._path)[0].hasAttribute('data-stop')) {

            mainCarsStop.push({
                id: layer.options.className.substring(14),
                time: $(layer._path).attr('data-stop')
            });

        }

    });

    mainMap.eachLayer(function (layer) {

        if ((layer.options.className !== undefined) && layer.options.className.indexOf('custom-') >= 0) {

            layer.remove();

        }

    });

    socket.on('carData',(data)=>{

        $(data).each(function () {

            var _t = this, _tColor = $('.map-car-select li[data-id="' + _t.id + '"] .map-car-color-select').spectrum('get');

            L.circleMarker(_t.lastCoords[0], {
                radius: 8,
                fillColor: _tColor,
                fillOpacity: 1,
                color: _tColor,
                opacity: 0.3,
                weight: 6,
                className: 'custom-marker-' + _t.id
            }).on('click', function(e) {
                L.popup({
                    maxWidth: 400,
                    className: 'custom-popup-' + _t.id
                }).setLatLng(e.latlng).setContent(
                    '<ul class="map-popup-list">' +
                    '<li><span>ID авто:</span> ' + _t.id + '</li>' +
                    '<li class="map-popup-list-address"><span>Адрес:</span> <strong>Обработка...</strong></li>' +
                    '<li><span>Имя водителя:</span> ' + _t.driverName + '</li>' +
                    '<li><span>Телефон:</span> ' + _t.driverPhone + '</li>' +
                    '<li class="map-popup-list-speed"><span>Скорость авто:</span> <strong>' + nonStaticDataFunc(_t.id).speed + '</strong></li>' +
                    '<li class="map-popup-list-work-time"><span>Время на линии:</span> <strong>' + nonStaticDataFunc(_t.id).workTime + '</strong></li>' +
                    '<li class="map-popup-list-coords"><span>Координаты:</span> <strong>' + nonStaticDataFunc(_t.id).lastCoords[0] + ', ' + nonStaticDataFunc(_t.id).lastCoords[1] + '</strong></li>' +
                    '</ul>'
                ).openOn(mainMap);

                geocodeService.reverse().latlng(e.latlng).language("rus").run(function (error, result) {
                    if (error) {
                        return;
                    }
                    $('.map-popup-list-address strong').text(result.address.LongLabel);
                });

            }).addTo(mainMap);

            L.polyline(_t.lastCoords, {
                weight: 5,
                lineJoin: 'round',
                color: _tColor,
                opacity: 0.5,
                interactive: false,
                className: 'custom-tail-' + _t.id
            }).addTo(mainMap);

        });

    });

    $(mainCarsStop).each(function () {

        var _t = this;

        mainMap.eachLayer(function (layer) {

            if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-marker') >= 0) {

                $(layer._path).attr('data-stop', _t.time);

            }

        });

    });

    firstMapDraw = 1;

}

//--------- Function moves cars popups and tails on main map
function carsMovement() {

    carsMovementTimeout = setInterval(function () {

        // $.post("/api/path/", { "cars": getSelectedCars(), "time": $('#map-time :selected').attr('data-time') }, function (response) {
        //
        //     response.status;
        //
        // });

        $(mapDataFormatTimeout).each(function (i) {

            var _t = this, _i = i;

            mainMap.eachLayer(function (layer) {

                //--------- Move markers & add new popups info
                if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-marker') >= 0) {

                    //--------- If car stop
                    if((parseInt(_t.speed) === 0) || (layer._latlng.lat === _t.lastCoords[0])) {
                        if(layer._path.hasAttribute('data-stop')) {
                            if(Math.abs(new Date() - new Date($(layer._path).attr('data-stop'))) > 1200000) {/*1sec - 1000; 20min - 1200000*/
                                $(layer._path).addClass('car-stop');
                            }
                        }
                        else {
                            $(layer._path).attr('data-stop', new Date());
                        }
                    }
                    else {
                        if(layer._path.hasAttribute('data-stop')) {
                            $(layer._path).removeClass('car-stop').removeAttr('data-stop');
                        }
                    }

                    layer.setLatLng(_t.lastCoords);

                }

                //--------- If popup exists move it with marker
                if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-popup') >= 0) {
                    layer.setLatLng(_t.lastCoords);
                }

                //--------- Move marker tails
                if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-tail') >= 0) {
                    var oldPoints = layer.getLatLngs();
                    oldPoints.map(function(point) {return [point.lat, point.lng];});
                    if(layer.getLatLngs().length < (mainTailsLength - 25)) {
                        oldPoints.unshift(_t.lastCoords);
                        layer.setLatLngs(oldPoints);
                    }
                    else {
                        oldPoints.pop();
                        oldPoints.unshift(_t.lastCoords);
                        layer.setLatLngs(oldPoints);
                    }
                }

            });

        });

    }, 1000);

}

//--------- Function changes main map cars tails length
function changeMainTails() {

    clearInterval(carsMovementTimeout);

    // $.post("/api/path/", { "cars": getSelectedCars(), "time": $('#map-time :selected').attr('data-time') }, function (response) {
    //
    //     response.status;
    //
    // });

    $(mapDataFormatTime).each(function () {

        var _t = this;

        mainMap.eachLayer(function (layer) {

            //--------- Move markers & add new popups info
            if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-marker') >= 0) {
                layer.setLatLng(_t.lastCoords[0]);
            }

            //--------- If popup exists move it with marker
            if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-popup') >= 0) {
                layer.setLatLng(_t.lastCoords[0]);
            }

            //--------- Move marker tails
            if ((layer.options.className !== undefined) && layer.options.className.indexOf(_t.id) >= 0 && layer.options.className.indexOf('custom-tail') >= 0) {
                layer.setLatLngs(_t.lastCoords);
            }

        });

    });

    carsMovement();

}

//--------- Function gets current date/time and format it for messages
function getFormattedCurrentSavedDate() {
    var date  = new Date(),
        hours = date.getHours(),
        minutes = date.getMinutes();
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var month = (parseInt(date.getMonth()) > 8) ? (date.getMonth()+1) : '0' + (date.getMonth()+1),
        day = (parseInt(date.getDate()) > 9) ? (date.getDate()) : '0' + (date.getDate());
    return day + '.' + month + '.' + date.getFullYear().toString().substr(-2) + ' - ' + hours + ':' + minutes;
}

//--------- Function gets date and format it for datepicker with difference in days
function getFormattedDatepickerDate(days) {
    var _d = new Date();
    _d.setDate(_d.getDate()+days);
    var month = (parseInt(_d.getMonth()) > 8) ? (_d.getMonth()+1) : '0' + (_d.getMonth()+1),
        day = (parseInt(_d.getDate()) > 9) ? (_d.getDate()) : '0' + (_d.getDate());
    return day + '/' + month + '/' + _d.getFullYear();
}

//--------- Function for tables sorting
function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index);
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
    }
}

//--------- Function for tables sorting
function getCellValue(row, index) {
    return $(row).children('td').eq(index).text();
}

//--------- Function draws circle chart
function roundChart(_e,_l,_d,_c) {
    new Chart(_e, {
        type: 'doughnut',
        data: {
            labels: _l,
            datasets: [{
                data: _d,
                backgroundColor: _c,
                label: 'Dataset 1'
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                mode: 'index'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            legend: {
                onClick: false
            }
        }
    });
}

//--------- Function draws bar chart
function barChart(_e,_l,_d,_c) {
    new Chart(_e, {
        type: 'bar',
        data: {
            labels: _l,
            datasets: [{
                data: _d,
                backgroundColor: _c
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    ticks: {
                        display: false,
                        fontColor: "rgba(0,0,0,0.54)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: "rgba(0,0,0,0.54)"
                    }
                }]
            },
            tooltips: {
                mode: 'index'
            },
            animation: {
                animateScale: true
            },
            legend: {
                display: false
            }
        }
    });
}