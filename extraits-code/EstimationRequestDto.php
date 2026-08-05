<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object (DTO) pour la requête d'estimation.
 * * Cette classe est utilisée par le contrôleur pour recevoir et valider les données
 * du formulaire d'estimation envoyées par le front-end, avant de les convertir
 * en Entité Estimation.
 */
class EstimationRequestDto
{
    // --- Localisation ---

    #[Assert\NotBlank(message: "L'adresse est requise.")]
    #[Assert\Type('string')]
    public $localisationAdresse;

    #[Assert\NotBlank(message: "Le code postal est requis.")]
    #[Assert\Regex(
        pattern: '/^73\d{3}$/',
        message:'Le code postal {{ value }} n\'est pas valide ou n\'est pas dans la zone de couverture (Département 73).'
    )]
    public ?string $localisationCodePostal;

    #[Assert\NotBlank(message: "La ville est requise.")]
    #[Assert\Type('string')]
    public $localisationVille;

    #[Assert\Type(['string', 'null'])]
    public $localisationQuartier;

    // --- Caractéristiques du Bien ---

    #[Assert\NotBlank(message: "Le type de bien est requis.")]
    #[Assert\Type('string')]
    public $bienType;

    #[Assert\NotBlank(message: "La surface habitable est requise.")]
    #[Assert\Positive(message: "La surface doit être supérieure à zéro.")]
    #[Assert\Type(['numeric'])]
    public $bienSurfaceHabitable;

    #[Assert\Type(['numeric', 'null'])]
    #[Assert\Positive(message: "La surface totale doit être supérieure à zéro.")]
    public $bienSurfaceTotal;

    #[Assert\NotBlank(message: "La surface du terrain est requise.")]
    #[Assert\Type(['numeric', 'null'])]
    public $bienSurfaceTerrain;

    #[Assert\Type(['int' , 'null'])]
    public $bienEtage;

    #[Assert\Type(['int' , 'null'])]
    public $bienNbEtages;

    #[Assert\Type(['bool' , 'null'])]
    public $bienAscenseur;

    // --- Caractéristiques Internes ---

    #[Assert\Type(['int' , 'null'])]
    public $caracNbPieces;

    #[Assert\Type(['integer', 'null'])]
    public $caracNbChambres;

    #[Assert\Type(['int' , 'null'])]
    public $caracNbSdb;

    /**
     * Liste des extérieurs (Balcon, Terrasse, etc.) et leur surface associée.
     * Ex: [ {"type": "Terrasse", "surface", "Piscine": 10}, {"type": "Jardin", "surface", "Piscine": 50} ]
     */
    #[Assert\Type(['array', 'null'])]
    public $caracExtremites = null;

    #[Assert\Type(['array', 'null'])]
    public $routeProxi = null;

    #[Assert\Type(['bool', 'null'])]
    public $caracParking;

    #[Assert\Type(['bool', 'null'])]
    public $caracCave;

    #[Assert\Choice(
    choices: ['degagee', 'vis a vis', 'vue sur le lac', 'normal'],
    message: 'Le choix de vue est invalide.'
    )]
    #[Assert\Type('string')]
    public $caracVue;

    // --- Technique ---
    #[Assert\NotBlank(message: "L'année de construction est requise.")]
    #[Assert\Type(['array', 'null'])]
    public $techAnneeConstruction;

    #[Assert\NotBlank(message: "L'etat général est requis.")]
    #[Assert\Type(['string'])]
    public $techEtatGeneral; // ex: 'Excellent', 'Bon', 'Moyen', 'À rénover'

    #[Assert\Type(['array', 'null'])]
    public $techTypeChauffage;

    #[Assert\Type(['array' , 'null'])]
    public $techMecanismeChauffage;

    #[Assert\Type(['array', 'null'])]
    public $techEquipements = [];

    #[Assert\Type('bool', 'null')]
    public ?bool $techPanneauSolaires = false;

    // --- Compte Utilisateur ---

    #[Assert\Type('string', 'null')]
    public $titreEstimation;

    // --- Lead / Contact ---

    // Note : dateSoumission n'est pas inclus car il sera créé par le Controller

    #[Assert\NotBlank(message: "Le nom est requis.")]
    #[Assert\Type('string')]
    public $leadNom;

    #[Assert\NotBlank(message: "Le prenom est requis.")]
    #[Assert\Type('string')]
    public $leadPrenom;

    #[Assert\NotBlank(message: "L'email est requis.")]
    #[Assert\Email(message: "L'adresse email '{{ value }}' n'est pas valide.")]
    public $leadEmail;

    #[Assert\Type(['string', 'null'])]
    public $leadTelephone;

    #[Assert\NotBlank(message: "Le projet de vente est requis.")]
    #[Assert\Type('string')]
    public $leadProjetVente;

    #[Assert\NotNull(message: "L'accord CGU est obligatoire.")]
    public ?bool $leadAccordCGU;

    public ?bool $leadAccordTel;

}
