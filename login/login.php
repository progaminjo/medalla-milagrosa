<?php
session_start();
include '../Conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $password = $_POST['password'];

    // Consulta para obtener el usuario por cédula
    $sql = "SELECT id_user, password FROM user WHERE cedula = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($password, $row['password'])) {
            $user_id = $row['id_user'];
            $sql = "SELECT id_rol FROM rol_user WHERE id_user = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Error en la preparación de la consulta: ' . $conn->error);
            }
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $role_row = $result->fetch_assoc();
                $role_id = $role_row['id_rol'];

                $_SESSION['user_id'] = $user_id; // Almacena el ID del usuario en la sesión
                $_SESSION['role_id'] = $role_id; // Almacena el ID del rol en la sesión

                // Redirigir según el rol del usuario
                switch ($role_id) {
                    case 1:
                        header("Location: ../vistas/dashboard.php");
                        break;
                    case 2:
                        header("Location: ../vistas/1.php");
                        break;
                    case 3:
                        header("Location: ../vistas/inicio.php");
                        break;
                    default:
                        echo "Rol desconocido";
                        break;
                }
                exit();
            } else {
                echo "Rol no encontrado";
            }
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title>Formulario</title>
  <link rel="stylesheet" href="../assets/css/registro.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="../assets/js/funcions.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <div class="fadeIn first">
      <br>
      <img src="../assets/img/escudo.gif" id="icon" alt="User Icon" />
      <h1>Medalla Milagrosa</h1>
      <h3>INICIO SESION</h3>
    </div>
    <br><br><br>
    <form action="login.php" method="post">
      <div class="form-group mb-2 fadeIn second">
        <input type="text" id="cedula" class="form-control fadeIn second" name="cedula" placeholder="cedula" required>
      </div>
      <div class="form-group mb-4 fadeIn third">
        <input type="password" id="password" class="form-control fadeIn third" name="password" placeholder="Contraseña" required>
      </div>
      <button type="submit" class="btn btn-info mb-3 btn-block fadeIn fourth">Iniciar Sesión</button>
    </form>
    <div id="formFooter">
      <a class="underlineHover  text-info " href="registro.php">Registrarme</a>
    </div>
  </div>
</div>
<script src="../assets/js/trabajador.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
