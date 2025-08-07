<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=impressos_db;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $servico = $_POST['servico'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';
    $ficheiroDestino = null;

    // Validação básica
    if (!$nome || !$telefone || !$servico) {
        throw new Exception('Preencha todos os campos obrigatórios.');
    }

    // Verificar e processar upload apenas se existir
    if (!empty($_FILES['ficheiro']['name'])) {
        $ficheiroNome = $_FILES['ficheiro']['name'];
        $ficheiroTemp = $_FILES['ficheiro']['tmp_name'];
        $ficheiroDestino = 'uploads/' . uniqid() . '-' . basename($ficheiroNome);

        // Criar diretório se não existir
        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        if (!move_uploaded_file($ficheiroTemp, $ficheiroDestino)) {
            throw new Exception('Erro ao enviar o ficheiro.');
        }
    }

    // Inserir no banco
    $stmt = $pdo->prepare("
        INSERT INTO copias (nome, telefone, email, servico, mensagem, ficheiro) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nome, $telefone, $email, $servico, $mensagem, $ficheiroDestino]);

    echo json_encode(['sucesso' => true, 'mensagem' => 'Consulta enviada com sucesso!']);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: ' . $e->getMessage()]);
}