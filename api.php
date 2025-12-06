<?php
// ======== CORS ========
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ======== Conexão com Banco ========
$db = new PDO('sqlite:jogadores.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function enviar_json($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
$segments = explode("/", $path);


if ($segments[0] === "api.php") {
    array_shift($segments);
}

// ======================================
//  ENDPOINT PRINCIPAL /jogadores
// ======================================
if ($segments[0] === "jogadores") {

    // GET /
    if ($method === "GET" && !isset($segments[1])) {
        $stmt = $db->query("SELECT * FROM jogadores");
        enviar_json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // GET /{id}
    if ($method === "GET" && isset($segments[1])) {
        $id = intval($segments[1]);
        $stmt = $db->prepare("SELECT * FROM jogadores WHERE id = ?");
        $stmt->execute([$id]);
        $jogador = $stmt->fetch(PDO::FETCH_ASSOC);
        enviar_json($jogador ?: ["erro" => "Jogador não encontrado"], $jogador ? 200 : 404);
    }

    // POST /
    if ($method === "POST") {
        $dados = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("INSERT INTO jogadores (nickname, classe, nivel, email) VALUES (?, ?, ?, ?)");
        $stmt->execute([$dados['nickname'], $dados['classe'], $dados['nivel'], $dados['email']]);
        enviar_json(["status" => "Jogador cadastrado com sucesso"]);
    }

    // PUT /{id}
    if ($method === "PUT" && isset($segments[1])) {
        $id = intval($segments[1]);
        $dados = json_decode(file_get_contents("php://input"), true);
        $stmt = $db->prepare("UPDATE jogadores SET nickname=?, classe=?, nivel=?, email=? WHERE id=?");
        $stmt->execute([$dados['nickname'], $dados['classe'], $dados['nivel'], $dados['email'], $id]);
        enviar_json(["status" => "Jogador atualizado"]);
    }

    // DELETE /{id}
    if ($method === "DELETE" && isset($segments[1])) {
        $id = intval($segments[1]);
        $stmt = $db->prepare("DELETE FROM jogadores WHERE id=?");
        $stmt->execute([$id]);
        enviar_json(["status" => "Jogador removido"]);
    }

    enviar_json(["erro" => "Método não permitido"], 405);
}

enviar_json(["erro" => "Endpoint inválido"], 404);
