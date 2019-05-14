<?php
declare(strict_types=1);

require_once("class-typprod.php");
require_once("class-typprodcarac.php");

define("QUERY_TYPPROD_INSERT", "INSERT INTO typprod (typprod_id, typprod_desc) VALUES (NULL, :typprod_desc)");

define("QUERY_TYPPROD_CARAC_INSERT", "INSERT INTO typprod_carac (typprod_id, typprod_caracnum, typprod_caracdesc) VALUES (:typprod_id, :typprod_caracnum, :typprod_caracdesc)");

define("QUERY_TYPPROD_CARAC_READ", "    SELECT typprod_caracnum, typprod_caracdesc
                                        FROM typprod_carac
                                        WHERE typprod_id=:typprod_id
                                        ORDER BY typprod_caracdesc");

define("QUERY_TYPPROD_READ", "  SELECT typprod_id, typprod_desc FROM typprod WHERE typprod_id = :typprod_id");

define("QUERY_TYPPROD_DELETE", "  DELETE FROM typprod WHERE typprod_id = :typprod_id");

define("QUERY_TYPPROD_CARAC_DELETE", "  DELETE FROM typprod_carac WHERE typprod_id = :typprod_id");

define("QUERY_TYPPROD_CARAC_DELETE_CARACNUM", "  DELETE FROM typprod_carac WHERE typprod_id = :typprod_id AND typprod_caracnum = :typprod_caracnum");

define("QUERY_TYPPROD_INDEX", "SELECT typprod_id, typprod_desc FROM typprod ORDER BY typprod_desc");

define("QUERY_TYPPROD_CARAC_UPDATE_CARACNUM", "  UPDATE typprod_carac SET typprod_caracdesc= :typprod_caracdesc WHERE typprod_id = :typprod_id AND typprod_caracnum = :typprod_caracnum");

define("QUERY_TYPPROD_UPDATE", "  UPDATE typprod SET typprod_desc= :typprod_desc WHERE typprod_id = :typprod_id");

// Modele DataBase: CRUD + index
class TypprodModel
{

    private $dbh;

    public function __construct( $dbh )
    {
        $this->dbh = $dbh;
    }

    public function create(Typprod $typprod):?int
    {
        $nCreateId = null;

        if (is_a($typprod, "Typprod")) {
            $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_INSERT);
            $stmt2 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_INSERT);
            $lCommit = true;

            // Debut de Transaction
            $this->dbh->beginTransaction();

            $stmt1->bindValue(':typprod_desc', $typprod->getTypprodDesc(), PDO::PARAM_STR);
            if ( $stmt1->execute() ) {
                $nCreateId = intval($this->dbh->lastInsertId());
                foreach($typprod->getCarac() as $key => $aRow) {
                    if ( $lCommit ) {
                        $stmt2->bindValue(':typprod_id', $nCreateId, PDO::PARAM_INT);
                        $stmt2->bindValue(':typprod_caracnum', $aRow['typprod_caracnum'], PDO::PARAM_INT);
                        $stmt2->bindValue(':typprod_caracdesc', $aRow['typprod_caracdesc'], PDO::PARAM_STR);
                        if ( $stmt2->execute() === false ) {
                            $lCommit = false;
                        }
                    }
                }
            } else {
                $lCommit = false;
            }

            // Fin de transaction
            if ($lCommit) {
                $this->dbh->commit();
            } else {
                $nCreateId = null;
                $this->dbh->rollBack();
            }
        }

        return($nCreateId);
    }

    public function read(int $nId):array
    {
        $aResult = array();

        if ( is_int($nId) ) {
            $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_READ);
            $stmt2 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_READ);

            $stmt1->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
            if ( $stmt1->execute() ) {
                $aTypprod = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                if (count($aTypprod)==1) {
                    $aResult = $aTypprod[0];
                    $stmt2->bindValue(':typprod_id', $aResult['typprod_id'], PDO::PARAM_INT);
                    if ( $stmt2->execute() ) {
                        $aResult['carac'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    }
                }
            }
        }

        return($aResult);
    }

    public function update(int $nId, Typprod $typprod):bool
    {
        // Lecture des caractéristiques existantes dans la base
        $aOldTypprod = self::read($nId);
        $aOldCarac = $aOldTypprod['carac'];

        $aNewCarac = $typprod->getCarac();

        // Debut de Transaction
        $lCommit = true;
        $this->dbh->beginTransaction();

        // Effacement des caractéristiques obsoletes
        $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_DELETE_CARACNUM);

        foreach ($aOldCarac as $keyOld => $aRowOld) {
            $lFound = false;

            foreach ($aNewCarac as $keyNew => $aRowNew) {
                if ( $lFound === false && $aRowOld['typprod_caracnum'] == $aRowNew['typprod_caracnum'] ) {
                    $lFound = true;
                }
            }

            if (!$lFound) {
                // Effacement de la caractéristique obsolete
                // dans la database
                $stmt1->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
                $stmt1->bindValue(':typprod_caracnum', $aRowOld['typprod_caracnum'], PDO::PARAM_INT);
                if ( $stmt1->execute() === false ) {
                    $lCommit = false;
                }
                //  et dans le tableau
                unset($aOldCarac[$keyOld]);
            }
        }

        // Ajout des nouvelles caractéristiques
        $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_INSERT);

        foreach ($aNewCarac as $keyNew => $aRowNew) {
            $lFound = false;

            foreach ($aOldCarac as $keyOld => $aRowOld) {
                if ( $lFound === false && $aRowOld['typprod_caracnum'] == $aRowNew['typprod_caracnum'] ) {
                    $lFound = true;
                }
            }

            if (!$lFound) {
                // insertion de la caractéristique
                // dans la database
                $stmt1->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
                $stmt1->bindValue(':typprod_caracnum', $aRowNew['typprod_caracnum'], PDO::PARAM_INT);
                $stmt1->bindValue(':typprod_caracdesc', $aRowNew['typprod_caracdesc'], PDO::PARAM_STR);
                if ( $stmt1->execute() === false ) {
                    $lCommit = false;
                }

                //  et suppression dans le tableau
                unset($aNewCarac[$keyNew]);
            }
        }

        // Mise à jour des caractéristiques modifiées
        $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_UPDATE_CARACNUM);

        foreach ($aNewCarac as $keyNew => $aRowNew) {
            $lFound = false;
            $lUpdate = false;

            foreach ($aOldCarac as $keyOld => $aRowOld) {
                if ( $lFound === false && $aRowOld['typprod_caracnum'] == $aRowNew['typprod_caracnum'] ) {
                    $lFound = true;
                    if ($aRowOld['typprod_caracdesc'] !== $aRowNew['typprod_caracdesc']) {
                        $lUpdate = true;
                    }
                }
            }

            if ($lFound && $lUpdate) {
                // MAJ de la caractéristique dans la database
                $stmt1->bindValue(':typprod_caracdesc', $aRowNew['typprod_caracdesc'], PDO::PARAM_STR);
                $stmt1->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
                $stmt1->bindValue(':typprod_caracnum', $aRowNew['typprod_caracnum'], PDO::PARAM_INT);
                if ( $stmt1->execute() === false ) {
                    $lCommit = false;
//                    print_r($stmt1->errorInfo());
                }
            }
        }

        // MAJ de la description du type produit si necessaire
        if ($typprod->getTypprodDesc() !== $aOldTypprod['typprod_desc'] ) {
            $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_UPDATE);
            $stmt1->bindValue(':typprod_desc', $typprod->getTypprodDesc(), PDO::PARAM_STR);
            $stmt1->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
            if ( $stmt1->execute() === false ) {
                $lCommit = false;
            }
        }

        // Fin de transaction
        if ($lCommit) {
            $this->dbh->commit();
        } else {
            $this->dbh->rollBack();
        }

        return($lCommit);
    }

    public function delete(int $nId):bool
    {
        $lReturn = false;

        if ( is_int($nId) ) {
            $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_DELETE);
            $stmt2 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_DELETE);

            // Debut de transaction
            $this->dbh->beginTransaction();

            $stmt1->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
            $stmt2->bindValue(':typprod_id', $nId, PDO::PARAM_INT);
            if ( $stmt1->execute() && $stmt2->execute()) {
                $lReturn = true;
                $this->dbh->commit();
            } else {
                $this->dbh->rollBack();
            }
        }

        return($lReturn);
    }

    public function index():array
    {
        $aResult = array();

        $stmt1 = $this->dbh->prepare(QUERY_TYPPROD_INDEX);
        $stmt2 = $this->dbh->prepare(QUERY_TYPPROD_CARAC_READ);

        if ( $stmt1->execute() ) {
          while ($aRow = $stmt1->fetch(PDO::FETCH_ASSOC)) {
              $stmt2->bindValue(':typprod_id', $aRow['typprod_id'], PDO::PARAM_INT);
              if ( $stmt2->execute() ) {
                  $aCarac = $stmt2->fetchAll(PDO::FETCH_ASSOC);
              }

              array_push($aResult, [
                  'typprod_id' => $aRow['typprod_id'],
                  'typprod_desc' => $aRow['typprod_desc'],
                  'carac' => $aCarac
              ]);
          }
        }

        return($aResult);
    }

}
