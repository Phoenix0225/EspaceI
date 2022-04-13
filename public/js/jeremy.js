//Ouverture du pop-up
var modalWrap = null;
var information = null;
const showModal = (
    nom_event, href, token
) => {

    if (modalWrap !== null){
        modalWrap.remove();
    }


    modalWrap = document.createElement('div');
    modalWrap.innerHTML = `
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">S'inscrire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPush">
                ${token}
                    <input class="modal-input" type="text" name="evenement" value="${nom_event}" readonly>
                    <br>
                    <input class="modal-input" type="text" name="nom" id="nom" placeholder="Nom" required>
                    <br>
                    <input class="modal-input" type="text" name="prenom" id="prenom" placeholder="Prenom" required>
                    <br>
                    <input id="phone" class="modal-input phone" type="text" name="telephone" id="telephone" placeholder="# Téléphone">
                    <br>
                    <input class="modal-input" type="email" name="courriel" placeholder="Adresse courriel" required>
                    <button id="ajaxSubmit" type="submit" class="btn btn-form btn-primary btn-sm" data-dismiss="">Participer</button>
                    </form>
            </div>
            </div>
        </div>
</div>
    
    
    
    `;

    document.body.append(modalWrap);

    var modal = new bootstrap.Modal(modalWrap.querySelector('.modal'), {
        backdrop: 'static'
    }); 
    modal.show();

}

const showInformation = (
    titre,
    adresse,
    date,
    place,
    description,
    duree,
    lieu
) => {

    if (information !== null){
        information.remove();
    }

    information = document.createElement('div');
    information.innerHTML = `
    <div class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document"=>
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Information évènement ${titre}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>${adresse} au ${lieu}</p>
                <p>Heure de début ${date} et se termine à ${duree}</p>
                <p>Place disponible: <strong>${place}</strong></p>
                <p>${description}</p>
            </div>
            </div>
        </div>
</div>
    
    
    
    `;
    document.body.append(information);
    var modal = new bootstrap.Modal(information.querySelector('.modal'), {
    }); 
    modal.show();

}

jQuery(function(){

    var charSpeciaux = [',', '\\', '^', '$', '.', '|', '!', '*', '+', '(', ')', '=', '_', '-', ' ', '&', '[', ']', '{', '}', '\'', '%', '@'];

    $("#btnAjoutEndroit").click(function(){

        $("#ajoutEndroit").toggle();
        $("#endroit_id").toggle();

        if( $("#btnAjoutEndroit").text() == "+") {
            $("#btnAjoutEndroit").html("-");
        }
        else {
            $("#btnAjoutEndroit").html("+");
            $("#nouvelAdresse").val("");
            $("#nouvelEndroit").val("");
        }

    });



    $("#btnAjoutTypeEvent").click(function(){

        $("#ajoutTypeEvent").toggle();
        $("#types_evenement_id").toggle();

        if( $("#btnAjoutTypeEvent").text() == "+") {
            $("#btnAjoutTypeEvent").html("-");
        }
        else {
            $("#btnAjoutTypeEvent").html("+");
            $("#nouvelEvent").val("");
        }

    });


    $(".information").click(function() {
        var titre = $(this).data('titre');
        var adresse = $(this).data('adresse');
        var lieu = $(this).data('lieu');
        var date = $(this).data('date');
        var nb_places = $(this).data('place');
        var descrip = $(this).data('descrip');
        var duree = $(this).data('duree');

        var newDate = moment(date, 'HH:mm').add(duree, 'm').format('HH:mm');


        if(nb_places > 100000)
        {
            nb_places = "illimité";
        }

        showInformation(titre, adresse, date, nb_places, descrip, newDate, lieu);
        
        
    });


    $(".participer").click(function(){

        var id = $(this).data('id');
        var titre = $(this).data('titre');

        var modal = $('#formPush');

        $('#id').val(id);
        $('#titre').val(titre);
        $('#nom').val("");
        $('#prenom').val("");
        $('#telephone').val("");
        $('#courriel').val("");


            $('#formPush').modal({
                focus: true,
                backdrop: 'static'
              })
            modal.show();

    });


    $("textarea, input:text").on('input', function(e){

        while(charSpeciaux.some(char => $(this).val().startsWith(char)))
        {
            $(this).val($(this).val().substring(1));
        }
    });

    $('.supprimer').click(function(){
        return confirm("Êtes-vous sûr de vouloir supprimer cet Évènement?");
    })

    $('.supprimer-inscription').click(function(){
        return confirm("Êtes-vous sûr de vouloir supprimer ce participant?");
    })


    $(document).ready(function(){
        $("#search").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#table tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });





});


$(document).ready(function () {
    $('#telephone').usPhoneFormat({
        format: 'xxx-xxx-xxxx',
    });   
});



