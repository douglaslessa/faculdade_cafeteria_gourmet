<?php
session_start();

// Destruir todas as sessões
session_unset();
session_destroy();

// Redirecionar para a página de login
header('Location: ../cms/login.php');
exit;
?>
