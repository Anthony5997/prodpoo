<?php
declare(strict_types=1);

require_once("model-typprod.php");
require_once("class-typprodcarac.php");
require_once("commun.php");

// Ouvre la connexion DB en mode persistant
try {
    $dbh = new PDO('mysql:host=localhost;dbname=prodpoo;charset=utf8', 'root', '', array(PDO::ATTR_PERSISTENT => true) );
} catch (PDOException $e) {
    echo "Erreur PDO: " . $e->getMessage() . "\n";
    die();
}

// Ouverture modele Type produit et liste des types produits
$typprodModel = new TypprodModel($dbh);
printArray( "Liste les types produits existants dans l'ordre alphabétique", $typprodModel->index() );

// Lire un type produit
printArray( "Affiche un type produit par son id", $typprodModel->read(3) );

printSeparator();
if ($typprodModel->delete(18)) {
    echo "Produit ID=18 effacé\n";
} else {
    echo "Effacement échoué\n";
}

// Création d'un typrod
$typprod = new Typprod();
$typprod->setTypprodDesc("Exemple type produit");
$aCarac = [
    [ 'typprod_caracnum' => 1, 'typprod_caracdesc' => "Caractéristique 1" ],
    [ 'typprod_caracnum' => 2, 'typprod_caracdesc' => "Caractéristique 2" ],
    [ 'typprod_caracnum' => 3, 'typprod_caracdesc' => "Caractéristique 3" ]
];
$typprod->setCarac($aCarac);
// Enregistrement du type produit dans la database
$nId = $typprodModel->create($typprod);
printSeparator();
if (is_null($nId)) {
    echo "Création échouée\n";
} else {
    echo "Création réussie ID=$nId\n";
}

// Update
$typprod2 = new Typprod();
$typprod2->setTypprodDesc("type produit Modifié");
$aCarac = [
    [ 'typprod_caracnum' => 1, 'typprod_caracdesc' => "Caractéristique 1" ],
    [ 'typprod_caracnum' => 3, 'typprod_caracdesc' => "Caractéristique 3 modifiée" ],
    [ 'typprod_caracnum' => 4, 'typprod_caracdesc' => "Caractéristique 4" ]
];
$typprod2->setCarac($aCarac);
$lUpdated = $typprodModel->update( $nId, $typprod2 );
printSeparator();
if (!$lUpdated) {
    echo "MAJ échouée\n";
} else {
    echo "MAJ réussie ID=$nId\n";
}

// Ferme Connexion DB
$dbh = null;
echo "Fin du programme\n";
