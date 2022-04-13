jQuery(function(){

    $(".clickable-row").on('click', function() {
        window.location = $(this).data("href");
    });

    $("#reset").on("click", function () {
        $('#categorie-option').prop('selected', function() {
            return this.defaultSelected;
        });

        $('#priorite-option').prop('selected', function() {
            return this.defaultSelected;
        });

        $('#statut-option').prop('selected', function() {
            return this.defaultSelected;
        });
    });

    $('#newCommentBillet').on('click', function() {

        let token = $(this).data("token");
        let billet_id = $('#billet_id').val();
        let commentaire = $('#commentaire').val();
        let time = (new Date()).toLocaleTimeString('it-IT');
        let date = (new Date()).toISOString().slice(0, 10);

        let formData = {
            "_token": token,
            utilisateur_id: $('#utilisateur_id').val(),
            billet_id: billet_id,
            commentaire: commentaire,
        };

        $.ajax({
            url: "/billets/storeCommentaire",
            type: 'POST',
            data: formData,
            encode: true,
            success: function(response) {
                let data = JSON.parse(response);
                $('#message-toaster').text(data['message']);
                $('#commentaire').val('');
                $('#comments-billet').prepend(' <div class="comment-billet">' +
                    '                               <p class="font-weight-bold d-inline">' + data['name'] + '</p><p class="d-inline"> @ ' + date + ' ' + time + '</p>' +
                    '                               <p class="ml-3">' + commentaire + '</p>' +
                    '                           </div>');
            },
        });
    });

    $('#telephone').usPhoneFormat({
        format: 'xxx-xxx-xxxx',
    });
});
