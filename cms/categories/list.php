<?php
session_start();
require '../includes/db.php';

// Buscar categorias
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<?php $pageTitle = "Categorias de Produtos"; ?>
<?php include '../header_admin.php'; ?>

<h1>Categorias de Produtos</h1>
<a href="create.php">Adicionar Nova Categoria de Produtos</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['id']; ?></td>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $category['id']; ?>">Editar</a>
                    <a href="delete.php?id=<?php echo $category['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta categoria?');">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../footer_admin.php'; ?>    
