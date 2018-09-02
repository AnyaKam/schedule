$(document).on('click','#add_one_more_customer', function(){
    $.ajax({  
        url: "add_customer",  
        cache: false,  
        success: function(html){  
            $("#result").append(html);
        }  
    });  
});
