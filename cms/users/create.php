<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'role' => $role
    ]);

    header('Location: list.php');
    exit;
}
?>

<?php $pageTitle = "Adicionar Usuário"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Adicionar Usuário</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Nome" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Senha" required>
        <select name="role" required>
            <option value="admin">Administrador</option>
            <option value="customer">Cliente</option>
        </select>
        <button type="submit">Salvar</button>
    </form>

<?php include '../footer_admin.php'; ?>