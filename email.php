<?php
$message = '';

if (isset($_POST['email']) && !empty($_POST['email'])){
  if (mail($_POST['email'], $_POST['subject'], $_POST['body'], '')){
    $message = "El email se ha enviado a <b>".$_POST['email']."</b>.<br>";
  }else{
    $message = "Error al enviar el mensaje <b>".$_POST['email']."</b>.<br>";
  }
}else{
  if (isset($_POST['submit'])){
    $message = "Falta la direccion de email!<br>";
  }
}

if (!empty($message)){
  $message .= "<br><br>";
}
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
      Mail test
    </title>
  </head>
  <body>
    <?php echo $message; ?>
    <form method="post" action="">
      <table>
        <tr>
          <td>
            Direcci&oacute;n e-mail
          </td>
          <td>
            <input name="email" value="<?php if (isset($_POST['email'])
            && !empty($_POST['email'])) echo $_POST['email']; ?>">
          </td>
        </tr>
        <tr>
          <td>
            Asunto
          </td>
          <td>
            <input name="subject">
          </td>
        </tr>
        <tr>
          <td>
            Mensaje
          </td>
          <td>
            <textarea name="body"></textarea>
          </td>
        </tr>
        <tr>
          <td>
            &nbsp;
          </td>
          <td>
            <input type="submit" value="Enviar" name="submit">
          </td>
        </tr>
      </table>
    </form>
    <?php phpinfo(); ?>
  </body>
</html>
