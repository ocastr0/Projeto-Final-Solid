<?php
namespace App\Domain\Entity;

class Moto extends Veiculo
{
    public function __construct(string $placa)
    {
        parent::__construct($placa);
        $this->tipo = 'moto';
    }
}