<?php
namespace App\Domain\Interface;

interface Preco
{
    public function calcular(\DateTimeImmutable $entrada, \DateTimeImmutable $saida): float;
}