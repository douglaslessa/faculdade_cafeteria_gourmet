<?php
session_start();
require 'cms/includes/db.php';

$stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC LIMIT 4');
$products = $stmt->fetchAll();
?>

<?php $pageTitle = "Cafeteria Gourmet"; ?>
<?php include 'header_site.php'; ?>

    <section class="products">
        <h2>Produtos em Destaque</h2>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="cms/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>R$<?php echo number_format($product['price'], 2, ',', '.'); ?></p>
                    <a href="product.php?id=<?php echo $product['id']; ?>">Ver Detalhes</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

<?php include 'footer_site.php'; ?>
