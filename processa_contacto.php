<?php
header('Content-Type: application/json');

$host = 'localhost';
$db   = 'impressos_db';
$user = 'root';
$pass = '';  // senha vazia

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if (!$nome || !$email || !$mensagem) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Por favor, preencha todos os campos.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Email inválido.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO contactos (nome, email, mensagem) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $mensagem]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Mensagem enviada com sucesso!']);
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor: ' . $e->getMessage()]);
}