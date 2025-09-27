/**
 * Agenda JavaScript - Funcionalidad para la aplicación de agenda de canchas
 * Maneja la visualización del calendario, reservas, y navegación entre vistas
 */

// Reemplazar con back-end
const CALENDAR_CONFIG = {
    DAYS: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    MONTHS: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    TIME_SLOTS: {
        START_HOUR: 8,
        END_HOUR: 22,
        INTERVAL: 1 // horas
    }
};

class AgendaApp {
    constructor() {
        this.currentDate = new Date();
        this.currentView = 'month';
        this.selectedCancha = null;
        this.reservas = [];
        
        // Caché de elementos DOM frecuentemente accedidos
        this.elements = {};
        
        this.init();
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.initializeDefaultView();
        this.updateDateDisplay();
        this.renderCurrentView();
        this.generateSampleData(); // reemplazar con back-end
    }


    // Cachear elementos DOM para optimizar rendimiento
    cacheElements() {
        this.elements = {
            // Vistas del calendario
            monthView: document.getElementById('monthView'),
            weekView: document.getElementById('weekView'),
            dayView: document.getElementById('dayView'),
            
            // Navegación
            todayBtn: document.getElementById('todayBtn'),
            prevBtn: document.getElementById('prevBtn'),
            nextBtn: document.getElementById('nextBtn'),
            dateSelector: document.getElementById('dateSelector'),
            currentDateDisplay: document.getElementById('currentDateDisplay'),
            
            // Sidebar
            canchaSelect: document.getElementById('canchaSelect'),
            crearReservaBtn: document.getElementById('crearReservaBtn'),
            gestionarSolicitudesBtn: document.getElementById('gestionarSolicitudesBtn'),
            
            // Headers
            dayViewHeader: document.getElementById('dayViewHeader')
        };
    }
    
    // Inicializar vista por defecto
    // Esto fue agregado porque apenas arrancaba la pestaña no mostraba la vista mensual
    initializeDefaultView() {
        // Establecer vista mensual como activa (solo si no está ya presente)
        if (!document.body.classList.contains('monthly-view-active')) {
            document.body.classList.add('monthly-view-active');
        }
        
        // Asegurar que la vista mensual esté visible por defecto
        if (this.elements.monthView) this.elements.monthView.classList.remove('d-none');
        if (this.elements.weekView) this.elements.weekView.classList.add('d-none');
        if (this.elements.dayView) this.elements.dayView.classList.add('d-none');
        
        // Actualizar selector de fecha con fecha actual
        if (this.elements.dateSelector) {
            this.elements.dateSelector.value = this.formatDateForInput(this.currentDate);
        }
    }

    bindEvents() {
        // Configurar todos los event listeners inmediatamente ya que el DOM está listo
        this.setupViewSwitching();
        this.setupDateNavigation();
        this.setupSidebar();
        this.setupDateSelector();
    }


    // Configurar cambio de vistas (mensual, semanal, diaria) 
    setupViewSwitching() {
        // Seleccionar todos los elementos con data-view (botones y dropdown items)
        const viewSelectors = document.querySelectorAll('[data-view]');
        
        viewSelectors.forEach(selector => {
            selector.addEventListener('click', (e) => {
                e.preventDefault();
                const targetView = selector.dataset.view;
                this.switchView(targetView);
                
                // Actualizar estado activo solo en botones (no en dropdown items)
                if (selector.tagName === 'BUTTON') {
                    const buttons = document.querySelectorAll('button[data-view]');
                    buttons.forEach(btn => btn.classList.remove('active'));
                    selector.classList.add('active');
                }
            });
        });
    }

    // Configurar navegación de fechas
    setupDateNavigation() {
        if (this.elements.todayBtn) {
            this.elements.todayBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.goToToday();
            });
        }
        
        if (this.elements.prevBtn) {
            this.elements.prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.navigateDate(-1);
            });
        }
        
        if (this.elements.nextBtn) {
            this.elements.nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.navigateDate(1);
            });
        }
    }

    // Configurar sidebar y selección de cancha
    setupSidebar() {
        if (this.elements.canchaSelect) {
            this.elements.canchaSelect.addEventListener('change', (e) => {
                this.selectedCancha = e.target.value;
                this.renderCurrentView();
                this.updateBadgeCount();
            });
        }
        
        if (this.elements.crearReservaBtn) {
            this.elements.crearReservaBtn.addEventListener('click', (e) => {
                this.crearReserva();
            });
        }
        
        if (this.elements.gestionarSolicitudesBtn) {
            this.elements.gestionarSolicitudesBtn.addEventListener('click', (e) => {
                this.gestionarSolicitudes();
            });
        }
    }

    // Métodos preparados para integración con backend
    crearReserva() {
        // TODO: Implementar gestión de solicitudes con backend
        console.log('Crear reserva para cancha:', this.selectedCancha);
    }

    gestionarSolicitudes() {
        // TODO: Implementar gestión de solicitudes con backend
        console.log('Gestionar solicitudes para cancha:', this.selectedCancha);
    }

    // Configurar selector de fecha
    setupDateSelector() {
        if (this.elements.dateSelector) {
            // Establecer fecha actual por defecto
            this.elements.dateSelector.value = this.formatDateForInput(this.currentDate);
            
            this.elements.dateSelector.addEventListener('change', (e) => {
                // Crear fecha usando componentes individuales para evitar problemas de zona horaria
                const dateString = e.target.value;
                const [year, month, day] = dateString.split('-').map(num => parseInt(num));
                this.currentDate = new Date(year, month - 1, day); // month - 1 porque los meses son 0-indexados
                this.updateDateDisplay();
                this.renderCurrentView();
            });
        }
    }

    // Cambiar vista del calendario
    switchView(view) {
        this.currentView = view;
        const calendarViews = document.querySelectorAll('.calendar-view');
        
        // Ocultar todas las vistas
        calendarViews.forEach(viewEl => viewEl.classList.add('d-none'));
        
        // Mostrar vista seleccionada
        const targetView = document.getElementById(view + 'View');
        if (targetView) {
            targetView.classList.remove('d-none');
            this.renderCurrentView();
        }
        
        // Alternar visibilidad de navegación según la vista
        const body = document.body;
        if (view === 'month') {
            body.classList.add('monthly-view-active');
        } else {
            body.classList.remove('monthly-view-active');
        }
    }

    // Ir a hoy
    goToToday() {
        this.currentDate = new Date();
        if (this.elements.dateSelector) {
            this.elements.dateSelector.value = this.formatDateForInput(this.currentDate);
        }
        this.updateDateDisplay();
        this.renderCurrentView();
    }

    // Navegar fechas (anterior/siguiente)
    navigateDate(direction) {
        const currentView = this.currentView;
        
        if (currentView === 'month') {
            this.currentDate.setMonth(this.currentDate.getMonth() + direction);
        } else if (currentView === 'week') {
            this.currentDate.setDate(this.currentDate.getDate() + (direction * 7));
        } else if (currentView === 'day') {
            this.currentDate.setDate(this.currentDate.getDate() + direction);
        }
        
        const dateSelector = document.getElementById('dateSelector');
        if (dateSelector) {
            dateSelector.value = this.formatDateForInput(this.currentDate);
        }
        
        this.updateDateDisplay();
        this.renderCurrentView();
    }

    // Actualizar display de fecha actual
    updateDateDisplay() {
        if (!this.elements.currentDateDisplay) return;
        
        const month = CALENDAR_CONFIG.MONTHS[this.currentDate.getMonth()];
        const year = this.currentDate.getFullYear();
        
        if (this.currentView === 'month') {
            this.elements.currentDateDisplay.textContent = `${month} ${year}`;
        } else if (this.currentView === 'week') {
            const weekStart = this.getWeekStart(this.currentDate);
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekEnd.getDate() + 6);
            
            this.elements.currentDateDisplay.textContent = `Semana del ${weekStart.getDate()} al ${weekEnd.getDate()} de ${month} ${year}`;
        } else if (this.currentView === 'day') {
            const dayName = CALENDAR_CONFIG.DAYS[this.currentDate.getDay()];
            
            this.elements.currentDateDisplay.textContent = `${dayName} ${this.currentDate.getDate()} de ${month} ${year}`;
        }
    }

    // Renderizar vista actual
    renderCurrentView() {
        switch (this.currentView) {
            case 'month':
                this.renderMonthView();
                break;
            case 'week':
                this.renderWeekView();
                break;
            case 'day':
                this.renderDayView();
                break;
        }
    }

    // Renderizar vista mensual
    renderMonthView() {
        if (!this.elements.monthView) return;
        
        const table = this.elements.monthView.querySelector('table tbody');
        if (!table) return;
        
        table.innerHTML = '';
        
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const firstDay = new Date(year, month, 1);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        let currentDate = new Date(startDate);
        
        for (let week = 0; week < 6; week++) {
            const row = document.createElement('tr');
            
            for (let day = 0; day < 7; day++) {
                const cell = document.createElement('td');
                const dayNumber = currentDate.getDate();
                const isCurrentMonth = currentDate.getMonth() === month;
                const isToday = this.isToday(currentDate);
                
                cell.textContent = dayNumber;
                
                if (!isCurrentMonth) {
                    cell.classList.add('text-muted');
                }
                
                if (isToday) {
                    cell.classList.add('table-primary', 'fw-bold');
                }
                
                // Agregar eventos del día
                const dayReservas = this.getReservasForDate(currentDate);
                if (dayReservas.length > 0) {
                    const badge = document.createElement('small');
                    badge.className = 'badge bg-success ms-1';
                    badge.textContent = dayReservas.length;
                    cell.appendChild(badge);
                }
                
                cell.addEventListener('click', ((clickedDate) => {
                    return () => {
                        this.currentDate = new Date(clickedDate);
                        this.switchView('day');
                    };
                })(new Date(currentDate)));
                
                row.appendChild(cell);
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            table.appendChild(row);
        }
    }

    // Renderizar vista semanal
    renderWeekView() {
        const weekView = document.getElementById('weekView');
        if (!weekView) return;
        
        const table = weekView.querySelector('table');
        if (!table) return;
        
        const weekStart = this.getWeekStart(this.currentDate);
        const hours = this.generateTimeSlots();
        
        // Actualizar headers con fechas de la semana
        const headers = table.querySelectorAll('thead th');
        for (let i = 1; i < headers.length; i++) {
            const date = new Date(weekStart);
            date.setDate(date.getDate() + (i - 1));
            
            const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            headers[i].textContent = `${dayNames[date.getDay()]} ${date.getDate()}`;
        }
        
        // Actualizar cuerpo de la tabla
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        
        hours.forEach(hour => {
            const row = document.createElement('tr');
            
            // Celda de hora
            const hourCell = document.createElement('td');
            hourCell.className = 'table-light';
            hourCell.textContent = hour;
            row.appendChild(hourCell);
            
            // Celdas para cada día de la semana
            for (let day = 0; day < 7; day++) {
                const cell = document.createElement('td');
                const cellDate = new Date(weekStart);
                cellDate.setDate(cellDate.getDate() + day);
                
                const reserva = this.getReservaForDateTime(cellDate, hour);
                if (reserva) {
                    cell.className = reserva.status === 'confirmed' ? 'table-success' : 'table-warning';
                    cell.innerHTML = `<strong>${reserva.title}</strong><br><small>${reserva.description}</small>`;
                }
                
                row.appendChild(cell);
            }
            
            tbody.appendChild(row);
        });
    }

    // Renderizar vista diaria
    renderDayView() {
        if (!this.elements.dayView) return;
        
        const table = this.elements.dayView.querySelector('table');
        if (!table) return;
        
        const hours = this.generateTimeSlots();
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        
        // Actualizar header con fecha del día usando el elemento cacheado
        if (this.elements.dayViewHeader) {
            const dayName = CALENDAR_CONFIG.DAYS[this.currentDate.getDay()];
            const monthName = CALENDAR_CONFIG.MONTHS[this.currentDate.getMonth()];
            
            this.elements.dayViewHeader.textContent = `${dayName} ${this.currentDate.getDate()} de ${monthName}`;
        }
        
        hours.forEach(hour => {
            const row = document.createElement('tr');
            
            // Celda de hora
            const hourCell = document.createElement('td');
            hourCell.className = 'table-light';
            hourCell.textContent = hour;
            row.appendChild(hourCell);
            
            // Celda de evento
            const eventCell = document.createElement('td');
            const reserva = this.getReservaForDateTime(this.currentDate, hour);
            
            if (reserva) {
                eventCell.className = reserva.status === 'confirmed' ? 'table-success' : 'table-warning';
                eventCell.innerHTML = `
                    <strong>${reserva.title}</strong><br>
                    <small class="text-muted">${reserva.description}</small>
                `;
            }
            
            row.appendChild(eventCell);
            tbody.appendChild(row);
        });
        
        // Actualizar resumen del día
        this.updateDaySummary();
    }

    // Actualizar resumen del día
    updateDaySummary() {
        const summaryCard = document.querySelector('#dayView .card-body');
        if (!summaryCard) return;
        
        const dayReservas = this.getReservasForDate(this.currentDate);
        const confirmed = dayReservas.filter(r => r.status === 'confirmed').length;
        const pending = dayReservas.filter(r => r.status === 'pending').length;
        const totalSlots = this.generateTimeSlots().length;
        const free = totalSlots - dayReservas.length;
        
        summaryCard.innerHTML = `
            <p class="card-text">
                <span class="badge bg-success me-2">${confirmed}</span>Reservas confirmadas<br>
                <span class="badge bg-warning me-2">${pending}</span>Solicitudes pendientes<br>
                <span class="badge bg-secondary me-2">${free}</span>Horarios libres
            </p>
        `;
    }

    // Actualizar contador de badge
    updateBadgeCount() {
        const badge = document.querySelector('.badge.bg-danger');
        if (badge) {
            const pendingCount = this.reservas.filter(r => r.status === 'pending').length;
            badge.textContent = pendingCount;
        }
    }

    // Utilidades
    formatDateForInput(date) {
        return date.toISOString().split('T')[0];
    }

    isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    getWeekStart(date) {
        const weekStart = new Date(date);
        weekStart.setDate(date.getDate() - date.getDay());
        return weekStart;
    }

    generateTimeSlots() {
        const slots = [];
        for (let hour = CALENDAR_CONFIG.TIME_SLOTS.START_HOUR; hour <= CALENDAR_CONFIG.TIME_SLOTS.END_HOUR; hour++) {
            slots.push(`${hour.toString().padStart(2, '0')}:00`);
        }
        return slots;
    }

    getReservasForDate(date) {
        return this.reservas.filter(reserva => {
            const reservaDate = new Date(reserva.date);
            return reservaDate.toDateString() === date.toDateString();
        });
    }

    getReservaForDateTime(date, time) {
        return this.reservas.find(reserva => {
            const reservaDate = new Date(reserva.date);
            return reservaDate.toDateString() === date.toDateString() && reserva.time === time;
        });
    }


}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño delay para asegurar que todos los elementos estén listos
    setTimeout(() => {
        window.agendaApp = new AgendaApp();
    }, 100);
});

// DATOS HARDCODEADOS - Mover a backend cuando esté listo
AgendaApp.prototype.generateSampleData = function() {
    // TODO: Reemplazar con llamadas a API del backend
    // Endpoints necesarios:
    // - GET /api/reservas?cancha={id}&fecha={date}
    // - GET /api/canchas
    // - POST /api/reservas
    // - PUT /api/reservas/{id}
    // - DELETE /api/reservas/{id}
    
    return [
        {
            id: 1,
            title: 'Reserva Equipo A',
            description: 'Cancha A - Confirmada',
            date: new Date(2025, 8, 26), // 26 de septiembre
            time: '08:00',
            status: 'confirmed',
            cancha: '1'
        },
        {
            id: 2,
            title: 'Reserva Equipo A',
            description: 'Cancha A - Confirmada',
            date: new Date(2025, 8, 26),
            time: '09:00',
            status: 'confirmed',
            cancha: '1'
        },
        {
            id: 3,
            title: 'Solicitud Equipo B',
            description: 'Cancha B - Pendiente',
            date: new Date(2025, 8, 26),
            time: '11:00',
            status: 'pending',
            cancha: '2'
        },
        {
            id: 4,
            title: 'Torneo Local',
            description: 'Cancha C - Confirmada',
            date: new Date(2025, 8, 27),
            time: '15:00',
            status: 'confirmed',
            cancha: '3'
        },
        {
            id: 5,
            title: 'Entrenamiento',
            description: 'Cancha A - Pendiente',
            date: new Date(2025, 8, 28),
            time: '10:00',
            status: 'pending',
            cancha: '1'
        }
    ];
};

// Exportar para uso en módulos si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AgendaApp;
}