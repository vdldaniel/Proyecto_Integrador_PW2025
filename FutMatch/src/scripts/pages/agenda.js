// Utilidades
    const pad = n => n.toString().padStart(2, '0');
    const esES = new Intl.DateTimeFormat('es-AR', { month: 'long', year: 'numeric' });
    const esESWeekdayShort = ['Lu','Ma','Mi','Ju','Vi','Sa','Do'];

    // Estado
    let baseDate = new Date();
    let view = 'month';
    let selectedMiniDate = new Date();

    // DOM
    const elPeriod = document.getElementById('currentPeriod');
    const grid = document.getElementById('calendarGrid');

    function render() {
      grid.setAttribute('aria-busy', 'true');
      if (view === 'month') renderMonthView();
      else if (view === 'week') renderWeekView();
      else renderDayView();

      elPeriod.textContent = esES.format(baseDate).replace(/^\w/, c => c.toUpperCase());

      const syncTo = new Date(baseDate.getFullYear(), baseDate.getMonth(), 1);
      renderMiniCalendar(syncTo, 'mini');
      renderMiniCalendar(syncTo, 'miniMobile');

      grid.setAttribute('aria-busy', 'false');
    }

    function renderMonthView() {
      const y = baseDate.getFullYear(), m = baseDate.getMonth();
      const first = new Date(y, m, 1);
      const startOffset = (first.getDay() + 6) % 7; // Lunes=0
      const daysInMonth = new Date(y, m + 1, 0).getDate();

      let html = '<div class="calendar-head d-none d-md-grid" style="grid-template-columns: repeat(7, 1fr);">';
      for (const wd of esESWeekdayShort) html += `<div class="text-center">${wd}</div>`;
      html += '</div>';

      html += '<div class="d-grid" style="grid-template-columns: repeat(7, 1fr); grid-auto-rows: minmax(104px, auto);">';
      const today = new Date();
      const totalCells = 42;

      for (let i = 0; i < totalCells; i++) {
        const dayNum = i - startOffset + 1;
        const inMonth = dayNum >= 1 && dayNum <= daysInMonth;
        const d = inMonth ? new Date(y, m, dayNum) : null;

        const isToday = inMonth &&
          d.getFullYear() === today.getFullYear() &&
          d.getMonth() === today.getMonth() &&
          d.getDate() === today.getDate();

        html += `<div class="calendar-cell ${isToday ? 'today' : ''} ${inMonth ? '' : 'opacity-50'}">`;
        html += `<span class="date-badge text-white-50">${inMonth ? dayNum : ''}</span>`;
        html += `</div>`;
      }
      html += '</div>';
      grid.innerHTML = html;
    }

    function renderWeekView() {
      const d = new Date(baseDate);
      const dayOfWeek = (d.getDay() + 6) % 7; // Lunes=0
      const monday = new Date(d); monday.setDate(d.getDate() - dayOfWeek);

      const days = Array.from({length:7}, (_,i) => {
        const x = new Date(monday);
        x.setDate(monday.getDate() + i);
        return x;
      });

      let html = '<div class="calendar-head d-grid" style="grid-template-columns: repeat(7, 1fr);">';
      for (const x of days) {
        const label = x.toLocaleDateString('es-AR', { weekday:'short', day:'2-digit', month:'short' });
        html += `<div class="text-center text-truncate">${label}</div>`;
      }
      html += '</div>';

      html += '<div class="d-grid" style="grid-template-columns: repeat(7, 1fr);">';
      const today = new Date();
      for (const x of days) {
        const isToday = x.toDateString() === today.toDateString();
        html += `<div class="calendar-cell ${isToday ? 'today' : ''}">
                  <span class="date-badge text-white-50">${x.getDate()}</span>
                 </div>`;
      }
      html += '</div>';

      grid.innerHTML = html;
    }

    function renderDayView() {
      const label = baseDate.toLocaleDateString('es-AR', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
      let html = `<div class="calendar-head">${label.charAt(0).toUpperCase() + label.slice(1)}</div>`;
      html += '<div class="p-3">';
      html += '<div class="alert alert-info mb-0">Vista de día – agrega tus intervalos y reservas aquí.</div>';
      html += '</div>';
      grid.innerHTML = html;
    }

    function renderMiniCalendar(refDate, target = 'mini') {
      const y = refDate.getFullYear(), m = refDate.getMonth();
      const first = new Date(y, m, 1);
      const startOffset = (first.getDay() + 6) % 7;
      const daysInMonth = new Date(y, m + 1, 0).getDate();

      const captionId = target === 'mini' ? 'miniCaption' : 'miniCaptionMobile';
      const bodyId    = target === 'mini' ? 'miniBody'    : 'miniBodyMobile';

      document.getElementById(captionId).textContent =
        esES.format(refDate).replace(/^\w/, c => c.toUpperCase());

      let cells = [];
      for (let i = 0; i < startOffset; i++) cells.push('');
      for (let d = 1; d <= daysInMonth; d++) cells.push(d);
      while (cells.length % 7) cells.push('');

      let html = '';
      for (let r = 0; r < cells.length; r += 7) {
        html += '<tr>';
        for (let c = 0; c < 7; c++) {
          const val = cells[r + c];
          const isToday = val && isSameDate(new Date(), new Date(y, m, val));
          const isActive = val && isSameDate(selectedMiniDate, new Date(y, m, val));
          html += '<td>';
          if (val) {
            html += `<button class="${isToday ? 'today' : ''} ${isActive ? 'active' : ''}"
                      data-y="${y}" data-m="${m}" data-d="${val}" type="button"
                      title="${val}/${pad(m+1)}/${y}">
                      ${val}
                    </button>`;
          } else {
            html += '&nbsp;';
          }
          html += '</td>';
        }
        html += '</tr>';
      }
      document.getElementById(bodyId).innerHTML = html;

      document.getElementById(bodyId).onclick = (ev) => {
        const btn = ev.target.closest('button[data-y]');
        if (!btn) return;
        const y = +btn.dataset.y, m = +btn.dataset.m, d = +btn.dataset.d;
        selectedMiniDate = new Date(y, m, d);
        baseDate = new Date(y, m, d);
        if (view === 'day') baseDate = selectedMiniDate;
        render();
      };
    }

    function isSameDate(a, b) {
      return a.getFullYear() === b.getFullYear() &&
             a.getMonth() === b.getMonth() &&
             a.getDate() === b.getDate();
    }

    // Controles
    document.getElementById('btnToday').addEventListener('click', () => {
      baseDate = new Date(); selectedMiniDate = new Date(); render();
    });
    document.getElementById('btnPrev').addEventListener('click', () => {
      if (view === 'month') baseDate = new Date(baseDate.getFullYear(), baseDate.getMonth() - 1, 1);
      else if (view === 'week') baseDate.setDate(baseDate.getDate() - 7);
      else baseDate.setDate(baseDate.getDate() - 1);
      render();
    });
    document.getElementById('btnNext').addEventListener('click', () => {
      if (view === 'month') baseDate = new Date(baseDate.getFullYear(), baseDate.getMonth() + 1, 1);
      else if (view === 'week') baseDate.setDate(baseDate.getDate() + 7);
      else baseDate.setDate(baseDate.getDate() + 1);
      render();
    });

    document.querySelectorAll('input[name="viewMode"]').forEach(r => {
      r.addEventListener('change', (e) => { view = e.target.value; render(); });
    });

    // Mini cal prev/next (desktop)
    document.getElementById('miniPrev').addEventListener('click', () => {
      const d = new Date(baseDate.getFullYear(), baseDate.getMonth() - 1, 1);
      renderMiniCalendar(d, 'mini'); baseDate = d; render();
    });
    document.getElementById('miniNext').addEventListener('click', () => {
      const d = new Date(baseDate.getFullYear(), baseDate.getMonth() + 1, 1);
      renderMiniCalendar(d, 'mini'); baseDate = d; render();
    });

    // Mini cal prev/next (mobile)
    document.getElementById('miniPrevMobile').addEventListener('click', () => {
      const d = new Date(baseDate.getFullYear(), baseDate.getMonth() - 1, 1);
      renderMiniCalendar(d, 'miniMobile'); baseDate = d; render();
    });
    document.getElementById('miniNextMobile').addEventListener('click', () => {
      const d = new Date(baseDate.getFullYear(), baseDate.getMonth() + 1, 1);
      renderMiniCalendar(d, 'miniMobile'); baseDate = d; render();
    });

    // Sincronizar selector cancha
    const selCancha = document.getElementById('selCancha');
    const selCanchaMobile = document.getElementById('selCanchaMobile');
    selCancha.addEventListener('change', () => selCanchaMobile.value = selCancha.value);
    selCanchaMobile.addEventListener('change', () => selCancha.value = selCanchaMobile.value);

    // Init
    render();