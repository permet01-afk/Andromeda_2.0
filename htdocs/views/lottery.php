<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/lottery.css?v=7" />

<div class="CMSContent lottery-wrapper">
  <section class="lottery">
    <header class="lottery__header">
      <h1>Andromeda Lottery</h1>
      <p>Draw instant rewards ranging from rare boosters to resources. Each spin adds a new entry to your recent rewards log.</p>
    </header>

    <div class="lottery__layout">
      <!-- 🎲 Zone tirage -->
      <article class="lottery-card">
        <header class="lottery-card__header">
          <h2>Try your luck</h2>
          <p>Press play to receive a random reward. The most recent drops appear at the top of your log.</p>
        </header>

        <div class="lottery-card__body">
          <div id="ticketCounter" class="lottery-tickets">
            🎟️ Tickets left: <strong id="ticketValue">—</strong>
          </div>

          <div id="rewards" class="lottery-log" aria-live="polite" aria-atomic="false"></div>

          <button id="bgen" class="lottery-button">Play lottery</button>
        </div>
      </article>

      <!-- 🚀 Zone thème spatial + animation -->
      <article class="lottery-card">
        <header class="lottery-card__header">
          <h2>Star Dock Lottery</h2>
          <p>Rewards beamed from the Andromeda star-dock. Chances shown in the drop table below.</p>
        </header>

        <div class="lottery-card__body lottery-card__body--chart">
          <!-- Holographic bay -->
          <div class="starbay" id="starbay">
            <div class="stars"></div>
            <div class="twinkle"></div>
            <div class="orbit"></div>

            <!-- Effets spéciaux -->
            <div class="beam"></div>
            <div class="strike"></div>
            <div class="shockwave"></div>
            <div class="sparks"></div>

            <!-- Vaisseau holographique -->
            <div class="ship">
              <svg viewBox="0 0 200 120" width="200" height="120" aria-hidden="true">
                <defs>
                  <linearGradient id="holo" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#7dd3fc" stop-opacity="0.85"/>
                    <stop offset="100%" stop-color="#c084fc" stop-opacity="0.85"/>
                  </linearGradient>
                  <filter id="glow">
                    <feGaussianBlur stdDeviation="3.5" result="coloredBlur"/>
                    <feMerge>
                      <feMergeNode in="coloredBlur"/>
                      <feMergeNode in="SourceGraphic"/>
                    </feMerge>
                  </filter>
                </defs>
                <path d="M100 10 L120 35 L180 45 L120 55 L100 110 L80 55 L20 45 L80 35 Z"
                      fill="url(#holo)" filter="url(#glow)" opacity="0.9"/>
              </svg>
            </div>
          </div>

          <!-- Drop table -->
          <ul class="space-legend">
            <li class="pill"><span class="dot" style="background:#38bdf8"></span><span class="text">a Token</span><span class="pct">5%</span></li>
            <li class="pill"><span class="dot" style="background:#60a5fa"></span><span class="text">NPC booster (2h)</span><span class="pct">20%</span></li>
            <li class="pill"><span class="dot" style="background:#f472b6"></span><span class="text">80–120 Xenomits</span><span class="pct">20%</span></li>
            <li class="pill"><span class="dot" style="background:#34d399"></span><span class="text">HP/DMG/Shield booster (4h)</span><span class="pct">25%</span></li>
            <li class="pill"><span class="dot" style="background:#facc15"></span><span class="text">500 Promeriums</span><span class="pct">10%</span></li>
            <li class="pill"><span class="dot" style="background:#a855f7"></span><span class="text">Speed booster (2h)</span><span class="pct">20%</span></li>
          </ul>
        </div>
      </article>
    </div>
  </section>
</div>

<!-- ====== JS principal ====== -->
<script>
(function() {
  const logBox = document.getElementById('rewards');
  const btn = document.getElementById('bgen');
  const ticketValue = document.getElementById('ticketValue');
  const ticketCounter = document.getElementById('ticketCounter');
  const starbay = document.getElementById('starbay');

  // Endpoints (chemins absolus si ton site est à la racine)
  const ENDPOINT   = '/views/lottery/generate.php';
  const TICKET_API = '/views/lottery/get_tickets.php';

  function prependLog(text, isError = false) {
    const entry = document.createElement('div');
    entry.className = 'lottery-log__entry';
    entry.textContent = text;
    if (isError) entry.style.color = '#ff8080';
    logBox.prepend(entry);
  }

  function setTicketsLeft(n) {
    const num = Number(n) || 0;
    ticketValue.textContent = num;

    if (num <= 0) {
      btn.disabled = true;
      btn.textContent = 'No tickets';
      if (!ticketCounter.querySelector('.buy-link')) {
        const a = document.createElement('a');
        a.href = 'view.php?page=shop&tab=items';
        a.className = 'buy-link';
        a.textContent = 'Buy tickets';
        a.style.marginLeft = '8px';
        ticketCounter.appendChild(a);
      }
    } else {
      btn.disabled = false;
      btn.textContent = 'Play lottery';
      const link = ticketCounter.querySelector('.buy-link');
      if (link) link.remove();
    }
  }

  async function updateTickets() {
    try {
      const resp = await fetch(TICKET_API, { credentials: 'same-origin' });
      const txt = (await resp.text()).trim();
      setTicketsLeft(/^\d+$/.test(txt) ? txt : 0);
    } catch {
      setTicketsLeft(0);
    }
  }

  // ⚡️ animation rayon + éclair + onde + étincelles
  function triggerLootAnimation() {
    starbay.classList.remove('beam-anim','strike-anim','boom-anim','loot-anim');
    // force reflow pour relancer proprement
    void starbay.offsetWidth;
    starbay.classList.add('beam-anim','strike-anim','boom-anim','loot-anim');
    setTimeout(() => {
      starbay.classList.remove('beam-anim','strike-anim','boom-anim','loot-anim');
    }, 1800);
  }

  async function playLottery() {
    // sécurité : pas de tirage si 0 ticket
    const r = await fetch(TICKET_API, { credentials: 'same-origin' });
    const t = (await r.text()).trim();
    const current = /^\d+$/.test(t) ? Number(t) : 0;
    if (current <= 0) { setTicketsLeft(0); return; }

    btn.disabled = true;
    const prev = btn.textContent;
    btn.textContent = 'Rolling…';

    triggerLootAnimation();

    try {
      const resp = await fetch(ENDPOINT, { method: 'POST', credentials: 'same-origin' });
      const text = await resp.text();
      if (!resp.ok) prependLog(`Error ${resp.status}: ${text || 'Request failed'}`, true);
      else prependLog(text || '(empty response)');
      await updateTickets();
    } catch (e) {
      prependLog('Network error: ' + e.message, true);
    } finally {
      btn.textContent = prev;
      await updateTickets();
    }
  }

  btn.addEventListener('click', playLottery);
  updateTickets();
})();
</script>

<!-- ====== Styles additionnels (peuvent rester ici ou aller dans lottery.css) ====== -->
<style>
/* Log + lien shop */
.lottery-log__entry{background:rgba(255,255,255,0.06);border-radius:12px;padding:12px;margin-bottom:10px;color:#e6e6e6;}
.buy-link{color:#60a5fa;text-decoration:underline;}
.buy-link:hover{color:#93c5fd}

/* Bouton grisé quand disabled */
.lottery-button:disabled{
  background:rgba(255,255,255,0.12);
  color:#9aa3b2;
  border:1px solid rgba(255,255,255,0.2);
  cursor:not-allowed;
  box-shadow:none;
}

/* ——— Zone Starbay ——— */
.starbay{
  position:relative;width:100%;height:300px;border-radius:16px;
  background:radial-gradient(120% 120% at 50% 0%, #0f172a 0%, #0b1220 60%, #0b0f1a 100%);
  overflow:hidden;box-shadow:inset 0 0 0 1px rgba(255,255,255,0.06);
}
.stars,.twinkle{
  position:absolute;inset:0;
  background:transparent url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160"><circle cx="10" cy="10" r="1.2" fill="white" opacity="0.8"/><circle cx="40" cy="20" r="0.9" fill="white" opacity="0.6"/><circle cx="120" cy="30" r="1.1" fill="white" opacity="0.75"/><circle cx="90" cy="90" r="0.8" fill="white" opacity="0.65"/><circle cx="140" cy="110" r="1" fill="white" opacity="0.7"/></svg>') repeat;
  opacity:.35;animation:drift 60s linear infinite;
}
.twinkle{animation:drift 90s linear infinite reverse;opacity:.25;filter:blur(0.3px);}
@keyframes drift{from{background-position:0 0}to{background-position:1000px 600px}}

.orbit{
  position:absolute;left:50%;top:50%;
  width:220px;height:220px;margin:-110px 0 0 -110px;border-radius:50%;
  border:2px dashed rgba(148,163,184,0.35);
  box-shadow:0 0 40px rgba(124,58,237,0.25), inset 0 0 40px rgba(56,189,248,0.08);
  animation:spin 18s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg)}}

.ship{position:absolute;left:50%;top:50%;transform:translate(-50%,-54%);filter:drop-shadow(0 4px 14px rgba(124,58,237,0.35));animation:float 4s ease-in-out infinite;}
@keyframes float{0%,100%{transform:translate(-50%,-54%)}50%{transform:translate(-50%,-58%)}}

/* ⚡ Rayon de lumière */
.beam{
  position:absolute;left:50%;top:-70%;transform:translateX(-50%);
  width:8px;height:0;
  background:linear-gradient(180deg, rgba(147,197,253,0) 0%, rgba(147,197,253,0.85) 40%, rgba(192,132,252,0.85) 80%, rgba(192,132,252,0) 100%);
  box-shadow:0 0 20px rgba(147,197,253,0.45), 0 0 40px rgba(192,132,252,0.35);
  border-radius:8px;opacity:0;
}
.starbay.beam-anim .beam{animation:beamDown 900ms ease-out forwards;}
@keyframes beamDown{
  0%{height:0;opacity:0;top:-70%}
  25%{opacity:1}
  100%{height:75%;top:-5%;opacity:1}
}

/* ⚡ Éclair */
.strike{
  position:absolute;left:50%;top:50%;
  width:220px;height:220px;transform:translate(-50%,-50%) scale(0.9);
  opacity:0;
  background:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path d="M110 20 L90 85 L130 85 L80 180 L100 120 L70 120 L110 20 Z" fill="none" stroke="white" stroke-width="4" stroke-linejoin="round" stroke-linecap="round" opacity="0.9"/></svg>') center/contain no-repeat;
  filter:drop-shadow(0 0 18px rgba(147,197,253,0.8)) drop-shadow(0 0 30px rgba(192,132,252,0.6));
}
.starbay.strike-anim .strike{animation:strikeFlash 520ms ease-out 260ms both;}
@keyframes strikeFlash{
  0%{opacity:0;transform:translate(-50%,-50%) scale(0.9)}
  20%{opacity:1;transform:translate(-50%,-50%) scale(1)}
  100%{opacity:0;transform:translate(-50%,-50%) scale(1.05)}
}

/* 💥 Onde de choc */
.shockwave{
  position:absolute;left:50%;top:50%;
  width:20px;height:20px;transform:translate(-50%,-50%) scale(0.2);
  border-radius:50%;
  box-shadow:0 0 0 2px rgba(148,163,184,0.45), inset 0 0 50px rgba(124,58,237,0.25);
  opacity:0;
}
.starbay.boom-anim .shockwave{animation:boom 900ms ease-out 260ms forwards;}
@keyframes boom{
  0%{opacity:0;transform:translate(-50%,-50%) scale(0.2)}
  10%{opacity:1}
  100%{opacity:0;transform:translate(-50%,-50%) scale(6)}
}

/* ✨ Étincelles */
.sparks,.sparks::after{
  content:"";position:absolute;left:50%;top:50%;
  width:4px;height:4px;transform:translate(-50%,-50%);
  background:
    radial-gradient(circle, rgba(248,250,252,0.9) 0 2px, transparent 3px) 0 0/6px 6px,
    radial-gradient(circle, rgba(147,197,253,0.9) 0 2px, transparent 3px) 16px -6px/8px 8px,
    radial-gradient(circle, rgba(192,132,252,0.9) 0 2px, transparent 3px) -12px 6px/7px 7px,
    radial-gradient(circle, rgba(56,189,248,0.9) 0 2px, transparent 3px) 10px 12px/7px 7px;
  opacity:0;
}
.starbay.boom-anim .sparks{animation:sparksOut 900ms ease-out 260ms forwards;}
.starbay.boom-anim .sparks::after{animation:sparksOut2 900ms ease-out 260ms forwards;}
@keyframes sparksOut{
  0%{opacity:0;transform:translate(-50%,-50%) scale(1)}
  10%{opacity:1}
  100%{opacity:0;transform:translate(-50%,-50%) scale(6)}
}
@keyframes sparksOut2{
  0%{opacity:0;transform:translate(-50%,-50%) rotate(25deg) scale(1)}
  10%{opacity:1}
  100%{opacity:0;transform:translate(-50%,-50%) rotate(25deg) scale(5.5)}
}

/* Drop table */
.space-legend{list-style:none;margin:14px 0 0;padding:0;display:flex;flex-direction:column;gap:10px;}
.pill{display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:14px;padding:10px 12px;}
.pill .dot{width:14px;height:14px;border-radius:4px;margin-right:10px;display:inline-block;}
.pill .text{color:#e6eefb;font-weight:600;font-size:14px;display:flex;align-items:center;gap:10px;}
.pill .pct{color:#cbd5e1;font-weight:600;font-size:14px;}
</style>
