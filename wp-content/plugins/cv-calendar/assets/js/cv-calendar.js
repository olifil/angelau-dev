jQuery(function(){

  // Optenir les options depuis la base de donnée ?
  // Premier jour de la semaine à utiliser pour le calendrier
  // Les couleurs à retenir pour les évènements (disponible, loue, option, ferme)

  // Récupérer les identifiants de connexion
  $.ajax({
    url: 'http://angelau.dev/wp-content/plugins/cv-calendar/include/cv-calendar.ajax.php',
    success: function(response){
      console.log(response.data);
    },
    error: function(response){
      alert(response);
    }
  });

  $.ajax({
    url: 'http://angelau.dev/wp-json',
    success: function(response){
      console.log(response._links);
    }
  });

  // On récupère les données depuis l'API de Clé vacances
  $.ajax({
    url: 'http://api.clevacances.com/services.json?login=olivier&pass=*****&params=xxx,zzz,yyy',
    success: function(response){

    },
    error: function(response){
      console.log(response);
    }
  });


  var events = {
    title: 'essai',
    start: '2016-10-06 12:00',
    end: '2016-10-07 23:00',
    color: '#257e4a'
  };

  $('#calendar').fullCalendar({
      locale: 'fr',
      firstDay: 6,
      events: [
        events
      ]
   })
});
