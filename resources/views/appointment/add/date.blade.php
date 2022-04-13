@extends('layouts.app')
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
    <input type="hidden" name="category" id="category" value="{{$probleme->id}}">
      <input type="hidden" name="time" id="time" hidden required>
      <label>Date<span class="text-danger">*</span>:</label>
      <div>
        <div class="w-25 mx-auto">
          <select class="form-control text-center" id="month">
            @php
              //Liste des mois en français
              $monthList = array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
              //Mois actuelle
              $month = date('m');
              //L'année actuelle
              $year = date('Y');
              
              //Si le mois actuelle est pendant l'été, on commence a charger a partir de août
              if($month < 8 && $month > 5)
                $month = 8;

              //Ajouter les choix pour le mois a l'utilisateur
              for($i = $month;$i<=17;$i++){
                
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
  </div>
</section>
@endsection

@section('script')
<script>
  let dates = ["{!! implode('","',$dates) !!}"];

  $(document).ready(function(){
      $("#month").change(function(){
          setCalendar();
      });

      $("#month").trigger("change");
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

  }
</script>
@endsection