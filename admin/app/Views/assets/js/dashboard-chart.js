new Chart(document.getElementById('mainChart'), {
  type: 'line',
  data: {
    labels: ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'],
    datasets: [
      {label:'Dons de sang',  data:[1500,1900,2400,2600,3000,2950,3500,3600,4200,4150,4600,5100], borderColor:'#c62d2d', tension:.3},
      {label:"Dons d'organes",data:[400,480,620,800,750,780,900,950,1000,880,820,860], borderColor:'#1b6b7a', tension:.3}
    ]
  }
});
