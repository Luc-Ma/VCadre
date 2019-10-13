$( document ).ready(function() {
    function changeState(state) {
        var mode = 0;
        if(state == 'priv')
            mode = 0;
        else
            mode = 1;

        $.ajax({
           type: 'POST',
           url: '/adherents/ajax',
           data: { act:mode } ,
           dataType: 'json',
           success: function (data) {
                if(data.SUCCES != "OK"){
                    //failed return to previous version
                    if(state == 'priv') {
                        $("#priv").prop( "checked", true );
                    } else {
                        $("#pub").prop( "checked", true );
                    }
                }
           }
       });
    }
    function changeMcvState(lid,state) {
        var mode = 'changeState';
        $.ajax({
           type: 'POST',
           url: '/adherents/ajax',
           data: { act : 3, id : lid} ,
           dataType: 'json',
           success: function (data) {
                if(data.SUCCES != "OK"){
                    //failed return to previous version
                    if(state == 'priv') {
                        $("#p"+lid).prop( "checked", true );
                    } else {
                        $("#u"+lid).prop( "checked", true );
                    }
                }
           }
       });
    }
    //fire if value is ON
    $(".prive").change(function(event){
        changeState('priv');
    });

    $(".public").change(function(event){
        changeState('pub');
    });
    //fire if value is ON
    $(".cvpriv").change(function(event){
        var id = event.target.id;
        id = id.substring(1);
        changeMcvState(id,'priv');
    });

    $(".cvpub").change(function(event){
        var id = event.target.id;
        id = id.substring(1);
        changeMcvState(id,'pub');
    });

    var errormodal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close"
    });
    errormodal.addFooterBtn('Okay', 'tingle-btn tingle-btn--primary tingle-btn--pull-right', function(){
        errormodal.close();
        //location.reload(true);
    });

    $("#delete").click(function() {

        var modalvalid = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            beforeClose: function() {
                return true; // close the modal
            }
        });
        modalvalid.setContent('Êtes-vous sur de vouloir suprimer votre CV  ?');
        modalvalid.addFooterBtn('Annuler', 'tingle-btn tingle-btn--primary tingle-btn--pull-left', function(){
            modalvalid.close();
            return;
        });
        modalvalid.addFooterBtn('Suprimer', 'tingle-btn tingle-btn--danger tingle-btn--pull-right', function(){
            $.ajax({
               type: 'POST',
               url: '/adherents/ajax',
               data: { act : 2 } ,
               dataType: 'json',
               success: function (data) {
                    if(data.SUCCES == "OK") {
                        location.reload(true);
                    } else {
                        errormodal.setContent("Votre cv n'as pas pus être suprimé");
                        errormodal.open();
                    }
               }
           });
            modalvalid.close();
        });
        modalvalid.open();

    });
    $(".cvdelete").click(function() {
        var id = event.target.id;
        var name = event.target.name;

        var modalvalid = new tingle.modal({
            footer: true,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            beforeClose: function() {
                return true; // close the modal
            }
        });
        modalvalid.setContent('Êtes-vous sur de vouloir suprimer votre CV '+ name +' ?');
        modalvalid.addFooterBtn('Annuler', 'tingle-btn tingle-btn--primary tingle-btn--pull-left', function(){
            modalvalid.close();
            return;
        });
        modalvalid.addFooterBtn('Suprimer', 'tingle-btn tingle-btn--danger tingle-btn--pull-right', function(){
            $.ajax({
               type: 'POST',
               url: '/adherents/ajax',
               data: { act : 4, id:id } ,
               dataType: 'json',
               success: function (data) {
                    if(data.SUCCES == "OK") {
                        location.reload(true);
                    } else {
                        errormodal.setContent("Votre mini CV n'as pas pus être suprimé");
                        errormodal.open();
                    }
               }
           });
            modalvalid.close();
        });
        modalvalid.open();

    });

}); //end document ready
