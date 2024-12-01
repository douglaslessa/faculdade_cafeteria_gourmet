<?php
session_start();
require '../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /shipping/list.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM shipping_options WHERE id = :id');
$stmt->execute(['id' => $id]);
$option = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region = $_POST['region'];
    $price = $_POST['price'];
    $estimated_time = $_POST['estimated_time'];

    $stmt = $pdo->prepare('UPDATE shipping_options SET region = :region, price = :price, estimated_delivery_time = :estimated_time WHERE id = :id');
    $stmt->execute([
        'region' => $region,
        'price' => $price,
        'estimated_time' => $estimated_time,
        'id' => $id
    ]);

    header('Location: list.php');
    exit;
}
?>

<?php $pageTitle = "Editar Opção de Envio"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Editar Opção de Envio</h1>
    <form method="POST">
        <input type="text" name="region" placeholder="Região" value="<?php echo htmlspecialchars($option['region']); ?>" required>
        <input type="number" step="0.01" name="price" placeholder="Preço" value="<?php echo $option['price']; ?>" required>
        <input type="text" name="estimated_time" placeholder="Prazo Estimado" value="<?php echo $option['estimated_delivery_time']; ?>" required>
        <button type="submit">Atualizar</button>
    </form>

<?php include '../footer_admin.php'; ?>
