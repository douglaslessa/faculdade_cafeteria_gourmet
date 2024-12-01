<?php
session_start();
require '../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    $stmt = $pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
    $stmt->execute([
        'status' => $status,
        'id' => $id
    ]);

    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare('SELECT status FROM orders WHERE id = :id');
$stmt->execute(['id' => $id]);
$order = $stmt->fetch();
?>

<?php $pageTitle = "Atualizar Status do Pedido"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Atualizar Status do Pedido #<?php echo $id; ?></h1>
    <form method="POST">
        <select name="status" required>
            <option value="pending" <?php if ($order['status'] === 'pending') echo 'selected'; ?>>Pendente</option>
            <option value="processing" <?php if ($order['status'] === 'processing') echo 'selected'; ?>>Processando</option>
            <option value="shipped" <?php if ($order['status'] === 'shipped') echo 'selected'; ?>>Enviado</option>
            <option value="delivered" <?php if ($order['status'] === 'delivered') echo 'selected'; ?>>Entregue</option>
            <option value="cancelled" <?php if ($order['status'] === 'cancelled') echo 'selected'; ?>>Cancelado</option>
        </select>
        <button type="submit">Atualizar</button>
    </form>

<?php include '../footer_admin.php'; ?>
    