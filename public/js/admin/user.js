$(document).ready(function() {
    var msgmodal = new tingle.modal({
    footer: true,
    stickyFooter: false,
    closeMethods: ['overlay', 'button', 'escape'],
    closeLabel: "Close"
    });
    msgmodal.addFooterBtn('Okay', 'tingle-btn tingle-btn--primary tingle-btn--pull-right', function(){
        errormodal.close();
    });

    $("#add").click(function(){
        var user = $("#users").val();
        console.log("sel user = " + user);
        var modaladd = new tingle.modal({
                footer: true,
                stickyFooter: false,
                closeMethods: ['overlay', 'button', 'escape'],
                closeLabel: "Fermer",
                beforeClose: function() {
                    return true; // close the modal
                }
        });
        modaladd.setContent('Ajouter '+$("#users option:selected").text() +' en administrateur  ?');
        modaladd.addFooterBtn('Annuler', 'tingle-btn tingle-btn--primary tingle-btn--pull-left', function(){
            modaladd.close();
        });
        modaladd.addFooterBtn('Ajouter', 'tingle-btn tingle-btn--danger tingle-btn--pull-right', function(){
            $.ajax({
               type: 'POST',
               url: '/admin/ajax',
               data: { act: 0 , id: user } ,
               dataType: 'json',
               success: function (data) {
                    if(data.SUCCES == "OK") {
                        location.reload(true);
                    } else {
                        errormodal.setContent("Il y a eu une érreur");
                        errormodal.open();
                    }
               }
           });
            modaladd.close();
        });
        modaladd.open();
    });
    $("#del").click(function(){
        var user = $("#users").val();
        console.log("sel user = " + user);
        var modaladd = new tingle.modal({
                footer: true,
                stickyFooter: false,
                closeMethods: ['overlay', 'button', 'escape'],
                closeLabel: "Fermer",
                beforeClose: function() {
                    return true; // close the modal
                }
        });
        modaladd.setContent('Suprimer '+$("#users option:selected").text() +' des administrateurs  ?');
        modaladd.addFooterBtn('Annuler', 'tingle-btn tingle-btn--primary tingle-btn--pull-left', function(){
            modaladd.close();
        });
        modaladd.addFooterBtn('Suprimer', 'tingle-btn tingle-btn--danger tingle-btn--pull-right', function(){
            $.ajax({
               type: 'POST',
               url: '/admin/ajax',
               data: { act: 1 , id: user } ,
               dataType: 'json',
               success: function (data) {
                    if(data.SUCCES == "OK") {
                        location.reload(true);
                    } else {
                        errormodal.setContent("Il y a eu une érreur");
                        errormodal.open();
                    }
               }
           });
            modaladd.close();
        });
        modaladd.open();
    });
});
