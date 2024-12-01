<?php
session_start();
require 'cms/includes/db.php';

// Verificar se o carrinho não está vazio
if (empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Seu carrinho está vazio.";
    header('Location: cart.php');
    exit;
}

// Inicializar variáveis
$errors = [];
$total = 0;
$cart = $_SESSION['cart'];

// Calcular o total do carrinho
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Processar o formulário de checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $shipping_address = trim($_POST['shipping_address']);
    $payment_method = $_POST['payment_method'];

    // Validar campos
    if (empty($customer_name)) $errors[] = "O nome é obrigatório.";
    if (empty($customer_email) || !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) $errors[] = "E-mail inválido.";
    if (empty($shipping_address)) $errors[] = "O endereço de entrega é obrigatório.";
    if (empty($payment_method)) $errors[] = "Selecione um método de pagamento.";

    // Inserir o pedido no banco de dados
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Inserir na tabela de pedidos
            $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, shipping_address, payment_method, total_price, status) 
                                   VALUES (:customer_name, :customer_email, :shipping_address, :payment_method, :total_price, 'pending')");
            $stmt->execute([
                ':customer_name' => $customer_name,
                ':customer_email' => $customer_email,
                ':shipping_address' => $shipping_address,
                ':payment_method' => $payment_method,
                ':total_price' => $total
            ]);
            $order_id = $pdo->lastInsertId();

            // Inserir os itens do pedido
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                   VALUES (:order_id, :product_id, :quantity, :price)");
            foreach ($cart as $product_id => $item) {
                $stmt->execute([
                    ':order_id' => $order_id,
                    ':product_id' => $product_id,
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            // Limpar o carrinho
            $_SESSION['cart'] = [];

            $pdo->commit();

            // Redirecionar para página de sucesso
            header('Location: success.php?order_id=' . $order_id);
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Erro ao processar o pedido: " . $e->getMessage();
        }
    }
}
?>

<?php $pageTitle = "Carrinho"; ?>
<?php include 'header_site.php'; ?>
    <h2>Resumo do Pedido</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>R$ <?php echo number_format($item['price'], 2, ',', '.'); ?></td>
                    <td>R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total:</strong></td>
                <td><strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <h2>Dados do Cliente</h2>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="customer_name">Nome:</label>
        <input type="text" name="customer_name" id="customer_name" required>

        <label for="customer_email">E-mail:</label>
        <input type="email" name="customer_email" id="customer_email" required>

        <label for="shipping_address">Endereço de Entrega:</label>
        <textarea name="shipping_address" id="shipping_address" required></textarea>

        <label for="payment_method">Método de Pagamento:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="">Selecione</option>
            <option value="credit_card">Cartão de Crédito</option>
            <option value="boleto">Boleto</option>
            <option value="pix">Pix</option>
        </select>

        <button type="submit">Finalizar Pedido</button>
    </form>

    <a href="cart.php">Voltar para o Carrinho</a>

<?php include 'footer_site.php'; ?>