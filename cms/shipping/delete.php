<?php
session_start();
require '../includes/db.php';

// Verificar se o ID foi passado
$id = $_GET['id'] ?? null;

if (!$id) {
    // Redirecionar para a lista de opções de envio se nenhum ID for fornecido
    header('Location: list.php');
    exit;
}

// Preparar a exclusão da opção de envio
$stmt = $pdo->prepare('DELETE FROM shipping_options WHERE id = :id');
$stmt->execute(['id' => $id]);

// Redirecionar de volta para a lista após a exclusão
header('Location: list.php');
exit;
?>
