$( document ).ready(function() {

    var settings = {
        /* Appearance */
        headerTag: "h1",
        bodyTag: "div",
        contentContainerTag: "div",
        actionContainerTag: "div",
        stepsContainerTag: "div",
        cssClass: "wizard",
        stepsOrientation: $.fn.steps.stepsOrientation.horizontal,

        /* Behaviour */
        autoFocus: false,
        enableAllSteps: false,
        enableKeyNavigation: true,
        enablePagination: true,
        suppressPaginationOnFocus: true,
        enableContentCache: true,
        enableCancelButton: true,
        enableFinishButton: true,
        preloadContent: false,
        showFinishButtonAlways: false,
        forceMoveForward: false,
        saveState: false,
        startIndex: 0,

        /* Transition Effects */
        transitionEffect: $.fn.steps.transitionEffect.none,
        transitionEffectSpeed: 200,

        /* Events */
        /*
        onStepChanging: function (event, currentIndex, newIndex) { return true; },
        onStepChanged: function (event, currentIndex, priorIndex) { }},
        onCanceled: function (event) { },
        onFinishing: function (event, currentIndex) { return true; },
        onFinished: function (event, currentIndex) { },
        */
        /* Labels */
        labels: {
            cancel: "Cancel",
            current: "current step:",
            pagination: "Pagination",
            finish: "Finish",
            next: "Next",
            previous: "Previous",
            loading: "Loading ..."
        }
    };

    $("#minicv").steps({
        /* Appearance */
        headerTag: "h3",
        bodyTag: "section",

        /* Behaviour */
        autoFocus: false,
        enableAllSteps: false,
        enableKeyNavigation: true,
        enablePagination: true,
        suppressPaginationOnFocus: true,
        enableContentCache: true,
        enableCancelButton: false,
        enableFinishButton: true,
        preloadContent: false,
        showFinishButtonAlways: false,
        forceMoveForward: false,
        saveState: false,
        startIndex: 0,

        /* Transition Effects */
        transitionEffect: "slide",
        transitionEffectSpeed: 200,

        /* Labels */
        labels: {
            cancel: "Annuler",
            current: "Etape :",
            pagination: "Pagination",
            finish: "Fin",
            next: "Suivant",
            previous: "Précédent",
            loading: "Chargement ..."
        }
    });
}); //end document ready
