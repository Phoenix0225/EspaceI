$(function ()
{

    // Si l'utilisateur clique sur la checkbox récurent
    // Si coché afiche la date de fin de la récurence
    // Si décocher, il disabled la date de fin et la clear
    $("#is_recur").click(function ()
    {
        if ($(this).is(":checked"))
        {
            $("#form_disp").removeAttr("action");
            $("#form_disp").attr("action", "http://127.0.0.1:8000/disponibilites/store_recur");

            $("#date_max").removeAttr("disabled");
            $("#date_max").focus();
        }
        else
        {
            $("#form_disp").removeAttr("action");
            $("#form_disp").attr("action", "http://127.0.0.1:8000/disponibilites/store");

            $("#date_max").attr("disabled", "disabled");
            $('#date_max').val('');
        }
    });

    //TODO : Ajouter une validation pour la date de fin

    // Lorsque l'utilisateur clique sur la case de la date de début, la date de fin se vide
    $('#debut').click(function (){
        $('#fin').val('');
    });

    $('#debut').on("change", function(){
        $("#fin").attr("min", $('#debut').val());
        $("#fin").attr("max", $('#debut').val().split('T')[0] + "T17:45:00");
    });
});
