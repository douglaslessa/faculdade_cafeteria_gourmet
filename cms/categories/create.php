<?php
session_start();
require '../includes/db.php';

$errors = [];
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $errors[] = "O nome da categoria é obrigatório.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute([':name' => $name]);
            $_SESSION['success'] = "Categoria criada com sucesso!";
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erro ao criar categoria: " . $e->getMessage();
        }
    }
}
?>

<?php $pageTitle = "Adicionar Categoria de Produtos"; ?>
<?php include '../header_admin.php'; ?>

<form method="POST" action="create.php">
    <label for="name">Nome da Categoria:</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
    <button type="submit">Salvar</button>
</form>

<?php include '../footer_admin.php'; ?>    
