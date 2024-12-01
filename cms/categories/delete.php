<?php
session_start();
require '../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "Categoria não encontrada.";
    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $_SESSION['success'] = "Categoria excluída com sucesso!";
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['error'] = "Erro ao excluir categoria: " . $e->getMessage();
    header('Location: list.php');
    exit;
}
?>
