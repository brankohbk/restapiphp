<?php
class Usuario
{
  private $nombre;
  private $apellido;
  private $edad;
  private $fechaAgregado;

  public function __construct($nombre, $apellido, $edad, $fechaAgregado)
  {
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->edad = $edad;
    $this->fechaAgregado = $fechaAgregado;
  }

  // Getters
  public function getNombre()
  {
    return $this->nombre;
  }
  public function getApellido()
  {
    return $this->apellido;
  }
  public function getedad()
  {
    return $this->edad;
  }
  public function getfechaAgregado()
  {
    return $this->fechaAgregado;
  }

  // Setters
  public function setNombre($nombre)
  {
    $this->nombre = $nombre;

    return $this;
  }
  public function setApellido($apellido)
  {
    $this->apellido = $apellido;

    return $this;
  }
  public function setedad($edad)
  {
    $this->edad = $edad;

    return $this;
  }
  public function setfechaAgregado($fechaAgregado)
  {
    $this->fechaAgregado = $fechaAgregado;

    return $this;
  }


  // METODOS
  public function __toString()
  {
    return $this->nombre." ".$this->apellido." (".$this->edad.", ".$this->fechaAgregado.").";
  }

  public function guardarUsuario()
  {
    $contenidoArchivo=file_get_contents("../data/usuarios.json");
    $usuarios = json_decode($contenidoArchivo, true);
    $usuarios[] = array(
      "nombre"=> $this->nombre,
      "apellido"=> $this->apellido,
      "edad"=> $this->edad,
      "fechaAgregado"=> $this->fechaAgregado
    );
    $archivo = fopen('../data/usuarios.json','w');
    fwrite($archivo, json_encode($usuarios));
    fclose($archivo);
  }

  public function obtenerUsuario()
  {
    
  }

  public function actualizarUsuario()
  {
  }

  public function eliminarUsuario()
  {
  }


}
