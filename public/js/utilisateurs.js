/*
Validation à faire pour l'utilisateur

    Les deux champs mot de passe sont identiques
    Valider que c'est un adresse courriel du Cégep
        @edu.cegeptr.qc.ca
        @cegeptr.qc.ca
 */

$(document).ready(function () {
    $('#telephone').usPhoneFormat({
        format: 'xxx-xxx-xxxx',
    });
});
