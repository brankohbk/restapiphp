<?php
require_once __DIR__ . ("/../../config.php");
// Seteo los headers.
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json; charset=utf-8');

// Recibir peticiones del cliente
// require_once __DIR__ . ("/../clases/class-usuario.php");


// Selecciono la tabla.
$table = "customers";

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    // Defino el array que voy a devolver.
    $array = array();
    if (isset($_GET['id'])) {

      $response['mensaje'] =  "Obtener el usuario con el id " . $_GET['id'];

      $query = 'SELECT * FROM ' . $table . ' WHERE id=' . $_GET['id'] . ';';
      $data = tryQuery($query);
      if ($data[0]["status"] == "Success") {
        // Mientras haya registros (rows), armo un objeto con ese registro.
        while ($row = $data[1]->fetch(PDO::FETCH_ASSOC)) {
          $row_array['id'] = $row['id'];
          $row_array['name'] = $row['name'];
          $row_array['age'] = $row['age'];
          $row_array['addedOn'] = $row['addedOn'];
          // Pusheo ese registro al array que voy a devolver.
          array_push($array, $row_array);
        }
      }
    } else {

      $response['mensaje'] =  "Obtener todos los usuarios ";

      $query = 'SELECT * FROM ' . $table . ' ORDER BY id';
      $data = tryQuery($query);
      if ($data[0]["status"] == "Success") {
        // Mientras haya registros (rows), armo un objeto con ese registro.
        while ($row = $data[1]->fetch(PDO::FETCH_ASSOC)) {
          $row_array['id'] = $row['id'];
          $row_array['name'] = $row['name'];
          $row_array['age'] = $row['age'];
          $row_array['addedOn'] = $row['addedOn'];
          // Pusheo ese registro al array que voy a devolver.
          array_push($array, $row_array);
        }
      }
    }
    $response['status'] = $data[0]["status"];
    if ($data[2] < 1) $response['status'] = "There is no item with id=" . $_GET['id'] . ". Nothing happened.";
    $response['data'] =  $array;

    echo json_encode($response);
    break;

  case 'POST':
    $response['mensaje'] = "Guardar usuario ";
    // Decodificar el json recibido por POST. 
    $_POST = json_decode(file_get_contents('php://input'), true);
    if (isset($_POST['name']) && isset($_POST['age'])) {
      $query = 'INSERT INTO ' . $table . ' (`name`,`age`,`addedOn`) VALUES ("' . $_POST['name'] . '",' . $_POST['age'] . ', NOW());';
      $data = tryQuery($query);
    } else {
      $response['status'] =  "Error: Complete all fields";
      exit(json_encode($response));
    }
    $response['status'] = $data[0]["status"];
    echo json_encode($response);
    break;

  case 'PUT':
    $response['mensaje'] =  "Actualizar el usuario dado un id.";
    // Chequear que pase el id por url param.
    if (!isset($_GET['id']) || !$_GET['id']) {
      $response['status'] =  "Error: Unknown id. Check you URI";
      exit(json_encode($response));
    }
    $response['mensaje'] .= " El id a utilizar es " .  $_GET['id'] . ".";

    // Guardar en la variable $_PUT el contenido del json recibido.
    $_PUT = json_decode(file_get_contents('php://input'), true);

    // Chequear que esté seteado el nombre o la edad
    if (isset($_PUT['name']) || isset($_PUT['age'])) {
      // Si ambos están seteados, cambiarlos en la BBDD.
      if ($_PUT['name'] && $_PUT['age']) {
        $query = 'UPDATE `' . $table . '` SET `name`="' . $_PUT['name'] . '", `age`=' . $_PUT['age'] . ' WHERE id=' . $_GET['id'] . ';';
        $data = tryQuery($query);
      } else if ($_PUT['name']) {
        // Si está seteado sólo el nombre, cambiarlo en la BBDD.
        $query = 'UPDATE `' . $table . '` SET `name`="' . $_PUT['name'] . '" WHERE id=' . $_GET['id'] . ';';
        $data = tryQuery($query);
      } else if ($_PUT['age']) {
        // Si está seteada sólo la edad, cambiarla en la BBDD.
        $query = 'UPDATE `' . $table . '` SET `age`=' . $_PUT['age'] . ' WHERE id=' . $_GET['id'] . ';';
        $data = tryQuery($query);
      }
    } else {
      // Si no está seteado ni nombre ni edad, no hacer nada.
      $response['status'] =  "Error: No name nor age have been received.";
    }
    // Avisar si ambos campos están vacíos.
    $response['status'] = isset($data[0]["status"]) ? $data[0]["status"] : "Error: At least one field must contain data.";

    if (isset($data[2]) && $data[2] < 1) $response['status'] = "There is no item with id=" . $_GET['id'] . ". Nothing happened.";
    $response['data'] =  $_PUT;
    echo json_encode($response);
    break;

  case 'DELETE':
    $response['mensaje'] =  "Eliminar un usuario dado un id.";
    // Chequear que pase el id por url param.
    if (!isset($_GET['id']) || !$_GET['id']) {
      $response['status'] =  "Error: Unknown id. Check you URI";
      exit(json_encode($response));
    }
    $response['mensaje'] .= " El id a utilizar es " .  $_GET['id'] . ".";
    $query = 'DELETE FROM `' . $table . '` WHERE `id`=' .  $_GET['id'] . ';';
    $data = tryQuery($query);

    $response['status'] = $data[2] < 1 ? "There is no item with id=" . $_GET['id'] . ". Nothing happened." : $data[0]["status"];
    echo json_encode($response);
    break;
}

