<?php
require_once "usuário.php";

$nome = $_POST['nome'];
$user = $_POST['user'];
$email = $_POST['email'];
$senha = md5($_POST['senha']);

$usuario = new Usuario();

$usuario->setNome($nome);;
$usuario->setUsuario($user);
$usuario->setEmail($email);
$usuario->setSenha($senha);

$username = "root";
$password = "";

try {
    $usuar = $usuario->getUsuario();
    $nome = $usuario->getNome();
    $email = $usuario->getEmail();
    $senha = $usuario->getSenha();


    $conn = new PDO('mysql:host=localhost:33066;dbname=trabalho', $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare('SELECT * FROM usuario WHERE usuario = :usuario or email = :email');
    $stmt->bindParam(':usuario', $usuar, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) > 0){
        echo "Usuário já cadastrado";
    }else {
        $stmt = $conn->prepare('INSERT INTO usuario(usuario, nome, email, senha) values(:usuario, :nome, :email, :senha)');
        $stmt->bindParam(':usuario', $usuar, PDO::PARAM_STR);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        echo "usuário cadastrado com sucesso.</br>";
    }

    $stmt = $conn->prepare('SELECT * FROM usuario');
    $stmt->execute();
    $result = $stmt->fetchAll();

    if ( count($result) ) {
        foreach($result as $row) {
            echo "</br>Nome: ".$row['nome']."</br>Usuário: ".$row['usuario']."</br>Email: ".$row['email'].'</br>';
        }
    } else {
        echo "Nenhum resultado retornado.";
    }

}catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
}

?>
