var app = {
    init: function(){
        $('#calendar').fullCalendar({
            defaultView: 'basicWeek'
          });
    }
}
$(app.init);