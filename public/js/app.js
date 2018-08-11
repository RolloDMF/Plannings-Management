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

        $('#planning-form').on('submit', function (e) {

            var dayDate = $('planning_date').val();
            var day = $('planning_day').val();
            var employee = $('planning_employee').val();
            var company = $('planning_company').val();
            var week = $('planning_week').val();
            var year = $('planning_year').val();
            var startTime = $('planning_startTime').val();
            var stopTime = $('planning_stopTime').val();

                //ajax call     
                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                }).done( function(data) {
                    console.log(data);             
                    $('#fade , .popup_block').fadeOut(function() {
                        $('#fade, a.close').remove();  
                    });
                }).fail(function(textmsg,errorThrown){
                    console.log(textmsg);
                    console.log(errorThrown);
                });
                return false;

            });

    }

}
$(app.init);