$( document ).ready(function() {

    function buildSelect(data,id,name)
    {
        for(cpt in data.data) {
            $("#c"+id).append("<option value="+data.data[cpt].value+">"+data.data[cpt].name +"</option>");
        }
    }

    $(".multicheck").click(function(){
        $(this).parent().toggleClass("check");
    });

    $(".comp").hide();

    $("select.mcompetence").change(function(event){
        var metier = $(this).val();
        //if (metier.length === 0) return;
        var id = event.target.id;
        $.ajax({
            type: 'POST',
            url: '/adherents/elements',
            data: { act : 0, value:metier} ,
            dataType: 'json',
            success: function (data) {
                if(data.SUCCES == "OK"){
                    $("#c"+id).find('option').remove();
                    buildSelect(data,id,'comp');
                    $("#c"+id).selectpicker('refresh');
                }
            }
        });
    });
}); //end document ready
