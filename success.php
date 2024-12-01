<?php
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido Concluído</title>
    <link rel="stylesheet" href="cms/assets/style.css">
</head>
<body>
    <header>
        <h1>Pedido Realizado com Sucesso!</h1>
    </header>

    <main>
        <p>Obrigado por comprar na Cafeteria Gourmet! Seu pedido foi registrado e está sendo processado.</p>
        <a href="index.php">Voltar à Página Inicial</a>
    </main>

    <footer>
        <p>&copy; 2024 Cafeteria Gourmet</p>
    </footer>
</body>
</html>