<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controller\TecnicoController;

header('Content-Type: application/json; charset=utf-8');

try {
    $controller = new TecnicoController();
    $termo = (string) ($_GET['q'] ?? '');
    echo json_encode($controller->pesquisar($termo), JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Falha ao pesquisar.']);
}