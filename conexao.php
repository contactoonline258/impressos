<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'impressos_db';

try {
    $conexao = new PDO("mysql:host=$host;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Verificar se as tabelas existem
    $tabelas = $conexao->query("SHOW TABLES LIKE 'contactos'")->rowCount();
    if ($tabelas == 0) {
        throw new PDOException("Tabela 'contactos' não existe no banco de dados");
    }
    
    $tabelas = $conexao->query("SHOW TABLES LIKE 'pedidos'")->rowCount();
    if ($tabelas == 0) {
        throw new PDOException("Tabela 'pedidos' não existe no banco de dados");
    }
} catch(PDOException $e) {
    // Log do erro em arquivo para debug
    file_put_contents('db_errors.log', date('Y-m-d H:i:s')." - Erro: ".$e->getMessage()."\n", FILE_APPEND);
    die(json_encode(['sucesso' => false, 'mensagem' => 'Erro de conexão com o banco de dados: '.$e->getMessage()]));
}
?>