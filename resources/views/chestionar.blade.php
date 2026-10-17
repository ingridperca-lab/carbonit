@verbatim
<!doctype html>
<html lang="ro">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title>CarbonIt Movement · Iași</title>
<meta name="claude:viewport-fit-sides" content="cover">
<meta name="description" content="Chestionar anonim despre cum ajung elevii din Iași dimineața la școală și ce amprentă de dioxid de carbon lasă drumul.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=IBM+Plex+Mono:wght@400;500&family=Instrument+Sans:wght@400..700&display=swap">
<style>
/* Concept: un "amprenta" din inele concentrice (amprentă și urmă de carbon) în spatele unui hero care glisează;
   sub el, un chestionar cu o singură întrebare pe ecran și o listă laterală cu numerele reale ale întrebărilor. */
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
body::before{content:"";position:fixed;inset:0 0 auto;height:env(safe-area-inset-top,0px);z-index:30;background:var(--bg)}
sub{font-size:.62em;line-height:0;vertical-align:baseline;position:relative;top:.28em}
a{color:inherit}
button{font:inherit;color:inherit}
:focus-visible{outline:2px solid var(--accent);outline-offset:3px}
.wrap{width:100%;max-width:1180px;margin-inline:auto;padding-left:max(16px,env(safe-area-inset-left,0px));padding-right:max(16px,env(safe-area-inset-right,0px))}

/* ---------- Header ---------- */
.site-header{position:sticky;top:env(safe-area-inset-top,0px);z-index:31;background:var(--bg);border-bottom:1px solid var(--line)}
.bar{display:flex;align-items:center;gap:24px;height:64px}
.brand{display:flex;align-items:center;gap:10px;font:700 1.15rem/1 var(--font-display);letter-spacing:-.02em;text-decoration:none}
.brand svg{width:26px;height:26px;color:var(--accent)}
.bar nav{margin-left:auto;display:flex;gap:28px;font-size:.95rem}
.bar nav a{position:relative;padding:8px 0;text-decoration:none;color:var(--muted);transition:color .2s}
.bar nav a:hover,.bar nav a[aria-current="true"]{color:var(--fg)}
.bar nav a::after{content:"";position:absolute;left:0;right:0;bottom:2px;height:2px;background:var(--accent);transform:scaleX(0);transform-origin:left;transition:transform .35s cubic-bezier(.2,.8,.2,1)}
.bar nav a[aria-current="true"]::after{transform:scaleX(1)}
.show-s{display:none}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:.6em;padding:.85em 1.5em;border-radius:999px;border:1px solid var(--accent);background:var(--accent);color:var(--accent-fg);font-weight:600;font-size:.98rem;line-height:1.1;text-decoration:none;cursor:pointer;transition:transform .2s,opacity .2s,background .2s,border-color .2s}
.btn:hover:not(:disabled){transform:translateY(-1px)}
.btn:disabled{opacity:.4;cursor:not-allowed}
.btn-ghost{background:transparent;color:var(--fg);border-color:var(--line)}
.btn-ghost:hover:not(:disabled){border-color:var(--fg)}
.btn-small{padding:.65em 1.15em;font-size:.9rem}

/* ---------- Hero ---------- */
.hero{position:relative;overflow:hidden;border-bottom:1px solid var(--line)}
#print{position:absolute;top:0;right:0;height:100%;width:min(62vw,860px);pointer-events:none;-webkit-mask-image:linear-gradient(90deg,transparent 0,#000 30%);mask-image:linear-gradient(90deg,transparent 0,#000 30%)}
.viewport{position:relative;z-index:1;overflow:hidden;touch-action:pan-y}
.track{display:flex;transform:translate3d(calc(var(--n,0) * -100%),0,0);transition:transform .95s cubic-bezier(.7,0,.2,1);will-change:transform}
.slide{flex:0 0 100%;min-width:0;display:flex;align-items:center;min-height:clamp(560px,78vh,740px);padding-block:56px 116px}
@supports (height:1svh){.slide{min-height:clamp(560px,78svh,740px)}}
.copy{max-width:40rem}
.eyebrow{display:flex;align-items:center;gap:12px;margin:0;font:500 .78rem/1 var(--font-mono);letter-spacing:.14em;text-transform:uppercase;color:var(--accent)}
.eyebrow::before{content:"";width:28px;height:1px;background:currentColor}
.display{margin:20px 0 22px;font-family:var(--font-display);font-weight:700;letter-spacing:-.035em;line-height:.98;text-wrap:balance}
h1.display{font-size:clamp(2.7rem,7.2vw,5.4rem)}
h2.display{font-size:clamp(2.2rem,5.4vw,4.2rem)}
.ln{display:block;overflow:hidden;padding-bottom:.1em}
.ln>span{display:block}
.lead{margin:0;max-width:34rem;font-size:clamp(1.05rem,1.6vw,1.22rem);color:var(--muted)}
.actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:32px}
.facts{list-style:none;margin:28px 0 0;padding:0;border-top:1px solid var(--line);max-width:36rem}
.facts li{display:grid;grid-template-columns:10.5rem minmax(0,1fr);gap:16px;padding:14px 0;border-bottom:1px solid var(--line);font-size:.96rem;color:var(--muted)}
.facts strong{font:600 1rem/1.3 var(--font-display);color:var(--fg)}

@keyframes rv{from{opacity:0;transform:translateY(18px)}}
@keyframes rise{from{transform:translateY(105%)}}
.slide.is-active .rv{animation:rv .8s cubic-bezier(.2,.7,.2,1) both;animation-delay:calc(var(--i,0) * 90ms + 260ms)}
.slide.is-active .ln>span{animation:rise 1s cubic-bezier(.2,.8,.2,1) both;animation-delay:calc(var(--i,0) * 90ms + 160ms)}

.hero-ctl{position:absolute;z-index:2;left:0;right:0;bottom:28px}
.ctl-row{display:flex;align-items:center;gap:20px}
.cnt{font:500 .82rem/1 var(--font-mono);letter-spacing:.1em;color:var(--muted);font-variant-numeric:tabular-nums}
.segs{display:flex;gap:8px}
.seg{position:relative;width:64px;height:20px;padding:0;border:0;background:none;cursor:pointer}
.seg::before,.seg i{content:"";position:absolute;left:0;right:0;top:9px;height:2px}
.seg::before{background:var(--line)}
.seg i{background:var(--fg);transform:scaleX(0);transform-origin:left;transition:transform .1s linear}
.arrows{margin-left:auto;display:flex;gap:10px}
.arrow{display:grid;place-items:center;width:44px;height:44px;border-radius:50%;border:1px solid var(--line);background:var(--bg);cursor:pointer;transition:border-color .2s,transform .2s}
.arrow:hover{border-color:var(--fg)}
.arrow svg{width:18px;height:18px}

/* ---------- Chestionar ---------- */
.survey{overflow-x:clip;padding-block:clamp(56px,9vw,120px);scroll-margin-top:64px}
.survey-grid{display:grid;grid-template-columns:minmax(0,5fr) minmax(0,7fr);gap:clamp(32px,6vw,88px);align-items:start}
.side{position:sticky;top:calc(env(safe-area-inset-top,0px) + 96px)}
.side h2.display{font-size:clamp(2rem,4vw,3.1rem);margin:18px 0 18px}
.side .lead{font-size:1.02rem}
.rail{list-style:none;margin:34px 0 0;padding:0;border-top:1px solid var(--line)}
.rail li{display:grid;grid-template-columns:2.6rem minmax(0,1fr);align-items:baseline;padding:10px 0;border-bottom:1px solid var(--line);font-size:.94rem;color:var(--muted);transition:color .3s,opacity .3s}
.rail .rn{font:500 .76rem/1 var(--font-mono);letter-spacing:.06em}
.rail li[data-s="current"]{color:var(--fg);font-weight:600}
.rail li[data-s="current"] .rn,.rail li[data-s="done"] .rn{color:var(--accent)}
.rail li[data-s="done"] .rt::after{content:" ✓";color:var(--accent)}
.rail li[data-s="skipped"]{opacity:.42}
.sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

.card{display:flex;flex-direction:column;gap:28px;min-height:480px;padding:clamp(20px,3.5vw,40px);background:var(--surface);border:1px solid var(--line);border-radius:18px}
.card-top{display:flex;flex-direction:column;gap:14px}
.qid{font:500 .78rem/1 var(--font-mono);letter-spacing:.12em;text-transform:uppercase;color:var(--muted)}
.meter{height:3px;background:var(--line);border-radius:3px;overflow:hidden}
.meter i{display:block;height:100%;background:var(--accent);transform-origin:left;transform:scaleX(.1);transition:transform .6s cubic-bezier(.2,.8,.2,1)}
.stage{flex:1;min-width:0}
.q-title{margin:0 0 10px;font:600 clamp(1.5rem,3vw,2.05rem)/1.12 var(--font-display);letter-spacing:-.02em;text-wrap:balance;outline:none}
.q-hint{margin:0 0 22px;font-size:.96rem;color:var(--muted);max-width:38rem}
.q-hint+.q-hint{margin-top:-14px}
.card-nav{display:flex;align-items:center;gap:12px}
.card-nav .btn[type="submit"]{margin-left:auto}
.send-err{margin:18px 0 0;padding:12px 14px;border:1px solid var(--heat);border-radius:10px;background:color-mix(in srgb,var(--heat) 8%,var(--surface));font-size:.95rem}

.opts{display:grid;gap:10px}
.opts.two{grid-template-columns:repeat(2,minmax(0,1fr))}
.opt{position:relative;display:flex;align-items:center;gap:14px;padding:14px 16px;border:1px solid var(--line);border-radius:10px;cursor:pointer;transition:border-color .2s,background .2s}
.opt:hover{border-color:var(--muted)}
.opt input{position:absolute;opacity:0;pointer-events:none}
.mark{flex:none;width:20px;height:20px;border:1.5px solid var(--muted);transition:background .2s,border-color .2s}
.mark.r{border-radius:50%}
.mark.c{border-radius:5px}
.mark::after{content:"";display:block;width:100%;height:100%;background:var(--accent-fg);transform:scale(0);transition:transform .2s}
.mark.r::after{border-radius:50%;transform:scale(0)}
.opt input:checked+.mark{background:var(--accent);border-color:var(--accent)}
.opt input:checked+.mark.r::after{transform:scale(.38)}
.opt input:checked+.mark.c::after{transform:scale(.55);clip-path:polygon(14% 52%,0 66%,38% 100%,100% 22%,86% 8%,38% 70%)}
.opt:has(input:checked){border-color:var(--accent);background:color-mix(in srgb,var(--accent) 10%,var(--surface))}
.opt:has(input:focus-visible){outline:2px solid var(--accent);outline-offset:2px}
.opt:has(input:disabled){opacity:.45;cursor:not-allowed}
.otx{display:grid;gap:2px;min-width:0}
.ol{font-weight:500}
.od{font-size:.88rem;color:var(--muted)}

.chips{display:flex;flex-wrap:wrap;gap:10px}
.chip{position:relative}
.chip input{position:absolute;opacity:0;pointer-events:none}
.chip span{display:grid;place-items:center;width:62px;height:62px;border:1px solid var(--line);border-radius:12px;font:600 1.5rem/1 var(--font-display);cursor:pointer;transition:background .2s,border-color .2s,color .2s}
.chip:hover span{border-color:var(--muted)}
.chip input:checked+span{background:var(--accent);border-color:var(--accent);color:var(--accent-fg)}
.chip input:focus-visible+span{outline:2px solid var(--accent);outline-offset:3px}
.chip-note{margin:14px 0 0;font-size:.9rem;color:var(--muted)}

.modes{border-top:1px solid var(--line)}
.mode{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:10px 0;border-bottom:1px solid var(--line)}
.mode-name{min-width:0;font-weight:500}
.stepper{display:flex;align-items:center;gap:10px;flex:none}
.stepper button{width:40px;height:40px;border-radius:50%;border:1px solid var(--line);background:transparent;font-size:1.3rem;line-height:1;cursor:pointer;transition:border-color .2s,color .2s}
.stepper button:hover:not(:disabled){border-color:var(--accent);color:var(--accent)}
.stepper button:disabled{opacity:.35;cursor:not-allowed}
.stepper output{min-width:1.7rem;text-align:center;font:600 1.25rem/1 var(--font-display);font-variant-numeric:tabular-nums}
.mtot{margin:16px 0 0;font:500 .86rem/1.4 var(--font-mono);letter-spacing:.02em;color:var(--muted)}
.mtot.ok{color:var(--accent)}

.field{display:grid;gap:8px;margin-top:20px}
.field label{font-weight:600;font-size:.95rem}
.field textarea{width:100%;resize:vertical;padding:12px 14px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--fg);font:inherit;font-size:.98rem;line-height:1.5}
.field textarea:focus-visible{outline:2px solid var(--accent);outline-offset:2px}
.field small{color:var(--muted);font-size:.85rem}

.done{display:grid;gap:14px;justify-items:start}
.done svg{width:52px;height:52px;color:var(--accent)}
.done p{margin:0;max-width:36rem;color:var(--muted)}
.done .scn{color:var(--fg);font-weight:500}

.foot{border-top:1px solid var(--line);padding-block:28px;font-size:.88rem;color:var(--muted)}
.foot-row{display:flex;flex-wrap:wrap;justify-content:space-between;gap:10px 32px}

@media (max-width:900px){
  .survey-grid{grid-template-columns:minmax(0,1fr)}
  .side{position:static}
  .rail{display:none}
}
@media (max-width:720px){
  .bar{gap:14px}
  .brand span{display:none}
  .bar nav{gap:20px;font-size:.93rem}
  .hide-s{display:none}
  .show-s{display:inline}
  #print{width:100%;opacity:.4;-webkit-mask-image:none;mask-image:none}
  .facts li{grid-template-columns:minmax(0,1fr);gap:4px}
  .seg{width:44px}
  .opts.two{grid-template-columns:minmax(0,1fr) minmax(0,1fr)}
}
@media (prefers-reduced-motion:reduce){
  .track{transition:none}
  .slide.is-active .rv,.slide.is-active .ln>span{animation:none}
  .meter i,.btn,.opt,.chip span{transition:none}
}

/* ---------- Statistici ---------- */
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
.m-view.grab{cursor:grabbing}
.m-tiles,.m-svg{position:absolute;inset:0}
.m-tiles{pointer-events:none}
.m-tile{position:absolute;left:0;top:0;width:256px;height:256px;max-width:none;opacity:0;transition:opacity .25s;will-change:transform}
.m-tile.on{opacity:1}
.m-svg{width:100%;height:100%;overflow:hidden}
.m-zone{cursor:pointer;outline:none}
.m-zone circle{transition:fill-opacity .15s}
.m-zone:hover circle,.m-zone:focus circle{fill-opacity:.72}
.m-zone:focus-visible circle{stroke:var(--fg)}
.m-label{font:600 12px/1 var(--font-body);fill:var(--fg);stroke:var(--surface);stroke-width:3.5px;paint-order:stroke;pointer-events:none;stroke-linejoin:round}
.m-tip{position:absolute;z-index:3;display:grid;gap:2px;padding:9px 12px;border-radius:8px;background:var(--fg);color:var(--bg);font-size:.88rem;line-height:1.3;pointer-events:none;box-shadow:0 4px 14px rgb(0 0 0/.25)}
.m-tip strong{font-weight:600}
.m-ctl{position:absolute;z-index:4;top:12px;right:12px;display:grid;border:1px solid var(--line);border-radius:10px;overflow:hidden;background:var(--surface)}
.m-btn{width:44px;height:44px;border:0;background:transparent;color:var(--fg);font:500 1.5rem/1 var(--font-body);cursor:pointer}
.m-btn+.m-btn{border-top:1px solid var(--line)}
.m-btn:hover:not(:disabled){background:color-mix(in srgb,var(--accent) 12%,var(--surface))}
.m-btn:disabled{opacity:.35;cursor:default}
.m-empty{position:absolute;z-index:2;left:50%;top:50%;translate:-50% -50%;margin:0;padding:12px 16px;max-width:80%;border-radius:8px;background:var(--surface);border:1px solid var(--line);text-align:center;color:var(--muted)}
.m-attr{position:absolute;z-index:2;right:0;bottom:0;margin:0;padding:3px 8px;background:color-mix(in srgb,var(--surface) 82%,transparent);font-size:.7rem;color:var(--muted)}
.m-attr a{color:inherit}
.m-legend{display:flex;align-items:center;gap:12px;margin-top:16px;font:500 .76rem/1 var(--font-mono);letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.m-grad{flex:0 1 220px;height:10px;border-radius:5px;background:linear-gradient(90deg,hsl(125,72%,44%),hsl(62,72%,46%),hsl(0,72%,44%))}
.m-help{margin:10px 0 0;color:var(--muted);font-size:.9rem}
.m-rank{list-style:none;margin:28px 0 0;padding:0;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0 clamp(32px,6vw,80px);border-top:1px solid var(--line)}
.m-rank button{display:flex;align-items:center;gap:12px;width:100%;padding:12px 2px;border:0;border-bottom:1px solid var(--line);background:none;color:var(--fg);font:inherit;text-align:left;cursor:pointer}
.m-rank button:hover .m-rn{text-decoration:underline}
.m-dot{flex:none;width:14px;height:14px;border-radius:50%}
.m-rn{flex:1;min-width:0}
.m-rv{flex:none;font:500 .88rem/1.5 var(--font-mono);color:var(--muted)}
@media (max-width:720px){.m-rank{grid-template-columns:minmax(0,1fr)}.m-view{height:clamp(340px,60vh,520px)}}

/* ---------- Despre noi ---------- */
.about{position:relative;overflow:hidden;border-top:1px solid var(--line);padding-block:clamp(64px,10vw,130px);scroll-margin-top:64px}
.about-mark{position:absolute;right:-8%;top:50%;width:min(56vw,600px);color:var(--ridge);opacity:.13;transform:translateY(-50%);pointer-events:none}
.about-grid{position:relative;display:grid;grid-template-columns:minmax(0,5fr) minmax(0,7fr);gap:clamp(32px,6vw,88px);align-items:start}
.about .display{margin:18px 0 0;font-size:clamp(2.2rem,4.8vw,3.6rem)}
.about-copy{display:grid;gap:18px;min-width:0}
.about-copy .lead{color:var(--fg);font-size:clamp(1.15rem,1.9vw,1.4rem);max-width:none}
.about-copy p{margin:0;max-width:38rem}
.about-copy p:not(.lead){color:var(--muted)}
.about .facts{max-width:none;margin-top:10px}
.about .actions{margin-top:14px}
.about-copy p.quote{color:var(--fg);font-size:clamp(1.05rem,1.5vw,1.2rem);line-height:1.6}
.about-big{position:relative;margin:clamp(56px,9vw,120px) 0 0;padding-top:clamp(28px,4vw,48px);border-top:1px solid var(--line);font:700 clamp(2.6rem,9vw,7.4rem)/.96 var(--font-display);letter-spacing:-.04em;text-wrap:balance}
.about-big em{font-style:normal;color:var(--accent)}
.about-tag{margin:clamp(14px,2vw,24px) 0 0;font:500 clamp(1rem,2.2vw,1.5rem)/1.2 var(--font-mono);letter-spacing:.04em;color:var(--muted)}
@media (max-width:900px){.about-grid{grid-template-columns:minmax(0,1fr)}}
</style>
</head>
<body>


<header class="site-header">
  <div class="wrap bar">
    <a class="brand" href="#acasa" aria-label="CarbonIt Movement, acasă">
      <svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><ellipse cx="16" cy="16" rx="13" ry="14.5"/><ellipse cx="16" cy="16" rx="9.5" ry="11"/><ellipse cx="16" cy="16" rx="6" ry="7.5"/><ellipse cx="16" cy="16" rx="2.5" ry="3.6"/></svg>
      <span>CarbonIt Movement</span>
    </a>
    <nav aria-label="Principal">
      <a href="#acasa">Acasă</a>
      <a href="#statistici">Statistici</a>
      <a href="#harta">Harta</a>
    </nav>
    <a class="btn btn-small" href="#chestionar"><span class="hide-s">Completează chestionarul</span><span class="show-s">Chestionar</span></a>
  </div>
</header>

<main>
<section id="acasa" class="hero" aria-label="Prezentare">
  <canvas id="print" aria-hidden="true"></canvas>
  <div class="viewport" id="viewport" role="group" aria-roledescription="carousel" aria-label="Prezentare" tabindex="0">
    <div class="track" id="track">

      <article class="slide is-active" role="group" aria-roledescription="slide" aria-label="1 din 3">
        <div class="wrap"><div class="copy">
          <p class="eyebrow rv" style="--i:0">Chestionar pentru elevii din Iași</p>
          <h1 class="display"><span class="ln"><span style="--i:1">Amprenta</span></span><span class="ln"><span style="--i:2">dioxidului</span></span><span class="ln"><span style="--i:3">de carbon</span></span></h1>
          <p class="lead rv" style="--i:4">Fiecare mașină lasă în urmă mai mult decât urme pe asfalt. Emisiile de CO<sub>2</sub> contribuie la încălzirea globală și la schimbările climatice, iar traficul intens amplifică impactul asupra mediului. Putem schimba direcția spre un viitor mai verde?</p>
          <div class="actions rv" style="--i:5">
            <a class="btn" href="#chestionar">Completează chestionarul</a>
            <button type="button" class="btn btn-ghost" data-go="1">Citește mai departe</button>
          </div>
        </div></div>
      </article>

      <article class="slide" role="group" aria-roledescription="slide" aria-label="2 din 3">
        <div class="wrap"><div class="copy">
          <p class="eyebrow rv" style="--i:0">În viața de zi cu zi</p>
          <h2 class="display"><span class="ln"><span style="--i:1">Te afectează</span></span><span class="ln"><span style="--i:2">în fiecare zi</span></span></h2>
          <p class="lead rv" style="--i:3">Nu e ceva îndepărtat. Se vede în aerul orașului, în vară și în traficul de dimineață.</p>
          <ul class="facts rv" style="--i:4">
            <li><strong>Aerul din oraș</strong><span>Aceleași motoare care produc CO<sub>2</sub> eliberează și gaze și praf pe care le respirăm.</span></li>
            <li><strong>Veri tot mai fierbinți</strong><span>Mai mult CO<sub>2</sub> în atmosferă înseamnă canicule mai dese și mai lungi.</span></li>
            <li><strong>Dimineți aglomerate</strong><span>Cu cât sunt mai multe mașini la ora școlii, cu atât stăm mai mult în trafic.</span></li>
          </ul>
        </div></div>
      </article>

      <article class="slide" role="group" aria-roledescription="slide" aria-label="3 din 3">
        <div class="wrap"><div class="copy">
          <p class="eyebrow rv" style="--i:0">Primul pas</p>
          <h2 class="display"><span class="ln"><span style="--i:1">Totul începe</span></span><span class="ln"><span style="--i:2">cu drumul</span></span><span class="ln"><span style="--i:3">spre școală</span></span></h2>
          <p class="lead rv" style="--i:4">Vrem să reducem consumul de combustibil ce ne poluează planeta, iar totul începe cu pași mici, incluzând drumul zilnic până la școală.</p>
          <div class="actions rv" style="--i:5">
            <a class="btn" href="#chestionar">Începe chestionarul</a>
          </div>
        </div></div>
      </article>

    </div>
  </div>
  <div class="hero-ctl">
    <div class="wrap ctl-row">
      <span class="cnt" id="cnt" aria-live="off">01 / 03</span>
      <div class="segs">
        <button type="button" class="seg" data-go="0" aria-label="Mergi la panoul 1"><i></i></button>
        <button type="button" class="seg" data-go="1" aria-label="Mergi la panoul 2"><i></i></button>
        <button type="button" class="seg" data-go="2" aria-label="Mergi la panoul 3"><i></i></button>
      </div>
      <div class="arrows">
        <button type="button" class="arrow" id="prev" aria-label="Panoul anterior"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg></button>
        <button type="button" class="arrow" id="next-slide" aria-label="Panoul următor"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
      </div>
    </div>
  </div>
</section>

<section id="chestionar" class="survey">
  <div class="wrap survey-grid">
    <aside class="side">
      <p class="eyebrow">Chestionar</p>
      <h2 class="display">Cum ajungi dimineața la școală?</h2>
      <p class="lead">Cel mult douăsprezece întrebări, cam patru minute. Răspunsurile sunt anonime. Nu cerem numele sau adresa de acasă.</p>
      <ol class="rail" id="rail" aria-label="Întrebările chestionarului"></ol>
    </aside>

    <form class="card" id="card" novalidate>
      <div class="card-top">
        <span class="qid" id="qid">Întrebarea 01 din 12</span>
        <div class="meter" aria-hidden="true"><i id="meterFill"></i></div>
      </div>
      <div class="stage" id="stage" aria-live="polite"></div>
      <div class="card-nav" id="nav">
        <button type="button" class="btn btn-ghost" id="back" hidden>Înapoi</button>
        <button type="submit" class="btn" id="next" disabled>Continuă</button>
      </div>
    </form>
  </div>
</section>

<section id="statistici" class="stats" aria-labelledby="st-title">
  <div class="wrap">
    <div class="st-head">
      <p class="eyebrow">Statistici</p>
      <h2 class="display" id="st-title">Ce spun elevii din Iași</h2>
      <p class="lead">Răspunsurile din chestionar, adunate la un loc: cum ajung elevii la școală, cât timp petrec în trafic și ce schimbări și-ar dori.</p>
    </div>
    <p class="st-note" id="stNote" role="note" hidden></p>
    <div id="stBody"></div>
  </div>
</section>

<section id="harta" class="mapsec" aria-labelledby="mp-title">
  <div class="wrap">
    <div class="st-head">
      <p class="eyebrow">Harta</p>
      <h2 class="display" id="mp-title">Pe unde merg elevii dimineața</h2>
      <p class="lead">Zonele din Iași trecute de elevi în traseele lor. Cu cât sunt mai aglomerate, cu atât culoarea se apropie de roșu. Cele mai rar menționate sunt verzi.</p>
    </div>
    <p class="st-note" id="mpNote" role="note" hidden></p>
    <div id="mpBody" class="mp-body"></div>
    <p class="p-foot mp-foot">Harta arată cartierele și reperele pe care le-au scris elevii, nu străzile exacte, iar pozițiile sunt aproximative. Apar doar zonele menționate de cel puțin 2 elevi.</p>
  </div>
</section>

<section id="despre" class="about" aria-labelledby="ab-title">
  <svg class="about-mark" viewBox="0 0 400 480" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true"><ellipse cx="200" cy="240" rx="15" ry="21"/><ellipse cx="200" cy="240" rx="32" ry="38"/><ellipse cx="200" cy="240" rx="46" ry="55"/><ellipse cx="200" cy="240" rx="57" ry="72"/><ellipse cx="200" cy="240" rx="70" ry="93"/><ellipse cx="200" cy="240" rx="85" ry="110"/><ellipse cx="200" cy="240" rx="102" ry="127"/><ellipse cx="200" cy="240" rx="116" ry="144"/><ellipse cx="200" cy="240" rx="127" ry="165"/><ellipse cx="200" cy="240" rx="140" ry="182"/><ellipse cx="200" cy="240" rx="155" ry="199"/><ellipse cx="200" cy="240" rx="172" ry="216"/><ellipse cx="200" cy="240" rx="186" ry="237"/></svg>
  <div class="wrap about-grid">
    <div>
      <p class="eyebrow">Despre noi</p>
      <h2 class="display" id="ab-title">O echipă de elevi care vrea o schimbare</h2>
    </div>
    <div class="about-copy">
      <p class="lead">Suntem o echipă de elevi de clasa a IX-a, dornici să facem o schimbare în orașul Iași.</p>
      <p class="quote">Înțelegem prin ce trec elevii în fiecare dimineață, căci și noi trecem prin aceleași cozi nesfârșite de trafic. De aceea ne punem toată încrederea că, împreună, ne vom mobiliza spre formarea unui oraș green.</p>
    </div>
  </div>
  <div class="wrap"><p class="about-big">Împreună pentru un Iași <em>mai verde.</em></p><p class="about-tag">#echipaCarbonIT</p></div>
</section>
</main>

<footer class="foot">
  <div class="wrap foot-row">
    <span>© 2026 CarbonIt Movement · Iași</span>
    <span>Chestionar anonim. Nu cerem numele, adresa de acasă sau alte date personale.</span>
  </div>
</footer>

<script src="/assets/stats.js"></script>
<script src="/assets/map.js"></script>
<script>
(function(){
'use strict';
const root=document.documentElement; root.lang='ro';
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
const API_BASE='/api/';
const $=(s,r)=>(r||document).querySelector(s);
const $$=(s,r)=>Array.from((r||document).querySelectorAll(s));
const pad=n=>String(n).padStart(2,'0');
const esc=s=>String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

/* ---------- Derulare lină la rubrici ---------- */
let glideRaf=0;
function glideTo(y){
  const start=window.scrollY, dist=y-start;
  cancelAnimationFrame(glideRaf);
  if(reduce||Math.abs(dist)<2){ window.scrollTo(0,y); return; }
  const dur=Math.min(1400,Math.max(700,Math.abs(dist)*.5)), t0=performance.now();
  const ease=t=>t<.5?4*t*t*t:1-Math.pow(-2*t+2,3)/2;
  const evs=['wheel','touchstart','keydown'];
  const stop=()=>{cancelAnimationFrame(glideRaf);evs.forEach(ev=>window.removeEventListener(ev,stop));};
  evs.forEach(ev=>window.addEventListener(ev,stop,{passive:true}));
  const step=now=>{
    const p=Math.min(1,(now-t0)/dur);
    window.scrollTo(0,start+dist*ease(p));
    if(p<1) glideRaf=requestAnimationFrame(step); else stop();
  };
  glideRaf=requestAnimationFrame(step);
}
document.addEventListener('click',e=>{
  const a=e.target.closest('a[href^="#"]'); if(!a) return;
  const id=a.getAttribute('href').slice(1), el=document.getElementById(id); if(!el) return;
  e.preventDefault();
  glideTo(id==='acasa'?0:Math.max(0,el.getBoundingClientRect().top+window.scrollY-64));
});
const spyIds=['acasa','chestionar','statistici','harta','despre'];
const tabs=$$('.bar nav a');
function spy(){
  const y=64+Math.min(160,window.innerHeight*.2);
  let now='acasa';
  spyIds.forEach(id=>{const el=document.getElementById(id); if(el&&el.getBoundingClientRect().top<=y) now=id;});
  tabs.forEach(t=>t.setAttribute('aria-current',t.getAttribute('href')==='#'+now?'true':'false'));
}
let spyTick=false;
window.addEventListener('scroll',()=>{ if(spyTick) return; spyTick=true; requestAnimationFrame(()=>{spyTick=false;spy();}); },{passive:true});
window.addEventListener('resize',spy);
spy();

try{
/* ---------- Amprenta (canvas) ---------- */
const cv=$('#print'), ctx=cv.getContext('2d');
let W=0,H=0,dpr=1,running=false,last=0;
const col={ridge:'#2e5f58',heat:'#d9531e'};
const POSES=[{rot:-.15,gap:.030,sway:0},{rot:.30,gap:.026,sway:1.6},{rot:.75,gap:.034,sway:3.2}];
const P=Object.assign({},POSES[0]); let T=POSES[0];
const SEG=150;
function readCols(){
  const cs=getComputedStyle(root);
  col.ridge=cs.getPropertyValue('--ridge').trim()||col.ridge;
  col.heat=cs.getPropertyValue('--heat').trim()||col.heat;
}
function resize(){
  const r=cv.getBoundingClientRect();
  dpr=Math.min(window.devicePixelRatio||1,2); W=r.width; H=r.height;
  cv.width=Math.max(1,Math.round(W*dpr)); cv.height=Math.max(1,Math.round(H*dpr));
}
function ring(u,t,cx,cy,rx,ry){
  ctx.beginPath();
  for(let j=0;j<=SEG;j++){
    const th=j/SEG*Math.PI*2;
    const w=u*u*(.10*Math.sin(3*th+5*u+t*.35+P.sway)+.06*Math.sin(5*th-7*u-t*.25)+.04*Math.sin(2*th+11*u+t*.2));
    const rr=u+w, a=th+P.rot*u*3;
    const x=cx+Math.cos(a)*rr*rx, y=cy+Math.sin(a)*rr*ry;
    if(j) ctx.lineTo(x,y); else ctx.moveTo(x,y);
  }
  ctx.closePath();
}
function draw(t){
  if(!W||!H) return;
  ctx.setTransform(dpr,0,0,dpr,0,0); ctx.clearRect(0,0,W,H);
  const cx=W*.56, cy=H*.5, R=Math.min(W*.5,H*.5), rx=R*.80, ry=R*1.02;
  ctx.save(); ctx.translate(cx,cy); ctx.rotate(-.18); ctx.translate(-cx,-cy);
  ctx.lineWidth=1.25; ctx.strokeStyle=col.ridge;
  const n=Math.floor(1/P.gap);
  for(let k=1;k<=n;k++){
    const u=k*P.gap;
    ctx.globalAlpha=Math.max(0,.9*(1-Math.pow(u,2.4)));
    ring(u,t,cx,cy,rx,ry); ctx.stroke();
  }
  if(!reduce){
    ctx.strokeStyle=col.heat; ctx.lineWidth=2.4;
    [0,.5].forEach(o=>{
      const u=(t*.09+o)%1;
      ctx.globalAlpha=Math.max(0,.85*(1-u)*Math.min(1,u*8));
      ring(Math.max(u,.02),t,cx,cy,rx,ry); ctx.stroke();
    });
  }
  ctx.restore(); ctx.globalAlpha=1;
}
function frame(ts){
  if(!running) return;
  requestAnimationFrame(frame);
  if(ts-last<32) return; last=ts;
  P.rot+=(T.rot-P.rot)*.05; P.gap+=(T.gap-P.gap)*.05; P.sway+=(T.sway-P.sway)*.05;
  draw(ts/1000);
}
function startPrint(){ if(running||reduce) return; running=true; requestAnimationFrame(frame); }
function stopPrint(){ running=false; }
function setPose(i){ T=POSES[i]; if(reduce){ Object.assign(P,T); draw(0); } }
try{readCols(); resize(); draw(0);}catch(_){}
if('ResizeObserver' in window) new ResizeObserver(()=>{resize();draw(performance.now()/1000);}).observe(cv);
if('IntersectionObserver' in window) new IntersectionObserver(es=>{es[0].isIntersecting?startPrint():stopPrint();}).observe($('#acasa'));
else startPrint();
document.addEventListener('visibilitychange',()=>{ if(document.hidden) stopPrint(); else startPrint(); });
const recolor=()=>{readCols(); if(reduce) draw(0);};
try{const mq=matchMedia('(prefers-color-scheme: dark)'); if(mq.addEventListener) mq.addEventListener('change',recolor); else if(mq.addListener) mq.addListener(recolor);}catch(_){}
try{new MutationObserver(recolor).observe(root,{attributes:true,attributeFilter:['data-theme']});}catch(_){}

/* ---------- Hero care glisează ---------- */
const track=$('#track'), slides=$$('.slide'), segs=$$('.seg'), cnt=$('#cnt'), vp=$('#viewport');
const N=slides.length, DUR=8000;
let cur=0, elapsed=0, paused=false;
function go(n){
  cur=(n+N)%N; elapsed=0;
  track.style.setProperty('--n',cur);
  slides.forEach((s,i)=>{ s.classList.toggle('is-active',i===cur); try{s.inert=i!==cur;}catch(_){} s.setAttribute('aria-hidden',i===cur?'false':'true'); });
  segs.forEach((b,i)=>{
    b.setAttribute('aria-current',i===cur?'true':'false');
    b.firstElementChild.style.transform=(i<cur||(reduce&&i===cur))?'scaleX(1)':'scaleX(0)';
  });
  cnt.textContent=pad(cur+1)+' / '+pad(N);
  setPose(cur);
}
$$('[data-go]',$('#acasa')).forEach(b=>b.addEventListener('click',()=>go(Number(b.dataset.go))));
$('#prev').addEventListener('click',()=>go(cur-1));
$('#next-slide').addEventListener('click',()=>go(cur+1));
vp.addEventListener('keydown',e=>{ if(e.key==='ArrowRight') go(cur+1); else if(e.key==='ArrowLeft') go(cur-1); });
let sx=null,sy=0;
vp.addEventListener('pointerdown',e=>{ if(e.pointerType==='mouse') return; sx=e.clientX; sy=e.clientY; });
vp.addEventListener('pointerup',e=>{
  if(sx==null) return;
  const dx=e.clientX-sx, dy=e.clientY-sy; sx=null;
  if(Math.abs(dx)>50&&Math.abs(dx)>Math.abs(dy)*1.5) go(cur+(dx<0?1:-1));
});
vp.addEventListener('pointercancel',()=>{sx=null;});
const hero=$('#acasa');
hero.addEventListener('mouseenter',()=>{paused=true;});
hero.addEventListener('mouseleave',()=>{paused=false;});
hero.addEventListener('focusin',()=>{paused=true;});
hero.addEventListener('focusout',()=>{paused=false;});
if(!reduce){
  setInterval(()=>{
    if(paused||document.hidden) return;
    elapsed+=100;
    segs[cur].firstElementChild.style.transform='scaleX('+Math.min(elapsed/DUR,1)+')';
    if(elapsed>=DUR) go(cur+1);
  },100);
}
go(0);
}catch(err){ if(window.console) console.error(err); }

/* ---------- Chestionar ---------- */
const MODES=[['foot','Pe jos'],['bike','Bicicletă'],['public','Transport public'],['car','Mașină, cu familia sau colegi'],['taxi','Taxi'],['other','Altul']];
const carDays=a=>a.modes?a.modes.car:0;
const hasCar=a=>carDays(a)>0;
const Q=[
 {n:1,key:'student',rail:'Elev în Iași',title:'Ești elev la o școală sau un liceu din Iași?',type:'single',cols:2,opts:[['da','Da'],['nu','Nu']]},
 {n:2,key:'days',rail:'Zile la școală',title:'În câte zile ai fost fizic la școală?',hint:'Numără zilele din ultima săptămână de școală. Dacă alegi 0, nu mai cerem detalii despre drum.',type:'chips',range:()=>[0,5],unit:'zile'},
 {n:3,key:'modes',rail:'Cum ai ajuns',title:'Cum ai ajuns dimineața la școală?',hint:'Împarte zilele din întrebarea 02 pe tipuri de drum. Totalul trebuie să fie egal cu numărul lor. Dacă ai folosit mai multe mijloace într-o zi, alege segmentul cel mai lung.',type:'modes'},
 {n:4,key:'dist',rail:'Lungimea drumului',title:'Cam cât de lung este drumul într-un singur sens?',hint:'Doar o estimare.',type:'single',opts:[['sub1','Sub 1 km'],['1-3','1–3 km'],['3-5','3–5 km'],['5-10','5–10 km'],['peste10','Peste 10 km'],['nu_stiu','Nu știu']]},
 {n:5,key:'traffic',rail:'Timp în trafic',title:'Cât timp stai de obicei în trafic, într-un singur sens?',hint:'Estimează timpul în care te deplasezi greu sau stai pe loc, inclusiv așteptarea la semafoare și în stații.',type:'single',when:a=>!!a.modes&&(a.modes.car+a.modes.public+a.modes.taxi+a.modes.other)>0,opts:[['deloc','Aproape deloc'],['sub5','Sub 5 minute'],['5-10','5–10 minute'],['10-20','10–20 minute'],['20-30','20–30 de minute'],['peste30','Peste 30 de minute'],['nu_stiu','Nu știu']]},
 {n:6,key:'route',rail:'Traseul tău',title:'Pe ce traseu mergi de obicei dimineața?',hint:'Scrie străzile sau zonele principale pe care treci, de exemplu: Podu Roș – Piața Unirii – Copou. Nu scrie adresa ta exactă de acasă.',type:'text'},
 {n:7,key:'why',rail:'De ce face șoferul drumul',title:'Dacă folosești mașina, de ce face șoferul acel drum?',hint:'Drumurile cu taxiul le analizăm separat.',type:'single',when:hasCar,opts:[['special','Special pentru școală','Altfel nu ar fi mers pe acolo'],['ocol','Face un ocol','Se abate de la drumul lui obișnuit'],['oricum','Merge oricum pe acolo','Școala e pe traseul lui'],['diferit','Situații diferite','Depinde de zi'],['nu_stiu','Nu știu']]},
 {n:8,key:'shared',rail:'Alți elevi în mașină',title:'Mai vin și alți elevi cu aceeași mașină?',type:'single',when:hasCar,opts:[['nu','Nu'],['da','Da'],['uneori','Uneori'],['nu_stiu','Nu știu']]},
 {n:9,key:'alt',rail:'Alternativă realistă',title:'Ce alternativă ai putea încerca realist?',hint:'Schimbarea se discută cu familia, când este cazul.',type:'single',when:hasCar,opts:[['foot','Pe jos'],['bike','Bicicletă'],['public','Transport public'],['other','Altă variantă'],['niciuna','Niciuna acum']]},
 {n:10,key:'replace',rail:'Dimineți fără mașină',title:'Dacă ai repeta săptămâna raportată, în câte dimineți ai putea înlocui mașina?',type:'chips',range:a=>[0,carDays(a)],unit:'dimineți',when:a=>hasCar(a)&&a.alt!=='niciuna'},
 {n:11,key:'block',rail:'Ce te împiedică',title:'Ce te împiedică cel mai mult?',hint:'Alege cel mult 2 răspunsuri.',type:'multi',max:2,when:hasCar,opts:[['dist','Distanța'],['timp','Timpul'],['legaturi','Legăturile de transport'],['traseu','Traseul dificil'],['orar','Orarul'],['vreme','Vremea'],['alt','Alt motiv'],['nu_raspund','Prefer să nu răspund']]},
 {n:12,key:'help',rail:'Ce te-ar ajuta',title:'Ce te-ar ajuta?',hint:'Poți alege mai multe răspunsuri.',type:'multi',comment:true,when:hasCar,opts:[['info','Informații despre transport'],['rute','Adăugarea unor noi rute de autobuz/tramvai'],['piste','Implementarea pistelor pentru bicicletă'],['altceva','Altceva']]}
];
const TEXTS={
  not_student:{t:'Mulțumim pentru răspuns!',p:'Chestionarul este pentru elevii din Iași, așa că răspunsul tău nu intră în statistica elevilor.'},
  zero_days:{t:'Mulțumim, am terminat!',p:'Fiindcă nu ai fost la școală în acea săptămână, nu mai avem nevoie de detalii despre drum.'},
  no_car:{t:'Mulțumim, am terminat!',p:'Nu ai venit cu mașina, așa că nu mai avem alte întrebări pentru tine. Drumurile mai curate contează.'},
  complete:{t:'Mulțumim pentru răspunsuri!',p:'Ne ajută să vedem ce schimbări sunt realiste pentru drumul spre școală.'}
};

const form=$('#card'), stage=$('#stage'), qidEl=$('#qid'), meter=$('#meterFill'), rail=$('#rail'), nav=$('#nav'), backBtn=$('#back'), nextBtn=$('#next');
let a={}, i=0, hist=[], fin=null, busy=false;

const show=q=>q.when?q.when(a):true;
function nextIdx(from){ for(let j=from+1;j<Q.length;j++) if(show(Q[j])) return j; return -1; }
const total=m=>MODES.reduce((s,[k])=>s+(m[k]||0),0);

function valid(){
  const q=Q[i], v=a[q.key];
  switch(q.type){
    case 'single': return v!==undefined;
    case 'chips': return typeof v==='number';
    case 'modes': return !!v&&total(v)===a.days;
    case 'multi': return Array.isArray(v)&&v.length>0;
    case 'text': return true;
  }
  return false;
}
function endReason(){
  const q=Q[i];
  if(q.key==='student'&&a.student==='nu') return 'not_student';
  if(q.key==='days'&&a.days===0) return 'zero_days';
  if(nextIdx(i)<0) return hasCar(a)?'complete':'no_car';
  return null;
}

function optHTML(type,name,val,label,desc,checked){
  return '<label class="opt"><input type="'+type+'" name="'+name+'" value="'+val+'"'+(checked?' checked':'')+'><span class="mark '+(type==='radio'?'r':'c')+'" aria-hidden="true"></span><span class="otx"><span class="ol">'+label+'</span>'+(desc?'<span class="od">'+desc+'</span>':'')+'</span></label>';
}
function bodyHTML(q){
  const v=a[q.key];
  if(q.type==='single'){
    return '<div class="opts'+(q.cols===2?' two':'')+'" role="radiogroup" aria-labelledby="qt">'+q.opts.map(o=>optHTML('radio','q',o[0],o[1],o[2],v===o[0])).join('')+'</div>';
  }
  if(q.type==='chips'){
    const r=q.range(a); let h='';
    for(let k=r[0];k<=r[1];k++) h+='<label class="chip"><input type="radio" name="q" value="'+k+'"'+(v===k?' checked':'')+'><span>'+k+'</span></label>';
    const note=q.key==='days'?'Zile de școală în ultima săptămână, între 0 și 5.':'Între 0 și '+r[1]+', cel mult zilele în care ai venit cu mașina (întrebarea 03). Este un scenariu pentru același program, nu o promisiune. Ceilalți elevi pot susține campania.';
    return '<div class="chips" role="radiogroup" aria-labelledby="qt">'+h+'</div><p class="chip-note">'+note+'</p>';
  }
  if(q.type==='modes'){
    if(!a.modes) a.modes={foot:0,bike:0,public:0,car:0,taxi:0,other:0};
    return '<div class="modes">'+MODES.map(([k,l])=>'<div class="mode"><span class="mode-name">'+l+'</span><div class="stepper"><button type="button" data-m="'+k+'" data-d="-1" aria-label="Scade zilele: '+l+'">−</button><output id="v-'+k+'" aria-live="polite">0</output><button type="button" data-m="'+k+'" data-d="1" aria-label="Adaugă o zi: '+l+'">+</button></div></div>').join('')+'</div><p class="mtot" id="mtot" role="status"></p>';
  }
  if(q.type==='text'){
    return '<div class="field"><label for="rt">Traseul tău (opțional)</label><textarea id="rt" rows="4" maxlength="300" placeholder="De exemplu: Podu Roș – Piața Unirii – Copou">'+esc(a.route||'')+'</textarea><small>Poți lăsa câmpul gol dacă preferi.</small></div>';
  }
  if(q.type==='multi'){
    const arr=Array.isArray(v)?v:[];
    let h='<div class="opts" role="group" aria-labelledby="qt">'+q.opts.map(o=>optHTML('checkbox','q',o[0],o[1],o[2],arr.includes(o[0]))).join('')+'</div>';
    if(q.comment) h+='<div class="field"><label for="cm">Comentariu (opțional)</label><textarea id="cm" rows="3" maxlength="300" placeholder="Scrie aici, fără nume sau adrese.">'+esc(a.comment||'')+'</textarea><small>Nu include nume, adrese sau alte date personale.</small></div>';
    return h;
  }
  return '';
}
function syncModes(){
  const m=a.modes, sum=total(m);
  MODES.forEach(([k])=>{
    $('#v-'+k).textContent=m[k];
    $('[data-m="'+k+'"][data-d="-1"]').disabled=m[k]<=0;
    $('[data-m="'+k+'"][data-d="1"]').disabled=sum>=a.days;
  });
  const box=$('#mtot'), ok=sum===a.days;
  box.className='mtot'+(ok?' ok':'');
  box.textContent=ok?'Totalul se potrivește: '+sum+' din '+a.days+' zile.':'Ai repartizat '+sum+' din '+a.days+' zile.';
}
function syncMulti(q){
  if(!q.max) return;
  const arr=a[q.key]||[], full=arr.length>=q.max;
  $$('input[type=checkbox]',stage).forEach(cb=>{ cb.disabled=full&&!cb.checked&&cb.value!=='nu_raspund'; });
}

function updateNav(){
  const ok=valid(); nextBtn.disabled=!ok;
  const r=ok?endReason():null;
  nextBtn.textContent=r?((r==='complete'||r==='no_car')?'Trimite răspunsurile':'Încheie'):'Continuă';
  backBtn.hidden=hist.length===0;
}
function renderRail(){
  rail.innerHTML=Q.map((q,j)=>{
    let s='pending';
    if(fin) s=a[q.key]!==undefined?'done':'skipped';
    else if(j===i) s='current';
    else if(hist.includes(j)) s='done';
    else if(j<i) s='skipped';
    return '<li data-s="'+s+'"><span class="rn">'+pad(q.n)+'</span><span class="rt">'+q.rail+(s==='skipped'?'<span class="sr"> (sărită)</span>':'')+'</span></li>';
  }).join('');
}
function renderQuestion(){
  const q=Q[i];
  stage.innerHTML='<h3 class="q-title" id="qt" tabindex="-1">'+q.title+'</h3>'+(q.hint?'<p class="q-hint">'+q.hint+'</p>':'')+bodyHTML(q);
  qidEl.textContent='Întrebarea '+pad(q.n)+' din 12';
  meter.style.transform='scaleX('+(q.n/12)+')';
  nav.hidden=false;
  if(q.type==='modes') syncModes();
  if(q.type==='multi') syncMulti(q);
  renderRail(); updateNav();
}
function ensureVisible(){
  const r=form.getBoundingClientRect();
  if(r.top<84) window.scrollTo({top:window.scrollY+r.top-96,behavior:reduce?'auto':'smooth'});
}
async function swap(dir,fn){
  busy=true;
  if(!reduce){
    const out=stage.animate([{opacity:1,transform:'none'},{opacity:0,transform:'translateX('+(-24*dir)+'px)'}],{duration:200,easing:'ease-in',fill:'forwards'});
    try{await out.finished;}catch(_){}
    out.cancel();
  }
  fn();
  if(!reduce) stage.animate([{opacity:0,transform:'translateX('+(28*dir)+'px)'},{opacity:1,transform:'none'}],{duration:380,easing:'cubic-bezier(.2,.8,.2,1)'});
  const t=$('.q-title',stage)||stage; t.focus({preventScroll:true});
  ensureVisible(); busy=false;
}

function prune(){
  Q.forEach(q=>{ if(!show(q)) delete a[q.key]; });
  if(!show(Q[Q.length-1])) delete a.comment;
  if(hasCar(a)&&a.alt==='niciuna') a.replace=0;
  if(typeof a.replace==='number'&&a.replace>carDays(a)) a.replace=carDays(a);
}
async function trimite(payload){
  if(!API_BASE){ (window.__raspunsuriAmprenta=window.__raspunsuriAmprenta||[]).push(payload); return; }
  const res=await fetch(API_BASE+'submit',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
  let j=null; try{ j=await res.json(); }catch(_){}
  if(!res.ok||!j||!j.ok){ const e=new Error('trimitere'); e.status=res.status; throw e; }
}
async function finish(reason){
  if(busy) return;
  prune();
  const payload={
    trimisLa:new Date().toISOString(),
    stare:reason,
    inStatisticaElevilor:reason!=='not_student',
    taxiAnalizaSeparata:!!(a.modes&&a.modes.taxi>0),
    raspunsuri:JSON.parse(JSON.stringify(a))
  };
  busy=true; nextBtn.disabled=true; nextBtn.textContent='Se trimite…';
  const old=$('.send-err',stage); if(old) old.remove();
  try{ await trimite(payload); }
  catch(err){
    busy=false; updateNav();
    const p=document.createElement('p'); p.className='send-err'; p.setAttribute('role','alert');
    p.textContent=(err&&err.status===429)?'Prea multe răspunsuri de pe această rețea într-un timp scurt. Încearcă din nou peste câteva minute.':'Nu am putut trimite răspunsurile. Verifică conexiunea la internet și încearcă din nou.';
    stage.appendChild(p); return;
  }
  busy=false; fin=reason;
  const tx=TEXTS[reason];
  let extra='';
  if(reason==='complete'&&typeof a.replace==='number'){
    const c=carDays(a);
    extra='<p class="scn">Scenariul tău: '+a.replace+' din '+c+' '+(c===1?'dimineață':'dimineți')+' fără mașină. Este un scenariu, nu o promisiune.</p>';
  }
  swap(1,()=>{
    stage.innerHTML='<div class="done"><svg viewBox="0 0 52 52" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="26" cy="26" r="23"/><path d="M16 27l7 7 14-15"/></svg><h3 class="q-title" id="qt" tabindex="-1">'+tx.t+'</h3><p>'+tx.p+'</p>'+extra+'<button type="button" class="btn btn-ghost" id="again">Completează din nou</button></div>';
    qidEl.textContent='Gata';
    meter.style.transform='scaleX(1)';
    nav.hidden=true;
    renderRail();
  });
}
function advance(){
  if(busy||!valid()) return;
  const r=endReason();
  if(r){ finish(r); return; }
  hist.push(i); i=nextIdx(i);
  swap(1,renderQuestion);
}
function back(){
  if(busy||!hist.length) return;
  i=hist.pop();
  swap(-1,renderQuestion);
}
function reset(){
  if(busy) return;
  a={}; i=0; hist=[]; fin=null;
  swap(-1,renderQuestion);
}

form.addEventListener('submit',e=>{e.preventDefault();advance();});
backBtn.addEventListener('click',back);
stage.addEventListener('click',e=>{
  if(e.target.closest('#again')){ reset(); return; }
  const b=e.target.closest('button[data-m]'); if(!b) return;
  const k=b.dataset.m, d=Number(b.dataset.d), m=a.modes;
  if(m[k]+d<0||total(m)+d>a.days) return;
  m[k]+=d; syncModes(); updateNav();
});
stage.addEventListener('change',e=>{
  const t=e.target; if(!(t instanceof HTMLInputElement)) return;
  const q=Q[i];
  if(t.type==='radio'){
    const v=(q.type==='chips')?Number(t.value):t.value;
    if(q.key==='days'&&a.days!==v&&a.modes&&total(a.modes)!==v) delete a.modes;
    a[q.key]=v;
  }else if(t.type==='checkbox'){
    let arr=$$('input[type=checkbox]',stage).filter(x=>x.checked).map(x=>x.value);
    if(q.max){
      if(t.value==='nu_raspund'&&t.checked) arr=['nu_raspund'];
      else arr=arr.filter(x=>x!=='nu_raspund');
      if(arr.length>q.max){ arr=arr.filter(x=>x!==t.value); }
      $$('input[type=checkbox]',stage).forEach(cb=>{cb.checked=arr.includes(cb.value);});
    }
    a[q.key]=arr; syncMulti(q);
  }
  updateNav();
});
stage.addEventListener('input',e=>{ if(e.target.id==='cm') a.comment=e.target.value.slice(0,300); if(e.target.id==='rt') a.route=e.target.value.slice(0,300); });

/* ---------- Statistici și hartă ---------- */
const stBody=$('#stBody'), stNote=$('#stNote'), mpBody=$('#mpBody'), mpNote=$('#mpNote');
function showMap(zones,sample){
  try{
    if(mpNote){ mpNote.hidden=!sample; if(sample) mpNote.innerHTML='<b>Date de exemplu</b><span>Zonele colorate de mai jos sunt simulate. Pe site-ul publicat harta se completează din traseele reale ale elevilor.</span>'; }
    if(window.AmprentaMap) AmprentaMap.mount(mpBody,{zones:zones});
  }catch(err){ if(window.console) console.error(err); mpBody.innerHTML='<p class="empty">Harta nu a putut fi afișată.</p>'; }
}
function loadStats(){
  try{
    if(!API_BASE){
      const rows=AmprentaSample();
      AmprentaStats.render({body:stBody,note:stNote,rows:rows,sample:true});
      showMap(AmprentaSampleZones(rows),true);
      return;
    }
    AmprentaStats.loading(stBody);
    mpBody.innerHTML='<p class="empty">Se încarcă harta…</p>';
    fetch(API_BASE+'stats',{headers:{'Accept':'application/json'}})
      .then(r=>{ if(!r.ok) throw new Error('http'); return r.json(); })
      .then(d=>{ if(!d||!d.ok) throw new Error('date'); AmprentaStats.render({body:stBody,note:stNote,rows:d.responses,sample:false}); showMap(d.zones||[],false); })
      .catch(()=>{ AmprentaStats.error(stBody,loadStats); AmprentaStats.error(mpBody,loadStats); });
  }catch(err){ if(window.console) console.error(err); }
}
loadStats();

renderQuestion();
})();
</script>

</body>
</html>
@endverbatim
