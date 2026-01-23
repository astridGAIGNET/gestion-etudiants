# Guide Alpine.js DataTable + Bootstrap 5
## Pour le projet Livret de Compétences CIPECMA

---

## 📚 Table des matières

1. [Introduction](#introduction)
2. [Pourquoi Alpine.js plutôt que DataTables.js ?](#pourquoi-alpine)
3. [Ressources essentielles](#ressources)
4. [Concepts clés Alpine.js](#concepts)
5. [Exemples adaptés au projet](#exemples)
6. [Intégration Laravel + Alpine](#integration)

---

## 🎯 Introduction {#introduction}

Ce guide explique comment créer des **tables dynamiques** avec Alpine.js et Bootstrap 5 pour remplacer DataTables.js et éviter les conflits.

### Fonctionnalités que nous allons construire :

✅ **Recherche** (search/filter)
✅ **Tri** (sorting) sur les colonnes
✅ **Pagination** avec sélection du nombre d'éléments par page
✅ **Bootstrap 5** pour le style
✅ **Pas de conflit** avec Alpine.js
✅ **Performance** : adapté pour 100-500 lignes

---

## ⚖️ Pourquoi Alpine.js plutôt que DataTables.js ? {#pourquoi-alpine}

### ❌ Problèmes avec DataTables.js + Alpine.js

1. **Manipulation du DOM conflictuelle**
   - DataTables reconstruit le HTML → Alpine perd ses références
   - Les directives `x-data`, `x-model` cessent de fonctionner

2. **Double rendu**
   - Alpine rend la table
   - DataTables la re-rend → doublon ou bug

3. **Complexité d'intégration**
   - Nécessite `wire:ignore` ou `x-ignore`
   - Code de contournement complexe

### ✅ Avantages Alpine.js pur

| Critère | DataTables.js | Alpine.js pur |
|---------|---------------|---------------|
| Poids | ~80 KB | ~15 KB |
| Conflits Alpine | ⚠️ Fréquents | ✅ Aucun |
| Courbe d'apprentissage | Moyenne | Faible |
| Personnalisation | Limitée | Totale |
| Performance (< 500 lignes) | Bonne | Excellente |
| Bootstrap 5 natif | Plugin requis | Direct |

**Conclusion** : Pour ton projet (max 100-200 stagiaires affichés), Alpine.js pur est **plus simple, plus léger et sans conflit**.

---

## 📖 Ressources essentielles {#ressources}

### Documentation officielle

1. **Alpine.js** : https://alpinejs.dev/
   - 🔥 **Start Here** : https://alpinejs.dev/start-here
   - Directives : https://alpinejs.dev/directives/data

2. **Bootstrap 5 Tables** : https://getbootstrap.com/docs/5.3/content/tables/

### Tutoriels recommandés (en ordre de difficulté)

#### 🟢 Débutant - À lire en PREMIER

1. **"Building Table Sorting and Pagination in Alpine.js"** par Raymond Camden
   - URL : https://www.raymondcamden.com/2022/05/02/building-table-sorting-and-pagination-in-alpinejs
   - ⭐ **EXCELLENT pour débuter** : très pédagogique, étape par étape
   - Couvre : chargement données, tri, pagination
   - Durée lecture : 15 min

2. **"Alpine.js: Displaying API data in a HTML table"** par w3collective
   - URL : https://w3collective.com/alpine-js-api-data-table/
   - Simple et court, bon pour comprendre `x-for`
   - Durée : 10 min

#### 🟡 Intermédiaire - Ensuite

3. **"How to build a data table with sorting and pagination"** par Lexington Themes
   - URL : https://lexingtonthemes.com/blog/how-to-build-a-data-table-with-sorting-and-pagination-using-alpinejs
   - **Le plus complet et récent (oct 2024)** 🔥
   - Avec Tailwind, mais concepts transposables à Bootstrap
   - Code complet et production-ready

4. **"Building an accessible, filterable and paginated list"** par Manuel Matuzovic (Smashing Magazine)
   - URL : https://www.smashingmagazine.com/2022/04/accessible-filterable-paginated-list-11ty-alpinejs/
   - Focus accessibilité + progressive enhancement
   - Excellent pour comprendre les filtres multiples

#### 🔴 Avancé - Optionnel

5. **Alpine.js Sort Plugin** (pour le drag & drop CCP)
   - URL : https://alpinejs.dev/plugins/sort
   - Pour réorganiser les CCP par glisser-déposer

### Exemples de code prêts à l'emploi

- **CodePen Alpine DataTable (Tailwind)** : https://codepen.io/tommyia/pen/GRoJMey
- **CodePen Table with search + pagination** : https://codepen.io/hpal/pen/ExVGZYZ

---

## 🧩 Concepts clés Alpine.js {#concepts}

### Les 6 directives essentielles pour les tables

#### 1. `x-data` - Déclare le composant

```html
<div x-data="{ 
    stagiaires: [], 
    search: '', 
    sortBy: 'nom', 
    sortDir: 'asc',
    page: 1,
    perPage: 10
}">
    <!-- Votre table -->
</div>
```

📝 **Ce qu'il faut retenir** : Toutes les données réactives vont ici.

---

#### 2. `x-init` - Initialise au chargement

```html
<div x-data="tableData()" 
     x-init="loadStagiaires()">
```

📝 Équivalent de `mounted()` en Vue ou `useEffect()` en React.

---

#### 3. `x-for` - Boucle sur les données

```html
<template x-for="stagiaire in filteredStagiaires" :key="stagiaire.id">
    <tr>
        <td x-text="stagiaire.nom"></td>
        <td x-text="stagiaire.prenom"></td>
    </tr>
</template>
```

⚠️ **Important** : `x-for` doit être sur un `<template>`, pas directement sur `<tr>`.

---

#### 4. `x-model` - Binding bidirectionnel

```html
<input type="text" 
       x-model="search" 
       placeholder="Rechercher...">
```

📝 Comme `v-model` en Vue, met à jour automatiquement la variable.

---

#### 5. `@click` - Gestion des événements

```html
<button @click="page++">Suivant</button>
<th @click="sort('nom')">Nom ↕</th>
```

📝 Raccourci de `x-on:click`, similaire à `@click` en Vue.

---

#### 6. `x-text` - Affichage de texte

```html
<td x-text="stagiaire.nom"></td>
```

📝 Alternative à `{{ }}`, plus sûr (évite XSS).

---

### Getters computés en Alpine.js

Alpine n'a pas de `computed` comme Vue, mais on utilise des **getters** :

```javascript
x-data="{
    stagiaires: [...],
    search: '',
    
    // Getter - recalculé automatiquement
    get filteredStagiaires() {
        return this.stagiaires.filter(s => 
            s.nom.toLowerCase().includes(this.search.toLowerCase())
        );
    }
}"
```

📝 Utilisation : `<template x-for="stagiaire in filteredStagiaires">`

---

## 💡 Exemples adaptés au projet {#exemples}

### Exemple 1 : Table simple des stagiaires

Voir le fichier `stagiaires-table-simple.blade.php` 📄

---

### Exemple 2 : Table complète avec toutes les fonctionnalités

Voir le fichier `stagiaires-table-complete.blade.php` 📄

---

### Exemple 3 : Table du référentiel (titres professionnels)

Voir le fichier `referentiel-table.blade.php` 📄

---

## 🔗 Intégration Laravel + Alpine {#integration}

### Où placer Alpine.js ?

#### Option 1 : CDN (pour démarrer rapidement)

Dans `resources/views/layouts/app.blade.php` :

```html
<head>
    <!-- ... Bootstrap ... -->
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
```

#### Option 2 : Via NPM (recommandé en production)

```bash
npm install alpinejs
```

Dans `resources/js/app.js` :

```javascript
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
```

Puis dans ton layout :

```blade
@vite(['resources/js/app.js'])
```

---

### Passer des données Laravel à Alpine

#### Méthode 1 : JSON inline

```blade
<div x-data="{
    stagiaires: @json($stagiaires)
}">
```

#### Méthode 2 : Via composant Alpine (recommandé)

**Dans `resources/js/components/stagiaires-table.js` :**

```javascript
export default function stagiaireTable() {
    return {
        stagiaires: [],
        loading: true,
        
        async init() {
            try {
                const response = await fetch('/api/stagiaires');
                this.stagiaires = await response.json();
            } catch (error) {
                console.error('Erreur chargement:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
```

**Dans ton Blade :**

```blade
<div x-data="stagiaireTable()" x-init="init()">
```

---

## 🎨 Styling Bootstrap 5

### Classes Bootstrap utiles pour les tables

```html
<table class="table table-striped table-hover table-bordered">
    <thead class="table-dark">
        <!-- En-têtes -->
    </thead>
</table>
```

| Classe | Effet |
|--------|-------|
| `table` | Style de base |
| `table-striped` | Lignes alternées |
| `table-hover` | Hover sur les lignes |
| `table-bordered` | Bordures |
| `table-sm` | Compact |
| `table-dark` | Fond sombre |
| `table-responsive` | Responsive (scroll horizontal) |

---

## 🚀 Performance et bonnes pratiques

### ✅ À FAIRE

1. **Limiter les données affichées**
   ```javascript
   get paginatedData() {
       const start = (this.page - 1) * this.perPage;
       return this.filteredData.slice(start, start + this.perPage);
   }
   ```

2. **Utiliser `:key` dans x-for**
   ```html
   <template x-for="item in items" :key="item.id">
   ```

3. **Debounce sur la recherche** (pour éviter trop d'updates)
   ```javascript
   x-model.debounce.300ms="search"
   ```

### ❌ À ÉVITER

1. ❌ **Ne pas mettre toute la logique dans x-data inline**
   - Extraire dans un composant Alpine séparé

2. ❌ **Ne pas oublier la pagination**
   - Plus de 100 lignes = performance dégradée

3. ❌ **Ne pas manipuler le DOM manuellement**
   - Laisser Alpine gérer, pas de jQuery !

---

## 📊 Comparaison DataTables vs Alpine

### Quand utiliser DataTables.js ?

✅ **Beaucoup de données** (1000+ lignes)
✅ **Pas d'Alpine.js** dans le projet
✅ **Export Excel/PDF intégré** requis immédiatement

### Quand utiliser Alpine.js pur ?

✅ **Projet Laravel + Alpine** (ton cas !)
✅ **Données modérées** (< 500 lignes affichées)
✅ **Personnalisation poussée**
✅ **Pas de conflit** voulu

---

## 🎓 Plan d'apprentissage pour la stagiaire

### Semaine 1 : Bases Alpine.js
1. ✅ Lire la doc "Start Here" d'Alpine (30 min)
2. ✅ Tutoriel Raymond Camden (15 min lecture + 1h pratique)
3. ✅ Reproduire l'exemple simple (stagiaires-table-simple.blade.php)

### Semaine 2 : Table complète
4. ✅ Ajouter la recherche
5. ✅ Ajouter le tri sur colonnes
6. ✅ Ajouter la pagination

### Semaine 3 : Intégration projet
7. ✅ Intégrer dans le module Stagiaires
8. ✅ Adapter au référentiel (titres/CCP/compétences)
9. ✅ Ajouter les filtres (formateur, titre, dates)

---

## 🆘 Aide et support

### Ressources de débogage

1. **Alpine DevTools** (extension Chrome)
   - https://chrome.google.com/webstore/detail/alpinejs-devtools

2. **Console Alpine**
   ```javascript
   // Dans la console Chrome
   $data // Affiche les données du composant
   ```

3. **Forum Laravel FR**
   - https://laravel.fr/

### Erreurs courantes

#### Erreur 1 : "Alpine is not defined"

**Solution** : Vérifier que Alpine est bien chargé avant ton code.

```html
<script defer src="alpine.js"></script>
<script defer src="ton-code.js"></script>
```

#### Erreur 2 : `x-for` ne fonctionne pas

**Solution** : Doit être sur `<template>`, pas sur `<tr>` directement.

❌ Mauvais :
```html
<tr x-for="item in items">
```

✅ Bon :
```html
<template x-for="item in items">
    <tr>...</tr>
</template>
```

#### Erreur 3 : Les données ne se mettent pas à jour

**Solution** : Utiliser `this.` dans les méthodes.

❌ Mauvais :
```javascript
sort(col) {
    sortBy = col; // Variable globale !
}
```

✅ Bon :
```javascript
sort(col) {
    this.sortBy = col; // Propriété Alpine
}
```

---

## 🎁 Bonus : Composants réutilisables

### Créer un composant Alpine global

**Dans `resources/js/components/data-table.js` :**

```javascript
export default function dataTable(config = {}) {
    return {
        // Configuration
        items: config.items || [],
        columns: config.columns || [],
        
        // State
        search: '',
        sortBy: config.defaultSort || '',
        sortDir: 'asc',
        page: 1,
        perPage: config.perPage || 10,
        
        // Computed
        get filteredItems() {
            if (!this.search) return this.items;
            
            return this.items.filter(item => {
                return Object.values(item).some(val => 
                    String(val).toLowerCase().includes(this.search.toLowerCase())
                );
            });
        },
        
        get sortedItems() {
            if (!this.sortBy) return this.filteredItems;
            
            return [...this.filteredItems].sort((a, b) => {
                const aVal = a[this.sortBy];
                const bVal = b[this.sortBy];
                
                if (this.sortDir === 'asc') {
                    return aVal > bVal ? 1 : -1;
                } else {
                    return aVal < bVal ? 1 : -1;
                }
            });
        },
        
        get paginatedItems() {
            const start = (this.page - 1) * this.perPage;
            return this.sortedItems.slice(start, start + this.perPage);
        },
        
        get totalPages() {
            return Math.ceil(this.sortedItems.length / this.perPage);
        },
        
        // Methods
        sort(column) {
            if (this.sortBy === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDir = 'asc';
            }
            this.page = 1;
        },
        
        nextPage() {
            if (this.page < this.totalPages) this.page++;
        },
        
        prevPage() {
            if (this.page > 1) this.page--;
        }
    }
}
```

**Utilisation :**

```blade
<div x-data="dataTable({
    items: @json($stagiaires),
    columns: ['nom', 'prenom', 'email'],
    defaultSort: 'nom',
    perPage: 15
})">
    <!-- Ta table -->
</div>
```

---

## 📝 Checklist finale

Avant de commencer à coder, vérifie que tu as :

- [ ] Installé Alpine.js (CDN ou NPM)
- [ ] Bootstrap 5 configuré
- [ ] Lu au moins 1 tutoriel recommandé
- [ ] Compris les 6 directives essentielles
- [ ] Testé l'exemple simple sur une page de test
- [ ] Préparé tes données Laravel (controller + route)

**Ensuite, tu es prête à créer ta première table Alpine ! 🚀**

---

## 📞 Contact et questions

Si tu bloques sur un point :
1. Consulte la doc Alpine : https://alpinejs.dev/
2. Regarde les exemples CodePen liés
3. Pose ta question à Lordo avec un exemple de code

**Bon courage et bon apprentissage ! 💪**
