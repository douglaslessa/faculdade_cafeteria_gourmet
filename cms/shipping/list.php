<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT * FROM shipping_options');
$options = $stmt->fetchAll();
?>

<?php $pageTitle = "Opções de Envio"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Configuração de Envio</h1>
    <a href="create.php">Adicionar Opção de Envio</a>
    <table>
        <thead>
            <tr>
                <th>Região</th>
                <th>Preço</th>
                <th>Prazo Estimado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($options as $option): ?>
                <tr>
                    <td><?php echo htmlspecialchars($option['region']); ?></td>
                    <td>R$<?php echo number_format($option['price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($option['estimated_delivery_time']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $option['id']; ?>">Editar</a>
                        <a href="delete.php?id=<?php echo $option['id']; ?>" onclick="return confirm('Deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>