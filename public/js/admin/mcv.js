$( document ).ready(function() {


    function changeState(lid) {
        $.ajax({
           type: 'POST',
           url: '/admin/ajax',
           data: { act : 12, id : lid} ,
           dataType: 'json',
       });
    }
    //fire if value is ON
    $(".change").change(function(event){
        var id = event.target.id;
        id = id.substring(1);
        console.log("fire");
        changeState(id);
    });
}); //end document ready
