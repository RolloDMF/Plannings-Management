var formManip = {
    init: function(){

        //size of selecte field
        $('#employee_company').addClass('custom-select col-2');

        //company creation check
        $("#company-creation-btn").on('click', function(e){

            var zipcode = $("#company_zipCode").val();
            
            if(zipcode.length != 5){
                e.preventDefault();
                window.alert('le code postale n\' est pas correcte');
                console.log(zipcode);
                console.log(zipcode.length);
            }

        });
    },

}
$(formManip.init);