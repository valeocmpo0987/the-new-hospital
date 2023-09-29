<?php
// Incluye el archivo de conexión a la base de datos (conn.php)
include 'php/conn.php';

// Inicia la sesión (debe hacerse al comienzo de tu script)
session_start();

// Verifica si se ha enviado el formulario
if (isset($_POST['guardar'])) {
    // Obtiene los valores del formulario
    $nombres = $_POST['nombres'];
    $documento = $_POST['documento'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para insertar un nuevo usuario en la tabla "usuarios"
    $sql = "INSERT INTO usuarios (nombres, documento, telefono, usuario, contrasena) VALUES (:nombres, :documento, :telefono, :usuario, :contrasena)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':nombres', $nombres);
    $stmt->bindParam(':documento', $documento);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasena', $contrasena);

      // Ejecutar la consulta
    if ($stmt->execute()) {
      // Registro exitoso, muestra una alerta
      echo "<script>alert('Usuario registrado exitosamente.');</script>";
    } else {
      // Error en el registro, muestra una alerta de error
      echo "<script>alert('Error al registrar el usuario: " . $stmt->errorInfo()[2] . "');</script>";
    }
}

// Verifica si se ha enviado el formulario de inicio de sesión
if (isset($_POST['ingresar'])) {
    // Obtiene el valor del nombre de usuario y la contraseña del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para buscar al usuario por su nombre de usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->execute();
    $usuarioEncontrado = $stmt->fetch();

    // Verifica si se encontró un usuario con el nombre de usuario proporcionado
    if ($usuarioEncontrado) {
        // Verifica si la contraseña proporcionada coincide con la contraseña almacenada
        if ($contrasena === $usuarioEncontrado['contrasena']) {
            // Inicio de sesión exitoso, establece una sesión para el usuario
            $_SESSION['usuario'] = $usuarioEncontrado['usuario'];

            // Redirige al usuario a otra página, como el panel de control
            header("Location: /conmed/admin/index.php");
            exit();
        }
    }

    // Credenciales incorrectas, almacena un mensaje de error en la sesión
    $_SESSION['error'] = "Credenciales incorrectas. Por favor, inténtalo nuevamente.";

    // Redirige al usuario de nuevo al inicio de sesión
    header("Location: ./login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión</title>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap'>
  <link rel="stylesheet" href="css/style_login.css">
</head>
<body>
    <?php
    // Verifica si hay un mensaje de error en la sesión
    if (isset($_SESSION['error'])) {
        // Muestra el mensaje de error
        echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
        // Limpia el mensaje de error de la sesión
        unset($_SESSION['error']);
    }
    ?>
  <br>
  <br>
  <div class="cont">
    <form method="POST" action="">
      <div class="form sign-in">
        <h2>Bienvenid@</h2>
        <label>
          <span>Usuario</span>
          <input type="text" name="usuario" id="usuario" />
        </label>
        <label>
          <span>Contraseña</span>
          <input type="password" name="contrasena" id="contrasena" />
        </label>
        <p class="forgot-pass"><a href="index.html">Volver a inicio</a></p>
        <button type="submit" class="submit" name="ingresar">Ingresar</button>
      </div>
    </form>
    <form method="POST" action="">
      <div class="sub-cont">
        <div class="img">
          <div class="img__text m--up">
            <h3>¿No tiene una cuenta? Por favor, ¡regístrese!</h3>
          </div>
          <div class="img__text m--in">
            <h3>Si ya tiene una cuenta, sólo tiene que iniciar sesión.</h3>
          </div>
          <div class="img__btn">
            <span class="m--up">Registro</span>
            <span class="m--in">Ingresar</span>
          </div>
        </div>
        <div class="form sign-up">
          <h2>Cree su cuenta</h2>
          <label>
            <span>Nombre completo</span>
            <input type="text" name="nombres" id="nombres" />
          </label>
          <label>
            <span>Documento</span>
            <input type="number" name="documento" id="documento" />
          </label>
          <label>
            <span>Teléfono</span>
            <input type="number" name="telefono" id="telefono" />
          </label>
          <label>
            <span>Usuario</span>
            <input type="text" name="usuario" id="usuario" />
          </label>
          <label>
            <span>Contraseña</span>
            <input type="password" name="contrasena" id="contrasena" />
          </label>
          <p class="forgot-pass"><a href="index.html">Volver a inicio</a></p>
          <button type="submit" class="submit" name="guardar">Crear cuenta</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    document.querySelector('.img__btn').addEventListener('click', function() {
      document.querySelector('.cont').classList.toggle('s--signup');
    });
  </script>
</body>
</html>
