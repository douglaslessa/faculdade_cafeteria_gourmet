<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT * FROM payment_methods');
$methods = $stmt->fetchAll();
?>

<?php $pageTitle = "Métodos de Pagamento"; ?>
<?php include '../header_admin.php'; ?>

    <a href="create.php">Adicionar Método de Pagamento</a>
    <table>
        <thead>
            <tr>
                <th>Nome do Método</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($methods as $method): ?>
                <tr>
                    <td><?php echo htmlspecialchars($method['method_name']); ?></td>
                    <td><?php echo $method['active'] ? 'Sim' : 'Não'; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $method['id']; ?>">Editar</a>
                        <a href="delete.php?id=<?php echo $method['id']; ?>" onclick="return confirm('Deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>