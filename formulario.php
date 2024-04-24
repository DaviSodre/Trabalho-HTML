<?php

    if(isset($_POST['submit']))
    {
       // print_r('Nome: '. $_POST['nome']);
       // print_r('<br>');
       // print_r('Email: ' . $_POST['email']);
       // print_r('<br>');
       // print_r('Cidade: ' . $_POST['cidade']);
       // print_r('<br>');
       // print_r('Estado: '. $_POST['estado']);
       // print_r('<br>');
       // print_r('Sexo: ' . $_POST['gênero']);
       // print_r('<br>');
       // print_r('Data de Nascimento: ' . $_POST['data_nascimento']);
       // print_r('<br>');

       include_once('config.php');

       $nome = $_POST['nome'];
       $email = $_POST['email'];
       $estado = $_POST['estado'];
       $cidade = $_POST['cidade'];
       $sexo = $_POST['gênero'];
       $data_nasc = $_POST['data_nascimento'];
       $senha = $_POST['senha'];

       $result = mysqli_query($conexao, "INSERT INTO usuarios(usuario,email,sexo,data_nasc,cidade,estado,senha) 
       VALUES ('$nome','$email','$sexo','$data_nasc','$cidade','$estado', '$senha')") or die(mysqli_error($con));
       header('Location: login.php');
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
        }
        .box{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 15px;
            width: 20%;
            color: white;
        }
        fieldset{
            border: 3px solid dodgerblue;
        }

        legend{
            border: 1px solid dodgerblue;
            padding: 10px;
            text-align: center;
            background-color: dodgerblue;
            border-radius: 8px;
            color: white;
        }

        .inputBox{
            position: relative;

        }
        .inputUser{
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;

        }
        .labelInput{
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput,
        .inputUser:valid ~ .labelInput{
            top: -20px;
            font-size: 12px;
            color: dodgerblue;
        }

        #data_nascimento{
            border: none;
            padding: 8px;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        #submit{
            background-image: linear-gradient(to right, rgb(0, 92, 197), rgb(90, 20, 220));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            border-radius: 15px;
            font-size: 10px;
            cursor: pointer;
            outline: none;
        }
        #submit:hover{

            background-image: linear-gradient(to right, rgb(0, 80, 172), rgb(80, 19, 195));
        }
    </style>
</head>
<body>
    <div class="box">
        <form action="formulario.php" method="POST">
            <fieldset>
                <legend><b>Cadastro</b></legend>
                <br>
                <div class="inputBox">
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                    <label for="nome" class="labelInput">Usuário</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <input type="text" name="email" id="email" class="inputUser" required>
                    <label for="email" class="labelInput">Email</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <input type="password" name="senha" id="senha" class="inputUser" required>
                    <label for="senha" class="labelInput">Senha</label>
                </div>
                
                <p>Sexo:</p>
                <input type="radio" id="feminino" name="gênero" value="Feminino" required>
                <label for="feminino">Feminino</label>
                <br>

                <input type="radio" id="masculino" name="gênero" value="Masculino" required>
                <label for="masculino">Masculino</label>
                <br>

                <input type="radio" id="outro" name="gênero" value="outro" required>
                <label for="outro">Outro</label>

                <br><br>

                
                <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                <input type="date" name="data_nascimento" id="data_nascimento" required>
                    
               

                <br><br><br>

                <div class="inputBox">
                    <input type="text" name="cidade" id="cidade" class="inputUser" required>
                    <label for="cidade" class="labelInput">Cidade</label>
                </div>

                <br><br>

                <div class="inputBox">
                    <input type="text" name="estado" id="estado" class="inputUser" required>
                    <label for="estado" class="labelInput">Estado</label>
                </div>

                <br><br>


                <input type="submit" name="submit" id="submit">
            </fieldset>
        </form>
    </div>
</body>
<a href="home.php">Voltar</a>
</html>