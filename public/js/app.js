var app = {
    init: function(){
   
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
        
        
        $('#cancel-btn').on('click', function() { 
            $('#fade , .popup_block').fadeOut(function() {
                $('#fade, a.close').remove();  
        });
            
            return false;
        });

        /* make last hour line white */
        var hours = $('.hour');
        console.log(hours[hours.length - 1]);
        $(hours[hours.length - 1]).css({'background-color' : 'white'})

        $('#planning-form').on('submit', function () {

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
                return false;
            });

    },

    createPlanning: function(data, id){
        var id = id;
        var column = $("#day" + data['day']).data('position');
        var minOpenSchedule = $(".planning").data('minopenschedule');
        var startTime = app.transformTime(data['startTime']);
        var stopTime = app.transformTime(data['stopTime']);
        var workTime = stopTime - startTime;
        var shift = $(".planning").data('shift');
        var start = (startTime - minOpenSchedule + shift) * 4 + 1;

        var employeeFName = $('#planning_employee option[value="'+ data['employee'] +'"]').text();
        
        var planningStopTime = start + workTime * 4;

        var div = '<div class="employee'+ data['employee'] +'" id="'+ id +'" style="grid-column:' + column +'; grid-row: '+ start + '/' + planningStopTime +';">'+ employeeFName +' : ' + data['startTime'] +  'h-'+ data['stopTime'] + 'h</div>';

        $("#day" + data['day']).append(div);
    },

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

}
$(app.init);