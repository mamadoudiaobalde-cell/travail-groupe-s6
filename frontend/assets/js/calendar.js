// Gestion du calendrier
class CalendarManager {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            view: options.view || 'month',
            firstDay: options.firstDay || 1,
            events: options.events || [],
            onDayClick: options.onDayClick || null,
            onEventClick: options.onEventClick || null,
            ...options
        };
        this.currentDate = new Date();
        this.selectedDate = null;
        this.events = this.options.events;
        this.render();
    }
    
    render() {
        this.container.innerHTML = this.generateHTML();
        this.attachEvents();
        this.renderEvents();
    }
    
    generateHTML() {
        const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                           'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        const dayNames = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        let html = `
            <div class="calendar">
                <div class="calendar-header">
                    <button class="calendar-nav" data-action="prev"><i class="fas fa-chevron-left"></i></button>
                    <h3>${monthNames[month]} ${year}</h3>
                    <button class="calendar-nav" data-action="next"><i class="fas fa-chevron-right"></i></button>
                    <button class="calendar-today" data-action="today">Aujourd'hui</button>
                </div>
                <div class="calendar-day-names">
                    ${dayNames.map(day => <div class="day-name">${day}</div>).join('')}
                </div>
                <div class="calendar-grid">
        `;
        
        // Jours vides avant le premier jour du mois
        const startOffset = (firstDay - this.options.firstDay + 7) % 7;
        for (let i = 0; i < startOffset; i++) {
            html += <div class="calendar-day empty"></div>;
        }
        
        // Jours du mois
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const isToday = date.getTime() === today.getTime();
            const isSelected = this.selectedDate && date.getTime() === this.selectedDate.getTime();
            const dayEvents = this.getEventsForDate(date);
            
            html += `
                <div class="calendar-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${dayEvents.length > 0 ? 'has-events' : ''}"
                     data-date="${date.toISOString().split('T')[0]}">
                    <span class="day-number">${day}</span>
                    ${dayEvents.length > 0 ? <span class="event-dot"></span> : ''}
                </div>
            `;
        }
        
        html += `
                </div>
                ${this.options.view === 'month' ? this.renderEventList() : ''}
            </div>
        `;
        
        return html;
    }
    
    renderEventList() {
        const events = this.getEventsForMonth();
        if (events.length === 0) {
            return `
                <div class="calendar-events empty">
                    <p>Aucun événement ce mois-ci</p>
                </div>
            `;
        }
        
        return `
            <div class="calendar-events">
                <h4>Événements du mois</h4>
                ${events.map(event => `
                    <div class="calendar-event" data-event-id="${event.id}">
                        <span class="event-color" style="background: ${event.color || '#1976d2'};"></span>
                        <div class="event-info">
                            <div class="event-title">${event.title}</div>
                            <div class="event-date">${new Date(event.date).toLocaleDateString('fr-FR')}</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    getEventsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        return this.events.filter(e => e.date === dateStr);
    }
    
    getEventsForMonth() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        return this.events.filter(e => {
            const eventDate = new Date(e.date);
            return eventDate.getFullYear() === year && eventDate.getMonth() === month;
        });
    }
    
    attachEvents() {
        // Navigation
        this.container.querySelectorAll('.calendar-nav').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.currentTarget.dataset.action;
                if (action === 'prev') {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                } else if (action === 'next') {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                }
                this.render();
            });
        });
        
        // Aujourd'hui
        const todayBtn = this.container.querySelector('.calendar-today');
        if (todayBtn) {
            todayBtn.addEventListener('click', () => {
                this.currentDate = new Date();
                this.render();
            });
        }
        
        // Jours
        this.container.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
            day.addEventListener('click', (e) => {
                const dateStr = e.currentTarget.dataset.date;
                const date = new Date(dateStr + 'T00:00:00');
                this.selectedDate = date;
                this.render();
                
                if (this.options.onDayClick) {
                    this.options.onDayClick(date, this.getEventsForDate(date));
                }
            });
        });
        
        // Événements
        this.container.querySelectorAll('.calendar-event').forEach(event => {
            event.addEventListener('click', (e) => {
                const id = parseInt(e.currentTarget.dataset.eventId);
                if (this.options.onEventClick) {
                    this.options.onEventClick(id);
                }
            });
        });
    }
    
    renderEvents() {
        // Mettre à jour les événements affichés
        const eventList = this.container.querySelector('.calendar-events');
        if (eventList) {
            this.events = this.options.events;
            // Re-render events
        }
    }
    
    addEvent(event) {
        this.events.push(event);
        this.render();
    }
    
    removeEvent(id) {
        this.events = this.events.filter(e => e.id !== id);
        this.render();
    }
    
    updateEvents(events) {
        this.events = events;
        this.render();
    }
    
    goToMonth(month, year) {
        this.currentDate = new Date(year, month, 1);
        this.render();
    }
    
    goToDate(date) {
        this.currentDate = new Date(date);
        this.selectedDate = new Date(date);
        this.render();
    }
}

// Styles du calendrier
const calendarStyles = document.createElement('style');
calendarStyles.textContent = `
    .calendar {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        font-family: inherit;
    }
    
    .calendar-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .calendar-header h3 {
        flex: 1;
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    .calendar-nav {
        background: none;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 6px 12px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .calendar-nav:hover {
        background: #f5f5f5;
        border-color: #1976d2;
    }
    
    .calendar-today {
        background: #1976d2;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 16px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .calendar-today:hover {
        background: #1565c0;
    }
    
    .calendar-day-names {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        margin-bottom: 8px;
    }
    
    .day-name {
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: #666;
        padding: 8px 0;
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        font-size: 14px;
        min-height: 40px;
    }
    
    .calendar-day:not(.empty):hover {
        background: #f5f5f5;
    }
    
    .calendar-day.today {
        background: #e3f2fd;
        font-weight: 600;
        color: #1976d2;
    }
    
    .calendar-day.selected {
        background: #1976d2;
        color: white;
        font-weight: 600;
    }
    
    .calendar-day.empty {
        cursor: default;
    }
    
    .calendar-day .event-dot {
        position: absolute;
        bottom: 4px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1976d2;
    }
    
    .calendar-events {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .calendar-events h4 {
        margin: 0 0 12px 0;
        font-size: 14px;
        color: #666;
    }
    
    .calendar-events.empty {
        text-align: center;
        color: #999;
        padding: 20px 0;
    }
    
    .calendar-event {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 4px;
        margin-bottom: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .calendar-event:hover {
        background: #f5f5f5;
    }
    
    .calendar-event .event-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .calendar-event .event-info {
        flex: 1;
    }
    
    .calendar-event .event-title {
        font-weight: 500;
        font-size: 14px;
    }
    
    .calendar-event .event-date {
        font-size: 12px;
        color: #666;
    }
`;

document.head.appendChild(calendarStyles);

// Exporter
window.CalendarManager = CalendarManager;