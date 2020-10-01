var live_attend = parseInt(document.getElementById('live_session').value);
var ab_live_attend = parseInt(document.getElementById('ab_totlive').value);
var assignment = parseInt(document.getElementById('assignment').value);
var ab_totassignment = parseInt(document.getElementById('ab_totassignment').value);
var submiteedexam = parseInt(document.getElementById('submiteedexam').value);
var notsubmittedexam = parseInt(document.getElementById('notsubmittedexam').value);
//alert(ab_totassignment)
// Live chart 
var options = {
  chart: {
    height: 300,
    type: "pie"
  },
  colors: ["#b1f86b", "#fe6b6b"],
  legend: {
    position: 'top',
    horizontalAlign: 'center'
  },
  stroke: {
    colors: ['rgba(0,0,0,0)']
  },
  dataLabels: {
    enabled: false
  },
  series: [live_attend, ab_live_attend],
 // series: [24, 8],
  labels: ["Attendance", "Absents"]
};

var chart = new ApexCharts(document.querySelector("#live"), options);

chart.render();  
// Live chart end

// assignment Chart
var options = {
  chart: {
    height: 300,
    type: "pie"
  },
  colors: ["#7ee5e5", "#f77eb9"],
  legend: {
    position: 'top',
    horizontalAlign: 'center'
  },
  stroke: {
    colors: ['rgba(0,0,0,0)']
  },
  dataLabels: {
    enabled: false
  },
  series: [assignment, ab_totassignment],
 // series: [16, 6],
  labels: ["Submited", "Not Submited"]
};

var chart = new ApexCharts(document.querySelector("#assignmentchart"), options);

chart.render();  
// assignment chart end

// Exam Chart
var options = {
  chart: {
    height: 300,
    type: "pie"
  },
  colors: ["#6bfee4", "#fe6b6b"],
  legend: {
    position: 'top',
    horizontalAlign: 'center'
  },
  stroke: {
    colors: ['rgba(0,0,0,0)']
  },
  dataLabels: {
    enabled: false
  },
 series: [submiteedexam, notsubmittedexam],
 // series: [30, 6],
  labels: ["Submitted", "Not submitted"]
};

var chart = new ApexCharts(document.querySelector("#exam"), options);

chart.render();  
// Exam chart end