<?php
require '../includes/db.php';

$errors = [];
$name = '';
$price = '';
$category_id = ''; // Nova variável para a categoria

// Buscar categorias disponíveis
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = $_POST['category_id']; // Capturar a categoria
    $stock = trim($_POST['stock']);
    $image = $_FILES['image'];

    if (empty($name)) $errors[] = "O nome do produto é obrigatório.";
    if (empty($price) || !is_numeric($price)) $errors[] = "O preço deve ser válido.";
    if (empty($category_id)) $errors[] = "Selecione uma categoria.";
    if (empty($stock) || !is_numeric($stock) || $stock < 0) $errors[] = "O estoque deve ser um número válido.";
    if (empty($image['name'])) $errors[] = "A imagem do produto é obrigatória.";

    // Upload da imagem
    if (empty($errors)) {
        $imagePath = '../uploads/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, image, category_id, stock) VALUES (:name, :price, :image, :category_id, :stock)");
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':image' => $image['name'],
                ':category_id' => $category_id,
                ':stock' => $stock
            ]);
            $_SESSION['success'] = "Produto cadastrado com sucesso!";
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erro ao cadastrar o produto: " . $e->getMessage();
        }
    }
}
?>

<?php $pageTitle = "Adicionar Produto"; ?>
<?php include '../header_admin.php'; ?>

<h1>Cadastrar Produto</h1>
<form method="POST" enctype="multipart/form-data">
    <label for="name">Nome do Produto:</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>

    <label for="price">Preço:</label>
    <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>" required>

    <label for="category_id">Categoria:</label>
    <select name="category_id" id="category_id" required>
        <option value="">Selecione uma categoria</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($category['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="stock">Estoque:</label>
    <input type="number" name="stock" id="stock" value="0" min="0" required>

    <label for="image">Imagem do Produto:</label>
    <input type="file" name="image" id="image" required>

    <button type="submit">Salvar</button>
</form>

<?php include '../footer_admin.php'; ?>