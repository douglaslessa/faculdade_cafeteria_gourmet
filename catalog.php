<?php
session_start();
require_once 'cms/includes/db.php';

// Filtros
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 6; // Padrão: 6 produtos por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual
$offset = ($page - 1) * $perPage;

// Query para contar o total de produtos
$totalProductsQuery = "SELECT COUNT(*) AS total FROM products WHERE 1=1";
if (!empty($categoryFilter)) {
    $totalProductsQuery .= " AND category_id = :category";
}
$totalStmt = $pdo->prepare($totalProductsQuery);
if (!empty($categoryFilter)) {
    $totalStmt->bindValue(':category', $categoryFilter, PDO::PARAM_INT);
}
$totalStmt->execute();
$totalProducts = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calcular total de páginas
$totalPages = ceil($totalProducts / $perPage);

// Query para buscar produtos com limite e offset
$productQuery = "SELECT * FROM products WHERE 1=1";
if (!empty($categoryFilter)) {
    $productQuery .= " AND category_id = :category";
}
$productQuery .= " LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($productQuery);
if (!empty($categoryFilter)) {
    $stmt->bindValue(':category', $categoryFilter, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query para buscar categorias (menu dinâmico)
$categoryQuery = "SELECT * FROM categories ORDER BY name";
$categoryStmt = $pdo->query($categoryQuery);
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php $pageTitle = "Produtos"; ?>
<?php include 'header_site.php'; ?>

        <div class="container ctn-products">
            <aside class="sidebar">
                <div class="categories">
                    <h2>Categorias</h2>
                    <ul class="lista-categorias">
                        <li><a href="catalog.php">Todos</a></li>
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="catalog.php?category_id=<?php echo $category['id']; ?>" class="<?php //echo ($_GET['category_id'] == $category['id']) ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <h2>Preço</h2>
                <input type="text" placeholder="Todos">

                <h2>Avaliação</h2>
                <select>
                    <option value="all">Todas</option>
                </select>

                <form method="GET" class="products-filter">
                    <input type="hidden" name="category" value="<?= htmlspecialchars($categoryFilter) ?>">
                    <label for="perPage">Produtos por página:</label>
                    <select name="perPage" id="perPage" onchange="this.form.submit()">
                        <option value="6" <?= $perPage == 6 ? 'selected' : '' ?>>6</option>
                        <option value="9" <?= $perPage == 9 ? 'selected' : '' ?>>9</option>
                        <option value="12" <?= $perPage == 12 ? 'selected' : '' ?>>12</option>
                    </select>
                </form>

            </aside>

            <main class="products">
                <h2>Produtos</h2>
                <div class="product-grid">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="product product-item">
                                <img src="cms/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></p>
                                
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1">
                                    <button type="submit">Adicionar ao Carrinho</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum produto encontrado nesta categoria.</p>
                    <?php endif; ?>
                </div>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="catalog.php?page=<?= $page - 1 ?>&perPage=<?= $perPage ?>&category=<?= $categoryFilter ?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="catalog.php?page=<?= $i ?>&perPage=<?= $perPage ?>&category=<?= $categoryFilter ?>" 
                            class="<?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="catalog.php?page=<?= $page + 1 ?>&perPage=<?= $perPage ?>&category=<?= $categoryFilter ?>">Próxima</a>
                    <?php endif; ?>
                </div>
            </main>
        </div>

    <?php include 'footer_site.php'; ?>
