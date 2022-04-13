jQuery(function(){
    var charSpeciaux = [',', '\\', '^', '$', '.', '|', '!', '*', '+', '(', ')', '=', '_', '-', ' ', '&', '[', ']', '{', '}', '\'', '%', '@'];

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

    ///Ouverture des réponses lors du click des questions de la FAQ
    $(".question").on("click",
        function() {
            $(this).children(".reponse").toggle();

            if($(this).children(".reponse").is(":visible")) {
                $(this).find("i").addClass('bi-caret-up-fill').removeClass('bi-caret-down-fill');
            }
            else {
                $(this).find("i").addClass('bi-caret-down-fill').removeClass('bi-caret-up-fill');
            }
        }
    );

    ///Ouvre le champ pour créer des groupes de questions de la FAQ
    $("#ajout-groupe-faq").on("click",
        function() {

            $("#faq_groupe_id").toggle();
            $("#nouveauType").toggle();

            if($(this).text() === "+") {
                $("#ajout-groupe-faq").html("-");
            }
            else {
                $(this).html("+");
                $("#nouvelleCategorie").val("");
            }
        }
    );

    //TODO Ajouter cette fonction a toutes les pages
    //Trim tout les caractères indésirables au début du input
    $("textarea, input:text").on('input', function(e){

        while(charSpeciaux.some(char => $(this).val().startsWith(char)))
        {
            $(this).val($(this).val().substring(1));
        }
    });

    $('.supprimer-faq').on('click', function(e){
        if(!confirm("Êtes-vous certain(e) de vouloir supprimer cet entrée?")){
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
            url: "/faq/destroy/"+id,
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
                ceci.closest(".delete-parent").remove();
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

    $('.supprimer-faq-groupe').on('click', function(e){
        if(!confirm("Êtes-vous certain(e) de vouloir supprimer cet entrée?")){
            return;
        }

        let ceci = $(this);
        let id = $(this).data("id");
        let token = $(this).data("token");

        $.ajax(
            {
                url: "/faqGroupe/destroy/"+id,
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
                    ceci.closest(".delete-parent").remove();
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

    $('#create-faq').on('click', function() {

        let token = $(this).data("token");

        let formData = {
            "_token": token,
            faq_groupe_id: $("#faq_groupe_id").val(),
            nomGroupeFaq: $("#nouvelleCategorie").val(),
            question: $("#question").val(),
            reponse: $("#reponse").val(),
        };

        $.ajax({
            url: "/faq/store",
            type: 'POST',
            data: formData,
            encode: true,
            success: function(response) {
                removeClassesToast();
                let data = JSON.parse(response);
                if(data['type'] === 'success'){
                    addClassesSuccessToast();
                    $('#message-toaster').text(data['message']);
                    $('#nouvelleCategorie').val('');
                    $('#question').val('');
                    $('#reponse').val('');
                }
                else {
                    addClassesErrorToast();
                    let errors = '';
                    for (value in data['message']) {
                        if (data['message'].hasOwnProperty(value)) {
                            errors += '<i class="bi bi-exclamation-triangle"></i>' + data['message'][value] + '<br>';
                        }
                    }
                    console.log(errors);
                    $('#message-toaster').html(errors);
                }
            },
            error: function() {
                removeClassesToast();
                addClassesErrorToast();
                $('#message-toaster').text('Erreur dans l\'ajout.');
            },
            complete: function() {
                $('.toast').toast('show');
            }
        });
    });
});
