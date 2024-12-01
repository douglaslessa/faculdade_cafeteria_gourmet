<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method_name = $_POST['method_name'];
    $active = isset($_POST['active']) ? 1 : 0;

    $stmt = $pdo->prepare('INSERT INTO payment_methods (method_name, active) VALUES (:method_name, :active)');
    $stmt->execute([
        'method_name' => $method_name,
        'active' => $active
    ]);

    header('Location: /payment/list.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Adicionar Método de Pagamento</title>
</head>
<body>
    <h1>Adicionar Método de Pagamento</h1>
    <form method="POST">
        <input type="text" name="method_name" placeholder="Nome do Método" required>
        <label>
            <input type="checkbox" name="active" value="1" checked> Ativo
        </label>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>
