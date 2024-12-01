<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Cafeteria Gourmet'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="assets/img/logo.png" alt="Espresso Royale">
    </div>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">Sobre Nós</a>
        <a href="catalog.php">Produtos</a>
        <a href="contact.php">Contato</a>
        
        <?php 
            if(isset($_SESSION['counter_cart'])){
                $counter_cart = $_SESSION['counter_cart'];
            } else {
                $counter_cart = 0;
            }  
        ?>
        
        <span class="counter_cart" style="display: <?= ($counter_cart > 0)?'block':'none' ?>;"><?= $counter_cart ?></span>

        <a href="cart.php"><img src="assets/img/cart.png" style="vertical-align: bottom" alt="Carrinho"></a>
    </nav>
</header>
<main>
