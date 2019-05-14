<?php
declare(strict_types=1);

require_once("class-typprodcarac.php");

// Classe type produit
class Typprod extends TypprodCarac
{
    private $typprod_desc;

    public function __construct()
    {
        $this->typprod_desc = "";
        parent::__construct();
    }

    public function getTypprodDesc():string
    {
        return($this->typprod_desc);
    }

    public function setTypprodDesc(string $sTypprodDesc):void
    {
        if ( is_string($sTypprodDesc) ) {
            $this->typprod_desc = $sTypprodDesc;
        }
    }

    public function getCarac():array
    {
        return($this->carac);
    }

    public function setCarac( array $carac ):void
    {
        if (is_array($carac)) {
            $this->carac = $carac;
        }
    }

    public function toArray():array
    {
        return( [
            'typprod_desc' => $this->typprod_desc,
            'carac' => $this->carac
            ] );
    }

    public function toJSON():string
    {
        return( json_encode(self::toArray(), JSON_FORCE_OBJECT) );
    }

}
