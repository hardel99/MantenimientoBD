<?php
  /*
  @uthor: Harold Delgado
  date: 4/12/2018
  */

  //Creando la conexion
  $connection = mysqli_connect('localhost','root','','colegio');

  if ($connection -> connect_errno) {
    printf("Falló la conexión: %s\n", $connection -> connect_error);
    exit();
  }

  function tipoARegistro($value) {
    $converted = "";

    switch ($value) {
      case 'parvularia':
        $converted = "P";
        break;
      case 'basica':
        $converted = "B";
        break;
      case 'media':
        $converted = "M";
        break;
    }

    return $converted;
  }

  function estadoARegistro($valEst) {
    $est = ($valEst == "activo")? "a": "i";
    return $est;
  }

  //Asegurarnos que accion se ejecutara
  if(isset($_POST['accion'])){
    if ($_POST['accion'] == "ingresar") {
      //preparando la data
      $tipo = tipoARegistro($_POST['tipo']);
      $estado = estadoARegistro($_POST['estado']);

      //Insertando datos
      if (!$nuevo = $connection -> query("INSERT INTO grado VALUES('','". $_POST['nombre'] ."','". $tipo ."', '". $estado ."');")){
        echo "Error al insertar los datos! ". $connection -> error;
      }
    } else if($_POST['accion'] == "actualizar") {
      //Actualizar

      //Preparando datos para registro
      $tipoUp = tipoARegistro($_POST['tipoUp']);
      $estadoUp = estadoARegistro($_POST['estadoUp']);

      if (!$updat = $connection -> query("UPDATE grado SET nombregra='". $_POST['nombreUp'] ."', nivel='". $tipoUp ."', estadogra='". $estadoUp ."' WHERE idgra=". $_POST['idUp'] .";")){
        echo "Error al insertar los datos! ". $connection -> error;
      }
    } else if($_POST['accion'] == "eliminar") {
      //Eliminar
      if (!$delet = $connection -> query("DELETE FROM grado WHERE idgra=". $_POST['idDel'].";")){
        echo "Error al insertar los datos! ". $connection -> error;
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="css/master.css">
  <title>Mantenimiento</title>
</head>
<body>
  <h1 class="display-1 mx-1 mt-3 px-5">Mantenimiento de Grado</h1>

  <div class="container">
    <div class="contain-search my-5">
      <div class="header searcher px-3 py-1">
        <h2>Registro de Grado</h2>
      </div>
      <form action="index.php" method="post">
        <input type="hidden" name="accion" value="ingresar">

        <div class="entradas mt-4 px-5 py-2 row">
          <div class="col-sm-4">
            <label for="nombre">Nombre: </label>
            <input required class="form-control" type="text" id="nombre" name="nombre" value="" placeholder="Ingrese los datos">
          </div>

          <div class="col-sm-4">
            <label for="tipo">Tipo: </label>
            <select required class="form-control" id="tipo" name="tipo">
              <option value="parvularia">Parvularia</option>
              <option value="basica">Basica</option>
              <option value="media">Media</option>
            </select>
          </div>

          <div class="col-sm-4">
            <label for="estado">Estado: </label>
            <select required class="form-control" id="estado" name="estado">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>

        <div class="row mt-3 px-5">
          <div class="col-md-2">
            <button class="btn" type="submit" name="button" onclick="insert()"><i class="fas fa-check"></i> Ingresar</button>
          </div>
          <div class="col-md-2">
            <button class="btn" type="reset"><i class="fas fa-ban"></i> Cancelar</button>
          </div>
        </div>
      </form>
    </div>

    <table class="table">
        <th scope="col">#</th>
        <th scope="col">Nombre</th>
        <th scope="col">Tipo</th>
        <th scope="col">Estado</th>
        <th scope="col">Acciones</th>
      </thead>
      <tbody>
        <?php
          if($data = $connection -> query("SELECT * FROM grado")){
            while($rows = $data -> fetch_array(MYSQLI_ASSOC)){

              $nivel = "";
              $estado = ($rows['estadogra'] == 'i')? "INACTIVO": "ACTIVO";
              if($rows['nivel'] == 'P'){
                $nivel = "PARVULARIA";
              }else {
                $nivel = ($rows['nivel'] == 'B')? "BASICA": "MEDIA";
              }

              echo "<tr>
                      <th scope='row'>". $rows['idgra'] ."</th>
                      <td>". $rows['nombregra'] ."</td>
                      <td>". $nivel ."</td>
                      <td>". $estado ."</td>
                      <td><a class='left' href='#' onclick='arUShure(". $rows['idgra'] .", \".delete\")'><i class='fas fa-eraser'></i> Eliminar</a> <a href='#' class='right' onclick='arUShure(". $rows['idgra'] .", \".update\"), fullUpdate(\"". $rows['nombregra'] ."\", \"". $nivel ."\", \"". $estado ."\")'><i class='fas fa-pen'></i> Actualizar</a></td>
                    </tr>";
            }
          }else {
            echo "No hay datos que mostrar";
          }
       ?>
      </tbody>
    </table>
  </div>
  <div class="update" id='update'>
    <div class="frame px-5 py-5">
      <h1><i class='fas fa-pen'></i> Actualizar</h1>

      <form action="index.php" method="post">
        <input type="hidden" name="idUp" id="idHaid">
        <input type="hidden" name="accion" value="actualizar">

        <div class="row my-4">
          <div class="col-sm-4">
            <label for="nombreHaid">Nombre: </label>
            <input class="form-control" id="nombreHaid" type="text" name="nombreUp" value="">
          </div>

          <div class="col-sm-4">
            <label for="tipoHaid">Tipo: </label>
            <select required class="form-control" id="tipoHaid" name="tipoUp">
              <option value="parvularia">Parvularia</option>
              <option value="basica">Basica</option>
              <option value="media">Media</option>
            </select>
          </div>

          <div class="col-sm-4">
            <label for="estadoHaid">Estado: </label>
            <select required class="form-control" id="estadoHaid" name="estadoUp">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>

        <div class="row haiden my-5">
          <div class="col-sm-3">
            <button type="submit" class="btn">Aceptar</button>
          </div>
          <div class="col-sm-3">
            <button type="reset" class="btn" onclick="disapir('update')">Cancelar</button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <div class="delete" id="delete">
    <div class="frame px-5 py-5">
      <h1><i class="fas fa-question"></i> ¿Estas seguro de eliminar?</h1>

      <form action="index.php" method="post">
        <input type="hidden" name="idDel" id="idDel">
        <input type="hidden" name="accion" value="eliminar">

        <div class="row haiden my-5">
          <div class="col-sm-3">
            <button type="submit" class="btn">Eliminar</button>
          </div>
          <div class="col-sm-3">
            <button type="button" class="btn" onclick="disapir('delete')">Cancelar</button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script type="text/javascript">

    function arUShure(id, frame) {
      document.querySelector(frame).classList.add('hidden');

      if(frame == ".update"){
        document.querySelector('#idHaid').value = id;
      }else {
        document.querySelector('#idDel').value = id;
      }
    }

    function fullUpdate(name, tipo, estado) {
      var tip, est;

      switch (tipo) {
        case 'PARVULARIA':
          tip = 0;
          break;
        case 'BASICA':
          tip = 1;
          break;
        case 'MEDIA':
          tip = 2;
          break;
      }

      switch (estado) {
        case 'ACTIVO':
          est = 0;
          break;
        case 'INACTIVO':
          est = 1;
          break;
      }

      document.querySelector('#nombreHaid').value = name;
      document.querySelector('#tipoHaid').selectedIndex = tip;
      document.querySelector('#estadoHaid').selectedIndex = est;
    }

    function disapir(identifier) {
      document.getElementById(identifier).classList.remove('hidden');
    }

    function insert() {
      var nombre = document.querySelector('#nombre').value;

      if(nombre.length === 0){
        alert("Por favor complete los datos");
      }
    }
  </script>
</body>
</html>
<?php
  //cerrar la conexion
  $connection -> close();
?>
