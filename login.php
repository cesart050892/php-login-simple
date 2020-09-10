<?php
  
  //Inicio de la sesión
  session_start();
  //Verficamos si la sesión tiene la variable requerida
  if (isset($_SESSION['user_id'])) {
    //Redirecionamos index.php
    header('Location: /php-login-simple');
  }
  //Requerimos la base de datos
  //para la comprobación de las credenciales
  require 'database.php';

  //Verificamos las variables POST
  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    //Hacemos la preparación de la consulta con la clausula email
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    //Emparejamos la variable protegida con POST[email]
    $records->bindParam(':email', $_POST['email']);
    //Ejecutamos la consulta
    $records->execute();
    //Guardamos el unico resultado fetch a result
    $results = $records->fetch(PDO::FETCH_ASSOC);

    //Iniciamos la variable message
    $message = '';
    //Verificamos que el resultado sea mayor que 1
    //y comparamos la variable POST con la respuesta de la base de datos
    if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
      //Creamos la variable de sesion y le asignamos el id
      $_SESSION['user_id'] = $results['id'];
      //redireciona a index.php
      header("Location: /php-login-simple");
    } else {
       //Mensaje de error de comprobacion de credenciales
      $message = 'Sorry, those credentials do not match';
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <!-- Header -->
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <h1>Login</h1>
    <span>or <a href="signup.php">SignUp</a></span>

    <form action="login.php" method="POST">
      <input name="email" type="text" placeholder="Enter your email" autocomplete="off">
      <input name="password" type="password" placeholder="Enter your Password" autocomplete="off">
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
