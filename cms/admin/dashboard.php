<?php
session_start();
require '../includes/db.php';

// Resumo de vendas por dia
$stmt = $pdo->query('SELECT DATE(created_at) AS date, SUM(total_price) AS total_sales 
                     FROM orders 
                     GROUP BY DATE(created_at)');
$sales_data = $stmt->fetchAll();

// Preparar dados para o gráfico
$dates = [];
$sales = [];

foreach ($sales_data as $data) {
    $dates[] = $data['date'];
    $sales[] = $data['total_sales'];
}
?>

<?php $pageTitle = "Dashboard"; ?>
<?php include '../header_admin.php'; ?>

    <h2>Vendas Recentes</h2>
    <canvas id="salesChart" width="400" height="200"></canvas>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Vendas Totais (R$)',
                    data: <?php echo json_encode($sales); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

<?php include '../footer_admin.php'; ?>
