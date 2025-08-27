# Dashboard Employeur - GoTeach

## Vue d'ensemble

Le dashboard employeur a été complètement redesigné avec un design moderne et adapté aux besoins des employeurs. Il offre une interface intuitive pour gérer les offres d'emploi, rechercher des talents et suivre les candidatures.

## Fonctionnalités principales

### 1. Header moderne
- **Barre de recherche intelligente** : Recherche de talents avec placeholder dynamique
- **Notifications en temps réel** : Badge animé pour les nouvelles candidatures
- **Menu profil déroulant** : Accès rapide au profil, paramètres et déconnexion

### 2. Sidebar gauche
- **Profil entreprise** : Affichage du nom de l'entreprise avec statistiques
- **Navigation rapide** : Liens directs vers les fonctionnalités principales
- **Statistiques en temps réel** : Nombre d'offres actives, candidatures, etc.

### 3. Contenu principal
- **Actions rapides** : Cartes interactives pour les actions principales
- **Statistiques de performance** : Vue d'ensemble des métriques importantes
- **Nouveaux talents** : Liste des candidats récents avec actions

### 4. Sidebar droite
- **Activité récente** : Timeline des dernières activités
- **Événements GoTeach** : Événements à venir et opportunités

## Structure des fichiers

```
resources/
├── views/
│   └── pages/
│       └── employeurs/
│           └── dashboard.blade.php    # Template HTML
├── css/
│   └── employeurs/
│       └── styles.css                 # Styles CSS
└── js/
    └── employeurs/
        └── script.js                  # Interactions JavaScript
```

## Technologies utilisées

### CSS
- **Variables CSS** : Système de couleurs cohérent
- **Grid Layout** : Layout responsive moderne
- **Animations CSS** : Transitions fluides et animations
- **Media Queries** : Design responsive complet

### JavaScript
- **ES6+** : Syntaxe moderne
- **Event Listeners** : Gestion des interactions
- **Intersection Observer** : Animations au scroll
- **Toast Notifications** : Système de notifications

### Couleurs principales
```css
--primary: #1c1f26;       /* Gris-noir profond */
--secondary: #ffcc00;     /* Jaune DHL */
--accent: #005577;        /* Bleu pétrole foncé */
--light: #ffffff;         /* Fond blanc */
--dark: #0d1117;
--gray: #6e7a8a;
```

## Fonctionnalités interactives

### 1. Recherche intelligente
- Placeholder dynamique qui change toutes les 3 secondes
- Recherche en temps réel avec suggestions
- Historique des recherches

### 2. Menu profil
- Dropdown avec animation
- Accès direct au profil et paramètres
- Bouton de déconnexion intégré

### 3. Notifications
- Badge animé avec compteur
- Notifications toast en temps réel
- Mises à jour automatiques

### 4. Animations
- Animations d'entrée au chargement
- Effets de hover sur les cartes
- Animations des statistiques au scroll

## Responsive Design

### Breakpoints
- **Desktop** : > 1200px (3 colonnes)
- **Tablet** : 768px - 1200px (2 colonnes)
- **Mobile** : < 768px (1 colonne)

### Adaptations mobiles
- Menu de navigation adaptatif
- Cartes empilées verticalement
- Boutons pleine largeur
- Texte centré pour les candidats

## Intégration avec Laravel

### Routes utilisées
```php
route('dashboard')           // Dashboard principal
route('offres-emplois.create') // Créer une offre
route('offres-emplois.index')  // Liste des offres
route('profile.edit')        // Édition du profil
route('logout')             // Déconnexion
```

### Modèles utilisés
```php
\App\Models\OffreEmplois::where('employeur_id', Auth::id())
\App\Models\Postulation::whereHas('offreEmplois', ...)
```

## Installation et configuration

### 1. Compilation des assets
```bash
npm install
npm run dev
```

### 2. Configuration Vite
Le fichier `vite.config.js` inclut automatiquement les assets des employeurs.

### 3. Layout principal
Le layout `app.blade.php` charge automatiquement les styles et scripts selon le rôle de l'utilisateur.

## Personnalisation

### Modifier les couleurs
Éditez les variables CSS dans `resources/css/employeurs/styles.css` :
```css
:root {
    --primary: #votre-couleur;
    --secondary: #votre-couleur;
    --accent: #votre-couleur;
}
```

### Ajouter des fonctionnalités
1. Modifiez le template HTML dans `dashboard.blade.php`
2. Ajoutez les styles dans `styles.css`
3. Implémentez les interactions dans `script.js`

### Modifier les animations
Les animations sont définies dans le CSS avec `@keyframes` et peuvent être personnalisées.

## Performance

### Optimisations
- **Lazy loading** : Images et contenu chargés à la demande
- **Debouncing** : Recherche optimisée
- **Throttling** : Animations limitées
- **Intersection Observer** : Animations déclenchées au scroll

### Bonnes pratiques
- Utilisation de `transform` pour les animations
- Éviter les reflows avec `will-change`
- Optimisation des images
- Minification des assets en production

## Support navigateur

### Navigateurs supportés
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Fonctionnalités fallback
- Animations CSS désactivées si `prefers-reduced-motion`
- JavaScript non-bloquant
- Design fonctionnel sans JavaScript

## Maintenance

### Mise à jour des dépendances
```bash
npm update
composer update
```

### Debugging
- Console JavaScript pour les interactions
- Inspecteur CSS pour les styles
- Logs Laravel pour les données

## Prochaines étapes

1. **Intégration des modals** : Implémentation complète des modals de recherche et candidatures
2. **API temps réel** : WebSockets pour les notifications
3. **Filtres avancés** : Système de filtrage des candidats
4. **Analytics** : Suivi des performances du dashboard
5. **Thèmes** : Système de thèmes personnalisables

## Contact

Pour toute question ou suggestion d'amélioration, contactez l'équipe de développement GoTeach. 