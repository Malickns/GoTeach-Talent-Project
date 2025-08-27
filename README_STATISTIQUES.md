# Système de Statistiques - GoTeach

## Vue d'ensemble

Le système de statistiques de GoTeach permet de récupérer dynamiquement toutes les données statistiques de l'application depuis la base de données, remplaçant ainsi les données hardcodées qui étaient utilisées précédemment.

## Architecture

### 1. Contrôleur de Statistiques (`StatistiqueController`)

Le contrôleur principal qui gère toutes les requêtes de statistiques :

- `statistiquesGenerales()` - Statistiques générales pour les admins
- `statistiquesEmployeur($employeurId)` - Statistiques spécifiques à un employeur
- `statistiquesJeune($jeuneId)` - Statistiques spécifiques à un jeune
- `statistiquesLocales($region)` - Statistiques locales pour les admins locaux
- `statistiquesNationales()` - Statistiques nationales pour les admins nationaux

### 2. Service de Statistiques (`StatistiqueService`)

Service qui encapsule toute la logique de calcul des statistiques :

```php
use App\Services\StatistiqueService;

// Statistiques employeur
$stats = StatistiqueService::getStatistiquesEmployeur();

// Statistiques jeune
$stats = StatistiqueService::getStatistiquesJeune();

// Statistiques locales
$stats = StatistiqueService::getStatistiquesLocales();

// Statistiques nationales
$stats = StatistiqueService::getStatistiquesNationales();
```

### 3. Helper de Statistiques (`StatistiqueHelper`)

Helper pour faciliter l'utilisation des statistiques dans les vues :

```php
use App\Helpers\StatistiqueHelper;

// Statistiques formatées
$stats = StatistiqueHelper::getStatistiquesFormatees('employeur');

// Statistique spécifique
$candidatures = StatistiqueHelper::getStatistique('employeur', 'candidatures_recues');

// Formatage de nombres
$nombre = StatistiqueHelper::formatNombre(1500); // Retourne "1.5K"
```

## Utilisation dans les Vues

### Dashboard Employeur

```php
<?php
use App\Services\StatistiqueService;
$stats = StatistiqueService::getStatistiquesEmployeur();
?>

<!-- Affichage des statistiques -->
<span class="stat-number">{{ $stats['offres_publiees'] }}</span>
<span class="stat-number">{{ $stats['candidatures_recues'] }}</span>
<span class="stat-number">{{ $stats['talents_contactes'] }}</span>
<span class="stat-number">{{ $stats['entretiens'] }}</span>
<span class="stat-number">{{ $stats['taux_reponse'] }}%</span>
<span class="stat-number">{{ $stats['embauches'] }}</span>
```

### Dashboard Admin Local

```php
<?php
use App\Services\StatistiqueService;
$stats = StatistiqueService::getStatistiquesLocales();
?>

<!-- Affichage des statistiques -->
<p><span class="font-bold">{{ number_format($stats['jeunes_inscrits']) }}</span> jeunes inscrits</p>
<p><span class="font-bold">{{ number_format($stats['entreprises_partenaires']) }}</span> entreprises partenaires</p>
<p><span class="font-bold">{{ number_format($stats['evenements_mois']) }}</span> événements ce mois</p>
```

### Dashboard Admin National

```php
<?php
use App\Services\StatistiqueService;
$stats = StatistiqueService::getStatistiquesNationales();
?>

<!-- Affichage des statistiques -->
<p class="text-2xl font-bold">{{ number_format($stats['jeunes_inscrits']) }}</p>
<p class="text-2xl font-bold">{{ number_format($stats['entreprises']) }}</p>
<p class="text-2xl font-bold">{{ number_format($stats['evenements_mois']) }}</p>
<p class="text-2xl font-bold">{{ $stats['taux_activite'] }}%</p>
```

## Routes Disponibles

### Statistiques Générales
```
GET /statistiques/generales
```

### Statistiques Employeur
```
GET /statistiques/employeur
GET /statistiques/employeur/{employeurId}
```

### Statistiques Jeune
```
GET /statistiques/jeune
GET /statistiques/jeune/{jeuneId}
```

### Statistiques Locales
```
GET /statistiques/locales
GET /statistiques/locales/{region}
```

### Statistiques Nationales
```
GET /statistiques/nationales
```

## Types de Statistiques Calculées

### Statistiques Employeur
- **offres_publiees** : Nombre d'offres publiées par l'employeur
- **candidatures_recues** : Nombre de candidatures reçues
- **talents_contactes** : Nombre de talents uniques contactés
- **entretiens** : Nombre d'entretiens effectués
- **vues_profil** : Nombre de vues du profil (estimation)
- **taux_reponse** : Taux de réponse aux candidatures (%)
- **embauches** : Nombre d'embauches effectuées
- **offres_actives** : Nombre d'offres actuellement actives

### Statistiques Jeune
- **total_candidatures** : Nombre total de candidatures
- **entretiens** : Nombre d'entretiens obtenus
- **emplois_obtenus** : Nombre d'emplois obtenus
- **offres_disponibles** : Nombre d'offres disponibles
- **taux_reponse** : Taux de réponse aux candidatures (%)

### Statistiques Locales
- **jeunes_inscrits** : Nombre de jeunes inscrits dans la région
- **entreprises_partenaires** : Nombre d'entreprises partenaires
- **evenements_mois** : Nombre d'événements ce mois
- **offres_actives** : Nombre d'offres actives
- **candidatures_mois** : Nombre de candidatures ce mois
- **embauches_mois** : Nombre d'embauches ce mois

### Statistiques Nationales
- **jeunes_inscrits** : Nombre total de jeunes inscrits
- **entreprises** : Nombre total d'entreprises
- **evenements_mois** : Nombre d'événements ce mois
- **taux_activite** : Taux d'activité global (%)
- **regions_actives** : Top 5 des régions les plus actives
- **secteurs_populaires** : Top 5 des secteurs les plus populaires

## Gestion des Erreurs

Le système gère automatiquement les cas où :
- Aucune donnée n'est disponible (retourne 0)
- Les relations n'existent pas
- Les utilisateurs n'ont pas de profil complet

## Performance

Les statistiques sont calculées à la demande et peuvent être optimisées en :
- Ajoutant des index sur les colonnes fréquemment utilisées
- Mettant en cache les statistiques pour les données qui changent peu
- Utilisant des requêtes optimisées pour les grandes quantités de données

## Extensions Futures

Le système peut être étendu pour inclure :
- Statistiques temporelles (évolution dans le temps)
- Graphiques et visualisations
- Export de rapports
- Alertes automatiques basées sur les statistiques
- Comparaisons entre périodes
