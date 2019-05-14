<?php
declare(strict_types=1);

require_once("class-typprod.php");
require_once("commun.php");

// Cree un objet type produit
$typprod = new Typprod();
$typprod->setTypprodDesc("Exemple type produit");
printArray( "Nouvel objet", $typprod->toArray() );

// Caracteristiques insérées en tant que tableau
$aCarac = [
    [ 'typprod_caracnum' => 1, 'typprod_caracdesc' => "Caractéristique 1" ],
    [ 'typprod_caracnum' => 2, 'typprod_caracdesc' => "Caractéristique 2" ]
];
$typprod->setCarac($aCarac);
printArray( "Affichage type produit", $typprod->toArray() );

$typprod->setCarac(array());
printArray( "Effacement caracteristiques", $typprod->toArray() );

$typprod->addCarac("Caracteristique 1");
$typprod->addCarac("Caracteristique 2");
$typprod->addCaracnum(42, "Caracteristique 42");
printArray( "Ajout de caracteristiques", $typprod->toArray() );

if ($typprod->existCaracnum(42)) {
    $typprod->removeCaracnum(42);
}
printArray( "Affichage caractéristiques", $typprod->toArray() );

printString( "Export JSON", $typprod->toJSON() );
