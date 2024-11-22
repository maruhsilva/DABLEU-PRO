<!-- <?php

    // print_r($_REQUEST);
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
    {
        require_once 'config.php';
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM usuarios WHERE email = '$email' and senha = '$senha'";

        $result = $mysqli->query($sql);
        
        print_r(value: $sql);
        print_r($result);

        if(mysqli_num_rows($result) < 1)
        {
            // header("Location: /DABLEU-PRO/user-login.php");
            // print_r("Usuário Não Cadastrado!");
        }
        else
        {
           header('Location: user-logado.php');
        }
    }
    else
    {
        header('Location: user-login.php');
    }

?> -->