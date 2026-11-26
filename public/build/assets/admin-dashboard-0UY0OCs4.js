document.addEventListener(`DOMContentLoaded`, function () {
    let e = document.getElementById(`chartSvgContainer`), t = document.getElementById(`chartXAxis`), n = document.getElementById(`studentTrendArea`), r = document.getElementById(`btnPrevMonth`), i = document.getElementById(`btnNextMonth`), a = document.getElementById(`chartMonthRange`); if (e && typeof monthlyTrendData < `u`) {
        let o = Object.keys(monthlyTrendData), s = Object.values(monthlyTrendData), c = typeof chartMaxY < `u` && chartMaxY > 0 ? chartMaxY : 4, l = typeof currentMonthNum < `u` ? currentMonthNum : 11, u = Math.min(5, o.length), d = Math.max(0, Math.min(o.length - u, l - u)); function f() {
            let n = o.slice(d, d + u), l = s.slice(d, d + u); a && n.length > 0 && (a.textContent = `${n[0]} - ${n[n.length - 1]}`), r && (r.disabled = d <= 0, r.style.opacity = d <= 0 ? `0.4` : `1`), i && (i.disabled = d >= o.length - u, i.style.opacity = d >= o.length - u ? `0.4` : `1`), t && (t.innerHTML = n.map(e => `<span>${e}</span>`).join(``)); let f = e.clientWidth || 400, p = e.clientHeight || 160, m = f / l.length, h = []; if (l.forEach((e, t) => { let n = t * m + m / 2, r = p - e / c * p; h.push({ x: n, y: r, val: e }) }), h.length > 0) {
                let t = `M ${h[0].x} ${h[0].y}`; for (let e = 1; e < h.length; e++)t += ` L ${h[e].x} ${h[e].y}`; let n = `
                    <svg width="100%" height="100%" viewBox="0 0 ${f} ${p}" style="overflow: visible;">
                        <defs>
                            <linearGradient id="blueToWhiteGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#4EACD4" stop-opacity="0.22" />
                                <stop offset="60%" stop-color="#CBE9F6" stop-opacity="0.08" />
                                <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <path d="${`${t} L ${h[h.length - 1].x} ${p} L ${h[0].x} ${p} Z`}" fill="url(#blueToWhiteGrad)" />
                        <path d="${t}" fill="none" stroke="#4EACD4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                `; h.forEach(e => {
                    n += `
                        <circle cx="${e.x}" cy="${e.y}" r="5" fill="#ffffff" stroke="#4EACD4" stroke-width="3" />
                        <text x="${e.x}" y="${e.y - 10}" text-anchor="middle" font-size="10" font-weight="600" fill="#27566A">${e.val}</text>
                    `}), n += `</svg>`, e.innerHTML = n
            }
        } r && r.addEventListener(`click`, function () { d > 0 && (d--, f()) }), i && i.addEventListener(`click`, function () { d < o.length - u && (d++, f()) }), n && n.addEventListener(`wheel`, function (e) { if (Math.abs(e.deltaX) > 8 || Math.abs(e.deltaY) > 8) { e.preventDefault(); let t = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY; t > 0 && d < o.length - u ? (d++, f()) : t < 0 && d > 0 && (d--, f()) } }, { passive: !1 }), window.addEventListener(`resize`, f), f()
    } let o = document.querySelector(`.header-notif`); o && o.addEventListener(`click`, function () { alert(`Belum ada notifikasi baru.`) })
});