<?php
// setup_db.php
$db = new PDO('sqlite:jogadores.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS jogadores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nickname TEXT NOT NULL UNIQUE,
    classe TEXT NOT NULL,
    nivel INTEGER NOT NULL,
    email TEXT NOT NULL
)");

$stmt = $db->prepare("INSERT OR IGNORE INTO jogadores (nickname, classe, nivel, email) VALUES (?, ?, ?, ?)");

$exemplos = [
    ['ShadowRider', 'Arqueiro', 35, 'shadow@example.com'],
    ['IronMage', 'Mago', 28, 'ironmage@example.com']
];

foreach ($exemplos as $j) {
    $stmt->execute($j);
}

echo "Banco de jogadores criado com sucesso.";
