<?php
header("Content-Type: application/json; charset=UTF-8");

$db = new PDO('sqlite:jogadores.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function enviar_json($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = array_values(array_filter(explode('/', $path)));

if (isset($segments[0]) && $segments[0] === 'jogadores') {

    // GET /jogadores
    if ($method === 'GET' && !isset($segments[1])) {
        $stmt = $db->query("SELECT * FROM jogadores");
        $jogadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        enviar_json($jogadores, 200);
    }

    // GET /jogadores/{id}
    if ($method === 'GET' && isset($segments[1])) {
        $id = intval($segments[1]);
        $stmt = $db->prepare("SELECT * FROM jogadores WHERE id = ?");
        $stmt->execute([$id]);
        $jogador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($jogador) enviar_json($jogador, 200);
        else enviar_json(['erro' => 'Jogador não encontrado'], 404);
    }

    enviar_json(['erro' => 'Método não suportado.'], 405);
}

enviar_json(['erro' => 'Endpoint inválido.'], 404);
