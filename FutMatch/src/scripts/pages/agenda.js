/**
 * Agenda JavaScript - Funcionalidad para la aplicación de agenda de canchas
 * Maneja la visualización del calendario, reservas, y navegación entre vistas
 */

class AgendaApp {
    constructor() {
        this.currentDate = new Date();
        this.currentView = 'month';
        this.selectedCancha = null;
        this.reservas = this.generateSampleData();
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeDefaultView();
        this.updateDateDisplay();
        this.renderCurrentView();
    }
    
    // Inicializar vista por defecto
    initializeDefaultView() {
        console.log('Initializing default view...'); // Debug
        
        // Establecer vista mensual como activa
        document.body.classList.add('monthly-view-active');
        
        // Asegurar que la vista mensual esté visible por defecto
        const monthView = document.getElementById('monthView');
        const weekView = document.getElementById('weekView');
        const dayView = document.getElementById('dayView');
        
        console.log('Views found:', { monthView, weekView, dayView }); // Debug
        
        if (monthView) {
            monthView.classList.remove('d-none');
            console.log('Month view made visible');
        }
        if (weekView) weekView.classList.add('d-none');
        if (dayView) dayView.classList.add('d-none');
        
        // Actualizar selector de fecha con fecha actual
        const dateSelector = document.getElementById('dateSelector');
        if (dateSelector) {
            dateSelector.value = this.formatDateForInput(this.currentDate);
            console.log('Date selector updated to:', dateSelector.value);
        }
    }

    bindEvents() {
        // Configurar todos los event listeners inmediatamente ya que el DOM está listo
        this.setupViewSwitching();
        this.setupDateNavigation();
        this.setupSidebar();
        this.setupDateSelector();
        this.setupAdditionalViewSelectors();
    }
    
    // Configurar selectores de vista adicionales (para dropdown y botones móviles)
    setupAdditionalViewSelectors() {
        // Esto se llamará después de que la aplicación esté completamente inicializada
        // La configuración real se hace en el evento DOMContentLoaded
    }

    // Configurar cambio de vistas (mensual, semanal, diaria)
    setupViewSwitching() {
        const viewButtons = document.querySelectorAll('[data-view]');
        const calendarViews = document.querySelectorAll('.calendar-view');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const targetView = button.dataset.view;
                this.switchView(targetView);
                
                // Actualizar estado activo de botones
                viewButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    }

    // Configurar navegación de fechas
    setupDateNavigation() {
        const todayBtn = document.getElementById('todayBtn');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        console.log('Setting up navigation:', { todayBtn, prevBtn, nextBtn }); // Debug
        
        if (todayBtn) {
            todayBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Today button clicked'); // Debug
                this.goToToday();
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Previous button clicked'); // Debug
                this.navigateDate(-1);
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Next button clicked'); // Debug
                this.navigateDate(1);
            });
        }
    }

    // Configurar sidebar y selección de cancha
    setupSidebar() {
        const canchaSelect = document.getElementById('canchaSelect');
        const crearReservaBtn = document.getElementById('btn-crear-reserva');
        const gestionarSolicitudesBtn = document.getElementById('btn-gestionar-solicitudes');
        
        console.log('Setting up sidebar:', { canchaSelect, crearReservaBtn, gestionarSolicitudesBtn }); // Debug
        
        if (canchaSelect) {
            canchaSelect.addEventListener('change', (e) => {
                this.selectedCancha = e.target.value;
                console.log('Cancha selected:', this.selectedCancha); // Debug
                this.renderCurrentView();
                this.updateBadgeCount();
            });
        }
        
        if (crearReservaBtn) {
            crearReservaBtn.addEventListener('click', (e) => {
                console.log('Crear reserva clicked'); // Debug
                // Add functionality later
            });
        }
        
        if (gestionarSolicitudesBtn) {
            gestionarSolicitudesBtn.addEventListener('click', (e) => {
                console.log('Gestionar solicitudes clicked'); // Debug
                // Add functionality later
            });
        }
    }

    // Configurar selector de fecha
    setupDateSelector() {
        const dateSelector = document.getElementById('dateSelector');
        
        if (dateSelector) {
            // Establecer fecha actual por defecto
            dateSelector.value = this.formatDateForInput(this.currentDate);
            
            dateSelector.addEventListener('change', (e) => {
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
        const dateSelector = document.getElementById('dateSelector');
        if (dateSelector) {
            dateSelector.value = this.formatDateForInput(this.currentDate);
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
        const display = document.getElementById('currentDateDisplay');
        if (!display) return;
        
        const months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        
        const month = months[this.currentDate.getMonth()];
        const year = this.currentDate.getFullYear();
        
        if (this.currentView === 'month') {
            display.textContent = `${month} ${year}`;
        } else if (this.currentView === 'week') {
            const weekStart = this.getWeekStart(this.currentDate);
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekEnd.getDate() + 6);
            
            display.textContent = `Semana del ${weekStart.getDate()} al ${weekEnd.getDate()} de ${month} ${year}`;
        } else if (this.currentView === 'day') {
            const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const dayName = days[this.currentDate.getDay()];
            
            display.textContent = `${dayName} ${this.currentDate.getDate()} de ${month} ${year}`;
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
        console.log('Rendering month view for:', this.currentDate); // Debug
        const monthView = document.getElementById('monthView');
        if (!monthView) {
            console.error('Month view element not found');
            return;
        }
        
        const table = monthView.querySelector('table tbody');
        if (!table) {
            console.error('Month view table body not found');
            return;
        }
        
        table.innerHTML = '';
        
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
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
                
                cell.addEventListener('click', () => {
                    this.currentDate = new Date(currentDate);
                    this.switchView('day');
                });
                
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
        const dayView = document.getElementById('dayView');
        if (!dayView) return;
        
        const table = dayView.querySelector('table');
        if (!table) return;
        
        const hours = this.generateTimeSlots();
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        
        // Actualizar header con fecha del día usando el ID específico
        const header = document.getElementById('dayViewHeader');
        if (header) {
            const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            
            header.textContent = `${days[this.currentDate.getDay()]} ${this.currentDate.getDate()} de ${months[this.currentDate.getMonth()]}`;
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
        for (let hour = 8; hour <= 22; hour++) {
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

    // Generar datos de ejemplo
    generateSampleData() {
        const today = new Date();
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
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing AgendaApp...'); // Debug
    
    // Add small delay to ensure all elements are ready
    setTimeout(() => {
        window.agendaApp = new AgendaApp();
        console.log('AgendaApp initialized:', window.agendaApp); // Debug
        
        // Setup view selectors after app is initialized
        document.querySelectorAll('.view-selector').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // This prevents the href="#" navigation
                const view = this.getAttribute('data-view');
                console.log('View selector clicked:', view); // Debug
                if (window.agendaApp && window.agendaApp.switchView) {
                    window.agendaApp.switchView(view);
                } else {
                    console.error('AgendaApp not ready');
                }
            });
        });
    }, 100);
});

// Exportar para uso en módulos si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AgendaApp;
}