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
    if (isset($_POST['nome'], $_POST['usuario'], $_POST['senha'])) {
        
        // Coletar dados do formulário
        $nome = trim($_POST['nome']);
        $usuario = trim($_POST['usuario']);
        $senha = trim($_POST['senha']);
        
        // Validar os dados (exemplo básico)
        if (empty($nome) || empty($usuario) || empty($senha)) {
            die("Por favor, preencha todos os campos do formulário.");
        }

        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Preparar a declaração SQL para inserção de dados na tabela 'usuario'
        $sql = 'INSERT INTO usuario (nome, usuario, senha) VALUES (:nome, :usuario, :senha)';
        $stmt = $pdo->prepare($sql);
        
        // Executar a inserção
        try {
            $stmt->execute([
                'nome'     => $nome,
                'usuario'  => $usuario,
                'senha'    => $senha_hash,
            ]);
            echo "Usuário cadastrado com sucesso!";
        } catch (\PDOException $e) {
            die("Erro ao cadastrar o usuário: " . $e->getMessage());
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>
