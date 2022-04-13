
var today = new Date();

var min = today.getMinutes();
var hh = today.getHours();
var dd = today.getDate();
var mm = today.getMonth() + 1; //January is 0!
var yyyy = today.getFullYear();

if (dd < 10) {
   dd = '0' + dd;
}

if (mm < 10) {
   mm = '0' + mm;
} 

if (hh < 10) {
    hh = '0' + hh;
}

if (min < 10) {
    min = '0' + min;
}
    
today = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;
document.getElementById("date_heure").min = today;





