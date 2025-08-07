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
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $produto = trim($_POST['produto'] ?? '');
    $quantidade = intval($_POST['quantidade'] ?? 1);
    $observacoes = trim($_POST['mensagem'] ?? '');

    if (!$nome || !$telefone || !$produto || $quantidade < 1) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Por favor, preencha os campos obrigatórios corretamente.']);
        exit;
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Email inválido.']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO pedidos (nome, telefone, email, produto, quantidade, observacoes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $telefone, $email, $produto, $quantidade, $observacoes]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido enviado com sucesso! Entraremos em contacto.']);
} catch (PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor: ' . $e->getMessage()]);
}
