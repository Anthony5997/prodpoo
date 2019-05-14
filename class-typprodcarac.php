<?php
declare(strict_types=1);

// Classe liste caractéristiques
class TypprodCarac
{
    protected $carac;

    public function __construct()
    {
        $this->carac = array();
    }

    public function addCarac(string $sCaracDesc):void
    {
        self::addCaracnum($this->getNextCaracnum(), $sCaracDesc);
    }

    public function addCaracnum(int $nNumCarac, string $sCaracDesc):void
    {
        if (is_int($nNumCarac) && is_string($sCaracDesc) && ! self::existCaracnum($nNumCarac) ) {
            array_push($this->carac, [
                    'typprod_caracnum' => $nNumCarac,
                    'typprod_caracdesc' => $sCaracDesc
                ] );
        }
    }

    public function getCaracnum(int $nNumCarac):string
    {
        $sReturn = "";

        if (is_int($nNumCarac)) {
            foreach( $this->carac as $aRow ) {
                if ($aRow['typprod_caracnum']==$nNumCarac) {
                    $sReturn = $aRow['typprod_caracdesc'];
                }
            }
        }

        return($sReturn);
    }

    public function setCaracnum(int $nNumCarac, string $sCaracDesc):void
    {
        if (is_int($nNumCarac) && is_string($sCaracDesc) ) {
            foreach( $this->carac as $key => $aRow ) {
                if ($aRow['typprod_caracnum']==$nNumCarac) {
                    $this->carac[$key]['typprod_caracdesc']=$sCaracDesc;
                }
            }
        }
    }

    public function removeCaracnum(int $nNumCarac):void
    {
        $lFound = false;

        foreach( $this->carac as $key => $aRow ) {
            if ( $lFound === false && $aRow['typprod_caracnum']==$nNumCarac ) {
                unset($this->carac[$key]);
                $lFound=true;
            }
        }
    }

    public function existCaracnum(int $nNumCarac):bool
    {
        $lExist = false;

        if (is_int($nNumCarac)) {
            foreach( $this->carac as $aRow ) {
                if ( $lExist === false && $aRow['typprod_caracnum']==$nNumCarac ) {
                    $lExist=true;
                }
            }
        }

        return($lExist);
    }

    protected function getNextCaracnum():int
    {
        $nNumCarac = 0;

        if ( count($this->carac)>0 ) {
            foreach ($this->carac as $aRow) {
                $nNumCarac = max($nNumCarac, $aRow['typprod_caracnum']);
            }
        }

        return($nNumCarac+1);
    }

    public function toArray():array
    {
        return( $this->carac );
    }

    public function toJSON():string
    {
        return( json_encode($this->carac, JSON_FORCE_OBJECT) );
    }


}
