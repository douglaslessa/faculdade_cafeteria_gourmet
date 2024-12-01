<?php
session_start();
require 'cms/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare('INSERT INTO contact_messages (user_id, message) VALUES (:user_id, :message)');
    $stmt->execute([
        'user_id' => null,
        'message' => "De: $name <$email>\n\n$message"
    ]);

    $success = "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.";
}
?>

<?php $pageTitle = "Fale Conosco"; ?>
<?php include 'header_site.php'; ?>
    <section class="contact">
        <h2>Fale Conosco</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Seu Nome" required>
            <input type="email" name="email" placeholder="Seu E-mail" required>
            <textarea name="message" placeholder="Sua Mensagem" required></textarea>
            <button type="submit">Enviar</button>
        </form>
        <?php if (!empty($success)): ?>
            <p><?php echo $success; ?></p>
        <?php endif; ?>
    </section>
<?php include 'footer_site.php'; ?>
