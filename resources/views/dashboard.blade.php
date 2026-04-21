<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
background:linear-gradient(135deg,#050505,#0d0d0d);
color:white;
font-family:Arial;
padding:20px;
}

/* layout */

.layout{
display:flex;
}

/* left */

.left{
flex:1;
}

.title{
font-size:28px;
margin-bottom:20px;
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
transform:translateY(-5px);
}

/* status */

.status-up{
color:#00ff9c;
}

.status-down{
color:#ff3b3b;
}

/* chart */

.chart{
width:120px;
margin:auto;
}

/* right log panel */

.right{
width:320px;
margin-left:20px;
background:#111;
padding:20px;
border-radius:16px;
height:85vh;
overflow:auto;
}

/* log */

.log{
background:#1a1a1a;
padding:12px;
margin-bottom:10px;
border-radius:10px;
border-left:3px solid #ff3b3b;
font-size:12px;
animation:fade .4s ease;
}

@keyframes fade{
from{opacity:0;transform:translateY(10px);}
to{opacity:1;}
}

</style>

</head>

<body>

<div class="layout">

<div class="left">

<div class="title">Render Monitor</div>

<div class="grid">

@foreach($servers as $server)

<div class="card">

<h3>{{ $server->name }}</h3>

<p class="{{ $server->is_up ? 'status-up' : 'status-down' }}">
{{ $server->is_up ? '● UP' : '● DOWN' }}
</p>

<canvas id="cpu{{$server->id}}" class="chart"></canvas>
<canvas id="mem{{$server->id}}" class="chart"></canvas>

<p>Response: {{ $server->response_time }} ms</p>

</div>

@endforeach

</div>
</div>

<div class="right">

<h3>異常ログ</h3>

@foreach($incidents as $log)

<div class="log">
<div>{{ $log->created_at }}</div>
<div>{{ $log->message }}</div>
<div>CPU {{ $log->cpu }}%</div>
<div>MEM {{ $log->memory }}%</div>
</div>

@endforeach

</div>

</div>

<script>

@foreach($servers as $server)

// CPU chart
new Chart(document.getElementById('cpu{{$server->id}}'),{
type:'doughnut',
data:{
datasets:[{
data:[{{$server->cpu}},100-{{$server->cpu}}],
backgroundColor:['#ff4d4d','#222'],
borderWidth:0
}]
},
options:{
plugins:{legend:{display:false}},
cutout:'70%'
}
});

// memory chart
new Chart(document.getElementById('mem{{$server->id}}'),{
type:'doughnut',
data:{
datasets:[{
data:[{{$server->memory}},100-{{$server->memory}}],
backgroundColor:['#00d4ff','#222'],
borderWidth:0
}]
},
options:{
plugins:{legend:{display:false}},
cutout:'70%'
}
});

@endforeach

</script>

</body>
</html>