var information = null;

$(document).ready(function () {
    $('#telephone').usPhoneFormat({
        format: 'xxx-xxx-xxxx',
    });   
});


const showInformation = (
    titre,
    adresse,
    date,
    place,
    description,
    duree,
    lieu,
    type
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
                <p>Type: ${type}</p>
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

    $(".information").click(function() {
        var titre = $(this).data('titre');
        var adresse = $(this).data('adresse');
        var lieu = $(this).data('lieu');
        var date = $(this).data('date');
        var nb_places = $(this).data('place');
        var descrip = $(this).data('descrip');
        var type = $(this).data('type');
        var duree = $(this).data('duree');

        var newDate = moment(date, 'HH:mm').add(duree, 'm').format('HH:mm');


        if(nb_places > 100000)
        {
            nb_places = "illimité";
        }

        showInformation(titre, adresse, date, nb_places, descrip, newDate, lieu, type);
        
        
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

    $('.supprimer').click(function(){
        return confirm("Êtes-vous sûr de vouloir supprimer cet Évènement?");
    })


    $("textarea, input:text").on('input', function(e){

        while(charSpeciaux.some(char => $(this).val().startsWith(char)))
        {
            $(this).val($(this).val().substring(1));
        }
    });


});