<?php
header('Content-Type: application/json');

// Verificar se o formulário foi enviado por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não suportado']);
    exit;
}

// Incluir o arquivo de conexão
require_once 'conexao.php';

try {
    // Verificar se os dados foram enviados
    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['mensagem'])) {
        throw new Exception('Preencha todos os campos.');
    }

    // Preparar e executar a query
    $stmt = $conexao->prepare("INSERT INTO contactos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)");
    $stmt->execute([
        ':nome' => $_POST['nome'],
        ':email' => $_POST['email'],
        ':mensagem' => $_POST['mensagem']
    ]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Mensagem enviada com sucesso!']);
} catch(Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
}
?>