document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById('chartSvgContainer');
    const xAxisEl = document.getElementById('chartXAxis');
    const chartArea = document.getElementById('studentTrendArea');
    const btnPrev = document.getElementById('btnPrevMonth');
    const btnNext = document.getElementById('btnNextMonth');
    const rangeLabel = document.getElementById('chartMonthRange');

    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('fypSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const closeSidebarBtn = document.getElementById('sidebarCloseBtn');

    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', function () {
            setTimeout(function () {
                if (sidebar.getBoundingClientRect().left < 0) {
                    sidebar.classList.add('open', 'active', 'show');
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.style.left = '0px';
                    if (backdrop) {
                        backdrop.classList.add('active', 'show');
                        backdrop.style.display = 'block';
                    }
                }
            }, 10);
        });
    }

    const closeMobileSidebar = function () {
        if (!sidebar) return;
        sidebar.classList.remove('open', 'active', 'show');
        sidebar.style.transform = '';
        sidebar.style.left = '';
        if (backdrop) {
            backdrop.classList.remove('active', 'show');
            backdrop.style.display = '';
        }
    };

    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeMobileSidebar);
    if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

    if (container && typeof monthlyTrendData !== 'undefined') {
        const labels = Object.keys(monthlyTrendData);
        const values = Object.values(monthlyTrendData);
        const maxVal = (typeof chartMaxY !== 'undefined' && chartMaxY > 0) ? chartMaxY : 4;
        const activeMonth = (typeof currentMonthNum !== 'undefined') ? currentMonthNum : 11;
        const visibleCount = Math.min(5, labels.length);

        let startIndex = Math.max(0, Math.min(labels.length - visibleCount, activeMonth - visibleCount));

        function renderTrendChart() {
            const sliceLabels = labels.slice(startIndex, startIndex + visibleCount);
            const sliceValues = values.slice(startIndex, startIndex + visibleCount);

            if (rangeLabel && sliceLabels.length > 0) {
                rangeLabel.textContent = `${sliceLabels[0]} - ${sliceLabels[sliceLabels.length - 1]}`;
            }
            if (btnPrev) {
                btnPrev.disabled = startIndex <= 0;
                btnPrev.style.opacity = startIndex <= 0 ? '0.4' : '1';
            }
            if (btnNext) {
                btnNext.disabled = startIndex >= labels.length - visibleCount;
                btnNext.style.opacity = startIndex >= labels.length - visibleCount ? '0.4' : '1';
            }

            if (xAxisEl) {
                xAxisEl.innerHTML = sliceLabels.map(lbl => `<span>${lbl}</span>`).join('');
            }

            const width = container.clientWidth || 400;
            const height = container.clientHeight || 160;
            const stepX = width / sliceValues.length;
            let points = [];

            sliceValues.forEach((val, idx) => {
                const x = (idx * stepX) + (stepX / 2);
                const y = height - ((val / maxVal) * height);
                points.push({ x, y, val });
            });

            if (points.length > 0) {
                let pathD = `M ${points[0].x} ${points[0].y}`;
                for (let i = 1; i < points.length; i++) {
                    pathD += ` L ${points[i].x} ${points[i].y}`;
                }

                const areaPathD = `${pathD} L ${points[points.length - 1].x} ${height} L ${points[0].x} ${height} Z`;

                let svgHtml = `
                    <svg width="100%" height="100%" viewBox="0 0 ${width} ${height}" style="overflow: visible;">
                        <defs>
                            <linearGradient id="blueToWhiteGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#4EACD4" stop-opacity="0.22" />
                                <stop offset="60%" stop-color="#CBE9F6" stop-opacity="0.08" />
                                <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <path d="${areaPathD}" fill="url(#blueToWhiteGrad)" />
                        <path d="${pathD}" fill="none" stroke="#4EACD4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                `;

                points.forEach((p) => {
                    svgHtml += `
                        <circle cx="${p.x}" cy="${p.y}" r="5" fill="#ffffff" stroke="#4EACD4" stroke-width="3" />
                        <text x="${p.x}" y="${p.y - 10}" text-anchor="middle" font-size="10" font-weight="600" fill="#27566A">${p.val}</text>
                    `;
                });

                svgHtml += `</svg>`;
                container.innerHTML = svgHtml;
            }
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                if (startIndex > 0) {
                    startIndex--;
                    renderTrendChart();
                }
            });
        }

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                if (startIndex < labels.length - visibleCount) {
                    startIndex++;
                    renderTrendChart();
                }
            });
        }

        if (chartArea) {
            chartArea.addEventListener('wheel', function (e) {
                if (Math.abs(e.deltaX) > 8 || Math.abs(e.deltaY) > 8) {
                    e.preventDefault();
                    const dir = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
                    if (dir > 0 && startIndex < labels.length - visibleCount) {
                        startIndex++;
                        renderTrendChart();
                    } else if (dir < 0 && startIndex > 0) {
                        startIndex--;
                        renderTrendChart();
                    }
                }
            }, { passive: false });
        }

        window.addEventListener('resize', renderTrendChart);
        renderTrendChart();
    }

    const notifBtn = document.querySelector('.header-notif');
    if (notifBtn) {
        notifBtn.addEventListener('click', function () {
            alert('Belum ada notifikasi baru.');
        });
    }
});