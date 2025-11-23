<?php
namespace App\Application;

use App\Domain\Entity\Carro;
use App\Domain\Entity\Moto;
use App\Domain\Entity\Caminhao;
use App\Domain\Interface\VeiculoRepository;
use App\Domain\Interface\EstacionamentoRepository;
use App\Domain\Service\PrecoCarro;
use App\Domain\Service\PrecoMoto;
use App\Domain\Service\PrecoCaminhao;

class ServicoPatio
{
    private VeiculoRepository $veiculoRepo;
    private EstacionamentoRepository $estacionamentoRepo;

    public function __construct(
        VeiculoRepository $veiculoRepo,
        EstacionamentoRepository $estacionamentoRepo
    ) {
        $this->veiculoRepo = $veiculoRepo;
        $this->estacionamentoRepo = $estacionamentoRepo;
    }

    public function entrada(string $placa, string $tipo): void
    {
        $veiculo = $this->veiculoRepo->findByPlaca($placa);

        if (!$veiculo) {
            $veiculo = $this->criarVeiculo($placa, $tipo);
            $this->veiculoRepo->save($veiculo);
        }

        $ativo = $this->estacionamentoRepo->buscarAtivo($placa);
        if ($ativo) {
            throw new \Exception("Veículo já está estacionado!");
        }

        $agora = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $this->estacionamentoRepo->registrarEntrada($placa, $agora);
    }

    public function saida(string $placa): float
    {
        $ativo = $this->estacionamentoRepo->buscarAtivo($placa);
        if (!$ativo) {
            throw new \Exception("Nenhum estacionamento ativo para essa placa!");
        }

        $veiculo = $this->veiculoRepo->findByPlaca($placa);
        if (!$veiculo) {
            throw new \Exception("Veículo não encontrado!");
        }

        $entrada = new \DateTimeImmutable($ativo['entrada']);
        $saida = new \DateTimeImmutable();

        $strategy = $this->getPricingStrategy($veiculo->getTipo());
        $valor = $strategy->calcular($entrada, $saida);

        $this->estacionamentoRepo->registrarSaida(
            $placa,
            $saida->format('Y-m-d H:i:s'),
            $valor
        );

        return $valor;
    }

    public function relatorio(): array
    {
        $dados = $this->estacionamentoRepo->listarFinalizados();
        return $this->agruparPorTipo($dados);
    }

    private function criarVeiculo(string $placa, string $tipo)
    {
        return match ($tipo) {
            'carro' => new Carro($placa),
            'moto' => new Moto($placa),
            'caminhao' => new Caminhao($placa),
            default => throw new \Exception("Tipo inválido: {$tipo}")
        };
    }

    private function getPricingStrategy(string $tipo)
    {
        return match ($tipo) {
            'carro' => new PrecoCarro(),
            'moto' => new PrecoMoto(),
            'caminhao' => new PrecoCaminhao(),
            default => throw new \Exception("Tipo desconhecido: {$tipo}")
        };
    }

    private function agruparPorTipo(array $dados): array
    {
        $resultado = [];
        foreach ($dados as $item) {
            $tipo = $item['tipo'];
            if (!isset($resultado[$tipo])) {
                $resultado[$tipo] = ['total' => 0, 'faturamento' => 0.0];
            }
            $resultado[$tipo]['total']++;
            $resultado[$tipo]['faturamento'] += $item['valor'];
        }
        return $resultado;
    }
}
