$( document ).ready(function() {

var errormodal = new tingle.modal({
footer: true,
stickyFooter: false,
closeMethods: ['overlay', 'button', 'escape'],
closeLabel: "Close"
});
errormodal.addFooterBtn('Okay', 'tingle-btn tingle-btn--primary tingle-btn--pull-right', function(){
    errormodal.close();
    location.reload(true);
});

$("#delete").click(function() {

    var id = $("#imetier").val();
    console.log(id);

    var modalvalid = new tingle.modal({
        footer: true,
        stickyFooter: false,
        closeMethods: ['overlay', 'button', 'escape'],
        closeLabel: "Close",
        beforeClose: function() {
            return true; // close the modal
        }
    });
    modalvalid.setContent('Êtes-vous sur de vouloir suprimer le.s metier.s selectioné.s ?');
    modalvalid.addFooterBtn('Annuler', 'tingle-btn tingle-btn--primary tingle-btn--pull-left', function(){
        modalvalid.close();
        return;
    });
    modalvalid.addFooterBtn('Suprimer', 'tingle-btn tingle-btn--danger tingle-btn--pull-right', function(){
        $.ajax({
           type: 'POST',
           url: '/admin/ajax',
           data: { act : 3, id: id } ,
           dataType: 'json',
           success: function (data) {
                if(data.SUCCES == "OK") {
                    location.reload(true);
                } else {
                    errormodal.setContent("Erreur le.s metier.s ont des compétences qui leurs sont attaché.s");
                    errormodal.open();
                }
           }
       });
        modalvalid.close();
    });
    modalvalid.open();

});
}); //end document ready
