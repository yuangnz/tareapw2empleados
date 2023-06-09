<!DOCTYPE html>
<html>
<head>
  <title>Formulario de Datos Personales</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
    <h2>Formulario de empleados</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="nombre">Nombre y Apellido:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
      </div>
      <div class="form-group">
        <label for="edad">Edad:</label>
        <input type="number" class="form-control" id="edad" name="edad" required>
      </div>
      <div class="form-group">
        <label for="estado_civil">Estado Civil:</label>
        <select class="form-control" id="estado_civil" name="estado_civil" required>
          <option value="Soltero">Soltero</option>
          <option value="Casado">Casado</option>
          <option value="Viudo">Viudo</option>
        </select>
      </div>
      <div class="form-group">
        <label for="sexo">Sexo:</label>
        <select class="form-control" id="sexo" name="sexo" required>
          <option value="Femenino">Femenino</option>
          <option value="Masculino">Masculino</option>
        </select>
      </div>
      <div class="form-group">
        <label for="sueldo">Sueldo:</label>
        <select class="form-control" id="sueldo" name="sueldo" required>
          <option value="Menos de 1000$">Menos de 1000$</option>
          <option value="Entre 1000$ y 2500$">Entre 1000$ y 2500$</option>
          <option value="Mas de 2500$">Mas de 2500$</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <h2>Empleadosñs</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre y Apellido</th>
          <th>Edad</th>
          <th>Estado Civil</th>
          <th>Sexo</th>
          <th>Sueldo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        function obtenerDatos() {
          $datosJson = file_get_contents("datos.json");
          $datos = json_decode($datosJson, true);
          return $datos;
        }

        function guardarDatos($datos) {
          $datosJson = json_encode($datos);
          file_put_contents("datos.json", $datosJson);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $nombre = $_POST["nombre"];
          $edad = $_POST["edad"];
          $estadoCivil = $_POST["estado_civil"];
          $sexo = $_POST["sexo"];
          $sueldo = $_POST["sueldo"];

          $nuevoDato = array(
            "nombre" => $nombre,
            "edad" => $edad,
            "estado_civil" => $estadoCivil,
            "sexo" => $sexo,
            "sueldo" => $sueldo
          );

          $datos = obtenerDatos();
          $datos[] = $nuevoDato;
          guardarDatos($datos);
          header("Location: index.php");
          exit();
        }

        $datos = obtenerDatos();

        foreach ($datos as $dato) {
          echo "<tr>";
          echo "<td>" . $dato["nombre"] . "</td>";
          echo "<td>" . $dato["edad"] . "</td>";
          echo "<td>" . $dato["estado_civil"] . "</td>";
          echo "<td>" . $dato["sexo"] . "</td>";
          echo "<td>" . $dato["sueldo"] . "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <h2>Estadísticas</h2>
    <?php
    $totalFemenino = 0;
    $totalHombresCasados = 0;
    $totalMujeresViudas = 0;
    $totalEdadHombres=0;
    $contadorHombres=0;

    foreach ($datos as $dato) {
      if ($dato["sexo"] == "Femenino") {
        $totalFemenino++;
      }

      if ($dato["sexo"] == "Masculino" && $dato["estado_civil"] == "Casado" && $dato["sueldo"] == "Mas de 2500$") {
        $totalHombresCasados++;
      }

      if ($dato["sexo"] == "Femenino" && $dato["estado_civil"] == "Viudo" && $dato["sueldo"] == "Mas de 1000$") {
        $totalMujeresViudas++;
      }
      if ($dato["sexo"] == "Masculino") {
        $totalEdadHombres += $dato["edad"];
        $contadorHombres++;
      }
    }

    echo "<p>Total de empleados del sexo femenino: " . $totalFemenino . "</p>";
    echo "<p>Total de hombres casados que ganan más de 2500$: " . $totalHombresCasados . "</p>";
    echo "<p>Total de mujeres viudas que ganan más de 1000$: " . $totalMujeresViudas . "</p>";
    if ($contadorHombres > 0) {
        $promedioEdadHombres = $totalEdadHombres / $contadorHombres;
        echo "<p>Edad promedio de los hombres: " . $promedioEdadHombres . "</p>";
      } else {
        echo "<p>No se encontraron hombres registrados.</p>";
      }
    ?>
  </div>
</body>
</html>
