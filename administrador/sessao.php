<?php 
error_reporting(0);
session_start("adm2.0");
session_name("adm2.0");
require_once("../configuracoes.php");

$codigoUsuario = $_SESSION['cod_usuario'];
$nomeUsuario = $_SESSION['nomeUsuario'];

// SELECIONA O USUÁRIO QUE ESTA LOGADO
$usuario = mysql_fetch_object(mysql_query("SELECT * FROM admin_usuarios WHERE codigo='{$codigoUsuario}'"));

// NOME DO BD PELO NOME DA PAGINA
$nomeDaPagina = (empty($nomeDaPagina) ? $sql->nomeDaPagina() : $nomeDaPagina);

// URL PADRAO
$urlPadrao = "listar_$nomeDaPagina.php?tabela=$tabela&p=$p&nomeDaPagina=$nomeDaPagina&tipo=$tipo&busca=$busca";

@require_once("dependencias.php");

 if(!isset($codigoUsuario)) { echo "<script>$(location).attr('href', '".$urlserver."login.php')</script>"; } ?>
