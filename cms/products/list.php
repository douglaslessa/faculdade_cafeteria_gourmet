<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<?php $pageTitle = "Produtos"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Produtos</h1>
    <a href="create.php">Adicionar Produto</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>R$<?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $product['id']; ?>">Editar</a>
                        <a href="delete.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>
