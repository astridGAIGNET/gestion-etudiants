/**
 * Composant Alpine.js : List.js
 * Tableau avec recherche, tri et pagination
 */
export default function listJs(config = {}) {
    return {
        listInstance: null,
        searchQuery: '',
        currentPage: 1,
        itemsPerPage: config.itemsPerPage || 25,
        totalItems: 0,
        totalPages: 0,
        sortColumn: '',
        sortOrder: 'asc',

        init() {
            this.$nextTick(() => {
                const options = {
                    valueNames: config.valueNames || [],
                    page: this.itemsPerPage,
                    pagination: {
                        innerWindow: 2,     // Nombre de pages visibles autour de la page actuelle
                        outerWindow: 1,     // Nombre de pages visibles au début et à la fin
                        left: 1,            // Pages à gauche
                        right: 1,           // Pages à droite
                        paginationClass: 'pagination',
                        item: '<li class="page-item"><a class="page-link page"></a></li>',
                    }
                };

                this.listInstance = new window.List(config.id || 'list-container', options);
                this.totalItems = this.listInstance.size();
                this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);

                console.log('List.js initialisé:', {
                    totalItems: this.totalItems,
                    itemsPerPage: this.itemsPerPage,
                    totalPages: this.totalPages
                });
            });
        },

        // Recherche
        search() {
            if (this.listInstance) {
                this.listInstance.search(this.searchQuery);

                // Recalcule le nombre de pages après recherche
                this.totalItems = this.listInstance.matchingItems.length;
                this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
            }
        },

        // Réinitialise la recherche
        clearSearch() {
            this.searchQuery = '';
            if (this.listInstance) {
                this.listInstance.search('');
                this.totalItems = this.listInstance.size();
                this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
            }
        },

        // Tri
        sortBy(column) {
            if (!this.listInstance) return;

            // Alterne l'ordre si c'est la même colonne
            if (this.sortColumn === column) {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortOrder = 'asc';
            }

            this.listInstance.sort(column, { order: this.sortOrder });
        },

        // Icône de tri
        getSortIcon(column) {
            if (this.sortColumn !== column) {
                return 'bi-arrow-down-up text-muted';
            }
            return this.sortOrder === 'asc' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-up';
        }
    };
}
