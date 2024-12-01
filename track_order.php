<?php
require 'cms/includes/db.php';

$order_id = $_GET['order_id'] ?? null;
$tracking = null;

if ($order_id) {
    $stmt = $pdo->prepare('SELECT * FROM delivery_tracking WHERE order_id = :order_id');
    $stmt->execute(['order_id' => $order_id]);
    $tracking = $stmt->fetch();
}
?>

<?php $pageTitle = "Rastreamento de Pedido" ?>
<?php include 'header_site.php'; ?>

        <form method="GET">
            <label for="order_id">Digite o número do pedido:</label>
            <input type="text" id="order_id" name="order_id" required>
            <button type="submit">Rastrear</button>
        </form>

        <?php if ($order_id && $tracking): ?>
            <h2>Status do Pedido</h2>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($tracking['status']); ?></p>
            <p><strong>Localização Atual:</strong> <?php echo htmlspecialchars($tracking['location']); ?></p>
            <p><strong>Última Atualização:</strong> <?php echo htmlspecialchars($tracking['updated_at']); ?></p>
        <?php elseif ($order_id): ?>
            <p>Pedido não encontrado ou ainda não está disponível para rastreamento.</p>
        <?php endif; ?>

<?php include 'footer_site.php'; ?>
