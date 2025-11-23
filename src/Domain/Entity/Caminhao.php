<?php
namespace App\Domain\Entity;

class Caminhao extends Veiculo
{
    public function __construct(string $placa)
    {
        parent::__construct($placa);
        $this->tipo = 'caminhao';
    }
}