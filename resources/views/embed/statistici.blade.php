<!doctype html>
<html lang="ro">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>Statistici CarbonIt Movement</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=IBM+Plex+Mono:wght@400;500&family=Instrument+Sans:wght@400..700&display=swap">
<link rel="stylesheet" href="/assets/stats.css">
<style>
@verbatim
:root{
  --bg:#EDF2F0; --surface:#F8FBFA; --fg:#0E1F1C; --muted:#4F625E; --line:#C9D7D3;
  --accent:#0A6B63; --accent-fg:#F4FBF9; --heat:#D9531E; --ridge:#2E5F58;
  --font-display:"Bricolage Grotesque","Segoe UI",system-ui,sans-serif;
  --font-body:"Instrument Sans","Segoe UI",system-ui,sans-serif;
  --font-mono:"IBM Plex Mono",ui-monospace,Menlo,Consolas,monospace;
}
@media (prefers-color-scheme: dark){
  :root:not([data-theme="light"]){
    --bg:#0A1413; --surface:#111E1C; --fg:#E4EEEB; --muted:#93A9A4; --line:#25403B;
    --accent:#6FD3C3; --accent-fg:#06201C; --heat:#FF8A57; --ridge:#7FB8AE; color-scheme:dark;
  }
}
:root[data-theme="dark"]{
  --bg:#0A1413; --surface:#111E1C; --fg:#E4EEEB; --muted:#93A9A4; --line:#25403B;
  --accent:#6FD3C3; --accent-fg:#06201C; --heat:#FF8A57; --ridge:#7FB8AE; color-scheme:dark;
}

*,*::before,*::after{box-sizing:border-box}
[hidden]{display:none!important}
body{margin:0;background:var(--bg);color:var(--fg);font:400 1.0625rem/1.55 var(--font-body);-webkit-font-smoothing:antialiased}
.noop::before{content:"";position:fixed;inset:0 0 auto;height:env(safe-area-inset-top,0px);z-index:30;background:var(--bg)}
sub{font-size:.62em;line-height:0;vertical-align:baseline;position:relative;top:.28em}
a{color:inherit}
button{font:inherit;color:inherit}
:focus-visible{outline:2px solid var(--accent);outline-offset:3px}
.wrap{width:100%;max-width:1180px;margin-inline:auto;padding-left:max(16px,env(safe-area-inset-left,0px));padding-right:max(16px,env(safe-area-inset-right,0px))}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:.6em;padding:.85em 1.5em;border-radius:999px;border:1px solid var(--accent);background:var(--accent);color:var(--accent-fg);font-weight:600;font-size:.98rem;line-height:1.1;text-decoration:none;cursor:pointer;transition:transform .2s,opacity .2s,background .2s,border-color .2s}
.btn:hover:not(:disabled){transform:translateY(-1px)}
.btn:disabled{opacity:.4;cursor:not-allowed}
.btn-ghost{background:transparent;color:var(--fg);border-color:var(--line)}
.btn-ghost:hover:not(:disabled){border-color:var(--fg)}
.btn-small{padding:.65em 1.15em;font-size:.9rem}
.eyebrow{display:flex;align-items:center;gap:12px;margin:0;font:500 .78rem/1 var(--font-mono);letter-spacing:.14em;text-transform:uppercase;color:var(--accent)}
.eyebrow::before{content:"";width:28px;height:1px;background:currentColor}
.display{margin:20px 0 22px;font-family:var(--font-display);font-weight:700;letter-spacing:-.035em;line-height:.98;text-wrap:balance}
h2.display{font-size:clamp(2.2rem,5.4vw,4.2rem)}
.lead{margin:0;max-width:34rem;font-size:clamp(1.05rem,1.6vw,1.22rem);color:var(--muted)}
.stats{background:var(--surface);border-top:1px solid var(--line);padding-block:clamp(56px,9vw,120px);scroll-margin-top:64px}
.st-head{max-width:44rem}
.st-head .display{margin:18px 0 18px;font-size:clamp(2.2rem,5vw,3.6rem)}
.st-note{display:flex;flex-wrap:wrap;gap:6px 14px;align-items:baseline;margin:28px 0 0;padding:14px 16px;border:1px dashed var(--heat);border-radius:10px;background:color-mix(in srgb,var(--heat) 8%,var(--surface));font-size:.95rem}
.st-note b{font:500 .74rem/1 var(--font-mono);letter-spacing:.12em;text-transform:uppercase}
.kpis{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));margin-top:40px;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.kpi{display:grid;gap:12px;align-content:start;min-width:0;padding:28px clamp(16px,2.4vw,32px) 28px 0}
.kpi+.kpi{border-left:1px solid var(--line);padding-left:clamp(16px,2.4vw,32px)}
.k-l{font:500 .76rem/1 var(--font-mono);letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
.k-v{display:block;font:700 clamp(1.7rem,3.2vw,2.7rem)/1.05 var(--font-display);letter-spacing:-.03em;text-wrap:balance}
.k-v.acc{color:var(--accent)}
.k-v small{margin-left:.25em;font-size:.42em;font-weight:600;letter-spacing:0;color:var(--muted)}
.k-s{color:var(--muted);font-size:.95rem}
.k-duo{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.k-duo span{display:block;color:var(--muted);font-size:.9rem;line-height:1.35}
.panels{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:52px clamp(32px,6vw,80px);margin-top:56px}
.panel{min-width:0;padding-top:18px;border-top:2px solid var(--fg)}
.panel.wide{grid-column:1/-1}
.panel h3{margin:0;font:600 1.3rem/1.2 var(--font-display);letter-spacing:-.02em}
.p-sub{margin:6px 0 22px;color:var(--muted);font-size:.9rem}
.p-foot{margin:20px 0 0;color:var(--muted);font-size:.92rem}
.p-foot strong{color:var(--fg);font-weight:600}
.big-line{display:flex;align-items:baseline;gap:14px;margin:0 0 22px}
.big-line strong{font:700 clamp(2rem,4vw,3rem)/1 var(--font-display);letter-spacing:-.03em;color:var(--accent)}
.big-line span{color:var(--muted);font-size:.95rem;max-width:20rem}
.bars{list-style:none;margin:0;padding:0;display:grid;gap:14px}
.panel.wide .bars{grid-template-columns:repeat(2,minmax(0,1fr));gap:14px clamp(32px,6vw,80px)}
.b-top{display:flex;justify-content:space-between;gap:12px;margin-bottom:6px;font-size:.95rem}
.b-l{min-width:0}
.b-v{flex:none;font:500 .88rem/1.5 var(--font-mono);font-variant-numeric:tabular-nums;white-space:nowrap}
.b-n{color:var(--muted)}
.b-track{height:10px;overflow:hidden;border-radius:2px;background:color-mix(in srgb,var(--line) 70%,transparent)}
.b-fill{height:100%;background:color-mix(in srgb,var(--accent) 38%,var(--surface));transform-origin:left;transition:transform .9s cubic-bezier(.2,.8,.2,1)}
.b-fill.pre{transform:scaleX(0)}
.bars li.lead .b-fill{background:var(--accent)}
.bars li.lead .b-l{font-weight:600}
.empty{margin:0;color:var(--muted)}
@media (max-width:900px){
  .panels{grid-template-columns:minmax(0,1fr)}
  .panel.wide .bars{grid-template-columns:minmax(0,1fr)}
}
@media (max-width:720px){
  .kpis{grid-template-columns:minmax(0,1fr)}
  .kpi,.kpi+.kpi{padding:24px 0;border-left:0}
  .kpi+.kpi{border-top:1px solid var(--line)}
}
@media (prefers-reduced-motion:reduce){.b-fill{transition:none}}


/* ---------- Harta ---------- */
.mapsec{padding-block:clamp(56px,9vw,120px);scroll-margin-top:64px;border-top:1px solid var(--line)}
.mp-body{margin-top:36px}
.mp-foot{margin-top:18px;max-width:46rem}
.m-view{position:relative;height:clamp(380px,64vh,640px);overflow:hidden;border:1px solid var(--line);border-radius:12px;background:color-mix(in srgb,var(--line) 55%,var(--surface));touch-action:none;cursor:grab;user-select:none;-webkit-user-select:none}
.stats{padding-block:32px}.mapsec{padding-block:32px}.stats,.mapsec{scroll-margin-top:0}

@endverbatim
</style>
</head>
<body>
<section class="stats" aria-labelledby="st-title">
  <div class="wrap">
    <div class="st-head">
      <p class="eyebrow">Statistici</p>
      <h2 class="display" id="st-title">Ce spun elevii din Iași</h2>
      <p class="lead">Răspunsurile din chestionar, adunate la un loc.</p>
    </div>
    <div id="stBody"></div>
  </div>
</section>
<section class="mapsec" aria-labelledby="mp-title">
  <div class="wrap">
    <div class="st-head">
      <p class="eyebrow">Harta</p>
      <h2 class="display" id="mp-title">Pe unde merg elevii dimineața</h2>
    </div>
    <div id="mpBody" class="mp-body"></div>
    <p class="p-foot mp-foot">Zonele sunt aproximative și apar doar dacă au fost menționate de cel puțin 2 elevi.</p>
  </div>
</section>
<p class="p-foot" style="text-align:center;padding:0 16px 24px"><a href="{{ config('app.url') }}" target="_blank" rel="noopener">Completează chestionarul pe CarbonIt Movement</a></p>

<script src="/assets/stats.js"></script>
<script src="/assets/map.js"></script>
@verbatim
<script>
(function(){
  var stBody=document.getElementById('stBody'), mpBody=document.getElementById('mpBody');
  function sendHeight(){
    var h=Math.ceil(document.documentElement.scrollHeight);
    if(window.parent!==window) window.parent.postMessage({type:'carbonit-height',height:h},'*');
  }
  if(window.ResizeObserver) new ResizeObserver(sendHeight).observe(document.body);
  window.addEventListener('load',sendHeight);
  function load(){
    AmprentaStats.loading(stBody);
    fetch('/api/stats',{headers:{'Accept':'application/json'}})
      .then(function(r){ if(!r.ok) throw new Error('http'); return r.json(); })
      .then(function(d){ AmprentaStats.render({body:stBody,rows:d.responses,sample:false}); AmprentaMap.mount(mpBody,{zones:d.zones||[]}); sendHeight(); })
      .catch(function(){ AmprentaStats.error(stBody,load); });
  }
  load();
})();
</script>
@endverbatim
</body>
</html>
