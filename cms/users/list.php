<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT id, name, email, role FROM users');
$users = $stmt->fetchAll();
?>

<?php $pageTitle = "Usuários"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Usuários</h1>
    <link rel="stylesheet" href="../assets/style.css">
    <a href="create.php">Adicionar Usuário</a>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $user['id']; ?>">Editar</a>
                        <a href="delete.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>