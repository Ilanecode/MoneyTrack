# 🌟 MoneyTrack - Aperçu Général

**MoneyTrack** est une application web moderne (basée sur *Laravel* et *Tailwind CSS*) conçue pour la gestion de caisse et le suivi de patrimoine. Son objectif est de remplacer les vieux fichiers Excel par une interface dynamique, intuitive et sécurisée, tout en séparant strictement les droits entre la direction et le terrain.

---

## 🔐 Sécurité & Rôles (Workflow d'Approbation)

L'application repose sur un système de rôles très strict pour éviter les fraudes et erreurs :

*   **L'Administrateur** : A les pleins pouvoirs. Il peut tout voir, créer des utilisateurs, gérer les paramètres globaux, et a le dernier mot sur l'argent qui sort.
*   **Le Caissier** : Peut enregistrer les rentrées d'argent (qui sont automatiquement validées) et soumettre des demandes de dépenses. 
*   **Sécurité Anti-Déficit** : Le caissier est techniquement bloqué (avec un message d'alerte) s'il essaie de soumettre une dépense alors que le solde de la caisse est à 0 ou en négatif.
*   **Validation des sorties** : Toute dépense soumise par le caissier passe en statut *"En attente"*. L'administrateur reçoit une notification animée (la cloche qui sonne avec un point rouge) et doit approuver ou rejeter la transaction.

---

## 🛠️ Fonctionnalités Principales (Modules)

### 1. Tableau de Bord (Dashboard)
*   Affichage en temps réel du **Solde Actuel** (Entrées - Sorties).
*   Calcul dynamique des revenus et dépenses du jour.
*   Graphiques interactifs permettant de basculer entre les dépenses de la semaine et du mois.
*   Liste rapide des opérations récentes.

### 2. Gestion des Transactions
*   Historique complet avec filtres poussés (par date, type, catégorie, utilisateur).
*   Indicateurs financiers réels en haut de page (Épargne Totale calculée dynamiquement, Dépenses du mois).
*   Exports automatiques de la comptabilité au format **PDF** et **Excel (CSV)**.

### 3. Gestion des Catégories
*   Interface permettant de créer et classer les flux financiers (Ex: Alimentation, Salaire, Factures).
*   Chaque catégorie est associée à des icônes professionnelles et des couleurs spécifiques (Rouge pour les sorties, Vert/Bleu pour les entrées) générées dynamiquement.

### 4. Rapports Financiers & Analytique
*   Cartes KPI (Indicateurs de Performance) mesurant les hausses ou baisses en pourcentage par rapport au mois précédent.
*   Bilan mensuel détaillé pour comparer les performances sur les 12 derniers mois.

### 5. Audit & Paramètres (Le Journal)
*   L'application garde une trace absolue de **tout** : qui a créé quelle transaction, qui a approuvé quoi, et à quelle heure (Piste d'Audit).
*   Un module exclusif à l'administrateur permet de **sauvegarder** l'intégralité de la base de données (Backup ZIP/SQL) pour garantir que les données ne soient jamais perdues.

---

## 🎨 Design & Expérience Utilisateur (UI/UX)

*   **100% Responsive** : Nous avons adapté chaque tableau, chaque formulaire et chaque bouton pour que l'application soit aussi belle et utilisable sur un smartphone que sur un grand écran d'ordinateur.
*   **Identité Visuelle Premium** : Utilisation d'un thème Vert Canard/Teal (couleur de la richesse et de la précision), avec des ombres douces, des coins arrondis modernes et des polices professionnelles (*Plus Jakarta Sans*).
*   **Micro-interactions** : Des petits détails qui rendent l'application vivante (la cloche qui s'anime, les boutons qui réagissent au survol, l'icône personnalisée MoneyTrack dans l'onglet du navigateur).

---
> **En résumé :** Vous avez entre les mains un véritable ERP financier miniature, robuste en back-end (sauvegardes, audits, rôles) et ultra-moderne en front-end.
