// Gestion des tableaux de données
class DataTable {
    constructor(tableId, options = {}) {
        this.table = document.getElementById(tableId);
        if (!this.table) {
            console.warn('Tableau "${tableId}" non trouvé');
            return;
        }
        
        this.options = {
            perPage: options.perPage || 25,
            searchable: options.searchable !== false,
            sortable: options.sortable !== false,
            pagination: options.pagination !== false,
            responsive: options.responsive !== false,
            ...options
        };
        
        this.data = [];
        this.filteredData = [];
        this.currentPage = 1;
        this.perPage = this.options.perPage;
        this.sortColumn = null;
        this.sortDirection = 'asc';
        this.searchTerm = '';
        this.totalItems = 0;
        
        this.init();
    }
    
    init() {
        this.extractData();
        this.initControls();
        this.render();
    }
    
    extractData() {
        const tbody = this.table.querySelector('tbody');
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        const headers = this.table.querySelectorAll('thead th');
        
        this.data = [];
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = {};
            cells.forEach((cell, index) => {
                const header = headers[index];
                if (header) {
                    const key = this.getColumnKey(header);
                    rowData[key] = cell.textContent.trim();
                    rowData['html' + key] = cell.innerHTML;
                }
            });
            this.data.push(rowData);
        });
        
        this.filteredData = [...this.data];
        this.totalItems = this.data.length;
    }
    
    getColumnKey(header) {
        let key = header.textContent.trim().toLowerCase();
        key = key.replace(/\s+/g, '_');
        key = key.replace(/[^a-z0-9_]/g, '');
        return key || 'col_' + Math.random().toString(36).substr(2, 5);
    }
    
    initControls() {
        // Barre de recherche
        if (this.options.searchable) {
            const searchContainer = document.createElement('div');
            searchContainer.className = 'datatable-search';
            searchContainer.style.cssText = `
                padding: 15px 20px;
                background: #f8f9fa;
                border-bottom: 1px solid #eee;
            `;
            searchContainer.innerHTML = `
                <div style="display: flex; gap: 10px; align-items: center; max-width: 400px;">
                    <i class="fas fa-search" style="color: #999;"></i>
                    <input type="text" class="form-control" placeholder="Rechercher..." 
                           style="flex: 1; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                </div>
            `;
            this.table.parentNode.insertBefore(searchContainer, this.table);
            
            const searchInput = searchContainer.querySelector('input');
            searchInput.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filter();
                this.render();
            });
        }
        
        // En-têtes triables
        if (this.options.sortable) {
            const headers = this.table.querySelectorAll('thead th');
            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    const key = this.getColumnKey(header);
                    this.sort(key);
                });
                header.classList.add('sortable');
            });
        }
        
        // Responsive
        if (this.options.responsive) {
            this.table.classList.add('table-responsive');
        }
    }
    
    filter() {
        if (!this.searchTerm) {
            this.filteredData = [...this.data];
            return;
        }
        
        this.filteredData = this.data.filter(row => {
            return Object.values(row).some(value => 
                String(value).toLowerCase().includes(this.searchTerm)
            );
        });
        
        this.currentPage = 1;
        this.totalItems = this.filteredData.length;
    }
    
    sort(column) {
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
        }
        
        this.filteredData.sort((a, b) => {
            let valA = a[column] || '';
            let valB = b[column] || '';
            
            // Trier les nombres
            if (!isNaN(valA) && !isNaN(valB)) {
                valA = parseFloat(valA);
                valB = parseFloat(valB);
            }
            
            if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
            if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
        
        this.render();
    }
    
    render() {
        const tbody = this.table.querySelector('tbody');
        if (!tbody) return;
        
        // Pagination
        const totalPages = Math.ceil(this.totalItems / this.perPage);
        const startIndex = (this.currentPage - 1) * this.perPage;
        const endIndex = Math.min(startIndex + this.perPage, this.totalItems);
        const pageData = this.filteredData.slice(startIndex, endIndex);
        
        // Rendre les lignes
        const headers = this.table.querySelectorAll('thead th');
        tbody.innerHTML = '';
        
        if (pageData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="${headers.length}" class="empty-state">
                        <i class="fas fa-search" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                        <h4>Aucun résultat trouvé</h4>
                        <p>Essayez de modifier vos critères de recherche</p>
                    </td>
                </tr>
            `;
        } else {
            pageData.forEach(rowData => {
                const tr = document.createElement('tr');
                headers.forEach(header => {
                    const key = this.getColumnKey(header);
                    const td = document.createElement('td');
                    td.innerHTML = rowData['html' + key] || rowData[key] || '';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }
        
        // Pagination
        if (this.options.pagination && totalPages > 1) {
            this.renderPagination(totalPages);
        }
        
        // Mettre à jour les indicateurs de tri
        this.updateSortIndicators();
    }
    
    renderPagination(totalPages) {
        let paginationContainer = this.table.parentNode.querySelector('.datatable-pagination');
        if (!paginationContainer) {
            paginationContainer = document.createElement('div');
            paginationContainer.className = 'datatable-pagination';
            paginationContainer.style.cssText = `
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 20px;
                border-top: 1px solid #eee;
                flex-wrap: wrap;
                gap: 10px;
            `;
            this.table.parentNode.appendChild(paginationContainer);
        }
        
        const start = (this.currentPage - 1) * this.perPage + 1;
        const end = Math.min(this.currentPage * this.perPage, this.totalItems);
        
        let html = `
            <div class="pagination-info" style="color: #666; font-size: 14px;">
                Affichage de ${start} à ${end} sur ${this.totalItems} éléments
                ${this.searchTerm ? ('filtrés sur ${this.data.length} au total') : ''}
            </div>
            <div class="pagination-links" style="display: flex; gap: 5px;">
        `;
        
        // Précédent
        if (this.currentPage > 1) {
            html += <a href="#" data-page="${this.currentPage - 1}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; transition: all 0.3s;">&laquo;</a>;
        }
        
        // Pages
        const maxVisiblePages = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage < maxVisiblePages - 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        if (startPage > 1) {
            html += <a href="#" data-page="1" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; transition: all 0.3s;">1</a>;
            if (startPage > 2) html += <span style="padding: 0 5px; color: #999;">...</span>;
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += <a href="#" data-page="${i}" class="${i === this.currentPage ? 'active' : ''}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid ${i === this.currentPage ? '#1976d2' : '#ddd'}; border-radius: 4px; text-decoration: none; color: ${i === this.currentPage ? '#fff' : '#333'}; background: ${i === this.currentPage ? '#1976d2' : 'transparent'}; transition: all 0.3s;">${i}</a>;
        }
        
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += <span style="padding: 0 5px; color: #999;">...</span>;
            html += <a href="#" data-page="${totalPages}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; transition: all 0.3s;">${totalPages}</a>;
        }
        
        // Suivant
        if (this.currentPage < totalPages) {
            html += <a href="#" data-page="${this.currentPage + 1}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; transition: all 0.3s;">&raquo;</a>;
        }
        
        html += '</div>';
        paginationContainer.innerHTML = html;
        
        // Événements de pagination
        paginationContainer.querySelectorAll('[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(link.dataset.page);
                if (!isNaN(page) && page !== this.currentPage) {
                    this.currentPage = page;
                    this.render();
                }
            });
        });
    }
    
    updateSortIndicators() {
        const headers = this.table.querySelectorAll('thead th');
        headers.forEach(header => {
            header.classList.remove('asc', 'desc');
            const key = this.getColumnKey(header);
            if (key === this.sortColumn) {
                header.classList.add(this.sortDirection);
            }
        });
    }
    
    refresh() {
        this.extractData();
        this.filter();
        this.render();
    }
    
    setPerPage(perPage) {
        this.perPage = perPage;
        this.currentPage = 1;
        this.render();
    }
    
    goToPage(page) {
        const totalPages = Math.ceil(this.totalItems / this.perPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
            this.render();
        }
    }
}

// Initialiser automatiquement
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-datatable]').forEach(table => {
        const options = {};
        if (table.dataset.perPage) options.perPage = parseInt(table.dataset.perPage);
        if (table.dataset.searchable === 'false') options.searchable = false;
        if (table.dataset.sortable === 'false') options.sortable = false;
        if (table.dataset.pagination === 'false') options.pagination = false;
        
        new DataTable(table.id || table.dataset.datatable, options);
    });
});

// Exporter
window.DataTable = DataTable;