# 🏔️ Estimmo-Savoies

**Outil d'estimation immobilière sur-mesure pour le marché savoyard**

[![Live Demo](https://img.shields.io/badge/démo-en_ligne-2ea44f)](https://estimmo-savoies.fr/)
![React](https://img.shields.io/badge/React-Vite-61DAFB?logo=react&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-API_REST-000000?logo=symfony&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-PostGIS-336791?logo=postgresql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-OVH_Cloud-2496ED?logo=docker&logoColor=white)

> ⚠️ **Repo de présentation** — Le code source réel appartient à un client et est hébergé sur un dépôt privé. Ce repository documente l'architecture, les choix techniques et présente des extraits de code représentatifs, avec l'accord du client.

---

Les outils d'estimation nationaux lissent les prix immobiliers à l'échelle du pays. En Savoie (73), où les écarts entre zones rurales, bassins prisés et stations de prestige sont énormes, cette approche généraliste ne convainc plus personne.

**Estimmo-Savoies** a été conçu pour un agent immobilier indépendant qui avait besoin d'un outil taillé pour son marché local — précis, rapide, et générateur de leads qualifiés.

## 🎯 Contexte & objectif métier

### Le client

Le projet a été réalisé pour un agent immobilier indépendant (statut auto-entrepreneur, affilié au réseau Swixim), opérant sur le marché immobilier savoyard (73).

### Le problème

Le marché savoyard présente de fortes disparités entre zones rurales, secteurs prisés (bassin d'Aix-les-Bains) et stations de prestige. Les outils d'estimation nationaux généralistes lissent ces écarts et produisent des marges d'erreur trop importantes pour être crédibles auprès de vendeurs locaux.

### L'objectif

Concevoir un outil d'estimation sur-mesure, basé sur des règles métier locales, capable de :
- Générer une estimation précise à partir de données réelles du secteur
- Convertir les visiteurs en **leads qualifiés** (coordonnées collectées en échange de l'estimation)
- Rester économiquement viable pour un indépendant : utilisation exclusive de sources **Open Data gratuites** (DVF, BAN, IRIS INSEE)

### Contraintes de conformité

Le projet devait intégrer dès la conception :
- **RGPD / CNIL / Bloctel** — consentement explicite, gestion des données de contact
- **Accessibilité RGAA / WCAG 2.1 niveau AA** — formulaire multi-étapes, navigation clavier
- **SEO** — optimisation ciblée de la page d'accueil (le reste de l'app étant un tunnel de conversion SPA non indexable)
