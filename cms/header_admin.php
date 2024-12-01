<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Painel Administrativo'; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
    <h1><a href="/admin/dashboard.php" style="text-decoration: none; color: inherit;">Painel Administrativo</a></h1>
    <nav>
        <a href="../admin/dashboard.php">Dashboard</a>
        <a href="../categories/list.php">Categorias de Produtos</a>
        <a href="../products/list.php">Produtos</a>
        <a href="../orders/list.php">Pedidos</a>
        <a href="../shipping/list.php">Envios</a>
        <a href="../reports/sales.php">Relatórios</a>
        <a href="../users/list.php">Usuários</a>
        <a href="../logout.php" style="color: red;">Sair</a>
    </nav>
</header>
<main>
