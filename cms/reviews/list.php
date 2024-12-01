<?php
session_start();
require '../includes/db.php';

$stmt = $pdo->query('SELECT product_reviews.id, product_reviews.rating, product_reviews.comment, 
                            users.name AS user_name, products.name AS product_name 
                     FROM product_reviews 
                     JOIN users ON product_reviews.user_id = users.id 
                     JOIN products ON product_reviews.product_id = products.id');
$reviews = $stmt->fetchAll();
?>

<?php $pageTitle = "Avaliações"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Avaliações</h1>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Usuário</th>
                <th>Nota</th>
                <th>Comentário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                    <td><?php echo $review['rating']; ?>/5</td>
                    <td><?php echo htmlspecialchars($review['comment']); ?></td>
                    <td>
                        <a href="delete.php?id=<?php echo $review['id']; ?>" onclick="return confirm('Deseja excluir esta avaliação?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php include '../footer_admin.php'; ?>
