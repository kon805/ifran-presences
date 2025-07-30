# Architecture de l'Application IFRAN Présences

## Vue d'ensemble de l'architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        UTILISATEURS                             │
├─────────────────┬─────────────────┬─────────────────────────────┤
│   Coordinateur  │    Professeur   │    Parent/Étudiant          │
└─────────────────┴─────────────────┴─────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                    INTERFACE WEB                                │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │
│  │   Blade     │  │  Alpine.js  │  │     Tailwind CSS        │  │
│  │ Templates   │  │ (Frontend)  │  │      (Styles)           │  │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                   FRAMEWORK LARAVEL                             │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                    ROUTES                               │    │
│  │  • web.php (Interface utilisateur)                     │    │
│  │  • api.php (Notifications, données JSON)              │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           │                                     │
│                           ▼                                     │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                 CONTROLLERS                             │    │
│  │  • PresenceController (Gestion des présences)          │    │
│  │  • NotificationController (Notifications)              │    │
│  │  • UserController (Authentification)                   │    │
│  │  • ClasseController (Gestion des classes)              │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           │                                     │
│                           ▼                                     │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                   MODELS                                │    │
│  │  • User (Utilisateurs)                                 │    │
│  │  • Presence (Présences/Absences)                       │    │
│  │  • Cours (Cours)                                       │    │
│  │  • Classe (Classes)                                    │    │
│  │  • Matiere (Matières)                                  │    │
│  │  • Parent (Parents)                                    │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           │                                     │
│                           ▼                                     │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                 SERVICES                                │    │
│  │  • PresenceService (Logique métier)                    │    │
│  │  • NotificationService (Envoi notifications)           │    │
│  │  • LoggingService (Logs)                               │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           │                                     │
│                           ▼                                     │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                 OBSERVERS                               │    │
│  │  • PresenceObserver (Notifications automatiques)       │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                   BASE DE DONNÉES MySQL                         │
│                                                                 │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────────┐  │
│  │   Tables    │  │   Tables    │  │       Tables            │  │
│  │ Principales │  │ Relations   │  │     Système             │  │
│  ├─────────────┤  ├─────────────┤  ├─────────────────────────┤  │
│  │ • users     │  │ • presences │  │ • notifications         │  │
│  │ • classes   │  │ • cours     │  │ • sessions              │  │
│  │ • matieres  │  │ • parents   │  │ • migrations            │  │
│  │ • etudiants │  │             │  │ • cache                 │  │
│  └─────────────┘  └─────────────┘  └─────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────────┐
│                   SERVICES EXTERNES                             │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                EMAIL (SMTP Gmail)                       │    │
│  │  • Notifications par email                             │    │
│  │  • Rapports d'absence                                  │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │              SYSTÈME DE FICHIERS                        │    │
│  │  • Logs d'application                                  │    │
│  │  • Cache                                               │    │
│  │  • Sessions                                            │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## Flux de données principaux

### 1. Flux d'authentification
```
Utilisateur → Interface Web → Controller → Modèle User → Base de données
```

### 2. Flux de gestion des présences
```
Professeur → Interface → PresenceController → PresenceService → Modèle Presence
    ↓
PresenceObserver → NotificationService → Base de données (notifications)
    ↓
Email/Notifications → Parents/Coordinateurs/Étudiants
```

### 3. Flux de consultation
```
Utilisateur → Interface → Controller → Modèle → Base de données → Vue
```

## Technologies utilisées

| Couche | Technologies |
|--------|-------------|
| **Frontend** | Blade Templates, Alpine.js, Tailwind CSS |
| **Backend** | Laravel 11, PHP 8+ |
| **Base de données** | MySQL |
| **Authentification** | Laravel Jetstream, Fortify |
| **Notifications** | Laravel Notifications, SMTP Gmail |
| **Serveur Web** | Apache (WAMP) |

## Rôles et permissions

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Coordinateur   │    │   Professeur    │    │ Parent/Étudiant │
│                 │    │                 │    │                 │
│ • Voir tout     │    │ • Marquer       │    │ • Voir ses      │
│ • Gérer classes │    │   présences     │    │   présences     │
│ • Rapports      │    │ • Voir ses      │    │ • Recevoir      │
│ • Notifications │    │   cours         │    │   notifications │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

Cette architecture suit le pattern MVC (Model-View-Controller) de Laravel avec une séparation claire des responsabilités et une approche modulaire pour faciliter la maintenance et les évolutions futures.
