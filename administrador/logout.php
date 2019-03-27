<?php session_start('adm2.0');

unset($_SESSION['cod_usuario']);
unset($_SESSION['nomeUsuario']); ?>

<script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
<?php echo "<script>$(location).attr('href', 'login.php')</script>";