<?php
// Função para calcular o número de chaves em um range
function countKeysInRange($start, $end) {
    return gmp_strval(gmp_add(gmp_sub(gmp_init($end, 16), gmp_init($start, 16)), 1));
}

// URL do repositório e caminho para o arquivo JSON
$repoUrl = 'https://raw.githubusercontent.com/sophosgh1/Bitcoin-Puzzle-Tracker/main/';
$dataFile = $repoUrl . 'ranges.json';

// Carregar dados do arquivo JSON via API do GitHub
$json = file_get_contents($dataFile);
$ranges = json_decode($json, true);

// Ordenar os ranges por data de inclusão (mais recente primeiro)
usort($ranges, function($a, $b) {
    return strtotime($b['date_added']) - strtotime($a['date_added']);
});

// Informações sobre o tamanho do range da carteira 66
$startRange = '20000000000000000';
$endRange = '3ffffffffffffffff';
$totalKeys = countKeysInRange($startRange, $endRange);

// Contar chaves escaneadas
$scannedKeys = 0;
foreach ($ranges as $range) {
    list($start, $end) = explode('-', $range['range']);
    $scannedKeys += countKeysInRange($start, $end);
}

// Calcular chaves restantes e porcentagem escaneada
$remainingKeys = $totalKeys - $scannedKeys;
$percentageScanned = ($scannedKeys / $totalKeys) * 100;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Puzzle Tracker</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-theme">
    <div class="container mt-5">
        <div class="header text-center mb-5 p-4 rounded shadow">
            <h1 class="display-4">Bitcoin Puzzle Tracker</h1>
            <p class="lead">Carteira 66: 13zb1hQbWVsc2S7ZTZnP2G4undNNpdh5so</p>
        </div>

        <div class="range-list mt-4">
            <?php if (!empty($ranges)): ?>
                <div class="list-group">
                    <?php foreach ($ranges as $range): ?>
                        <?php list($start, $end) = explode('-', $range['range']); ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center range-item">
                            <span>Range: <?php echo htmlspecialchars($range['range']); ?></span>
                            <span>Nome: <?php echo htmlspecialchars($range['name']); ?></span>
                            <span>Data de atualizaão: <?php echo date('d/m/Y', strtotime($range['date_added'])); ?></span>
                            <span>Chaves Escaneadas: <?php echo number_format(countKeysInRange($start, $end)); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Nenhum range foi pesquisado ainda.</p>
            <?php endif; ?>
        </div>

        <div class="statistics mt-5">
            <h2>Estatísticas do Range</h2>
            <p><strong>Tamanho Total do Range:</strong> <?php echo number_format($totalKeys); ?> chaves</p>
            <p><strong>Chaves Escaneadas:</strong> <?php echo number_format($scannedKeys); ?></p>
            <p><strong>Chaves Restantes:</strong> <?php echo number_format($remainingKeys); ?></p>
            <p><strong>Porcentagem Escaneada:</strong> <?php echo number_format($percentageScanned, 20); ?>%</p>
        </div>
    </div>
</body>
</html>
