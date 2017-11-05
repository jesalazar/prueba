<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Index</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <style type="text/css">
            a {
                cursor: pointer;
            }
            .selected {
                font-weight: bold;
            }
            .error {
                color: #4e36d2;
            }
        </style>
    </head>
    <body class="body">
        <?php
            // Obtiene estado de likes de las fotos de usuario en sesion
            function getState($likes, $row) {
                $votos = array_filter(
                    $likes,
                    function ($e) use ($row) {
                        return $e->foto_id == $row;
                    }
                );
                foreach ($votos as $voto) {
                    if ($voto->me_gusta == '1') {
                        return true;
                    } else {
                        return false;
                    }
                } 
            }
        ?>
        <!-- End Header -->
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-header">
                        <a class="navbar-brand" href="">
                            <?= ($userName) ? 'Bienvenido '.$userName.'!' : ''; ?>
                        </a>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar">
                    <?php if ($userId): ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" data-toggle="modal" data-target="#modalFormFotos"><span class="glyphicon glyphicon-picture"></span> Subir foto</a></li>
                        <li><a href="index.php/usuario/logout"><span class="glyphicon glyphicon-log-in"></span> Cerrar sesón</a></li>
                    </ul>
                    <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#" data-toggle="modal" data-target="#modalFormRegistro"><span class="glyphicon glyphicon-user"></span> Regístrate</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#modalFormLogin"><span class="glyphicon glyphicon-log-in"></span> Inicia sesón</a></li>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <!-- End Header -->
        <div class="container">
            <!-- Tabla Registros -->
            <table cellpadding="0" cellspacing="0" border="0" class="data-tables table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Descripción</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i=0; $i < count($row); $i++) { ?>
                        <tr>
                            <td><?= $row[$i]->id; ?></td>
                            <td class="text-center">
                                <img src="<?=$row[$i]->foto;?>" style="height: 170px;"><br>
                                <?php if ($userId): ?>
                                    <?php $status = getState($userLikes, $row[$i]->id); ?>
                                    <?php if ($status===true): ?>
                                        <a class="like selected" data-like="1" data-foto="<?=$row[$i]->id;?>" data-url="index.php/usuario/likes">Me gusta</a>
                                        <a class="like" data-like="0" data-foto="<?=$row[$i]->id;?>" data-url="index.php/usuario/likes">No me gusta</a>
                                    <?php elseif ($status===false): ?>
                                        <a class="like" data-like="1" data-foto="<?=$row[$i]->id;?>" data-url="index.php/usuario/likes">Me gusta</a>
                                        <a class="like selected" data-like="0" data-foto="<?=$row[$i]->id;?>" data-url="index.php/usuario/likes">No me gusta</a>
                                    <?php else: ?>
                                        <a class="like" data-insert="1" data-like="1" data-foto="<?=$row[$i]->id;?>" data-url="index.php/usuario/likes">Me gusta</a>
                                        <a class="like" data-insert="1" data-like="0" data-foto="<?=$row[$i]->id;?>" data-url="index.php/usuario/likes">No me gusta</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $row[$i]->descripcion; ?></td>
                            <td><?= $row[$i]->usuario_id; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- End Tabla Registros -->
            <?php if ($userId): ?>
            <!-- Modal Form Fotos -->
            <div class="modal fade" id="modalFormFotos" role="dialog" >
                <div class="modal-dialog" style="width: 400px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Sube fotos de tus mejores momentos</h4>
                        </div>
                        <form action="index.php/usuario/upload" class="form-horizontal" id="formFotos" method="POST" role="form" enctype="multipart/form-data" style="padding:0 30px;">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="descripcion" class="control-label">Descripción</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Describe el momento..." rows="10"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" name="foto" id="foto">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="btnRegistro" class="btn btn-primary">Subir foto</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal Form Fotos -->
            <?php else: ?>
            <!-- Modal Form Login -->
            <div class="modal fade" id="modalFormLogin" role="dialog">
                <div class="modal-dialog" style="width: 340px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Inicia sesión</h4>
                        </div>
                        <form action="index.php/usuario/login" class="form-horizontal" id="formLogin" method="POST" role="form" style="padding:0 30px;">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="email" class="control-label">Email:</label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Ingresa tu correo">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="pass" class="control-label">Contraseña:</label>
                                    <input type="password" name="pass" id="pass" class="form-control" placeholder="Ingresa tu contraseña">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" class="btn btn-primary" value="Iniciar sesión">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal Form Login -->
            <!-- Modal Registro -->
            <div class="modal fade" id="modalFormRegistro" role="dialog" >
                <div class="modal-dialog" style="width: 340px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Regístrate</h4>
                        </div>
                        <form action="index.php/usuario/newuser" class="form-horizontal" id="formRegistro" method="POST" role="form" style="padding:0 30px;">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="identificacion" class="control-label">Identificación</label>
                                    <input type="text" name="identificacion" id="identificacion" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre" class="control-label">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fecha_nacimiento" class="control-label">Fecha de nacimiento</label>
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="correo" class="control-label">Correo</label>
                                    <input type="email" name="correo" id="correo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="modelo_vehiculo" class="control-label">Modelo de vehículo</label>
                                    <input type="text" name="modelo_vehiculo" id="modelo_vehiculo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password" class="control-label">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                            </div>
                            <label id="back-error" class="error" style="display:none;"></label>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="btnRegistro" class="btn btn-primary">Registrarme</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal Registro -->
            <?php endif; ?>
        </div>
        <!-- Footer -->
        <div role="main" class="container">
            <hr>
            <footer>
                <h5 class="text-center">© 2017, Desarrollado por Jhon Salazar</h5>
            </footer>
        </div>
        <!-- End Footer -->
        <script type="text/javascript" src="assets/js/lib/jquery.js"></script>
        <script type="text/javascript" src="assets/js/lib/jquery.validate.min.js"></script>
        <script type="text/javascript" src="assets/js/lib/additional-methods.min.js"></script>
        <script type="text/javascript" src="assets/js/lib/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="assets/js/lib/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/lib/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/lib/toastr.min.js"></script>
        <script type="text/javascript" src="assets/js/main.js"></script>
        <script type="text/javascript" src="assets/js/validations.js"></script>
    </body>
</html>
