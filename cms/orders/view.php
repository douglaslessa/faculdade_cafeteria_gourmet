<?php
session_start();
require '../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: /orders/list.php');
    exit;
}

$stmt = $pdo->prepare('SELECT orders.id, orders.customer_name, orders.total_price, orders.status, orders.created_at 
                       FROM orders 
                       WHERE orders.id = :id');
$stmt->execute(['id' => $id]);
$order = $stmt->fetch();

$stmtItems = $pdo->prepare('SELECT order_items.quantity, order_items.price, products.name 
                            FROM order_items 
                            JOIN products ON order_items.product_id = products.id 
                            WHERE order_items.order_id = :id');
$stmtItems->execute(['id' => $id]);
$orderItems = $stmtItems->fetchAll();
?>

<?php $pageTitle = "Detalhes do Pedido"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Detalhes do Pedido #<?php echo $order['id']; ?></h1>
    <p>Cliente: <?php echo htmlspecialchars($order['customer_name']); ?></p>
    <p>Status: <?php echo $order['status']; ?></p>
    <p>Data: <?php echo $order['created_at']; ?></p>
    <p>Total: R$<?php echo number_format($order['total_price'], 2, ',', '.'); ?></p>

    <h2>Itens do Pedido</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>R$<?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>
