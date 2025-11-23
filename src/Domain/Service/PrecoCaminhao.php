<?php
namespace App\Domain\Service;

use App\Domain\Interface\Preco;

class PrecoCaminhao implements Preco
{
    private const VALOR_HORA = 10.0;

    public function calcular(\DateTimeImmutable $entrada, \DateTimeImmutable $saida): float
    {
        $diff = $entrada->diff($saida);
        $horas = $this->calcularHoras($diff);
        return $horas * self::VALOR_HORA;
    }

    private function calcularHoras(\DateInterval $interval): int
    {
        $totalMinutos = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        return (int) ceil($totalMinutos / 60);
    }
}


