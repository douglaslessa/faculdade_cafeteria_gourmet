<?php
session_start();
require '../includes/db.php';

$errors = [];
$name = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "Categoria não encontrada.";
    header('Location: list.php');
    exit;
}

// Buscar a categoria
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->execute([':id' => $id]);
$category = $stmt->fetch();

if (!$category) {
    $_SESSION['error'] = "Categoria não encontrada.";
    header('Location: list.php');
    exit;
}

$name = $category['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $errors[] = "O nome da categoria é obrigatório.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
            $_SESSION['success'] = "Categoria atualizada com sucesso!";
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erro ao atualizar categoria: " . $e->getMessage();
        }
    }
}
?>

<?php $pageTitle = "Editar Categoria de Produtos"; ?>
<?php include '../header_admin.php'; ?>

<form method="POST" action="edit.php?id=<?php echo $id; ?>">
    <label for="name">Nome da Categoria:</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
    <button type="submit">Salvar</button>
</form>

<?php include '../footer_admin.php'; ?>    
