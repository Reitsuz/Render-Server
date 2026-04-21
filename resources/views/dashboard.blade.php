<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
background:linear-gradient(135deg,#050505,#0b0b0b);
color:white;
font-family:Arial;
padding:20px;
}

/* header */

.header{
background:rgba(20,20,20,.8);
padding:20px;
border-radius:16px;
margin-bottom:20px;
box-shadow:0 0 30px rgba(0,0,0,.6);
}

.status-big{
font-size:22px;
margin-top:5px;
}

.connected{
color:#00ff9c;
}

.down{
color:#ff3b3b;
}

/* grid */

.grid{
display:flex;
flex-wrap:wrap;
gap:20px;
}

/* card */

.card{
background:rgba(20,20,20,0.7);
backdrop-filter:blur(10px);
border-radius:16px;
padding:20px;
width:260px;
box-shadow:0 10px 30px rgba(0,0,0,.6);
transition:.3s;
}

.card:hover{
transform:translateY(-5px) scale(1.02);
}

/* chart */

.chart{
width:140px;
height:140px;
margin:auto;
}

/* summary */

.summary{
display:flex;
gap:15px;
margin-top:20px;
flex-wrap:wrap;
}

.summary-card{
background:rgba(20,20,20,.7);
padding:15px;
border-radius:12px;
width:150px;
box-shadow:0 0 20px rgba(0,0,0,.4);
}

.summary-card .value{
font-size:26px;
}

/* logs */

.logs{
margin-top:20px;
}

.log{
background:#111;
padding:12px;
margin-bottom:10px;
border-radius:10px;
border-left:3px solid red;
}

</style>

</head>

<body>

<!-- top status -->

<div class="header">
<h2>RENDER　サーバー死活監視</h2>
<h2>RENDER STATUS</h2>

<div class="status-big 
{{ $renderStatus == 'CONNECTED' ? 'connected' : 'down' }}">
{{ $renderStatus }}
</div>

</div>

<!-- summary -->

<div class="summary">

<div class="summary-card">
TOTAL
<div class="value">{{ $servers->count() }}</div>
</div>

<div class="summary-card">
UP
<div class="value">{{ rand(1,5) }}</div>
</div>

<div class="summary-card">
CPU AVG
<div class="value">{{ rand(20,90) }}%</div>
</div>

<div class="summary-card">
MEM AVG
<div class="value">{{ rand(20,90) }}%</div>
</div>

</div>

<!-- servers -->

<div class="grid">

@foreach($servers as $server)

<div class="card">

<h3>{{ $server->name }}</h3>

<canvas id="cpu{{$server->id}}" class="chart"></canvas>
<canvas id="mem{{$server->id}}" class="chart"></canvas>

<p>Response {{ rand(20,300) }} ms</p>

</div>

@endforeach

</div>

<!-- logs -->

<div class="logs">

<h3>異常ログ</h3>

@foreach($incidents as $log)

<div class="log">
{{ $log->created_at }}<br>
{{ $log->message }}
</div>

@endforeach

</div>

<script>

@foreach($servers as $server)

var cpu = Math.floor(Math.random()*80)+10;
var mem = Math.floor(Math.random()*80)+10;

new Chart(document.getElementById('cpu{{$server->id}}'),{
type:'doughnut',
data:{
datasets:[{
data:[cpu,100-cpu],
backgroundColor:['#ff4d4d','#222']
}]
},
options:{cutout:'70%'}
});

new Chart(document.getElementById('mem{{$server->id}}'),{
type:'doughnut',
data:{
datasets:[{
data:[mem,100-mem],
backgroundColor:['#00d4ff','#222']
}]
},
options:{cutout:'70%'}
});

@endforeach

</script>

</body>
</html>