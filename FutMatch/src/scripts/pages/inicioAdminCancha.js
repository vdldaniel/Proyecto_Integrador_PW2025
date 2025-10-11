/**
 * INICIO ADMIN CANCHA - Scripts para el dashboard del administrador de cancha
 * Funcionalidad: Gráfico de barras dinámico con filtro temporal y cálculo de ocupación
 */

// ============================================
// CONFIGURACIÓN DE HORARIOS (Simulación de BD)
// ============================================
// TODO: Reemplazar con datos de la tabla HORARIOS_CANCHA
// Estructura: id_horario | id_cancha | dia_semana | hora_apertura | hora_cierre | habilitada

const HORARIOS_CONFIG = {
  // Configuración general de horarios por día
  // 0=Domingo, 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado
  horarios: [
    { dia_semana: 1, hora_apertura: '08:00', hora_cierre: '23:00', habilitada: true }, // Lunes
    { dia_semana: 2, hora_apertura: '08:00', hora_cierre: '23:00', habilitada: true }, // Martes
    { dia_semana: 3, hora_apertura: '08:00', hora_cierre: '23:00', habilitada: true }, // Miércoles
    { dia_semana: 4, hora_apertura: '08:00', hora_cierre: '23:00', habilitada: true }, // Jueves
    { dia_semana: 5, hora_apertura: '08:00', hora_cierre: '23:00', habilitada: true }, // Viernes
    { dia_semana: 6, hora_apertura: '09:00', hora_cierre: '22:00', habilitada: true }, // Sábado
    { dia_semana: 0, hora_apertura: '09:00', hora_cierre: '20:00', habilitada: true }  // Domingo
  ],
  
  // Duración de cada turno de partido (incluye tiempo de juego + limpieza/preparación)
  // Un partido de fútbol 5 dura ~90 min, pero con buffer de limpieza son 2 horas por turno
  duracion_turno_horas: 2,
  
  // Número de canchas disponibles (simulado - vendrá de BD)
  num_canchas: 3
};

/**
 * Calcula la capacidad máxima de partidos para un período
 * @param {string} periodo - 'day', 'week', 'month', 'year'
 * @param {number} dia_semana - Día de la semana (0=Dom, 1=Lun, etc.) - opcional para 'day'
 * @returns {number} - Capacidad máxima de partidos
 */
function calcularCapacidadMaxima(periodo, dia_semana = null) {
  const duracion_turno = HORARIOS_CONFIG.duracion_turno_horas;
  
  switch(periodo) {
    case 'day': {
      // Capacidad de un día específico
      const horario = HORARIOS_CONFIG.horarios.find(h => h.dia_semana === dia_semana && h.habilitada);
      if (!horario) return 0;
      
      const horas_disponibles = calcularHorasDisponibles(horario.hora_apertura, horario.hora_cierre);
      const turnos_por_cancha = Math.floor(horas_disponibles / duracion_turno);
      return turnos_por_cancha * HORARIOS_CONFIG.num_canchas;
    }
    
    case 'week': {
      // Capacidad de una semana completa
      let total = 0;
      HORARIOS_CONFIG.horarios.forEach(horario => {
        if (horario.habilitada) {
          const horas_disponibles = calcularHorasDisponibles(horario.hora_apertura, horario.hora_cierre);
          const turnos_por_cancha = Math.floor(horas_disponibles / duracion_turno);
          total += turnos_por_cancha * HORARIOS_CONFIG.num_canchas;
        }
      });
      return total;
    }
    
    case 'month': {
      // Capacidad de un mes (4 semanas aprox)
      const capacidad_semanal = calcularCapacidadMaxima('week');
      return capacidad_semanal * 4;
    }
    
    case 'year': {
      // Capacidad de un año (52 semanas)
      const capacidad_semanal = calcularCapacidadMaxima('week');
      return capacidad_semanal * 52;
    }
    
    default:
      return 100; // Valor por defecto
  }
}

/**
 * Calcula las horas disponibles entre apertura y cierre
 * @param {string} apertura - Hora de apertura (formato "HH:MM")
 * @param {string} cierre - Hora de cierre (formato "HH:MM")
 * @returns {number} - Horas disponibles
 */
function calcularHorasDisponibles(apertura, cierre) {
  const [horaApertura, minApertura] = apertura.split(':').map(Number);
  const [horaCierre, minCierre] = cierre.split(':').map(Number);
  
  const minutosApertura = horaApertura * 60 + minApertura;
  const minutosCierre = horaCierre * 60 + minCierre;
  
  return (minutosCierre - minutosApertura) / 60;
}

/**
 * Calcula el porcentaje de ocupación
 * @param {number} partidos_reales - Partidos reservados
 * @param {number} capacidad_maxima - Capacidad máxima calculada
 * @returns {number} - Porcentaje de ocupación (0-100)
 */
function calcularPorcentajeOcupacion(partidos_reales, capacidad_maxima) {
  if (capacidad_maxima === 0) return 0;
  return Math.min((partidos_reales / capacidad_maxima) * 100, 100);
}

// ============================================
// INICIALIZACIÓN
// ============================================

document.addEventListener("DOMContentLoaded", () => {
  
  // Inicializar el filtro del gráfico
  initializeChartFilter();
  
  // Cargar gráfico inicial con vista de semana
  updateChart('week');
});

/**
 * Inicializa el filtro temporal del gráfico de barras
 */
function initializeChartFilter() {
  const filterSelect = document.getElementById('timeFilter');
  
  if (!filterSelect) {
    console.warn("⚠️ No se encontró el elemento timeFilter");
    return;
  }
  
  filterSelect.addEventListener('change', function(e) {
    const filter = e.target.value;
    updateChart(filter);
  });
}

/**
 * Actualiza el gráfico según el filtro seleccionado
 * @param {string} filter - Tipo de período: 'day', 'week', 'month', 'year'
 */
function updateChart(filter) {
  const barChart = document.getElementById('barChart');
  
  if (!barChart) {
    console.warn("⚠️ No se encontró el elemento barChart");
    return;
  }
  
  // TODO: Reemplazar con datos reales de la BD
  // Query sugerido: SELECT COUNT(*) as partidos, fecha FROM reservas 
  //                 WHERE fecha BETWEEN ? AND ? GROUP BY fecha
  
  // Datos de ejemplo para diferentes períodos
  const chartData = {
    day: [
      { label: '8h', value: 1, dia_semana: 1 },
      { label: '10h', value: 2, dia_semana: 1 },
      { label: '12h', value: 3, dia_semana: 1 },
      { label: '14h', value: 3, dia_semana: 1 },
      { label: '16h', value: 3, dia_semana: 1 },
      { label: '18h', value: 3, dia_semana: 1 },
      { label: '20h', value: 2, dia_semana: 1 },
      { label: '22h', value: 1, dia_semana: 1 }
    ],
    week: [
      { label: 'Lun', value: 12, dia_semana: 1 },  // 12/21 = 57% (cyan)
      { label: 'Mar', value: 15, dia_semana: 2 },  // 15/21 = 71% (amarillo)
      { label: 'Mié', value: 8, dia_semana: 3 },   // 8/21 = 38% (azul)
      { label: 'Jue', value: 18, dia_semana: 4 },  // 18/21 = 86% (amarillo)
      { label: 'Vie', value: 20, dia_semana: 5 },  // 20/21 = 95% (rojo)
      { label: 'Sáb', value: 17, dia_semana: 6 },  // 17/18 = 94% (rojo)
      { label: 'Dom', value: 10, dia_semana: 0 }   // 10/15 = 67% (cyan)
    ],
    month: [
      { label: 'Sem 1', value: 85 },   // 85/147 = 58% (cyan)
      { label: 'Sem 2', value: 110 },  // 110/147 = 75% (amarillo)
      { label: 'Sem 3', value: 65 },   // 65/147 = 44% (azul)
      { label: 'Sem 4', value: 135 }   // 135/147 = 92% (rojo)
    ],
    year: [
      { label: 'Ene', value: 420 },   // 420/588 = 71% (amarillo)
      { label: 'Feb', value: 380 },   // 380/588 = 65% (cyan)
      { label: 'Mar', value: 490 },   // 490/588 = 83% (amarillo)
      { label: 'Abr', value: 450 },   // 450/588 = 77% (amarillo)
      { label: 'May', value: 520 },   // 520/588 = 88% (amarillo)
      { label: 'Jun', value: 540 },   // 540/588 = 92% (rojo)
      { label: 'Jul', value: 560 },   // 560/588 = 95% (rojo)
      { label: 'Ago', value: 530 },   // 530/588 = 90% (rojo)
      { label: 'Sep', value: 480 },   // 480/588 = 82% (amarillo)
      { label: 'Oct', value: 460 },   // 460/588 = 78% (amarillo)
      { label: 'Nov', value: 410 },   // 410/588 = 70% (amarillo)
      { label: 'Dic', value: 350 }    // 350/588 = 60% (cyan)
    ]
  };
  
  const data = chartData[filter];
  
  if (!data) {
    console.error(`❌ No hay datos para el filtro: ${filter}`);
    return;
  }
  
  // Calcular capacidad máxima según el período
  let capacidadMaxima;
  if (filter === 'day') {
    // Para día, usar el día de la semana del primer elemento
    capacidadMaxima = calcularCapacidadMaxima('day', data[0].dia_semana);
  } else {
    capacidadMaxima = calcularCapacidadMaxima(filter);
  }
  
  
  // Regenerar barras con altura basada en porcentaje de ocupación
  barChart.innerHTML = data.map(item => {
    // Calcular capacidad específica si es necesario
    let capacidad = capacidadMaxima;
    if (filter === 'day' && item.dia_semana !== undefined) {
      capacidad = calcularCapacidadMaxima('day', item.dia_semana);
    }
    
    // Calcular porcentaje de ocupación real (esto será la altura de la barra)
    const ocupacionPercent = calcularPorcentajeOcupacion(item.value, capacidad);
    
    // Color de la barra según ocupación
    let barColor = '#0d6efd'; // Azul por defecto
    let colorName = 'azul';
    if (ocupacionPercent >= 90) {
      barColor = '#dc3545'; // Rojo - casi lleno
      colorName = 'rojo';
    } else if (ocupacionPercent >= 70) {
      barColor = '#ffc107'; // Amarillo - ocupación alta
      colorName = 'amarillo';
    } else if (ocupacionPercent >= 50) {
      barColor = '#0dcaf0'; // Cyan - ocupación media
      colorName = 'cyan';
    }
    
    const barHTML = `
      <div class="bar-wrapper">
        <div class="bar" 
             style="height: ${ocupacionPercent}%; background: linear-gradient(180deg, ${barColor} 0%, ${barColor}dd 100%);" 
             data-value="${item.value}"
             data-ocupacion="${ocupacionPercent.toFixed(1)}"
             title="${item.value} partidos (${ocupacionPercent.toFixed(1)}% ocupación)">
          <span class="bar-value">${item.value}</span>
          <span class="bar-percentage">${ocupacionPercent.toFixed(0)}%</span>
        </div>
        <div class="bar-label">${item.label}</div>
      </div>
    `;
    
    return barHTML;
  }).join('');
}
