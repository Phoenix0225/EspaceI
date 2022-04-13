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
