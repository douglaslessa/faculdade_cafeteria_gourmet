<?php
session_start();
require '../includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, role = :role, password = :password WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password' => $hashedPassword,
            'id' => $id
        ]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'id' => $id
        ]);
    }

    header('Location: list.php');
    exit;
}
?>

<?php $pageTitle = "Editar Usuário"; ?>
<?php include '../header_admin.php'; ?>

    <h1>Editar Usuário</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Nome" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <input type="email" name="email" placeholder="E-mail" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <select name="role" required>
            <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Administrador</option>
            <option value="customer" <?php if ($user['role'] === 'customer') echo 'selected'; ?>>Cliente</option>
        </select>
        <input type="password" name="password" placeholder="Nova senha (opcional)">
        <button type="submit">Atualizar</button>
    </form>

<?php include '../footer_admin.php'; ?>
