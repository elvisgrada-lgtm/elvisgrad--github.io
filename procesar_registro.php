<?php
// 1. Datos de conexión
$servidor = "localhost";
$usuario = "root"; 
$password_db = ""; 
$base_datos = "chocostarfish_db"; 

// Desactivar temporalmente los errores fatales visuales de mysqli para manejarlos nosotros
mysqli_report(MYSQLI_REPORT_OFF);

$conexion = new mysqli($servidor, $usuario, $password_db, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 2. Verificar datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['usuario_nombre'];
    $correo = $_POST['usuario_correo'];
    $password_form = $_POST['usuario_password'];

    $password_encriptada = password_hash($password_form, PASSWORD_BCRYPT);

    // 3. Insertar datos
    $sql = "INSERT INTO registro_usuarios (nombre, correo, password) VALUES ('$nombre', '$correo', '$password_encriptada')";

    if ($conexion->query($sql) === TRUE) {
        header("Location: dashboard.html?registro=exito");
        exit();
    } else {
        // Si el correo ya existe, muestra esto en vez de romperse
        if ($conexion->errno == 1062) {
            echo "<body style='background:#111; color:#fff; font-family:sans-serif; text-align:center; padding-top:50px;'>";
            echo "<h3>¡Aviso! El correo <strong>$correo</strong> ya está en uso.</h3>";
            echo "<a href='dashboard.html' style='color:#00ff00;'>Volver a intentar</a>";
            echo "</body>";
        } else {
            echo "Error: " . $conexion->error;
        }
    }
}

$conexion->close();
?>