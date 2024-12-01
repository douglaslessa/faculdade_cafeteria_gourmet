<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region = $_POST['region'];
    $price = $_POST['price'];
    $estimated_time = $_POST['estimated_time'];

    $stmt = $pdo->prepare('INSERT INTO shipping_options (region, price, estimated_delivery_time) VALUES (:region, :price, :estimated_time)');
    $stmt->execute([
        'region' => $region,
        'price' => $price,
        'estimated_time' => $estimated_time
    ]);

    header('Location: list.php');
    exit;
}
?>

<?php $pageTitle = "Adicionar Opção de Envio"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Adicionar Opção de Envio</h1>
    <form method="POST">
        <input type="text" name="region" placeholder="Região" required>
        <input type="number" step="0.01" name="price" placeholder="Preço" required>
        <input type="text" name="estimated_time" placeholder="Prazo Estimado" required>
        <button type="submit">Salvar</button>
    </form>

<?php include '../footer_admin.php'; ?>
