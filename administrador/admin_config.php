<?php require_once("sessao.php");
$nomeDaPagina = "admin_config";

$row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina")); 

if($acaoForm=="editar") { 
	mysql_query("UPDATE $nomeDaPagina SET titulo='$titulo', keywords='$keywords', descricao='$descricao', urlserver='$urlserver', endereco='$endereco', telefone='$telefone', celular='$celular', email='$email', facebook='$facebook', instagram='$instagram' WHERE codigo='1'");

	echo "<script>$('#modal-sucess-1').modal('show');</script>";
} ?>

<link rel="stylesheet" href="css/adicionar.css">
<link rel="stylesheet" href="css/responsive.css">

<div class="modal fade" id="modal-sucess-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Alterações efetuadas com sucesso!</div>
        </div>
    </div>
</div>

<div class="content adicionar-bg">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item active">Configurações do site</li>
	</ol>

	<div class="container-fluid">
		<div class="col-md-12"><h1>Configurações do <b>site</b></h1></div>
		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" autocomplete="off" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="editar" />
				<div class="col-md-7 form-group">
					<label for="titulo">Título</label>
					<input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $row->titulo; ?>" required>
				</div>
				<div class="col-md-5 form-group">
					<label for="keywords">Palavras-chave</label>
					<input type="text" class="form-control" id="keywords" name="keywords" value="<?php echo $row->keywords; ?>" required>
				</div>
				<div class="col-md-12 form-group">
					<label for="descricao">Descrição</label>
					<input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $row->descricao; ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="endereco">Endereço físico</label>
					<input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo $row->endereco; ?>">
				</div>
				<div class="col-md-3 form-group">
					<label for="telefone">Telefone</label>
					<input type="tel" class="form-control" id="telefone" name="telefone" value="<?php echo $row->telefone; ?>">
				</div>
				<div class="col-md-3 form-group">
					<label for="celular">Celular</label>
					<input type="tel" class="form-control" id="celular" name="celular" value="<?php echo $row->celular; ?>">
				</div>
				<div class="col-md-5 form-group">
					<label for="email">E-mail</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $row->email; ?>" required>
				</div>
				<div class="col-md-7 form-group has-danger">
					<label for="email">URL Padrão</label>
					<input type="text" class="form-control" id="urlserver" name="urlserver" value="<?php echo $row->urlserver; ?>" required>
 					<small class="form-text text-muted">Cuidado ao alterar este campo, ele define a URL de acesso ao site. Sempre termine com /</small>
				</div>
				<div class="col-md-6 form-group">
					<label for="facebook">Facebook</label>
					<input type="tel" class="form-control" id="facebook" name="facebook" value="<?php echo $row->facebook; ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="instagram">Instagram</label>
					<input type="tel" class="form-control" id="instagram" name="instagram" value="<?php echo $row->instagram; ?>">
				</div>

				<div class="col-md-12 adicionar-buttons" align="center"><button type="submit">Salvar</button></div>
			</form>
		</div>
	</div>
</div>