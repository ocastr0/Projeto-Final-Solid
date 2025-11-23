<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infra\Database\Connection;
use App\Infra\Repository\SQLiteVeiculoRepository;
use App\Infra\Repository\SQLiteEstacionamentoRepository;
use App\Application\ServicoPatio;

$dbPath = __DIR__ . '/../database.sqlite';
$pdo = Connection::getInstance($dbPath);

$veiculoRepo = new SQLiteVeiculoRepository($pdo);
$estacionamentoRepo = new SQLiteEstacionamentoRepository($pdo);
$service = new ServicoPatio($veiculoRepo, $estacionamentoRepo);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['entrada'])) {
            $service->entrada($_POST['placa'], $_POST['tipo']);
            echo "<p style='color: green;'>✓ Entrada registrada com sucesso!</p>";
        } elseif (isset($_POST['saida'])) {
            $valor = $service->saida($_POST['placa']);
            echo "<p style='color: blue;'>✓ Saída registrada! Valor: R$ " . number_format($valor, 2, ',', '.') . "</p>";
        }
    }

    if (isset($_GET['relatorio'])) {
        $relatorio = $service->relatorio();
        echo "<h3>Relatório de Faturamento</h3>";
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Tipo</th><th>Total Veículos</th><th>Faturamento</th></tr>";
        foreach ($relatorio as $tipo => $dados) {
            echo "<tr>";
            echo "<td>" . ucfirst($tipo) . "</td>";
            echo "<td>{$dados['total']}</td>";
            echo "<td>R$ " . number_format($dados['faturamento'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Erro: {$e->getMessage()}</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estacionamento</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        form { margin-bottom: 30px; padding: 20px; border: 1px solid #ccc; }
        input, select, button { padding: 8px; margin: 5px 0; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Controle de Estacionamento</h1>

    <form method="post">
        <h2>Registrar Entrada</h2>
        <input type="text" name="placa" placeholder="Placa (ex: ABC-1234)" required>
        <select name="tipo" required>
            <option value="">Selecione o tipo</option>
            <option value="carro">Carro</option>
            <option value="moto">Moto</option>
            <option value="caminhao">Caminhão</option>
        </select>
        <button type="submit" name="entrada">Registrar Entrada</button>
    </form>

    <form method="post">
        <h2>Registrar Saída</h2>
        <input type="text" name="placa" placeholder="Placa" required>
        <button type="submit" name="saida">Registrar Saída</button>
    </form>

    <a href="?relatorio=1"><button>Ver Relatório</button></a>
</body>
</html>

