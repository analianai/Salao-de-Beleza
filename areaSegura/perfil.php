<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Redireciona para a página de login se não estiver logado
    header('Location: sing_in.php');
    exit;
}

// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "salao");

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $mysqli->connect_error);
}

// Obtenha o ID do usuário logado
$user_id = $_SESSION['user_id'];

// Consulta para obter os dados do usuário
$query = "SELECT nome, sobrenome, celular, endereco FROM usuarios WHERE id = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Erro na preparação da consulta: " . $mysqli->error);
}

$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o usuário foi encontrado
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Erro: Usuário não encontrado.";
    exit;
}

// Fecha a conexão
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão de Beleza Perfil Dashboard</title>
    <!-- Bootstrap css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap css Icons-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <!-- Inclui o menu seguro -->
    <?php include '../componentes/menuSeguro.php'; ?>
    <section class="container">
        <div class="mt-5">
            <h3 class="pt-5"><i class="bi bi-person-check-fill"></i> Meu Perfil</h3>
            <hr>
        </div>
    </section>
    <div class="container">
    
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($user['nome'] . ' ' . $user['sobrenome']); ?></p>
        <p><strong>Celular (WhatsApp):</strong> <?php echo htmlspecialchars($user['celular']); ?></p>
        <p><strong>Endereço:</strong> <?php echo htmlspecialchars($user['endereco']); ?></p>
    </div>

    <script src="../assets/js/script.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
