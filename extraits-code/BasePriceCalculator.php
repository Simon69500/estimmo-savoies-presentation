<?php

namespace App\Service\Calculation;

use App\Repository\Analyse24mRepository;
use App\Repository\Analyse5ansRepository;
use App\Entity\Analyse24m;

/**
 * Gère la logique métier pour déterminer le prix moyen M² le plus fiable (solide)
 * en utilisant une approche hiérarchique (24 mois vs 5 ans) et des seuils.
 */
class BasePriceCalculator
{
    private Analyse24mRepository $analyse24mRepository;
    private Analyse5ansRepository $analyse5ansRepository;

    public function __construct(
        Analyse24mRepository $analyse24mRepository,
        Analyse5ansRepository $analyse5ansRepository
    )
    {
        $this->analyse24mRepository = $analyse24mRepository;
        $this->analyse5ansRepository = $analyse5ansRepository;
    }

    /**
     * Calcule le prix moyen au m² SOLIDE (Base de calcul) en tenant compte de la variation locale.
     *
     * @param array $codeGeographiques Le code IRIS/quartier (codeGeographique)
     * @param string $typeLocal Le type de bien (ex: 'Appartement', 'Maison')
     * @param int $minVentesSeuil Le nombre minimum de ventes pour valider une moyenne (fixé à 10)
     * @return float|null Le prix moyen au m² solide ou null si aucune donnée trouvée.
     */
    public function calculate(array $codeGeographiques, string $typeLocal, int $minVentesSeuil = 10): ?float
    {
        // 1. Tente de récupérer les analyses en utilisant la recherche hiérarchique (findBestPriceData)

        /** @var Analyse24m|null $analyse24m */
        $analyse24m = $this->analyse24mRepository->findBestPriceData(
            $codeGeographiques,
            $typeLocal
        );

        /** @var Analyse24m|null $analyse5ans */
        $analyse5ans = $this->analyse5ansRepository->findBestPriceData(
            $codeGeographiques,
            $typeLocal
        );

        // --- Logique de Fiabilité et d'Ajustement ---

        // Cas A : L'échantillon 24 mois est fiable
        $nombreDeVentes24m = $analyse24m ? ($analyse24m->getNombreDeVentes() ?? 0) : 0;

        if ($nombreDeVentes24m >= $minVentesSeuil) {
            // C'est le prix le plus actuel et statistiquement validé.
            $prix24m = (float) $analyse24m->getPrixMoyenM2() ?? 0.0;
            if ($prix24m > 0) {
                return $prix24m;
            }
        }

        // Cas B : Repli avec ajustement local (24m non fiable, mais les deux analyses existent)
        if ($analyse24m && $analyse5ans) {
            $prix5ans = (float) $analyse5ans->getPrixMoyenM2() ?? 0.0;
            $prix24m = (float) $analyse24m->getPrixMoyenM2() ?? 0.0;

            // Sécurité contre la division par zéro (si prix5ans est 0 ou si le prix a beaucoup baissé)
            if ($prix5ans <=0 || $prix24m <= 0) {
                return $prix24m > 0 ? $prix24m : null; // Retourne le 24m, même s'il n'est pas fiable
            }

            // Calcul du coefficient de revalorisation LOCAL : Variation entre 24m et 5 ans dans CE quartier/Commune
            $revalorisationCoefficient = $prix24m / $prix5ans;

            // On prend le prix 5 ans (stable) et on lui applique la variation observée sur CE quartier/commune
            return $prix5ans * $revalorisationCoefficient;
        }

        // Cas C : Dernier repli simple (seulement 5 ans disponible ou les 24m n'existent pas du tout)
        if ($analyse5ans) {
            // Si 24m n'existe pas, on retourne simplement le prix 5 ans (dernier filet de sécurité)
            $prix5ans = (float) $analyse5ans->getPrixMoyenM2() ?? 0.0;
            if ($prix5ans > 0) {
                return $prix5ans;
            }
        }

        // Cas D : Aucune donnée trouvée
        return null;
    }
}
