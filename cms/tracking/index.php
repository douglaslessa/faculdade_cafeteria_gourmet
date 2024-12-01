<?php
session_start();
require '../includes/db.php';

$order_id = $_GET['order_id'] ?? null;

if ($order_id) {
    $stmt = $pdo->prepare('SELECT * FROM delivery_tracking WHERE order_id = :order_id');
    $stmt->execute(['order_id' => $order_id]);
    $tracking = $stmt->fetch();
}

?>

<?php $pageTitle = "Rastreamento de Pedido"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Rastreamento de Pedido</h1>
    <?php if ($tracking): ?>
        <p>Status: <?php echo htmlspecialchars($tracking['status']); ?></p>
        <p>Localização Atual: <?php echo htmlspecialchars($tracking['location']); ?></p>
    <?php else: ?>
        <p>Pedido não encontrado ou ainda não está disponível para rastreamento.</p>
    <?php endif; ?>

<?php include '../footer_admin.php'; ?>
