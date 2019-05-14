<?php
require_once("class-typprodcarac.php");
require_once("commun.php");

// Cree un objet contenant une liste de caracteristiques
$listeCarac = new TypprodCarac();
printArray( "Nouvel Objet", $listeCarac->toArray() );

// Ajout de caractéristiques
$listeCarac->addCarac("Caracteristique 1");
$listeCarac->addCarac("Caracteristique 2");
$listeCarac->addCarac("Caracteristique 3");
$listeCarac->addCarac("Caracteristique 4");
printArray( "Affiche les caractéristiques", $listeCarac->toArray() );

printString( "Export JSON", $listeCarac->toJSON() );

// Ajout caracteristique par numéro
$listeCarac->addCaracnum(42, "Caracteristique 42");
$listeCarac->addCarac("une autre caracteristique");
printArray( "Affiche les caractéristiques", $listeCarac->toArray() );

// Efface une caractéristique par son numéro de caractéristique
$listeCarac->removeCaracnum(42);
if ($listeCarac->existCaracnum(43)) {
    $listeCarac->removeCaracnum(43);
}
printArray( "Supprime 2 caractéristiques", $listeCarac->toArray() );

// Lit une caractéristique par son numéro de caractéristique
printString("Caracnum(3)=", $listeCarac->getCaracnum(3) );

// Modifie une caractéristique par son numéro de caractéristique
if ($listeCarac->existCaracnum(1)) {
    $listeCarac->setCaracnum(1, "Caractéristique essentielle");
}
printArray( "Affiche les caractéristiques", $listeCarac->toArray() );

if (! $listeCarac->existCaracnum(99)) {
    printString("Caracnum(99) ", "N'existe pas" );
}
