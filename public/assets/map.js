/* Harta Iașului cu zonele din traseele elevilor, colorate de la verde (rar) la roșu (aglomerat).
   Hartă proprie, fără biblioteci externe: dale de hartă (OpenStreetMap / CARTO) + cercuri desenate peste ele.
   Folosită de site și de panoul de admin.
   AmprentaMap.mount(element, {zones:[{id,name,lat,lng,r,n}], base:numărDeTrasee, note:'text opțional'}) */
(function(){
'use strict';
var TILE=256, MINZ=11, MAXZ=17, START_Z=12, CENTER={lat:47.1625,lng:27.5875};
var BOUNDS={s:47.04,n:47.29,w:27.36,e:27.82};
var reduce=!!(window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches);
var SVGNS='http://www.w3.org/2000/svg';

function esc(s){return String(s).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
function toPx(lat,lng,z){
  var s=TILE*Math.pow(2,z), sl=Math.sin(lat*Math.PI/180);
  return {x:(lng+180)/360*s, y:(0.5-Math.log((1+sl)/(1-sl))/(4*Math.PI))*s};
}
function clamp(v,a,b){return Math.max(a,Math.min(b,v));}
function isDark(){
  var t=document.documentElement.getAttribute('data-theme');
  if(t==='dark') return true; if(t==='light') return false;
  return !!(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches);
}
/* Culoarea unei zone: t=0 verde (rar), t=1 roșu (aglomerat). */
function heat(t){return 'hsl('+Math.round(125*(1-clamp(t,0,1)))+',72%,'+(t>.35&&t<.65?46:44)+'%)';}
function el(tag,attrs,parent){
  var e=document.createElementNS(SVGNS,tag);
  Object.keys(attrs||{}).forEach(function(k){e.setAttribute(k,attrs[k]);});
  if(parent) parent.appendChild(e); return e;
}

function mount(host,opts){
  opts=opts||{};
  var zones=(opts.zones||[]).slice().sort(function(a,b){return a.n-b.n;});   // cele aglomerate se desenează ultimele, deasupra
  var nMax=zones.length?zones[zones.length-1].n:0, nMin=zones.length?zones[0].n:0;
  zones.forEach(function(z){z.t=nMax>nMin?(z.n-nMin)/(nMax-nMin):(zones.length>1?.5:1);});
  var ranked=zones.slice().reverse();

  host.innerHTML=
    '<div class="m-view" tabindex="0" role="group" aria-label="Harta Iașului cu zonele din traseele elevilor. Săgețile mută harta, plus și minus o măresc sau o micșorează.">'
    +'<div class="m-tiles" aria-hidden="true"></div>'
    +'<svg class="m-svg" aria-hidden="true"></svg>'
    +'<div class="m-tip" role="status" hidden></div>'
    +'<div class="m-ctl"><button type="button" class="m-btn" data-z="1" aria-label="Mărește harta">+</button><button type="button" class="m-btn" data-z="-1" aria-label="Micșorează harta">−</button></div>'
    +(zones.length?'':'<p class="m-empty">Încă nu avem destule trasee ca să colorăm harta.</p>')
    +'<p class="m-attr">© <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> · © <a href="https://carto.com/attributions" target="_blank" rel="noopener">CARTO</a></p>'
    +'</div>'
    +'<div class="m-legend"><span>Mai rar frecventat</span><i class="m-grad" aria-hidden="true"></i><span>Mai aglomerat</span></div>'
    +'<p class="m-help">Mărește cu butoanele + și −, cu dublu clic sau cu două degete. Trage harta ca să o muți.</p>'
    +(ranked.length?'<ol class="m-rank" aria-label="Zonele menționate cel mai des"></ol>':'');

  var view=host.querySelector('.m-view'), tiles=host.querySelector('.m-tiles'), svg=host.querySelector('.m-svg'), tip=host.querySelector('.m-tip');
  var btns=Array.prototype.slice.call(host.querySelectorAll('.m-btn'));
  var W=0,H=0, z=START_Z, c=toPx(CENTER.lat,CENTER.lng,z);
  var cache={};   // dalele deja create

  /* --- zone: câte un grup SVG (cerc + nume) --- */
  var nodes=zones.map(function(zn,i){
    var g=el('g',{'class':'m-zone',tabindex:'0',role:'img','aria-label':zn.name+': '+zn.n+(zn.n===1?' elev':' elevi')},svg);
    var color=heat(zn.t);
    var ci=el('circle',{fill:color,'fill-opacity':'.5',stroke:color,'stroke-width':'2'},g);
    var tx=el('text',{'class':'m-label','text-anchor':'middle'},g); tx.textContent=zn.name;
    g.addEventListener('pointerenter',function(e){ if(e.pointerType==='mouse') showTip(zn); });
    g.addEventListener('pointerleave',function(e){ if(e.pointerType==='mouse') hideTip(); });
    g.addEventListener('focus',function(){showTip(zn);});
    g.addEventListener('blur',hideTip);
    g.addEventListener('click',function(e){ e.stopPropagation(); if(moved) return; showTip(zn); });
    return {g:g,ci:ci,tx:tx,zn:zn,rank:ranked.indexOf(zn)};
  });
  var tipZone=null;
  function showTip(zn){
    tipZone=zn; tip.hidden=false;
    tip.innerHTML='<strong>'+esc(zn.name)+'</strong><span>'+zn.n+(zn.n===1?' elev a menționat':' elevi au menționat')+' zona</span>';
    placeTip();
  }
  function hideTip(){tipZone=null; tip.hidden=true;}
  function placeTip(){
    if(!tipZone) return;
    var p=project(tipZone), w=tip.offsetWidth||160, h=tip.offsetHeight||44;
    tip.style.left=clamp(p.x-w/2,8,Math.max(8,W-w-8))+'px';
    tip.style.top=clamp(p.y-p.r-h-10,8,Math.max(8,H-h-8))+'px';
  }
  function project(zn){
    var p=toPx(zn.lat,zn.lng,z), mpp=156543.03392*Math.cos(zn.lat*Math.PI/180)/Math.pow(2,z);
    return {x:p.x-(c.x-W/2), y:p.y-(c.y-H/2), r:Math.max(12,(zn.r||500)*(.75+.5*zn.t)/mpp)};
  }

  /* --- desenare --- */
  function limits(){
    var a=toPx(BOUNDS.n,BOUNDS.w,z), b=toPx(BOUNDS.s,BOUNDS.e,z);
    return {x0:a.x,x1:b.x,y0:a.y,y1:b.y};
  }
  function fit(){
    var L=limits();
    c.x=L.x1-L.x0<=W?(L.x0+L.x1)/2:clamp(c.x,L.x0+W/2,L.x1-W/2);
    c.y=L.y1-L.y0<=H?(L.y0+L.y1)/2:clamp(c.y,L.y0+H/2,L.y1-H/2);
  }
  function tileUrl(tx,ty){
    var style=isDark()?'dark_all':'light_all', sub='abcd'.charAt((tx+ty)&3), r=(window.devicePixelRatio||1)>1.4?'@2x':'';
    return 'https://'+sub+'.basemaps.cartocdn.com/'+style+'/'+z+'/'+tx+'/'+ty+r+'.png';
  }
  function draw(){
    if(!W||!H) return;
    fit();
    var ox=c.x-W/2, oy=c.y-H/2, n=Math.pow(2,z);
    var x0=Math.floor(ox/TILE), x1=Math.floor((ox+W)/TILE), y0=Math.max(0,Math.floor(oy/TILE)), y1=Math.min(n-1,Math.floor((oy+H)/TILE));
    var want={}, dark=isDark();
    for(var ty=y0;ty<=y1;ty++) for(var tx=x0;tx<=x1;tx++){
      if(tx<0||tx>=n) continue;
      var key=z+'/'+tx+'/'+ty+(dark?'d':'l'); want[key]=1;
      var im=cache[key];
      if(!im){
        im=document.createElement('img'); im.alt=''; im.draggable=false; im.decoding='async'; im.className='m-tile';
        im.width=TILE; im.height=TILE;
        im.onload=function(){this.classList.add('on');};
        im.onerror=function(){this.remove();};
        im.src=tileUrl(tx,ty); cache[key]=im; tiles.appendChild(im);
      }
      im.style.transform='translate('+Math.round(tx*TILE-ox)+'px,'+Math.round(ty*TILE-oy)+'px)';
    }
    Object.keys(cache).forEach(function(k){ if(!want[k]){ cache[k].remove(); delete cache[k]; } });
    var placed=[];
    nodes.slice().sort(function(a,b){return a.rank-b.rank;}).forEach(function(o){   // întâi zonele cele mai aglomerate
      var p=project(o.zn);
      o.ci.setAttribute('cx',p.x.toFixed(1)); o.ci.setAttribute('cy',p.y.toFixed(1)); o.ci.setAttribute('r',p.r.toFixed(1));
      o.tx.setAttribute('x',p.x.toFixed(1)); o.tx.setAttribute('y',(p.y+4).toFixed(1));
      var vis=!(p.x+p.r<-20||p.x-p.r>W+20||p.y+p.r<-20||p.y-p.r>H+20);
      var w=o.zn.name.length*6.6+8, box={l:p.x-w/2,r:p.x+w/2,t:p.y-9,b:p.y+9}, ok=vis&&(z>=13||o.rank<5);
      if(ok) placed.forEach(function(q){ if(box.l<q.r&&box.r>q.l&&box.t<q.b&&box.b>q.t) ok=false; });
      if(ok) placed.push(box);
      o.tx.style.display=ok?'':'none';
      o.g.style.display=vis?'':'none';
    });
    btns.forEach(function(b){ var d=+b.getAttribute('data-z'); b.disabled=(d>0&&z>=MAXZ)||(d<0&&z<=MINZ); });
    placeTip();
  }
  var raf=0; function schedule(){ if(!raf) raf=requestAnimationFrame(function(){raf=0;draw();}); }
  var fitted=false;
  function fitZones(){
    if(!zones.length||W<50) return;
    var best=MINZ;
    for(var zz=14;zz>=MINZ;zz--){
      var xs=[],ys=[];
      zones.forEach(function(q){var pp=toPx(q.lat,q.lng,zz);xs.push(pp.x);ys.push(pp.y);});
      var rm=44; if(Math.max.apply(null,xs)-Math.min.apply(null,xs)<=W-2*rm&&Math.max.apply(null,ys)-Math.min.apply(null,ys)<=H-2*rm){best=zz;break;}
    }
    z=best; var x=[],y=[];
    zones.forEach(function(q){var pp=toPx(q.lat,q.lng,z);x.push(pp.x);y.push(pp.y);});
    c.x=(Math.max.apply(null,x)+Math.min.apply(null,x))/2; c.y=(Math.max.apply(null,y)+Math.min.apply(null,y))/2;
  }
  function measure(){
    W=view.clientWidth; H=view.clientHeight; svg.setAttribute('width',W); svg.setAttribute('height',H);
    if(!fitted&&W>0){ fitted=true; fitZones(); }
    schedule();
  }

  function zoomTo(nz,ax,ay){
    nz=clamp(nz,MINZ,MAXZ); if(nz===z) return;
    var f=Math.pow(2,nz-z);
    if(ax===undefined){ax=W/2;ay=H/2;}
    var wx=(c.x-W/2+ax)*f, wy=(c.y-H/2+ay)*f;
    z=nz; c.x=wx-ax+W/2; c.y=wy-ay+H/2;
    Object.keys(cache).forEach(function(k){cache[k].remove();delete cache[k];});
    schedule();
  }
  function panBy(dx,dy){c.x+=dx;c.y+=dy;schedule();}

  /* --- gesturi: tragere, ciupire, dublu clic, rotiță cu Ctrl, tastatură --- */
  var ptrs={}, moved=false, pinch0=0, dragStart=null;
  function count(){return Object.keys(ptrs).length;}
  function dist(){var k=Object.keys(ptrs); if(k.length<2) return 0; var a=ptrs[k[0]],b=ptrs[k[1]]; return Math.hypot(a.x-b.x,a.y-b.y);}
  view.addEventListener('pointerdown',function(e){
    if(e.target.closest('.m-ctl')||e.target.closest('a')) return;
    view.setPointerCapture(e.pointerId);
    ptrs[e.pointerId]={x:e.clientX,y:e.clientY};
    dragStart={x:e.clientX,y:e.clientY}; moved=false;
    if(count()===2) pinch0=dist();
    view.classList.add('grab');
  });
  view.addEventListener('pointermove',function(e){
    var p=ptrs[e.pointerId]; if(!p) return;
    var dx=e.clientX-p.x, dy=e.clientY-p.y;
    if(count()===1){
      if(!moved&&Math.hypot(e.clientX-dragStart.x,e.clientY-dragStart.y)>5){moved=true;hideTip();}
      if(moved) panBy(-dx,-dy);
    } else { moved=true; }
    p.x=e.clientX; p.y=e.clientY;
  });
  function up(e){
    if(!ptrs[e.pointerId]) return;
    if(count()===2&&pinch0){
      var r=dist()/pinch0;
      if(r>1.3) zoomTo(z+1); else if(r<.77) zoomTo(z-1);
      pinch0=0;
    }
    delete ptrs[e.pointerId];
    if(!count()){ view.classList.remove('grab'); setTimeout(function(){moved=false;},0); }
  }
  view.addEventListener('pointerup',up); view.addEventListener('pointercancel',up);
  view.addEventListener('dblclick',function(e){
    if(e.target.closest('.m-ctl')) return;
    var r=view.getBoundingClientRect(); zoomTo(z+1,e.clientX-r.left,e.clientY-r.top);
  });
  var wheelAt=0;
  view.addEventListener('wheel',function(e){
    if(!(e.ctrlKey||e.metaKey)) return;       // fără Ctrl, rotița derulează pagina
    e.preventDefault();
    var now=Date.now(); if(now-wheelAt<180) return; wheelAt=now;
    var r=view.getBoundingClientRect(); zoomTo(z+(e.deltaY<0?1:-1),e.clientX-r.left,e.clientY-r.top);
  },{passive:false});
  view.addEventListener('keydown',function(e){
    if(e.target!==view) return;
    var k=e.key, s=80, h=true;
    if(k==='ArrowLeft') panBy(-s,0); else if(k==='ArrowRight') panBy(s,0); else if(k==='ArrowUp') panBy(0,-s); else if(k==='ArrowDown') panBy(0,s);
    else if(k==='+'||k==='=') zoomTo(z+1); else if(k==='-'||k==='_') zoomTo(z-1); else h=false;
    if(h) e.preventDefault();
  });
  btns.forEach(function(b){ b.addEventListener('click',function(e){e.stopPropagation(); zoomTo(z+(+b.getAttribute('data-z')));}); });
  view.addEventListener('click',function(e){ if(!e.target.closest('.m-zone')&&!e.target.closest('.m-ctl')) hideTip(); });

  /* --- clasamentul zonelor (alternativa text a hărții) --- */
  var rank=host.querySelector('.m-rank');
  if(rank){
    rank.innerHTML=ranked.slice(0,8).map(function(zn,i){
      return '<li><button type="button" data-i="'+i+'"><i class="m-dot" style="background:'+heat(zn.t)+'"></i><span class="m-rn">'+esc(zn.name)+'</span><span class="m-rv">'+zn.n+(zn.n===1?' elev':' elevi')+'</span></button></li>';
    }).join('');
    rank.addEventListener('click',function(e){
      var b=e.target.closest('button[data-i]'); if(!b) return;
      var zn=ranked[+b.getAttribute('data-i')];
      z=clamp(14,MINZ,MAXZ); var p=toPx(zn.lat,zn.lng,z); c.x=p.x; c.y=p.y;
      Object.keys(cache).forEach(function(k){cache[k].remove();delete cache[k];});
      draw(); showTip(zn);
      view.scrollIntoView({block:'nearest',behavior:reduce?'auto':'smooth'});
    });
  }

  if(window.ResizeObserver) new ResizeObserver(measure).observe(view); else window.addEventListener('resize',measure);
  if(window.matchMedia){ var mq=matchMedia('(prefers-color-scheme: dark)'); var cb=function(){Object.keys(cache).forEach(function(k){cache[k].remove();delete cache[k];});schedule();}; if(mq.addEventListener) mq.addEventListener('change',cb); else if(mq.addListener) mq.addListener(cb); }
  measure();
}
window.AmprentaMap={mount:mount,heat:heat};
})();
