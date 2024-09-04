<?php
include '../Conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contacto = $_POST['contacto'];
    $cedula = $_POST['cedula'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Crea un hash seguro de la contraseña
    $role_id = $_POST['role_id'];

    // Verificar si el role_id existe en la tabla roles
    $sql = "SELECT id_rol FROM rol WHERE id_rol = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $role_id); // Asigna el valor de role_id al parámetro en la consulta
    $stmt->execute(); // Ejecuta la consulta
    $result = $stmt->get_result(); // Obtiene el resultado de la consulta

    if ($result->num_rows > 0) { // Verifica si se encontró el role_id
        // Verificar si la cédula ya existe
        $sql = "SELECT id_user FROM user WHERE cedula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cedula); // Asigna el valor de cedula al parámetro en la consulta
        $stmt->execute(); // Ejecuta la consulta
        $result = $stmt->get_result(); // Obtiene el resultado de la consulta

        if ($result->num_rows == 0) { // Verifica si no se encontró la cédula (es decir, si es única)
            // Insertar usuario si la cédula no existe
            $sql = "INSERT INTO user (nombre, apellido, contacto, cedula, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nombre, $apellido, $contacto, $cedula, $password); // Asigna los valores a los parámetros en la consulta

            if ($stmt->execute() === TRUE) { // Verifica si la inserción del usuario fue exitosa
                // Obtener el ID del usuario insertado
                $user_id = $conn->insert_id;

                // Insertar en la tabla rol_user
                $sql = "INSERT INTO rol_user (id_user, id_rol) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $role_id); // Asigna los valores a los parámetros en la consulta

                if ($stmt->execute() === TRUE) { // Verifica si la inserción en rol_user fue exitosa
                    echo "<script>swal('Éxito', 'Usuario registrado exitosamente', 'success');</script>";
                } else {
                    echo "<script>swal('Error', 'Error al asignar el rol: " . $stmt->error . "', 'error');</script>";
                }
            } else {
                echo "<script>swal('Error', 'Error al registrar el usuario: " . $stmt->error . "', 'error');</script>";
            }
        } else {
            echo "<script>swal('Error', 'La cédula ya está en uso.', 'error');</script>";
        }
    } else {
        echo "<script>swal('Error', 'El role_id proporcionado no existe.', 'error');</script>";
    }

    $stmt->close(); // Cierra el statement
    $conn->close(); // Cierra la conexión a la base de datos
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
  <title>Gucobro Property Management</title>
  <link rel="stylesheet" href="../assets/css/registro.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="../assets/js/funcions.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <style>

  </style>
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <div class="fadeIn first text-center">
      <br>
      <img src="../assets/img/escudo.gif" id="icon" alt="User Icon" class="mb-3" />
      <h1>Medalla Milagrosa</h1>
      <h3>Registro Usuario</h3>
    </div>
    <br><br>
    <form action="registro.php" method="post">
      <div class="row">
        <div class="form-group col-md-6 fadeIn second">
          <input type="text" id="nombre" class="form-control" name="nombre" placeholder="Nombre" required>
        </div>
        <div class="form-group col-md-6 fadeIn second">
          <input type="text" id="apellido" class="form-control" name="apellido" placeholder="Apellido" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6 fadeIn third">
          <input type="text" id="contacto" class="form-control" name="contacto" placeholder="Contacto" required>
        </div>
        <div class="form-group col-md-6 fadeIn third">
          <input type="text" id="cedula" class="form-control" name="cedula" placeholder="Cédula" required>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6 fadeIn fourth">
          <select name="role_id" id="role_id" class="form-control">
            <option selected disabled>Seleccione su rol</option>
            <option value="1">coordinador</option>
            <option value="2">maestro</option>
            <option value="3">estudiante  </option>
          </select>
        </div>
        <div class="form-group col-md-6 fadeIn fourth">
          <input type="password" id="password" class="form-control" name="password" placeholder="Contraseña" required>
        </div>
 
      </div>
      <br>
      <div class="form-group  fadeIn fourth">
        <button type="submit" class="btn btn-info btn-block">Registrar</button>
      </div>
      <br>
    </form>
    <div id="formFooter">
      <a class="underlineHover text-info" href="login.php">¿Ya tienes una cuenta? Inicia sesión</a>
    </div>
  </div>
</div>
<script src="../assets/js/trabajador.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
