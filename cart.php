<?php
session_start();
require 'cms/includes/db.php';

// Inicializar o carrinho, caso ainda não exista
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Adicionar produtos ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    // Buscar o produto no banco de dados
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();

    if ($product) {
        // Verificar se o produto já está no carrinho
        if (isset($_SESSION['cart'][$productId])) {
            // Atualizar a quantidade
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            // Adicionar novo item ao carrinho
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];

            $_SESSION['counter_cart'] = 0;
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $_SESSION['counter_cart'] += $item['quantity'];
                }
            }

        }
    }

    // Redirecionar para o carrinho
    header('Location: cart.php');
    exit;
}

// Remover produtos do carrinho
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['product_id'])) {
    $productId = (int) $_GET['product_id'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }

    $_SESSION['counter_cart'] = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $_SESSION['counter_cart'] += $item['quantity'];
        }
    }

    header('Location: cart.php');
    exit;
}

// Obter o carrinho atual
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<?php $pageTitle = "Carrinho"; ?>
<?php include 'header_site.php'; ?>

    <section class="contact">
        <h2>Carrinho</h2>
        <?php if (!empty($cart)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $productId => $item): ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; ?>
                        <?php $total += $subtotal; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                            <td>
                                <a href="cart.php?action=remove&product_id=<?php echo $productId; ?>" onclick="return confirm('Deseja remover este item do carrinho?');">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td colspan="2">R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            <div>
                <a href="catalog.php">Continuar Comprando</a>
                |
                <a href="checkout.php" class="button">Finalizar Compra</a>
            </div>
        <?php else: ?>
            <p>Seu carrinho está vazio.</p>
            <a href="catalog.php" class="button">Voltar para o Catálogo</a>
        <?php endif; ?>
    </section>

<?php include 'footer_site.php'; ?>