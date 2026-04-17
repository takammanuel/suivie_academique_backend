<?php

// Script pour ajouter des filières via Docker
// Exécuter avec: docker-compose exec app php add_filieres.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Filiere;

$filieres = [
    ['code_filiere' => 'INFO', 'label_filiere' => 'Informatique', 'description_filiere' => 'Formation en informatique générale, programmation et systèmes d\'information'],
    ['code_filiere' => 'MATH', 'label_filiere' => 'Mathématiques', 'description_filiere' => 'Formation en mathématiques pures et appliquées'],
    ['code_filiere' => 'PHYS', 'label_filiere' => 'Physique', 'description_filiere' => 'Formation en physique fondamentale et appliquée'],
    ['code_filiere' => 'CHIM', 'label_filiere' => 'Chimie', 'description_filiere' => 'Formation en chimie générale, organique et analytique'],
    ['code_filiere' => 'BIO', 'label_filiere' => 'Biologie', 'description_filiere' => 'Formation en sciences biologiques et sciences de la vie'],
    ['code_filiere' => 'GECO', 'label_filiere' => 'Génie Civil', 'description_filiere' => 'Formation en construction, bâtiment et travaux publics'],
    ['code_filiere' => 'GELEC', 'label_filiere' => 'Génie Électrique', 'description_filiere' => 'Formation en électricité, électronique et automatisme'],
    ['code_filiere' => 'GMEC', 'label_filiere' => 'Génie Mécanique', 'description_filiere' => 'Formation en mécanique, conception et fabrication'],
    ['code_filiere' => 'GIND', 'label_filiere' => 'Génie Industriel', 'description_filiere' => 'Formation en gestion de production et optimisation industrielle'],
    ['code_filiere' => 'ECON', 'label_filiere' => 'Économie', 'description_filiere' => 'Formation en sciences économiques et gestion'],
    ['code_filiere' => 'GEST', 'label_filiere' => 'Gestion', 'description_filiere' => 'Formation en management et administration des entreprises'],
    ['code_filiere' => 'DROIT', 'label_filiere' => 'Droit', 'description_filiere' => 'Formation en sciences juridiques et droit des affaires'],
    ['code_filiere' => 'LETT', 'label_filiere' => 'Lettres Modernes', 'description_filiere' => 'Formation en littérature, linguistique et sciences du langage'],
    ['code_filiere' => 'ANGL', 'label_filiere' => 'Anglais', 'description_filiere' => 'Formation en langue et littérature anglaise'],
    ['code_filiere' => 'HIST', 'label_filiere' => 'Histoire', 'description_filiere' => 'Formation en histoire et sciences historiques'],
    ['code_filiere' => 'GEO', 'label_filiere' => 'Géographie', 'description_filiere' => 'Formation en géographie physique et humaine'],
    ['code_filiere' => 'SOCIO', 'label_filiere' => 'Sociologie', 'description_filiere' => 'Formation en sciences sociales et analyse sociologique'],
    ['code_filiere' => 'PSYCHO', 'label_filiere' => 'Psychologie', 'description_filiere' => 'Formation en psychologie clinique et sciences cognitives'],
    ['code_filiere' => 'MEDC', 'label_filiere' => 'Médecine', 'description_filiere' => 'Formation en sciences médicales et santé'],
    ['code_filiere' => 'PHAR', 'label_filiere' => 'Pharmacie', 'description_filiere' => 'Formation en sciences pharmaceutiques et pharmacologie'],
];

$count = 0;
foreach ($filieres as $filiere) {
    try {
        Filiere::create($filiere);
        echo "✓ Filière ajoutée: {$filiere['label_filiere']}\n";
        $count++;
    } catch (\Exception $e) {
        echo "✗ Erreur pour {$filiere['label_filiere']}: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Total: $count filières ajoutées avec succès!\n";
