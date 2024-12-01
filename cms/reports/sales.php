<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT DATE(created_at) AS date, SUM(total_price) AS total_sales, COUNT(*) AS total_orders 
                     FROM orders 
                     GROUP BY DATE(created_at) 
                     ORDER BY DATE(created_at) DESC');
$report = $stmt->fetchAll();
?>

<?php $pageTitle = "Relatório de Vendas"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Relatório de Vendas</h1>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Total de Pedidos</th>
                <th>Total Vendido</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report as $row): ?>
                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['total_orders']; ?></td>
                    <td>R$<?php echo number_format($row['total_sales'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>
