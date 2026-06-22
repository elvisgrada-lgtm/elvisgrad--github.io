<?php
// datos.php

$nombre     = $_POST["nombre"] ?? '';
$contrasena = $_POST["contrasena"] ?? '';
$correo     = $_POST["correo"] ?? '';

// ... Aquí iría tu código para guardar en la base de datos ...

// Redireccionar a la otra página HTML
header("Location: index2.html");
exit; // Es vital poner exit para detener la ejecución del script aquí



// procesar_inscripcion.php

// 1. CONFIGURACIÓN DE LA CONEXIÓN A LA BASE DE DATOS
$host    = "localhost";
$db      = "control_escolar"; // Asegúrate de que este sea el nombre de tu base de datos
$user    = "root";            // Usuario por defecto en XAMPP
$pass    = "";                // Contraseña por defecto en XAMPP (vacía)
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Activa el reporte de errores graves
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Formato de datos en arreglos asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactiva la emulación para mayor seguridad
];

// 2. RECUPERAR LOS DATOS ENVIADOS DESDE INDEX2.HTML
// Usamos los nombres exactos que pusiste en el atributo 'name' de tus inputs
$nombre_completo = $_POST["name_complete"] ?? '';
$grado_cursar    = $_POST["degree_to_be_studied"] ?? '';
$edad            = $_POST["age"] ?? '';
$sexo            = $_POST["gender"] ?? ''; // Captura el radio button seleccionado ('femenino' o 'masculino')
$discapacidad    = $_POST["suffers_from_a_disability"] ?? '';

// Validación básica: Verificar que los campos obligatorios no estén vacíos
if (empty($nombre_completo) || empty($grado_cursar) || empty($edad) || empty($sexo) || empty($discapacidad)) {
    die("<h3>Error: Todos los campos del formulario de inscripción son obligatorios. Por favor, regresa y completa el formulario.</h3>");
}

try {
    // 3. CONECTAR A LA BASE DE DATOS
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 4. PREPARAR LA CONSULTA SQL DE INSERCIÓN
    // Usamos marcadores (:placeholder) para proteger la base de datos de ataques (Inyección SQL)
    $sql = "INSERT INTO alumnos (nombre_completo, grado_cursar, edad, sexo, discapacidad) 
            VALUES (:nombre_completo, :grado_cursar, :edad, :sexo, :discapacidad)";
    
    $stmt = $pdo->prepare($sql);

    // 5. EJECUTAR LA CONSULTA PASANDO LOS DATOS REALES
    $stmt->execute([
        ':nombre_completo' => $nombre_completo,
        ':grado_cursar'    => $grado_cursar,
        ':edad'            => (int)$edad, // Lo transformamos a número entero por seguridad
        ':sexo'            => $sexo,
        ':discapacidad'    => $discapacidad
    ]);

    // 6. MENSAJE DE ÉXITO (Puedes cambiar esto por una redirección si lo prefieres)
    echo "<div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h2 style='color: #27ae60;'>¡Inscripción Guardada Exitosamente!</h2>";
    echo "<p>Los datos del alumno <strong>" . htmlspecialchars($nombre_completo) . "</strong> han sido registrados en el sistema.</p>";
    echo "<a href='index2.html' style='display:inline-block; padding:10px 20px; background-color:#3498db; color:white; text-decoration:none; border-radius:4px; margin-top:20px;'>Inscribir otro alumno</a>";
    echo "</div>";

} catch (\PDOException $e) {
    // Si algo falla en la base de datos (por ejemplo, la tabla no existe), se captura aquí
    die("Error crítico al intentar guardar en la base de datos: " . $e->getMessage());
}
?>