<?php require_once("sessao.php");

if($acao=="editar") { $row = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE codigo='$codigo'")); } ?>

<link rel="stylesheet" href="css/adicionar.css">
<link rel="stylesheet" href="css/responsive.css">

<div class="modal fade" id="modal-senha-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Para sua segurança, é proíbido utilizar senhas fáceis como 123456, 000000/999999, etc..</div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-email-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Este e-mail já existe no administrador!</div>
        </div>
    </div>
</div>

<div class="content adicionar-bg">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="capa.php" target="paginaTarget">Página Inicial</a></li>
		<li class="breadcrumb-item hidden-xs-down"><a href="<?php echo $urlPadrao; ?>" target="paginaTarget">Listagem de <?php echo $sql->admPagina($tabela); ?></a></li>
		<li class="breadcrumb-item active"><?php echo ucfirst($acao)." ".$sql->admPagina($tabela); ?></li>
	</ol>

	<div class="container-fluid">
		<div class="col-md-12"><h1><?php echo ucfirst($acao)." <b>".$sql->admPagina($tabela); ?></b></h1></div>
		<div class="container adicionar">
			<form id="form-adicionar" name="form-adicionar" method="post" action="" autocomplete="off" enctype="multipart/form-data">
				<input id="acaoForm" name="acaoForm" type="hidden" value="<?php echo $acao; ?>" />
				<div class="col-md-5 form-group">
					<input type="file" id="input-file-now" name="arquivo" class="dropify" <?php echo ($row->foto<>'' ? "data-default-file='{$row->foto}'" : ''); ?> data-max-file-size="1M" data-allowed-file-extensions="jpg png" />
				</div>
				<div class="col-md-7 form-group">
					<label for="nome">Nome</label>
					<input type="text" class="form-control" id="nome" name="nome" value="<?php echo $row->nome; ?>" required>
				</div>
				<div class="col-md-4 form-group">
					<label for="email">E-mail de acesso</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $row->email; ?>" required>
				</div>
				<div class="col-md-3 form-group">
					<label for="senha">Senha</label>
					<input type="password" class="form-control" id="senha" name="senha" value="<?php echo $row->senha; ?>" minlength="6" required>
					<small id="passwordHelpInline" class="text-muted">6 a 20 dígitos</small>
				</div>
				<div class="col-md-7 form-group">
					<label>Permissões do Usuário</label>
					<div class="row">
						<div class="col-sm-6 form-group">
							<label class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="selecionarTudo" name="selecionarTudo" onclick="$('input[name=\'codigoTabela[]\']').each(function() { $(this).prop('checked', true) });">
								<span class="custom-control-indicator"></span>
								<span class="custom-control-description">Selecionar todos</span>
							</label>
						</div>
						<?php $query_pe = mysql_query("SELECT * FROM admin_tabelas ORDER BY codigo ASC");
						while($row_pe = mysql_fetch_object($query_pe)) { 
							$verificaP = mysql_num_rows(mysql_query("SELECT * FROM admin_permissoes WHERE cod_usuario='$codigo' AND cod_tabela='$row_pe->codigo'")); ?>
							<div class="col-sm-6 form-group">
								<label class="custom-control custom-checkbox">
									<input value="<?php echo $row_pe->codigo; ?>" type="checkbox" class="custom-control-input" id="codigoTabela<?php echo $row_pe->codigo; ?>" name="codigoTabela[]" <?php echo ($verificaP<>0 ? 'checked' : ''); ?>>
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description"><?php echo $row_pe->item; ?></span>
								</label>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-12 adicionar-buttons" align="center">
					<button type="button" onclick="$(location).attr({'href':'<?php echo $urlPadrao; ?>', 'target':'paginaTarget'});">Cancelar</button>
					<button type="submit">Salvar</button>
				</div>

			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
$('.dropify').dropify({
	messages: {
        'default': 'Arraste e solte um arquivo aqui ou clique para selecionar',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
      	'error':   'Ooops, algo errado foi anexado.'
	}
});
$('input[id="input-file-now"]').change(function(ev) { $('#paginaTarget').height($('#paginaTarget').contents().height()); });
</script>

<?php 

// DIRETORIO DO ARQUIVO ANEXO
$diretorio = "fotos_".$sql->nomeDaPagina()."/";

if(isset($acaoForm)) {
	$selectUsuario = mysql_fetch_object(mysql_query("SELECT * FROM $nomeDaPagina WHERE email='{$email}'"));

	// INSERE NOVO REGISTRO NO BANCO
	if($acaoForm=="adicionar") {
		// VERIFICA SE A SENHA NAO TEM 123456
		if(strpos($senha,"123456")!==false) { echo "<script>$('#modal-senha-error').modal('show');</script>"; exit; }
		if($selectUsuario->email<>'') { echo "<script>$('#modal-email-error').modal('show');</script>"; exit; }

		mysql_query("INSERT INTO $nomeDaPagina (nome, email, senha) VALUES ('$nome', '$email', '$senha')");
		$lastid = mysql_fetch_array(mysql_query("SELECT LAST_INSERT_ID()"));
		$codigo = $lastid[0];

		sort($codigoTabela, SORT_NUMERIC);
		foreach($codigoTabela as $key=>$cod_tabela) {
			$query_verificaUsuario = mysql_query("SELECT * FROM admin_permissoes WHERE cod_usuario='$codigo' AND cod_tabela='$cod_tabela'");
			$total_verificaUsuario = mysql_num_rows($query_verificaUsuario);

			if($total_verificaUsuario==0) { 
				mysql_query("INSERT INTO admin_permissoes (cod_usuario, cod_tabela) VALUES ('$codigo', '$cod_tabela')");
			}
		}

		// ENVIA O ARQUIVO SE EXISTIR
		if(!empty($_FILES)) {
			$arquivoTemporario = $_FILES['arquivo']['tmp_name'];
			$arquivoParts  = pathinfo($_FILES['arquivo']['name']);
			$extensao = $arquivoParts['extension'];
			$destinoFinal = $diretorio.$codigo.".".$extensao;

			move_uploaded_file($arquivoTemporario, $destinoFinal);
			mysql_query("UPDATE $nomeDaPagina SET foto='".str_replace("../", "", $destinoFinal)."' WHERE codigo='$codigo'");
		}
		
	}
	// ALTERA UM REGISTRO NO BANCO
	if($acaoForm=="editar") { 
		// VERIFICA SE A SENHA NAO TEM 123456
		if(strpos($senha,"123456")!==false) { echo "<script>$('#modal-senha-error').modal('show');</script>"; exit; }

		// DELETA AS PERMISSOES ANTERIORES
		$selPerm = mysql_query("SELECT * FROM admin_permissoes WHERE cod_usuario='{$codigo}'");
		while($row_selPerm = mysql_fetch_object($selPerm)) { mysql_query("DELETE FROM admin_permissoes WHERE codigo='$row_selPerm->codigo'"); }

		// ENVIA O ARQUIVO SE EXISTIR
		if(!empty($_FILES)) {
			// DELETA O ARQUIVO ANTIGO
			$arquivoOld = $diretorio.$selectUsuario->foto;	
			if($arquivoOld<>'' && is_file($arquivoOld)) { unlink($arquivoOld); }

			if(!empty($_FILES['arquivo']['tmp_name'])) {
				$arquivoTemporario = $_FILES['arquivo']['tmp_name'];
				$arquivoParts  = pathinfo($_FILES['arquivo']['name']);
				$extensao = $arquivoParts['extension'];
				$destinoFinal = $diretorio.$codigo.".".$extensao;

				move_uploaded_file($arquivoTemporario, $destinoFinal);
			}
		}

		// ALTERA OS DADOS NO BANCO DE DADOS
		mysql_query("UPDATE $nomeDaPagina SET nome='$nome', email='$email', senha='$senha' ".(isset($destinoFinal) ? ", foto='$destinoFinal'" : "")." WHERE codigo='$codigo'");

		foreach($codigoTabela as $key=>$cod_tabela) {
			$query_verificaUsuario = mysql_num_rows(mysql_query("SELECT * FROM admin_permissoes WHERE cod_usuario='$codigo' AND cod_tabela='$cod_tabela'"));
			$total_verificaUsuario = mysql_num_rows($query_verificaUsuario);

			if($total_verificaUsuario==0) { $insert_permissoes = mysql_query("INSERT INTO admin_permissoes (cod_usuario, cod_tabela) VALUES ('$codigo', '$cod_tabela')"); }
		}
	}
	echo "<script>$(location).attr({'href':'$urlPadrao', 'target':'paginaTarget'})</script>";
} ?>