<?php
namespace App\Domain\Entity;

class Carro extends Veiculo
{
    public function __construct(string $placa)
    {
        parent::__construct($placa);
        $this->tipo = 'carro';
    }
}
