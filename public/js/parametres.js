jQuery(function(){

    $idType    = 1;
    $idCateg   = 1;
    $idEvent   = 1;
    $idEndroit = 1;

    /*
     * Affichage des sections
     */

    $(".table-settings-options").on("click",
        function() {
            $("#form-settings").children().hide();
        }
    );

    $("#settings-horaire-btn").on("click",
        function() {
            $("#settings-horaire").show();
        }
    );

    $("#settings-rendez-vous-btn").on("click",
        function() {
            $("#settings-rendez-vous").show();
        }
    );

    $("#settings-accueil-btn").on("click",
        function() {
            $("#settings-accueil").show();
        }
    );

    $("#settings-dashboard-btn").on("click",
        function() {
            $("#settings-dashboard").show();
        }
    );

    $("#settings-tutoriel-btn").on("click",
        function() {
            $("#settings-tutoriel").show();
        }
    );

    $("#settings-billets-btn").on("click",
        function() {
            $("#settings-billets").show();
        }
    );

    $("#settings-event-btn").on("click",
        function() {
            $("#settings-event").show();
        }
    );

    $("#settings-endroit-btn").on("click",
        function() {
            $("#settings-endroit").show();
        }
    );

    $("#settings-autres-btn").on("click",
        function() {
            $("#settings-autres").show();
        }
    );

    /*
     * Ajout des inputs dans les tableaux pour l'ajout d'éléments dans les tables
     */

    $('#add-type-billet').on('click', function(e){
        if($idType < 100) {
            $("<tr><td>Nouveau type: <input type='text' name='categorie" + $idType + "' minlength=\"1\" maxlength=\"75\" required></td></tr>").insertBefore('#row-add-type');
            $idType++;
        }
    });

    $('#add-rdv_categ').on('click', function(e){
        if($idCateg < 100)
        {
            $("<tr><td>Nouveau type: <input type='text' name='probleme" + $idCateg + "' minlength=\"1\" maxlength=\"75\" required></td>" +
                "<td>Durée (minute): <input type='number' min='1' name='duree" + $idCateg + "' required></td>" +
                "</tr>").insertBefore('#row-add-probleme');
            $idCateg++;
        }
    });

    $('#add-type-event').on('click', function(e){
        if($idEvent < 100) {
            $("<tr><td>Nouveau type: <input type='text' name='event" + $idEvent + "' minlength=\"1\" maxlength=\"75\" required></td></tr>").insertBefore('#row-add-event');
            $idEvent++;
        }
    });

    $('#add-endroit').on('click', function(e){
        if($idEndroit < 100)
        {
            $("<tr><td>Adresse : <input type='text' name='adresse" + $idEndroit + "'></td>" +
                "<td>Lieu : <input type='text' name='lieu" + $idEndroit + "' minlength=\"1\" maxlength=\"75\" required></td>" +
                "</tr>").insertBefore('#row-add-endroit');
            $idEndroit++;
        }
    });

    /*
     * Couleur et affichage de la toast
     */

    function removeClassesToast() {
        $('.toast').removeClass('bg-success').removeClass('bg-danger');
        $('.toast-header').removeClass('bg-success').removeClass('bg-danger');
        $('.icon-toast').removeClass('bi-check-circle').removeClass('bi-exclamation-circle');
    }

    function addClassesSuccessToast() {
        $('.toast').addClass('bg-success');
        $('.toast-header').addClass('bg-success');
        $('.icon-toast').addClass('bi-check-circle');
        $('.titre-toast').text('Succès');
    }

    function addClassesErrorToast() {
        $('.toast').addClass('bg-danger');
        $('.toast-header').addClass('bg-danger');
        $('.icon-toast').addClass('bi-exclamation-circle');
        $('.titre-toast').text('Erreur');
    }

    /*
     * Suppression en AJAX
     */

    $('.supprimer-type-billet').on('click', function(e){
        if(!confirm("Êtes-vous certain(e) de vouloir supprimer ce type?")){
            e.preventDefault();
        }
    });

    $('.supprimer-probleme').on('click', function(e){
        if(!confirm("Êtes-vous certain(e) de vouloir supprimer ce type de rendez-vous?")) {
            return;
        }

        let ceci = $(this);
        let id = $(this).data("id");
        let token = $(this).data("token");

        $('.toast').removeClass('bg-success').removeClass('bg-danger');
        $('.toast-header').removeClass('bg-success').removeClass('bg-danger');
        $('.icon-toast').removeClass('bi-check-circle').removeClass('bi-exclamation-circle');

        $.ajax(
            {
                url: "/probleme/destroy/"+id,
                type: 'DELETE',
                data: {
                    "id": id,
                    "_method": 'DELETE',
                    "_token": token,
                      },
                success: function(response) {
                    removeClassesToast();
                    addClassesSuccessToast();
                    $('#message-toaster').text(response);
                    ceci.closest(".parent").remove();
                },
                error: function() {
                    removeClassesToast();
                    addClassesErrorToast();
                    $('#message-toaster').text('Erreur dans la suppression.');
                },
                complete: function() {
                    $('.toast').toast('show');
                }
            });
    });

    $('.supprimer-type-event').on('click', function(e){
        if(!confirm("Êtes-vous certain(e) de vouloir supprimer ce type d'événement'?")) {
            return;
        }

        let ceci = $(this);
        let id = $(this).data("id");
        let token = $(this).data("token");

        $('.toast').removeClass('bg-success').removeClass('bg-danger');
        $('.toast-header').removeClass('bg-success').removeClass('bg-danger');
        $('.icon-toast').removeClass('bi-check-circle').removeClass('bi-exclamation-circle');

        $.ajax(
            {
                url: "/type_event/destroy/"+id,
                type: 'DELETE',
                data: {
                    "id": id,
                    "_method": 'DELETE',
                    "_token": token,
                },
                success: function(response) {
                    removeClassesToast();
                    addClassesSuccessToast();
                    $('#message-toaster').text(response);
                    ceci.closest(".parent").remove();
                },
                error: function() {
                    removeClassesToast();
                    addClassesErrorToast();
                    $('#message-toaster').text('Erreur dans la suppression.');
                },
                complete: function() {
                    $('.toast').toast('show');
                }
            });
    });

    $('.supprimer-endroit').on('click', function(e){
        if(!confirm("Êtes-vous certain(e) de vouloir supprimer cet endroit'?")) {
            return;
        }

        let ceci = $(this);
        let id = $(this).data("id");
        let token = $(this).data("token");

        $('.toast').removeClass('bg-success').removeClass('bg-danger');
        $('.toast-header').removeClass('bg-success').removeClass('bg-danger');
        $('.icon-toast').removeClass('bi-check-circle').removeClass('bi-exclamation-circle');

        $.ajax(
            {
                url: "/endroit/destroy/"+id,
                type: 'DELETE',
                data: {
                    "id": id,
                    "_method": 'DELETE',
                    "_token": token,
                },
                success: function(response) {
                    removeClassesToast();
                    addClassesSuccessToast();
                    $('#message-toaster').text(response);
                    ceci.closest(".parent").remove();
                },
                error: function() {
                    removeClassesToast();
                    addClassesErrorToast();
                    $('#message-toaster').text('Erreur dans la suppression.');
                },
                complete: function() {
                    $('.toast').toast('show');
                }
            });
    });
});
