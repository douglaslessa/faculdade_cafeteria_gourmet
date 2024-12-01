<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT orders.id, orders.customer_name, orders.total_price, orders.status, orders.created_at 
                     FROM orders 
                     ORDER BY orders.created_at DESC');
$orders = $stmt->fetchAll();
?>

<?php $pageTitle = "Pedidos"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Pedidos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Valor Total</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td>R$<?php echo number_format($order['total_price'], 2, ',', '.'); ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $order['id']; ?>">Visualizar</a>
                        <a href="update.php?id=<?php echo $order['id']; ?>">Atualizar Status</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>
