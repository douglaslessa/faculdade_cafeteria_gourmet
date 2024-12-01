<?php
require '../includes/db.php';

$errors = [];
$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "Produto não encontrado.";
    header('Location: list.php');
    exit;
}

// Buscar categorias disponíveis
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();

// Buscar produto existente
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    $_SESSION['error'] = "Produto não encontrado.";
    header('Location: list.php');
    exit;
}

$name = $product['name'];
$price = $product['price'];
$category_id = $product['category_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category_id = $_POST['category_id'];
    $stock = trim($_POST['stock']);
    $image = $_FILES['image'];
    
    if (empty($name)) $errors[] = "O nome do produto é obrigatório.";
    if (empty($price) || !is_numeric($price)) $errors[] = "O preço deve ser válido.";
    if (empty($category_id)) $errors[] = "Selecione uma categoria.";
    if (empty($stock) || !is_numeric($stock) || $stock < 0) $errors[] = "O estoque deve ser um número válido.";

    // Upload da imagem, se necessário
    if (empty($errors)) {
        if (!empty($image['name'])) {
            $imagePath = '../uploads/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imagePath);
            $product['image'] = $image['name'];
        }

        try {
            $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, category_id = :category_id, image = :image, stock = :stock WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':category_id' => $category_id,
                ':image' => $product['image'],
                ':stock' => $stock,
                ':id' => $id
            ]);
            $_SESSION['success'] = "Produto atualizado com sucesso!";
            header('Location: list.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erro ao atualizar o produto: " . $e->getMessage();
        }
    }
}
?>

<?php include '../header_admin.php'; ?>
<h1>Editar Produto</h1>

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
    <input type="number" name="stock" id="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" min="0" required>

    <label for="image">Imagem do Produto:</label>
    <input type="file" name="image" id="image">
    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Imagem Atual" width="100">

    <button type="submit">Salvar</button>
</form>

<?php include '../footer_admin.php'; ?>
