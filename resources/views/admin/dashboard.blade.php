@extends('admin.layout', ['title' => 'Panou', 'active' => 'dash'])

@section('content')
<h1>Panou de control</h1>

<div class="tiles">
  <div class="tile"><span>Răspunsuri primite</span><strong>{{ $total }}</strong></div>
  <div class="tile"><span>Elevi în statistică</span><strong>{{ $students }}</strong></div>
  <div class="tile"><span>Au venit cu mașina (complet)</span><strong>{{ $byStatus['complete'] }}</strong></div>
  <div class="tile"><span>Drum sub 15 minute</span><strong>{{ $byStatus['short_trip'] }}</strong></div>
  <div class="tile"><span>Nu sunt elevi/studenți</span><strong>{{ $byStatus['not_student'] }}</strong></div>
  <div class="tile"><span>0 zile la școală</span><strong>{{ $byStatus['zero_days'] }}</strong></div>
  <div class="tile"><span>Ultimele 24 de ore</span><strong>{{ $last24 }}</strong></div>
</div>
<p class="muted">Ultimul răspuns: {{ $lastAt ? \Illuminate\Support\Carbon::parse($lastAt)->format('d.m.Y H:i') : 'încă niciunul' }}</p>

<section class="a-sec">
  <h2>Răspunsuri pe zile</h2>
  <p class="muted">Ultimele 14 zile</p>
  <ul class="bars">
    @foreach ($days as $d => $c)
      <li @class(['lead' => $c === $maxDay && $c > 0])>
        <div class="b-top"><span class="b-l">{{ $d }}</span><span class="b-v">{{ $c }}</span></div>
        <div class="b-track"><div class="b-fill" style="width:{{ round($c / $maxDay * 100) }}%"></div></div>
      </li>
    @endforeach
  </ul>
</section>

<section class="a-sec">
  <h2>Statistici</h2>
  <p class="muted">Calculate din răspunsurile elevilor. Aici apar toate graficele, inclusiv cele pe care site-ul public nu le arată.</p>
  <div id="stBody"></div>
</section>

<section class="a-sec">
  <h2>Harta traseelor</h2>
  <p class="muted">Zonele menționate în trasee, chiar și o singură dată. Pe site apar doar cele menționate de cel puțin 2 elevi.</p>
  <div id="mapBody" class="mp-body"></div>
</section>

<section class="a-sec">
  <h2>Ce îi deranjează dimineața</h2>
  @if ($bothers->isEmpty())
    <p class="muted">Încă nu avem răspunsuri.</p>
  @else
    <ul class="comments">
      @foreach ($bothers as $c)
        <li><span class="muted">{{ $c->created_at->format('d.m.Y H:i') }}</span><p>{{ $c->bother }}</p></li>
      @endforeach
    </ul>
  @endif
</section>

<section class="a-sec">
  <h2>Ultimele comentarii</h2>
  @if ($comments->isEmpty())
    <p class="muted">Încă nu avem comentarii.</p>
  @else
    <ul class="comments">
      @foreach ($comments as $c)
        <li><span class="muted">{{ $c->created_at->format('d.m.Y H:i') }}</span><p>{{ $c->comment }}</p></li>
      @endforeach
    </ul>
  @endif
</section>
@endsection

@push('scripts')
<script src="/assets/stats.js"></script>
<script src="/assets/map.js"></script>
<script>
(function(){
  var body=document.getElementById('stBody'), mapBody=document.getElementById('mapBody');
  function load(){
    AmprentaStats.loading(body);
    fetch('{{ route('admin.data') }}',{credentials:'same-origin',headers:{'Accept':'application/json'}})
      .then(function(r){ if(r.status===401){ location.href='{{ route('admin.login') }}'; throw new Error('auth'); } if(!r.ok) throw new Error('http'); return r.json(); })
      .then(function(d){ AmprentaStats.render({body:body,rows:d.responses,full:true,routeMin:1}); AmprentaMap.mount(mapBody,{zones:d.zones||[]}); })
      .catch(function(){ AmprentaStats.error(body,load); });
  }
  load();
})();
</script>
@endpush
