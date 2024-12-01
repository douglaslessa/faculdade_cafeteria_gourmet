<?php
session_start();
require 'cms/includes/db.php';

// Obter o ID do produto da URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Verificar se o ID do produto é válido
if (!$productId) {
    header('Location: catalog.php');
    exit;
}

// Buscar os detalhes do produto
$stmt = $pdo->prepare("SELECT products.*, categories.name AS category_name FROM products 
                        JOIN categories ON products.category_id = categories.id 
                        WHERE products.id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: catalog.php');
    exit;
}

// Cálculo do preço formatado
$formattedPrice = number_format($product['price'], 2, ',', '.');
?>

<?php $pageTitle = htmlspecialchars($product['name']); ?>
<?php include 'header_site.php'; ?>

    <section class="product-page">
        <div class="product-detail-container">
            <div class="product-image">
                <img src="cms/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>

            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="price">R$ <?php echo $formattedPrice; ?></p>
                <p class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($product['category_name']); ?></p>
                <p><strong>Avaliação:</strong> <span class="rating">⭐⭐⭐⭐⭐</span></p>
                
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" required>
                    <button type="submit">Adicionar ao Carrinho</button>
                </form>
            </div>
        </div>
    </section>

<?php include 'footer_site.php'; ?>

<style>
    .product-page {
        padding: 20px;
    }

    .product-detail-container {
        display: flex;
        justify-content: space-between;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px auto;
        max-width: 680px;
        padding: 20px;
        border-radius: 8px;
    }

    .product-image {
        width: 100%;
    }

    .product-image img {
        width: 100%;
        max-height: 800px;
        object-fit: contain;
        border-radius: 8px;
    }

    .product-info {
        max-width: 600px;
        margin-left: 30px;
    }

    .product-info h2 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .price {
        font-size: 1.5rem;
        color: #c2936e;
        margin: 10px 0;
    }

    .description {
        font-size: 1rem;
        margin: 10px 0;
        line-height: 1.5;
    }

    .rating {
        color: #fbc02d;
    }

    footer {
        text-align: center;
        background-color: #c2936e;
        color: white;
        padding: 10px;
        margin-top: 40px;
    }
</style>