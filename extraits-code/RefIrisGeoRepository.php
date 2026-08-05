<?php

namespace App\Repository;

use App\Entity\RefIrisGeo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception;

/**
 * @extends ServiceEntityRepository<RefIrisGeo>
 */
class RefIrisGeoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefIrisGeo::class);
    }

    /**
     * Identifie l'entité IRIS contenant un point géographique précis (Point-in-Polygon).
     * Utilise la fonction PostGIS ST_Intersects pour vérifier l'appartenance spatiale.
     *
     * @param float $longitude La longitude du point (Coordonnée X).
     * @param float $latitude La latitude du point (Coordonnée Y).
     * @return RefIrisGeo|null L'entité complète correspondant au secteur, ou null si aucune intersection.
     */
    public function findCodeIrisByCoordinates(float $longitude, float $latitude): ?RefIrisGeo
    {
        // Utilisation du SQL natif car les fonctions spatiales PostGIS (ST_Intersects, ST_SetSRID)
        // ne sont pas supportées nativement par le DQL de Doctrine.
        $sql = '
            SELECT r.code_iris
            FROM ref_iris_geo r
            WHERE ST_Intersects(
                r.geom,
                ST_SetSRID(ST_MakePoint(:lon, :lat), 4326)
            )
            LIMIT 1
        ';

        try {
            $connection = $this->getEntityManager()->getConnection();

            // Exécution de la requête brute pour récupérer l'identifiant technique
            $result = $connection->executeQuery($sql, [
                'lon' => $longitude,
                'lat' => $latitude
            ])->fetchOne();

            if ($result) {
                // Si un ID est trouvé, on utilise l'EntityManager pour retourner
                // une instance d'objet RefIrisGeo gérée par Doctrine.
                return $this->find($result);
            }
        } catch (\Exception $e) {
            // En cas d'erreur (ex: problème de connexion), on retourne null
            // pour permettre un fallback sur une recherche par ville.
            return null;
        }

        return null;
    }
}
