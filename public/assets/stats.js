/* Statistici: calculează și desenează graficele din răspunsurile chestionarului.
   Folosit de site (secțiunea Statistici) și de panoul de admin.
   Fiecare element din `rows` are forma {inStatisticaElevilor, raspunsuri:{...}}. */
(function(){
'use strict';
var reduce=!!(window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches);
function $$(s,r){return Array.prototype.slice.call((r||document).querySelectorAll(s));}
function esc(s){return String(s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}

var MODES=[['foot','Pe jos'],['bike','Bicicletă'],['public','Transport public'],['car','Mașină, cu familia sau colegi'],['taxi','Taxi'],['other','Altul']];
var OPTS={
  dist:[['sub1','Sub 1 km'],['1-3','1–3 km'],['3-5','3–5 km'],['5-10','5–10 km'],['peste10','Peste 10 km'],['nu_stiu','Nu știu']],
  time:[['sub15','Sub 15 minute'],['15-20','15–20 de minute'],['20-30','20–30 de minute'],['peste30','Peste 30 de minute']],
  why:[['special','Special pentru școală'],['ocol','Face un ocol'],['oricum','Merge oricum pe acolo'],['diferit','Situații diferite'],['nu_stiu','Nu știu']],
  shared:[['nu','Nu'],['da','Da'],['uneori','Uneori']],
  alt:[['foot','Pe jos'],['bike','Bicicletă/trotinetă electrică'],['public','Transport public'],['other','Altă variantă'],['niciuna','Niciuna acum']],
  block:[['dist','Distanța'],['timp','Timpul'],['legaturi','Legăturile de transport'],['transport','Lipsa mijloacelor de transport'],['traseu','Traseul dificil'],['orar','Orarul'],['vreme','Vremea'],['alt','Alt motiv'],['nu_raspund','Prefer să nu răspund']],
  help:[['info','Informații despre transport'],['rute','Adăugarea unor noi rute de autobuz/tramvai'],['piste','Implementarea pistelor pentru bicicletă'],['altceva','Altceva']]
};
var TAIL=['nu_stiu','nu_raspund','altceva','alt','other'];

function optMap(k){var m={};OPTS[k].forEach(function(o){m[o[0]]=o[1];});return m;}
function pct(n,d){return d?Math.round(n/d*100):0;}

function countOpts(rows,key,o){
  o=o||{};
  var map=optMap(key);
  var got=rows.filter(function(r){return r[key]!==undefined&&(!o.multi||r[key].length);});
  var cnt={}; Object.keys(map).forEach(function(k){cnt[k]=0;});
  got.forEach(function(r){(o.multi?r[key]:[r[key]]).forEach(function(v){if(v in cnt)cnt[v]++;});});
  var items=Object.keys(map).map(function(k){return {k:k,label:map[k],n:cnt[k]};});
  if(o.sort) items.sort(function(a,b){return ((TAIL.indexOf(a.k)>-1?1:0)-(TAIL.indexOf(b.k)>-1?1:0))||(b.n-a.n);});
  return {items:items,base:got.length};
}
function barsHTML(items,base){
  if(!base) return '<p class="empty">Încă nu avem răspunsuri la această întrebare.</p>';
  var max=Math.max.apply(null,items.map(function(i){return i.n;}));
  return '<ul class="bars">'+items.map(function(i){
    var p=pct(i.n,base), lead=i.n===max&&max>0;
    return '<li'+(lead?' class="lead"':'')+'><div class="b-top"><span class="b-l">'+esc(i.label)+'</span><span class="b-v">'+p+'%<span class="b-n"> · '+i.n+'</span></span></div><div class="b-track"><div class="b-fill" style="width:'+p+'%"></div></div></li>';
  }).join('')+'</ul>';
}
function panel(title,sub,inner,wide){
  return '<section class="panel'+(wide?' wide':'')+'"><h3>'+title+'</h3><p class="p-sub">'+sub+'</p>'+inner+'</section>';
}
function routeTop(rows,min){
  var per=rows.map(function(r){return String(r.route||'').trim();}).filter(Boolean);
  var cnt={};
  per.forEach(function(t){
    var seen={};
    t.split(/\s+[-–—]\s+|[–—,;→>\n\/]+/).map(function(x){return x.trim().replace(/\s+/g,' ');}).filter(function(x){return x.length>=3;}).forEach(function(x){
      var k=x.toLowerCase();
      if(seen[k]) return; seen[k]=1;
      if(!cnt[k]) cnt[k]={label:x,n:0};
      cnt[k].n++;
    });
  });
  var items=Object.keys(cnt).map(function(k){return cnt[k];}).filter(function(e){return e.n>=min;}).sort(function(a,b){return b.n-a.n;}).slice(0,8).map(function(e){return {k:e.label,label:e.label,n:e.n};});
  return {items:items,base:per.length};
}
function animateBars(body){
  if(reduce||!('IntersectionObserver' in window)) return;
  var bars=$$('.b-fill',body);
  var io=new IntersectionObserver(function(es){es.forEach(function(e){
    if(!e.isIntersecting) return;
    $$('.b-fill',e.target).forEach(function(b){b.classList.remove('pre');}); io.unobserve(e.target);
  });},{threshold:.15});
  $$('.panel',body).forEach(function(p){$$('.b-fill',p).forEach(function(b){b.classList.add('pre');}); io.observe(p);});
  setTimeout(function(){bars.forEach(function(b){b.classList.remove('pre');});},6000);
}

/* o: {body, note, rows, full?, routeMin?, sample?} */
function render(o){
  var body=o.body, note=o.note;
  if(note){
    note.hidden=!o.sample;
    if(o.sample) note.innerHTML='<b>Date de exemplu</b><span>Cifrele de mai jos sunt simulate, ca să se vadă cum arată pagina. Pe site-ul publicat se înlocuiesc cu răspunsurile reale ale elevilor.</span>';
  }
  var rows=(o.rows||[]).filter(function(r){return r.inStatisticaElevilor;}).map(function(r){return r.raspunsuri;});
  if(!rows.length){ body.innerHTML='<p class="empty" style="margin-top:32px">Încă nu avem răspunsuri. Statisticile apar după primele chestionare completate.</p>'; return; }
  var moving=rows.filter(function(r){return r.days>0&&r.modes;});
  var car=moving.filter(function(r){return r.modes.car>0;});
  var pub=moving.filter(function(r){return r.modes.public>0;});

  var help=countOpts(rows.filter(function(r){return r.days>0&&r.modes;}),'help',{multi:true,sort:true});
  var topHelp=help.items[0];
  var withTime=rows.filter(function(r){return r.time!==undefined;});
  var shortTrip=withTime.filter(function(r){return r.time==='sub15';}).length;

  var kpis='<div class="kpis">';
  kpis+='<div class="kpi"><span class="k-l">Cel mai cerut</span><strong class="k-v acc">'+(help.base?esc(topHelp.label):'—')+'</strong><span class="k-s">'+(help.base?'alese de '+pct(topHelp.n,help.base)+'% dintre elevii care au răspuns':'Încă nu avem răspunsuri.')+'</span></div>';
  kpis+='<div class="kpi"><span class="k-l">Drum scurt</span><strong class="k-v">'+(withTime.length?pct(shortTrip,withTime.length)+'<small>%</small>':'—')+'</strong><span class="k-s">fac sub 15 minute până la școală'+(withTime.length?' ('+withTime.length+' elevi)':'')+'</span></div>';
  kpis+='<div class="kpi"><span class="k-l">Autobuz și mașină</span><div class="k-duo"><div><strong class="k-v">'+pub.length+'</strong><span>elevi cu transport public ('+pct(pub.length,moving.length)+'%)</span></div><div><strong class="k-v">'+car.length+'</strong><span>elevi cu mașina ('+pct(car.length,moving.length)+'%)</span></div></div><span class="k-s">dintre '+moving.length+' elevi, cel puțin o zi pe săptămână</span></div>';
  kpis+='</div>';

  var full=!!o.full;   // panoul de admin: toate graficele; site-ul public: doar cele de impact
  var P=[];
  P.push(panel('Ce i-ar ajuta pe elevi','Elevii care au răspuns, mai multe răspunsuri posibile · '+help.base+' elevi',barsHTML(help.items,help.base)));

  var tm=countOpts(withTime,'time');
  P.push(panel('Cât durează drumul până la școală','Estimare, într-un singur sens · '+tm.base+' elevi',barsHTML(tm.items,tm.base)+'<p class="p-foot">Elevii care fac sub 15 minute încheie chestionarul după această întrebare.</p>'));

  var modeItems=MODES.map(function(m){return {k:m[0],label:m[1],n:moving.filter(function(r){return r.modes[m[0]]>0;}).length};}).sort(function(a,b){return b.n-a.n;});
  var totalDays=moving.reduce(function(s,r){return s+r.days;},0);
  function dayShare(k){return pct(moving.reduce(function(s,r){return s+r.modes[k];},0),totalDays);}
  P.push(panel('Cum ajung elevii la școală','Elevi care au folosit mijlocul de transport cel puțin o zi. Un elev poate apărea la mai multe · '+moving.length+' elevi',
    barsHTML(modeItems,moving.length)+'<p class="p-foot">Din cele '+totalDays+' dimineți raportate: <strong>'+dayShare('car')+'%</strong> cu mașina, <strong>'+dayShare('public')+'%</strong> cu transport public, <strong>'+dayShare('foot')+'%</strong> pe jos.</p>'));

  var alt=countOpts(car,'alt',{sort:true});
  var withRep=car.filter(function(r){return typeof r.replace==='number';});
  var sumRep=withRep.reduce(function(s,r){return s+r.replace;},0), sumCar=withRep.reduce(function(s,r){return s+r.modes.car;},0);
  var bigLine=withRep.length?'<p class="big-line"><strong>'+pct(sumRep,sumCar)+'%</strong><span>din diminețile cu mașina ar putea fi înlocuite, într-un scenariu pentru același program</span></p>':'';
  P.push(panel('Ce alternativă ar încerca','Elevii care vin cu mașina · '+alt.base+' elevi',bigLine+barsHTML(alt.items,alt.base)));

  if(full){
    var blk=countOpts(car,'block',{multi:true,sort:true});
    P.push(panel('Ce îi împiedică cel mai mult','Cel mult două motive per elev · '+blk.base+' elevi',barsHTML(blk.items,blk.base)));
    var dist=countOpts(moving,'dist');
    P.push(panel('Cât de lung e drumul','Într-un singur sens · '+dist.base+' elevi',barsHTML(dist.items,dist.base)));
    var why=countOpts(car,'why',{sort:true});
    P.push(panel('De ce face șoferul drumul','Elevii care vin cu mașina · '+why.base+' elevi',barsHTML(why.items,why.base)));
    var sh=countOpts(car,'shared');
    P.push(panel('Mai vin și alți elevi în mașină?','Elevii care vin cu mașina · '+sh.base+' elevi',barsHTML(sh.items,sh.base)));
    var rt=routeTop(rows,o.routeMin||1);
    P.push(panel('Străzi și zone, așa cum le-au scris elevii','Toate mențiunile, chiar și cele făcute o singură dată · '+rt.base+' trasee',barsHTML(rt.items,rt.base),true));
  }

  body.innerHTML=kpis+'<div class="panels">'+P.join('')+'</div>';
  animateBars(body);
}
function loading(body){ body.innerHTML='<p class="empty" style="margin-top:32px">Se încarcă statisticile…</p>'; }
function error(body,retry){
  body.innerHTML='<p class="empty" style="margin-top:32px">Nu am putut încărca statisticile. <button type="button" class="btn btn-ghost btn-small" data-retry>Încearcă din nou</button></p>';
  var b=body.querySelector('[data-retry]'); if(b) b.addEventListener('click',retry);
}
window.AmprentaStats={render:render,loading:loading,error:error};
})();
