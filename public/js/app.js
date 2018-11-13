var app = {
    init: function(){
        
        // pop up creation
        $('a.poplight').on('click', function() {
            var popID = $(this).data('rel'); 
            var popWidth = $(this).data('width');
            var dateDay = $(this).data('date');
            var day = $(this).data('day');        
            
            $('option:nth-child(' + day + ')').attr('selected', 'selected');
            $('#planning_date').attr('value', dateDay);
            $('#' + popID).fadeIn().css({ 'width': popWidth});
            
            var popMargTop = ($('#' + popID).height() + 80) / 2;
            var popMargLeft = ($('#' + popID).width() + 80) / 2;
            
            
            $('#' + popID).css({ 
                'margin-top' : -popMargTop,
                'margin-left' : -popMargLeft
            });
            
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            
            return false;
        });
        
        //pop up for change planning
        $('#new-planning-company').on('click', function() {
            var popID = 'popup-new-planning-company-form'; 
            var popWidth = 500;       
            
            $('#' + popID).fadeIn().css({ 'width': popWidth});
            
            var popMargTop = ($('#' + popID).height() + 80) / 2;
            var popMargLeft = ($('#' + popID).width() + 80) / 2;
            
            
            $('#' + popID).css({ 
                'margin-top' : -popMargTop,
                'margin-left' : -popMargLeft
            });
            
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            
            return false;
        });
        
        //make a cancel button for pop up
        $('#cancel-btn').on('click', function() { 
            $('#fade , .popup_block').fadeOut(function() {
                $('#fade').remove();  
            });           
            return false;
        });
        
        $('#cancel-edit-btn').on('click', function() { 
            $('#fade , .popup_block').fadeOut(function() {
                $('#fade').remove();  
            });           
            return false;
        });
        
        $('#cancel-new-company-planning-btn').on('click', function() { 
            $('#fade , .popup_block').fadeOut(function() {
                $('#fade').remove();  
            });           
            return false;
        });

        //delete planning handleling
        $('#planning-delet-btn').on('click', function() { 

            var id = $('#popup-edition-form').data('planning-id');
            var employee = $('#planning-edition_employee').val();
            var startTime = $('#planning-edition_startTime').val();
            var stopTime = $('#planning-edition_stopTime').val();
            var day = $('#planning-edition_day').val();
            var id = $('#popup-edition-form').data('planning-id');

            var data = {
                "employee": employee,
                "startTime": startTime,
                "stopTime": stopTime,
                "day": day,
            }

            jQuery.getJSON( '/planning/'+ id, function(data) {
                
                //worktime RAZ
                var startTime = app.transformTime(data['starttime']);
                var stopTime = app.transformTime(data['stoptime']);
                var workTime = stopTime - startTime;
                
                var actualEmployeeWorktime = $('#' + data['employee'] + 'worktime').text();

                $('#' + data['employee'] + 'worktime').text(parseFloat(actualEmployeeWorktime) + parseFloat(workTime));

                if ((parseFloat(actualEmployeeWorktime) + parseFloat(workTime)) < 0 ) {
                    $('#' + data['employee'] + 'worktime').addClass('negativ');
                }else{
                    $('#' + data['employee'] + 'worktime').removeClass('negativ');
                };
            
            });

            $.ajax({
                method: 'DELETE',
                url: "/planning/" + id,
            }).done( function(){
                
                container = $('#' + id).parent().attr('id');

                $('#' + id).remove();

                if ($('#' + container + ' .planning-employee').length == 1){
                    var planning = $('#' + container + ' .planning-employee')[0];
                    var style = $(planning).attr('style');
                    $(planning).attr('style', style + 'grid-column: 1;');
                };

                $('#fade , .popup_block').fadeOut(function() {
                    $('#fade').remove();  
                });
            }).fail(function(textmsg,errorThrown){
                console.log(textmsg);
                console.log(errorThrown);
            });
            return false;
        });

        //appearance of planning edit form    
        $('.planning-employee').on('click', app.listener);

        // make last hour line white
        var hours = $('.hour');
        $(hours[hours.length - 1]).css({'background-color' : 'rgba(237,244,249,1)'})

        
        //creation planning form handleling
        $('#planning-form').on('submit', function (e) {
            
            var dayDate = $('#planning_date').val();
            var day = $('#planning_day').val();
            var employee = $('#planning_employee').val();
            var company = $('#planning_company').val();
            var week = $('#planning_week').val();
            var year = $('#planning_year').val();
            var startTime = $('#planning_startTime').val();
            var stopTime = $('#planning_stopTime').val();
 
            var data = {
                "date": dayDate,
                "day": day,
                "employee": employee,
                "company": company,
                "week": week,
                "year": year,
                "startTime": startTime,
                "stopTime": stopTime,
            }

            if(app.check(day, startTime, stopTime, e)) {
                window.alert('les horraires saisies ne sont pas correctes');
            }else{
                //ajax call     
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serializeArray(),
                }).done( function(response){
                    jQuery.getJSON( $('#company-id').data('path'), function(datas) {
                        var id = datas
                        app.createPlanning(data, id);           
                    });
                    $('#fade , .popup_block').fadeOut(function() {
                        $('#fade').remove();  
                    });
                }).fail(function(textmsg,errorThrown){
                    console.log(textmsg);
                    console.log(errorThrown);
                });
            };
            return false;

            });

        //creation planning edit form handleling
        $('#planning-edit-form').on('submit', function (e) {

            var employee = $('#planning-edition_employee').val();
            var startTime = $('#planning-edition_startTime').val();
            var stopTime = $('#planning-edition_stopTime').val();
            var day = $('#planning-edition_day').val();
            var id = $('#popup-edition-form').data('planning-id');

            var data = {
                "employee": employee,
                "startTime": startTime,
                "stopTime": stopTime,
                "day": day,
            }
            
            if(app.check(day, startTime, stopTime, e)) {
                window.alert('les horraires saisies ne sont pas correctes');
            }else{
            
                jQuery.getJSON( '/planning/'+ id, function(data) {
                    
                    //worktime RAZ
                    var startTime = app.transformTime(data['starttime']);
                    var stopTime = app.transformTime(data['stoptime']);
                    var workTime = stopTime - startTime;
                    
                    var actualEmployeeWorktime = $('#' + data['employee'] + 'worktime').text();

                    $('#' + data['employee'] + 'worktime').text(parseFloat(actualEmployeeWorktime) + parseFloat(workTime));

                    if ((parseFloat(actualEmployeeWorktime) + parseFloat(workTime)) < 0 ) {
                        $('#' + data['employee'] + 'worktime').addClass('negativ');
                    }else{
                        $('#' + data['employee'] + 'worktime').removeClass('negativ');
                    };
            
                });
                //ajax call     
                $.ajax({
                    method: $(this).attr('method'),
                    url: "/planning/"+ id +"/edit",
                    data: $(this).serializeArray(),
                }).done( function(){
                    container = $('#' + id).parent().attr('id');
                    //we remove the ancient planning
                    $('#' + id).remove();

                    if ($('#' + container + ' .planning-employee').length == 1){
                        var planning = $('#' + container + ' .planning-employee')[0];
                        var style = $(planning).attr('style');
                        $(planning).attr('style', style + 'grid-column: 1;');
                    };
                    //we replace by the modified planning
                    app.createPlanning(data, id);
                    $('#fade , .popup_block').fadeOut(function() {
                        $('#fade').remove();  
                    });
                }).fail(function(textmsg,errorThrown){
                    console.log(textmsg);
                    console.log(errorThrown);
                });
            }
            return false;
        });

        //planning duplication
        $('.duplication').on('click', function(e){

            e.preventDefault();

            var formId = $(this).parent('form').attr('id');
            var popID = 'popup-duplication-form'; 
            var popWidth = 500;       
            
            $('#' + popID).fadeIn().css({ 'width': popWidth});
            
            var popMargTop = ($('#' + popID).height() + 80) / 2;
            var popMargLeft = ($('#' + popID).width() + 80) / 2;
            
            
            $('#' + popID).css({ 
                'margin-top' : -popMargTop,
                'margin-left' : -popMargLeft
            });
            
            $('body').append('<div id="fade"></div>');
            $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
            
            $('#popup-duplication-form').data('form-id', formId);

            console.log($('#popup-duplication-form').data('form-id'));
            
            return false;
        });

        $('#planning-duplication').on('submit', function(e){
            e.preventDefault();

            var year = $('#year-pop').val();
            var week = $('#week-pop').val();

            var formId = $('#popup-duplication-form').data('form-id');

            $('#duplicate_year_' + formId).val(year);
            $('#duplicate_week_' + formId).val(week);

            if (week === "" || year === "") {
                window.alert("Veuillez remplir les deux champs 'années et semaine");
                
            }else{
                $('#' + formId).submit();
                console.log('#' + formId);
                
            }

            console.log('submit');
            
        })

    },

    //DOM manipulation for show plannings
    createPlanning: function(data, id){
        var id = id;
        var roundStartTime = app.round(data['startTime']);
        var roundStopTime = app.round(data['stopTime']);
        var startTime = app.transformTime(roundStartTime);
        var stopTime = app.transformTime(roundStopTime);
        var workTime = stopTime - startTime;
        var actualEmployeeWorktime = $('#' + data['employee'] + 'worktime').text();

        //worktime modification
        $('#' + data['employee'] + 'worktime').text(actualEmployeeWorktime - workTime);

        if ((actualEmployeeWorktime - workTime) < 0 ) {
            $('#' + data['employee'] + 'worktime').addClass('negativ');
        }else{
            $('#' + data['employee'] + 'worktime').removeClass('negativ');
        };
        
        var employeeFName = $('#planning_employee option[value="'+ data['employee'] +'"]').text();
        
        
        if (startTime > $('#' + data['day'] + 'morning').data('firsttimestop')) {
            
            var column = $('#' + data['day'] + 'afternoon div:last-child').data('column') + 1;
            if (isNaN(column)) {
                column = 1;
            };
            var start = (startTime - $('#' + data['day'] + 'afternoon').data('secondtimestart')) * 4 + 1;
            var planningStopTime = start + workTime * 4;
            
            var div = '<div class="employee'+ data['employee'] +' planning-employee" id="'+ id +'" data-column="' + column + '" style="grid-column:' + column +'; grid-row: '+ start + '/' + planningStopTime +';">'+ employeeFName +' : ' + roundStartTime +  'h-'+ roundStopTime + 'h</div>';
            
            $("#" + data['day'] + 'afternoon').append(div);

            //listener application on new dom element 
            $('#' + id).on('click', app.listener);

        }else{
            
            var column = $('#' + data['day'] + 'morning div:last-child').data('column') + 1;
            if (isNaN(column)) {
                column = 1;
            };
            var start = (startTime - $('#' + data['day'] + 'morning').data('firsttimestart')) * 4 + 1;
            var planningStopTime = start + workTime * 4;

            var div = '<div class="employee'+ data['employee'] +' planning-employee" id="'+ id +'" data-column="' + column + '" style="grid-column:' + column +'; grid-row: '+ start + '/' + planningStopTime +';">'+ employeeFName +' : ' + roundStartTime +  'h-'+ roundStopTime + 'h</div>';

            $("#" + data['day'] + 'morning').append(div);

            //listener application on new dom element 
            $('#' + id).on('click', app.listener);

        }


    },

    //convert time base 60 on base 100
    transformTime: function(time) {

        var splitedTime = time.split(":");

        if (splitedTime[1] !== 0) {
            convertedMinuntes = splitedTime[1]/0.6;
        }else{
            convertedMinuntes = 0;
        }

        convertedTime = splitedTime[0] +'.'+ convertedMinuntes;

        return parseFloat(convertedTime);
    },

    //listner for planning-employee
    listener: function(){
        var popID = "popup-edition-form"; 
        var popWidth = 500;     


        $('#' + popID).fadeIn().css({ 'width': popWidth});
        
        var popMargTop = ($('#' + popID).height() + 80) / 2;
        var popMargLeft = ($('#' + popID).width() + 80) / 2;
        
        
        $('#' + popID).css({ 
            'margin-top' : -popMargTop,
            'margin-left' : -popMargLeft
        });
        
        $('body').append('<div id="fade"></div>');
        $('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();

        //ajax call

        id = $(this).attr('id');

        $('#popup-edition-form').data('planning-id', id);

        $.getJSON("/planning/" + id, function(data){
            
            $('#edition_daydate').text(data['date']);
            $('#planning-edition_employee option[value="' + data['employee'] + '"]').attr('selected', 'selected');
            $('#planning-edition_startTime').val(data["starttime"]);
            $('#planning-edition_stopTime').val(data["stoptime"]);
            $('#planning-edition_day').val(data["day"]);

        });
        
        return false;
    },

    //transform weard hour into quarter of hour base
    round: function(time) {
        var splitedTime = time.split(":");

        minutes = splitedTime[1];
        hour = parseInt(splitedTime[0]);
        //transform value on quarter of hour
        switch (true) {
            case (minutes >=  8 && minutes < 23):
                minutes = 15;
                return (hour + ":" + minutes);
                break;

            case (minutes >= 23 && minutes < 38):
                minutes = 30;
                return (hour + ":" + minutes);
                break;

            case (minutes >= 38 && minutes < 52):
                minutes = 45;
                return (hour + ":" + minutes);
                break;

            case (minutes >= 52 ):
                minutes = 0;
                hour += 1; 
                return (hour + ":" + minutes + 0);
                break;

            default:
                minutes = 0;
                return (hour + ":" + minutes + 0);
                break;
        }
    },

    check: function(day, startTime, stopTime, e) {
        //avoid form submition
        e.preventDefault();
        //value for verification befor planning registration
        var convertedStartTime = app.transformTime(startTime);
        var convertedStopTime = app.transformTime(stopTime);
        var morningStartTime = $('#' + day + 'morning').data('firsttimestart');
        var morningStopTime = $('#' + day + 'morning').data('firsttimestop');

        if($('#' + day + 'afternoon').data('secondtimestart') != undefined){
            var afternoonStartTime = $('#' + day + 'afternoon').data('secondtimestart');
            var afternoonStopTime = $('#' + day + 'afternoon').data('secondtimestop');
        }else{
            var afternoonStartTime = morningStartTime;
            var afternoonStopTime = morningStopTime;
        };

        //validation planning 
        if ( ((convertedStartTime < morningStartTime || convertedStopTime > morningStopTime) && ((convertedStartTime < afternoonStartTime || convertedStopTime > afternoonStopTime)) || (convertedStopTime < convertedStartTime)) ) {
            return true;
        }else{
            return false;
        }
    },

}
$(app.init);