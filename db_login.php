<?php
// Dados de conexão com o banco de dados
$host = 'localhost';
$db   = 'login';
$user = 'root';
$pass = '';

// DSN e opções do PDO
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Estabelecer conexão com o banco de dados
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verificar se todos os campos foram preenchidos
    if (isset($_POST['usuario'], $_POST['senha'])) {
        
        // Coletar dados do formulário
        $usuario = trim($_POST['usuario']);
        $senha = trim($_POST['senha']);
        
        // Validar os dados (exemplo básico)
        if (empty($usuario) || empty($senha)) {
            die("Por favor, preencha todos os campos do formulário.");
        }

        // Preparar a declaração SQL para buscar o usuário
        $sql = 'SELECT senha FROM usuario WHERE usuario = :usuario';
        $stmt = $pdo->prepare($sql);
        
        // Executar a consulta
        try {
            $stmt->execute(['usuario' => $usuario]);
            $usuario_db = $stmt->fetch();
            
            if ($usuario_db && password_verify($senha, $usuario_db['senha'])) {
                echo "Login realizado com sucesso!";
                // Aqui você pode redirecionar o usuário para uma página de sucesso
            ('Location: welcome.php');
                exit();
            } else {
                echo "Usuário ou senha incorretos.";
            }
        } catch (\PDOException $e) {
            die("Erro ao processar o login: " . $e->getMessage());
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>
