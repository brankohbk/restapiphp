<?php
// //Para utilizar mysqli:
// $host = "localhost";
// $username ="root";
// $password ="";
// $dbname= "restAPI";
// $conn = new mysqli($host,$username,$password,$dbname);

// Para utilizar PDO (recomendable, dado que puede cambiarse el motor de BBDD):
class Connect extends PDO{
  public function __construct(){
    parent::__construct("mysql:host=localhost;dbname=restAPI", 'root', '',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }
}

// Función custom para ejecutar queries.
function tryQuery($query)
{
  $db = new Connect;
  $fetchedData = "";
  try {
    $response['status'] =  "Success";
    // Ejecuto la llamada a la BBDD.
    $fetchedData = $db->prepare($query);
    $fetchedData->execute();
    $count = $fetchedData->rowCount();
  } catch (PDOException $e) {
    $response['status'] =  "Error: " . $e->getMessage();
  }
  return array($response, $fetchedData, $count);
}