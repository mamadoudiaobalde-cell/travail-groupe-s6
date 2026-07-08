// Système de filtres dynamiques
class FilterManager {
    constructor(container, options = {}) {
        this.container = container;
        this.options = {
            filterSelector: options.filterSelector || '[data-filter]',
            targetSelector: options.targetSelector || '[data-filter-target]',
            itemSelector: options.itemSelector || '[data-filter-item]',
            emptySelector: options.emptySelector || '[data-filter-empty]',
            ...options
        };
        this.filters = {};
        this.items = [];
        this.init();
    }
    
    init() {
        // Récupérer les filtres
        const filterElements = this.container.querySelectorAll(this.options.filterSelector);
        filterElements.forEach(el => {
            const key = el.dataset.filter;
            this.filters[key] = {
                element: el,
                value: el.value || '',
                type: el.type || 'text'
            };
            
            el.addEventListener('change', () => this.filter());
            el.addEventListener('input', () => this.filter());
        });
        
        // Récupérer les items
        const target = this.container.querySelector(this.options.targetSelector);
        if (target) {
            this.items = Array.from(target.querySelectorAll(this.options.itemSelector));
        }
    }
    
    filter() {
        // Mettre à jour les valeurs des filtres
        Object.keys(this.filters).forEach(key => {
            const filter = this.filters[key];
            filter.value = filter.element.value || '';
        });
        
        // Filtrer les items
        let visibleCount = 0;
        this.items.forEach(item => {
            let show = true;
            
            Object.keys(this.filters).forEach(key => {
                const filter = this.filters[key];
                if (filter.value && filter.value !== '') {
                    const itemValue = item.dataset[key] || '';
                    show = show && this.matchFilter(itemValue, filter.value, filter.type);
                }
            });
            
            item.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });
        
        // Afficher le message "aucun résultat"
        const emptyElement = this.container.querySelector(this.options.emptySelector);
        if (emptyElement) {
            emptyElement.style.display = visibleCount === 0 ? 'block' : 'none';
        }
        
        // Déclencher l'événement
        this.container.dispatchEvent(new CustomEvent('filtered', {
            detail: { total: this.items.length, visible: visibleCount }
        }));
    }
    
    matchFilter(itemValue, filterValue, type) {
        if (!filterValue) return true;
        
        switch(type) {
            case 'text':
            case 'search':
                return itemValue.toLowerCase().includes(filterValue.toLowerCase());
            case 'select':
            case 'radio':
                return itemValue === filterValue;
            case 'checkbox':
                return itemValue === filterValue || (Array.isArray(itemValue) && itemValue.includes(filterValue));
            case 'range':
                const [min, max] = filterValue.split('-');
                const val = parseFloat(itemValue);
                if (min && max) return val >= parseFloat(min) && val <= parseFloat(max);
                if (min) return val >= parseFloat(min);
                if (max) return val <= parseFloat(max);
                return true;
            case 'date':
                const date = new Date(itemValue);
                const filterDate = new Date(filterValue);
                return date.toDateString() === filterDate.toDateString();
            default:
                return itemValue === filterValue;
        }
    }
    
    reset() {
        Object.keys(this.filters).forEach(key => {
            const filter = this.filters[key];
            if (filter.element.tagName === 'SELECT') {
                filter.element.selectedIndex = 0;
            } else {
                filter.element.value = '';
            }
            filter.value = '';
        });
        this.filter();
    }
    
    getActiveFilters() {
        const active = {};
        Object.keys(this.filters).forEach(key => {
            const filter = this.filters[key];
            if (filter.value && filter.value !== '') {
                active[key] = filter.value;
            }
        });
        return active;
    }
}

// Initialiser les filtres automatiquement
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-filter-container]').forEach(container => {
        const filterManager = new FilterManager(container);
        container.dataset.filterManager = true;
    });
});

// Exporter
window.FilterManager = FilterManager;