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
    ['Bandeira', 'Mago', 30, 'bandeira@example.com'],
    ['Macedo', 'Arqueiro', 29, 'macedo@example.com'],
    ['Albandês', 'Cavaleiro', 50, 'albandes@example.com']
];

foreach ($exemplos as $j) {
    $stmt->execute($j);
}

echo "Banco de jogadores criado com sucesso.";
