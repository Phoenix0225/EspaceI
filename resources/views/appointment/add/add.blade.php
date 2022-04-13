@extends('layouts.app')
@section('script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/jquery-input-mask-phone-number.min.js') }}"></script>
<script>
  
let dates = ["{!! implode('","',$dateList) !!}"];
$(document).ready(function(){
    $("#month").change(function(){
        setCalendar();
    });

    $("#telephone").usPhoneFormat({
      format: 'xxx-xxx-xxxx',
    });

    $("#month").trigger("change");

    $("#hours-select").change(function(){
      $("#disponibilite_id").val($(this).val().split("#")[0]);
      $("#heure_debut").val($(this).val().split("#")[1]);
    });
    $('#hours-select').select2();
});

function setCalendar(){
    $(".calendar-row").remove()
    var val = $("#month").val();
    var month = val.split("-")[0];
    var year = val.split("-")[1];

    var day = new Date(year,month,1);
    //Trouver le premier jour valide du mois
    while(day.getDay() < 1 || day.getDay() > 5)
        day.setDate(day.getDate() + 1);

    let row = "<tr class='calendar-row'>";

    //Remplir les cases vide au début du calendrier
    if(day.getDay() != 1)
        for(var i = day.getDay();i!=1;i--)
        row += "<td></td>";
    var dateLimite = new Date();
    dateLimite.setDate(dateLimite.getDate()+3);
    //Ajouter les dates
    while(day.getMonth() == month){
        if(day.getDay() == 5){  //Vendredi
        //Fermer et ajouter la rangé au tableau
        if(dates.includes(year+"-"+((parseInt(month)+1)>9?'':'0')+(parseInt(month)+1)+"-"+(day.getDate()>9?'':'0')+day.getDate()) && dateLimite < day)
          row += "<td id='"+(year+"-"+(month>9 ? '' : '0') + month+"-"+(day.getDate()>9 ? '' : '0') +day.getDate())+"' class='calendar-col'>"+day.getDate()+"</td></tr>";
        else
          row += "<td id='"+(year+"-"+(month>9 ? '' : '0') + month+"-"+(day.getDate()>9 ? '' : '0') +day.getDate())+"' class='calendar-col-disable text-danger'>"+day.getDate()+"</td></tr>";

        $("#date").append(row);

        row = "<tr class='calendar-row'>";
        day.setDate(day.getDate() + 3); //Passer la fin de semaine
        }else{    //Reste de la semaine
          if(dates.includes(year+"-"+((parseInt(month)+1)>9?'':'0')+(parseInt(month)+1)+"-"+(day.getDate()>9?'':'0')+day.getDate()) && dateLimite < day)
            row += "<td id='"+(year+"-"+(month>9 ? '' : '0') + month+"-"+(day.getDate()>9 ? '' : '0') +day.getDate())+"' class='calendar-col'>"+day.getDate()+"</td>";
          else
            row += "<td id='"+(year+"-"+(month>9 ? '' : '0') + month+"-"+(day.getDate()>9 ? '' : '0') +day.getDate())+"' class='calendar-col-disable text-danger'>"+day.getDate()+"</td>";


        day.setDate(day.getDate() + 1);
        
        }
    }

    day.setDate(day.getDate() - 1);
    if(day.getDay() != 5 && day.getDay() != 0 && day.getDay() != 6){  //Ajouter et compléter la dernière semaine du mois
        for(var i = day.getDay();i<5;i++)
        row += "<td></td>";
        row += "</tr>";
        $("#date").append(row);
    }

    //Quand l'utilisateur choissis une date
    $("td").click(function(){
        var id = $(this).attr('id');
        if (typeof id !== 'undefined' && id !== false && $(this).hasClass("calendar-col")) {
            $(this).addClass("bg-primary text-light");
            if($("#time").val())
                $("#"+$("#time").val()).removeClass("bg-primary text-light");
            $("#time").val(id);
            
            $("#dateForm").submit();
        }
        
        $("#show-hours").show();
    });
   
    $("#{{$date}}").addClass('bg-primary text-light');
}
</script>
@endsection
@section('content')
<!-- event section -->
<section class="event_section layout_padding">
  <div class="container">
    <div class="heading_container">
      <h3>
        Rendez-vous
      </h3>
      <p>
        Prendre un rendez-vous celon l'horaire disponible.
      </p>
    </div>
    <form id="dateForm" action="{{route("appointment.create")}}" method="POST">
        @csrf
      <!-- Date -->
      <input type="hidden" name="category" value="{{$probleme->id}}">
      <input type="hidden" name="time" id="time">
      <label>Date<span class="text-danger">*</span>:</label>
      <div>
        <div class="w-25 mx-auto">
          <select class="form-control text-center" id="month">
            @php
              date_default_timezone_set('America/Toronto');
              //Liste des mois en français
              $monthList = array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
              //Mois actuelle
              $month = date('m');
              //L'année actuelle
              $year = date('Y');

              //Date sélectionné
              $select = strtotime($date);
              
              //Si le mois actuelle est pendant l'été, on commence a charger a partir de août
              if($month < 8 && $month > 5)
                $month = 8;

              //Ajouter les choix pour le mois a l'utilisateur
              $selectMonth = date('m',$select);
              $selectMonth = $selectMonth>11?0:$selectMonth;
              for($i = $month;$i<=17;$i++){
                if(($month-1) == $selectMonth)
                  echo "<option value=".($month-1)."-".$year." selected>".$monthList[($month-1)]." ".$year."</option>";
                else
                  echo "<option value=".($month-1)."-".$year.">".$monthList[($month-1)]." ".$year."</option>";
                $month++;
                if($month > 12){
                  $month = 1;
                  $year++;
                }
              }

            @endphp
          </select>
        </div>
        <table class="table text-center table-bordered" id="date">
          <tr>
            <th style="width:20%;">Lundi</th>
            <th style="width:20%;">Mardi</th>
            <th style="width:20%;">Mercredi</th>
            <th style="width:20%;">Jeudi</th>
            <th style="width:20%;">Vendredi</th>
          </tr>
        </table>
      </div>
    </form>
  <form action="{{route("appointment.store")}}" method="POST">
    @csrf
        <input type="hidden" name="probleme_id" value="{{$probleme->id}}">
        <input type="hidden" name="heure_debut" id="heure_debut">
        <input type="hidden" name="disponibilite_id" id="disponibilite_id">
      <!-- Sélection heures -->
      <div>
        <label>Heure<span class="text-danger">*</span>:</label>
        <select class="form-control" id="hours-select" name="hours-select" required>
          <option selected disabled>Choissir une disponibilité</option>
          @foreach($dispos as $dispo)
          <option disabled>{{$dispo->Endroit->lieu}} {{$dispo->Endroit->adresse}}</option>
            @php
              $jump = $parametre->duree_plage_horaire;
              //Charger toutes les disponibilités possible au 5 minutes
              $start = strtotime($dispo->debut);  //Date et heure de début
              while(date('i',$start)%$jump!=0){
                $start = strtotime('+1 minutes',$start);  //Ajouter 1 minutes à la date
              }
              $hours = date('G',$start);  //Heure de début
              $end = strtotime($dispo->fin);  //Date et heure de fin
              echo '<optgroup label="'.$hours.':00">';
              while($start<$end){ //Tant que la date n'est pas plus grand que la date de fin
                $rdvEnd = strtotime('+'.$probleme->duree.'minutes',$start); //Date de fin du rdv
                if($rdvEnd > $end)  //Si l'heure de la fin du rdv est plus grand que l'heure de fin fermer la boucle
                  break;
                $isEmpty = true;
                foreach ($dispo->RendezVous as $rdv) {
                  
                  $startRdv = strtotime(date('Y-m-d',$start).' '.$rdv->heure_debut);
                  $endRdv = strtotime('+'.$rdv->duree_avg.' minutes',$startRdv);
                  if(($start >= $startRdv && $start < $endRdv) || ($rdvEnd > $startRdv && $rdvEnd <= $endRdv)){
                    $isEmpty = false;
                    break;
                  }
                }
                if($isEmpty){
                  if($hours < date('G',$start)){  //Afficher la catégorie de l'heure
                    $hours++;
                    echo '</optgroup><optgroup label="'.$hours.':00">';
                  }
                  echo '<option value="'.$dispo->id.'#'.date('G:i',$start).'">'.date('G:i',$start).' à '.date('G:i',$rdvEnd).'</option>'; //Afficher l'option. La valeur a le format suivant : {Id}#{Heure debut}
                }
                $start = strtotime('+'.$jump.' minutes',$start);  //Ajouter 5 minutes à la date
              }
            @endphp
          @endforeach
        </select>

        
        <div id="problem">
          <br>
            <div class="form-group">
              <label for="nom_client">Nom<span class="text-danger">*</span>:</label>
              <input type="text" id="nom_client" name="nom_client" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="courriel">Courriel<span class="text-danger">*</span>:</label>
              <input type="email" id="courriel" name="courriel" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="telephone">Téléphone:</label>
              <input type="phone" id="telephone" name="telephone" class="form-control">
            </div>
          <!-- Description -->
          <div class="form-group">
            <label for="description_rdv">Description du problème:</label>
            <textarea name="description_rdv" id="description_rdv" class="form-control" placeholder="Ex: Je n'arrive pas à rejoindre une réunion sur Zoom depuis le navigateur."></textarea>
          </div>
        </div>
        <!-- Submit -->
        <div class="btn-box" id="submit">
          <button type="submit" class="btn btn-primary">
            Prendre ce rendez-vous
          </button>
        </div>
      </div>
    </form>
  </div>
</section>

@endsection
@section('style')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
@endsection
