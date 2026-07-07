// Gestion des graphiques
class ChartManager {
    constructor() {
        this.charts = {};
        this.isChartJsLoaded = typeof Chart !== 'undefined';
    }
    
    createChart(elementId, type, data, options = {}) {
        if (!this.isChartJsLoaded) {
            console.warn('Chart.js n\'est pas chargé');
            return null;
        }
        
        const ctx = document.getElementById(elementId);
        if (!ctx) {
            console.warn('Élément ${elementId} non trouvé');
            return null;
        }
        
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12 }
                    }
                }
            }
        };
        
        const chartOptions = this.deepMerge(defaultOptions, options);
        
        const chart = new Chart(ctx, {
            type: type,
            data: data,
            options: chartOptions
        });
        
        this.charts[elementId] = chart;
        return chart;
    }
    
    createBarChart(elementId, labels, datasets, options = {}) {
        const data = {
            labels: labels,
            datasets: datasets.map(ds => ({
                ...ds,
                borderRadius: 4,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }))
        };
        
        return this.createChart(elementId, 'bar', data, options);
    }
    
    createLineChart(elementId, labels, datasets, options = {}) {
        const data = {
            labels: labels,
            datasets: datasets.map(ds => ({
                ...ds,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }))
        };
        
        return this.createChart(elementId, 'line', data, options);
    }
    
    createPieChart(elementId, labels, data, colors, options = {}) {
        const chartData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };
        
        return this.createChart(elementId, 'pie', chartData, options);
    }
    
    createDoughnutChart(elementId, labels, data, colors, options = {}) {
        const chartData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };
        
        return this.createChart(elementId, 'doughnut', chartData, options);
    }
    
    createHorizontalBar(elementId, labels, datasets, options = {}) {
        const data = {
            labels: labels,
            datasets: datasets.map(ds => ({
                ...ds,
                borderRadius: 4,
                barPercentage: 0.7
            }))
        };
        
        return this.createChart(elementId, 'bar', data, {
            ...options,
            indexAxis: 'y'
        });
    }
    
    destroyChart(elementId) {
        if (this.charts[elementId]) {
            this.charts[elementId].destroy();
            delete this.charts[elementId];
        }
    }
    
    destroyAllCharts() {
        Object.keys(this.charts).forEach(key => {
            this.charts[key].destroy();
        });
        this.charts = {};
    }
    
    // Utilitaires
    deepMerge(target, source) {
        const result = { ...target };
        for (const key in source) {
            if (source[key] && typeof source[key] === 'object') {
                result[key] = this.deepMerge(result[key] || {}, source[key]);
            } else {
                result[key] = source[key];
            }
        }
        return result;
    }
    
    getDefaultColors() {
        return [
            '#1976d2', '#2e7d32', '#ed6c02', '#d32f2f', '#9c27b0',
            '#0288d1', '#00695c', '#c2185b', '#3f51b5', '#827717'
        ];
    }
}

// Couleurs prédéfinies
const CHART_COLORS = {
    blue: '#1976d2',
    green: '#2e7d32',
    red: '#d32f2f',
    orange: '#ed6c02',
    purple: '#9c27b0',
    cyan: '#0288d1',
    teal: '#00695c',
    pink: '#c2185b',
    indigo: '#3f51b5',
    lime: '#827717',
    amber: '#ff8f00',
    brown: '#5d4037'
};

const CHART_COLORS_PALETTE = [
    CHART_COLORS.blue,
    CHART_COLORS.green,
    CHART_COLORS.orange,
    CHART_COLORS.red,
    CHART_COLORS.purple,
    CHART_COLORS.cyan,
    CHART_COLORS.teal,
    CHART_COLORS.pink,
    CHART_COLORS.indigo,
    CHART_COLORS.lime
];

// Exporter pour une utilisation globale
window.ChartManager = ChartManager;
window.CHART_COLORS = CHART_COLORS;
window.CHART_COLORS_PALETTE = CHART_COLORS_PALETTE;// Gestion des graphiques
class ChartManager {
    constructor() {
        this.charts = {};
        this.isChartJsLoaded = typeof Chart !== 'undefined';
    }
    
    createChart(elementId, type, data, options = {}) {
        if (!this.isChartJsLoaded) {
            console.warn('Chart.js n\'est pas chargé');
            return null;
        }
        
        const ctx = document.getElementById(elementId);
        if (!ctx) {
            console.warn('Élément ${elementId} non trouvé');
            return null;
        }
        
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12 }
                    }
                }
            }
        };
        
        const chartOptions = this.deepMerge(defaultOptions, options);
        
        const chart = new Chart(ctx, {
            type: type,
            data: data,
            options: chartOptions
        });
        
        this.charts[elementId] = chart;
        return chart;
    }
    
    createBarChart(elementId, labels, datasets, options = {}) {
        const data = {
            labels: labels,
            datasets: datasets.map(ds => ({
                ...ds,
                borderRadius: 4,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }))
        };
        
        return this.createChart(elementId, 'bar', data, options);
    }
    
    createLineChart(elementId, labels, datasets, options = {}) {
        const data = {
            labels: labels,
            datasets: datasets.map(ds => ({
                ...ds,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }))
        };
        
        return this.createChart(elementId, 'line', data, options);
    }
    
    createPieChart(elementId, labels, data, colors, options = {}) {
        const chartData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };
        
        return this.createChart(elementId, 'pie', chartData, options);
    }
    
    createDoughnutChart(elementId, labels, data, colors, options = {}) {
        const chartData = {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };
        
        return this.createChart(elementId, 'doughnut', chartData, options);
    }
    
    createHorizontalBar(elementId, labels, datasets, options = {}) {
        const data = {
            labels: labels,
            datasets: datasets.map(ds => ({
                ...ds,
                borderRadius: 4,
                barPercentage: 0.7
            }))
        };
        
        return this.createChart(elementId, 'bar', data, {
            ...options,
            indexAxis: 'y'
        });
    }
    
    destroyChart(elementId) {
        if (this.charts[elementId]) {
            this.charts[elementId].destroy();
            delete this.charts[elementId];
        }
    }
    
    destroyAllCharts() {
        Object.keys(this.charts).forEach(key => {
            this.charts[key].destroy();
        });
        this.charts = {};
    }
    
    // Utilitaires
    deepMerge(target, source) {
        const result = { ...target };
        for (const key in source) {
            if (source[key] && typeof source[key] === 'object') {
                result[key] = this.deepMerge(result[key] || {}, source[key]);
            } else {
                result[key] = source[key];
            }
        }
        return result;
    }
    
    getDefaultColors() {
        return [
            '#1976d2', '#2e7d32', '#ed6c02', '#d32f2f', '#9c27b0',
            '#0288d1', '#00695c', '#c2185b', '#3f51b5', '#827717'
        ];
    }
}

// Couleurs prédéfinies
const CHART_COLORS = {
    blue: '#1976d2',
    green: '#2e7d32',
    red: '#d32f2f',
    orange: '#ed6c02',
    purple: '#9c27b0',
    cyan: '#0288d1',
    teal: '#00695c',
    pink: '#c2185b',
    indigo: '#3f51b5',
    lime: '#827717',
    amber: '#ff8f00',
    brown: '#5d4037'
};

const CHART_COLORS_PALETTE = [
    CHART_COLORS.blue,
    CHART_COLORS.green,
    CHART_COLORS.orange,
    CHART_COLORS.red,
    CHART_COLORS.purple,
    CHART_COLORS.cyan,
    CHART_COLORS.teal,
    CHART_COLORS.pink,
    CHART_COLORS.indigo,
    CHART_COLORS.lime
];

// Exporter pour une utilisation globale
window.ChartManager = ChartManager;
window.CHART_COLORS = CHART_COLORS;
window.CHART_COLORS_PALETTE = CHART_COLORS_PALETTE;