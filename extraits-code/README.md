# Extraits de code — Estimmo-Savoies

Ces trois fichiers sont des extraits **réels** du projet (issus du dépôt privé), sélectionnés pour illustrer des choix techniques spécifiques. Ils ne constituent pas un projet exécutable de manière isolée — les dépendances, entités et repositories associés ne sont pas inclus ici.

---

## 1. [`EstimationRequestDto.php`](./EstimationRequestDto.php)

**Ce qu'il démontre :** la validation stricte de toute donnée entrante côté API, avant qu'elle n'atteigne la moindre entité métier.

Chaque champ du formulaire (React) possède sa propre contrainte de validation Symfony — types, formats, plages de valeurs, et règles métier (ex : le code postal est validé par une regex qui restreint la zone de couverture au département 73). Cette approche traduit le principe "ne jamais faire confiance au client" : le backend revalide systématiquement, indépendamment de ce que le frontend a déjà filtré.

## 2. [`BasePriceCalculator.php`](./BasePriceCalculator.php)

**Ce qu'il démontre :** la traduction d'une contrainte métier réelle (fiabilité statistique d'un échantillon de ventes) en logique de code lisible.

Sur un marché local comme la Savoie, le volume de transactions varie fortement d'un secteur à l'autre. Ce service implémente une hiérarchie de repli à 3 niveaux entre données récentes (24 mois) et données stables (5 ans), afin de toujours produire une estimation tout en reflétant honnêtement la fiabilité des données disponibles.

## 3. [`RefIrisGeoRepository.php`](./RefIrisGeoRepository.php)

**Ce qu'il démontre :** l'intégration de fonctions spatiales PostGIS natives via Doctrine, pour un calcul que le DQL classique ne sait pas exprimer.

La méthode `findCodeIrisByCoordinates` utilise `ST_Intersects` en SQL natif (via la couche DBAL) pour déterminer à quel secteur IRIS (découpage statistique INSEE) appartient une coordonnée géographique — un calcul de type "point dans un polygone", essentiel pour appliquer les bonnes données de marché local à chaque estimation.
