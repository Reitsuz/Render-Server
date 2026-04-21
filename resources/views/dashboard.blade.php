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

.title{
font-size:28px;
margin-bottom:20px;
}

.grid{
display:flex;
flex-wrap:wrap;
gap:20px;
}

.card{
background:rgba(20,20,20,0.7);
backdrop-filter:blur(10px);
border-radius:16px;
padding:20px;
width:260px;
box-shadow:0 10px 30px rgba(0,0,0,.6);
}

.status-up{color:#00ff9c;}
.status-down{color:#ff3b3b;}

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
width:130px;
}

.summary-card .label{
font-size:12px;
color:#aaa;
}

.summary-card .value{
font-size:24px;
}

/* log */

.logs{
margin-top:20px;
}

.log{
background:#1a1a1a;
padding:12px;
margin-bottom:10px;
border-radius:10px;
border-left:3px solid #ff3b3b;
font-size:12px;
}

</style>

</head>

<body>

<div class="title">Render サーバー状況</div>

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

<!-- summary -->

<div class="summary">

<div class="summary-card">
<div class="label">TOTAL</div>
<div class="value">{{ $servers->count() }}</div>
</div>

<div class="summary-card">
<div class="label">UP</div>
<div class="value">
{{ $servers->where('is_up',true)->count() }}
</div>
</div>

<div class="summary-card">
<div class="label">DOWN</div>
<div class="value">
{{ $servers->where('is_up',false)->count() }}
</div>
</div>

<div class="summary-card">
<div class="label">INCIDENT</div>
<div class="value">
{{ $incidents->count() }}
</div>
</div>

</div>

<!-- logs -->

<div class="logs">

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

<script>

@foreach($servers as $server)

var cpu{{$server->id}} = {{ $server->cpu }} || Math.floor(Math.random()*80)+10;
var mem{{$server->id}} = {{ $server->memory }} || Math.floor(Math.random()*80)+10;

new Chart(document.getElementById('cpu{{$server->id}}'),{
type:'doughnut',
data:{
datasets:[{
data:[cpu{{$server->id}},100-cpu{{$server->id}}],
backgroundColor:['#ff4d4d','#222']
}]
},
options:{cutout:'70%'}
});

new Chart(document.getElementById('mem{{$server->id}}'),{
type:'doughnut',
data:{
datasets:[{
data:[mem{{$server->id}},100-mem{{$server->id}}],
backgroundColor:['#00d4ff','#222']
}]
},
options:{cutout:'70%'}
});

@endforeach

</script>

</body>
</html>