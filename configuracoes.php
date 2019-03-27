<? $gets = explode('/',$_SERVER['REQUEST_URI']);
array_shift($gets);


class MySQL {
	public 	$database;
	public 	$socket;
	public 	$connected	= FALSE;
	public	$querys		= array();
	public	$querys2	= array();
	public	$nrows		= array();
	private	$execs		= array();
	public $codigoTabela;

	public function MySQL($host, $usuario, $senha, $db){
		$this->socket = mysql_connect($host, $usuario, $senha);
		mysql_query("SET NAMES utf8");
		($this->socket ? $this->connected=TRUE : $this->connected=FALSE);
		mysql_select_db($db, $this->socket);
	}

	public function Exec($query){
		$retorno = mysql_query($query, $this->socket) or die("<b>QUERY:</b> {$query}<br><b>ERROR:</b> " . mysql_error());
		return $retorno;
	}

	public function setQuery($num, $query){
		$this->querys2[$num] 		= $this->Exec($query);
		$this->querys[$num]['Q'] 	= $query;
		if($this->querys[$num]['ORDEM']==true)	
			$this->querys[$num]['Q'] .= ' ORDER by ' . $this->querys[$num]['O'][$this->OrdenField] . ' ' . $this->Ordens[$this->tpOrdem];
		

		if($this->querys[$num]['PAG']==true)
			$this->querys[$num]['Q'] .= ' LIMIT ' . ($this->pgSql*$this->querys[$num]['PGS']) . ',' . $this->querys[$num]['PGS'];
			
		$this->execs[$num] = $this->Exec($this->querys[$num]['Q']);
		$this->nrows[$num] = mysql_num_rows($this->execs[$num]);
	}

	public function Assoc($num){
		return mysql_fetch_assoc($this->execs[$num]);
	}

	public function oneRow($Field='', $tabela='', $where=''){
		$this->setQuery(999, "SELECT {$Field} FROM {$tabela} {$where}");
		$row = $this->Assoc(999);
		
		return ((count($row)==1) ? $row[$Field] : $row);
	}

	public function admPagina($codigoTabela) {
		$nomeAdm = $this->oneRow('*', 'admin_tabelas', "WHERE codigo='{$codigoTabela}'");
		return $nomeAdm[item];
	}
	public function nomeDaPagina() {
		$a = explode(".", basename($_SERVER['PHP_SELF']));
		$aa = str_replace("listar_", "", str_replace("adicionar_", "", $a[0]));
		return $aa;
	}
}

class Application {
	public $host			= '177.11.50.224';
	

	

	public $db				= 'midiabla_2019';
	public $usuario			= 'midiabla_2019';
	public $senha			= 'YAPqx8OjRH65';

	public function Application(){
		$this->SQL = new MySQL($this->host, $this->usuario, $this->senha, $this->db);
	}

	function config($campo) {
		$sql = new MySQL($this->host, $this->usuario, $this->senha, $this->db);
		$config = $sql->oneRow("$campo", 'admin_config', "WHERE codigo=1");
		return $config;
	}

	function SubStrTexto($texto, $limite) {
		$texto = strip_tags($texto);
		$texto = substr($texto,0,$limite).(strlen($texto)>$limite ? "..." : "");
		return $texto;
	}

	function replaceTitulo($valor) {
		$caracters = array(" ", "<", ">", "{", "}", "[", "]", "(", ")", "/", "\'", "\"", "~", "^", ";", ":", "!", "@", "#", "$", "%", "¨", "&", "*", "§", "+", "=", "www.", "www", ".com", ".br", "?", ",", "|", "´", "`", "°", "ª", "º", "á", "à", "ã", "â", "ä", "é", "ë", "è", "ê", "í", "ì", "î", "ï", "ó", "ò", "õ", "ô", "ö", "ç", "ú", "ù", "û", "ü", "Á", "À", "Ã", "Â", "Ä", "É", "È", "Ë", "Ê", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Õ", "Ô", "Ö", "Ç", "Ú", "Ù", "Û", "Ü", "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Z", "X", "C", "V", "B", "N", "M");
		$substitui_caracters =  array("-", "",  "",  "",  "",  "",  "",  "",  "",  "",  "",	  "",   "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",  "",     "",    "",     "",    "",  "",  "",  "",  "",  "",  "",  "",  "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "c", "u", "u", "u", "u", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "c", "u" ,"u", "u", "u", "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "a", "s", "d", "f", "g", "h", "j", "k", "l", "z", "x", "c", "v", "b", "n", "m");
		return ucfirst(str_replace($caracters, $substitui_caracters, $valor));
	}

	function acessaEmpreendimento($empreendimento) {
		$App 	= new Application();
		return "Empreendimento/".$App->replaceTitulo($empreendimento)."/";
	}

	function acessaOportunidade($codigo, $titulo) {
		$App 	= new Application();
		return "Oportunidades/".$codigo."-".$App->replaceTitulo($titulo)."/";
	}

	/*************************************************
	********* Funções de formatação de data **********
	*************************************************/
	function Data($data) {
		$dataK = explode(' ', $data);
		$dataKK = explode('-', $dataK[0]);
		$dataKKK = "$dataKK[2]/$dataKK[1]/$dataKK[0]";
		return $dataKKK;
	}
	public function DataHoraAmerican($data) {
		$dataK = explode(' ', $data);
		$dataK2 = explode(':', $dataK[1]);
		$dataKK = explode('/', $dataK[0]);
		$dataKKK = "$dataKK[2]-$dataKK[1]-$dataKK[0]".($dataK[1]<>''? " $dataK2[0]:$dataK2[1]:$dataK2[2]" : "");
		return $dataKKK;
	}
	public function converteData($data, $categoria) {
		$data_hora = explode(' ',$data);
		$data_d = explode('-',$data_hora[0]);
		$hora_d = explode(':',$data_hora[1]);
		$dia_d = $data_d[2];
		$mes_d = $data_d[1];
		$ano_d = $data_d[0];
		switch($mes_d){
			case 01: $nome_mes = 'Janeiro'; break;			case 02: $nome_mes = 'Fevereiro'; break;			case 03: $nome_mes = 'Março'; break;
			case 04: $nome_mes = 'Abril'; break;			case 05: $nome_mes = 'Maio'; break;					case 06: $nome_mes = 'Junho'; break;
			case 07: $nome_mes = 'Julho'; break;			case 8:  $nome_mes = 'Agosto'; break;				case 9:  $nome_mes = 'Setembro'; break;
			case 10: $nome_mes = 'Outubro'; break;			case 11: $nome_mes = 'Novembro'; break;				case 12: $nome_mes = 'Dezembro'; break;
		}
		$first_of_month = gmmktime(0,0,0,$mes_d,$dia_d,$ano_d);	
		$dia_semana = gmstrftime('%w',$first_of_month);	
		switch($dia_semana){
			case 0: $nome_dia = 'Domingo'; break;			case 1: $nome_dia = 'Segunda-Feira'; break;			case 2: $nome_dia = 'Terça-Feira'; break;
			case 3: $nome_dia = 'Quarta-Feira'; break;		case 4: $nome_dia = 'Quinta-Feira'; break;			case 5: $nome_dia = 'Sexta-Feira'; break;
			case 6: $nome_dia = 'Sábado'; break;
		}

		switch($categoria) {
			case 'enviodemail': $data_final = $nome_dia.', dia '.$dia_d.' de '.$nome_mes.' de '.$ano_d; break;
			case 'enviodemail2': $data_final = $dia_d.' de '.$nome_mes.' de '.$ano_d." às ".$hora_d[0].'h'.$hora_d[1]."m"; break;
		}
		return $data_final;
	}


	/*************************************************
	************** Funções de conteúdo ***************
	*************************************************/
	public function contato($tipo) {
		$App = new Application();
		$sql = new MySQL($this->host, $this->usuario, $this->senha, $this->db);

		switch($tipo) {
			case 1:
				$categoria = "TRIAD | CONTATO DE ".utf8_decode($_POST['nome']);

				$msg = "
					<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
					<html>
						<head>
							<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-2'>
							<style type='text/css'>* { margin:0; list-style:none; text-decoration:none; border:none; list-style-type:none; }</style>
						</head>
						<body style='margin:0; list-style:none; text-decoration:none; border:none; list-style-type:none; background:#FFF;'>
							<div style='width:551px; margin:0 auto;' align='center'>
								<div style='width:551px; margin:20px 0; float:left;'>
									<div style='width:551px; height:32px; background:url(".$App->Config("urlserver")."envioSMTP/images/topoItem01.png) no-repeat; float:left;'>&nbsp;</div>
									<div style='width:480px; padding:17px 24px 0 24px; background:#FFF; border:11px solid #e1e1e1; border-top:0; border-bottom:0; float:left;'>
										<div style='width:480px; float:left;'>
											<div style='width:288px; height:57px; background:url(".$App->Config("urlserver")."envioSMTP/images/topoLogo.png) no-repeat center; float:left;'>&nbsp;</div>	
											<div style='width:100px; padding-top:42px; float:right;' align='right'><a href='".$App->Config("urlserver")."' target='_blank' style='font-family:Arial; font-size:13px; text-decoration:none; font-weight:bolder; color:#9e9e9e;'>Ir para o site</a></div>
										</div>
										<div style='width:480px; height:24px; background:url(".$App->Config("urlserver")."envioSMTP/images/separador01.png) repeat-x center; float:left;'>&nbsp;</div>
										<div style='width:480px; height:140px; margin-bottom:34px; background:url(".$App->Config("urlserver")."envioSMTP/images/topoImagem.png) no-repeat center; float:left;'><img src='".$App->Config("urlserver")."envioSMTP/images/bannerMascara.png' width='480' height='141'></div>
										<div style='width:480px; float:left;' align='left'><font style='font-family:Arial; font-size:10px; color:#666666;'>".$App->converteData(date('Y-m-d H:i:s'), 'enviodemail')." &nbsp;|&nbsp; Mensagem automática</font></div>
										<div style='width:480px; height:24px; background:url(".$App->Config("urlserver")."envioSMTP/images/separador01.png) repeat-x center; float:left;'>&nbsp;</div>
										<div style='width:480px; padding-bottom:25px; float:left;' align='left'><font style='font-family:Arial; font-size:18px; font-weight:bold; text-align:center; color:#222222;'>Ola admin:</font></div>

										<div style='width:480px; padding-bottom:12px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Nome:</b> ".$_POST['nome']."</div>
										<div style='width:480px; padding-bottom:12px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Email:</b> ".$_POST['email']."</div>
										<div style='width:480px; padding-bottom:12px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Telefone:</b> ".$_POST['telefone']."</div>
										<div style='width:480px; padding-bottom:24px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Mensagem:</b> ".nl2br(utf8_decode($_POST['mensagem']))."</div>

										<div style='width:480px; padding-bottom:6px; margin-bottom:21px; border-bottom:1px solid #b6b6b6; font-family:Arial; font-size:11px; color:#666666; float:left;' align='justify'>Este contato foi enviado dia ".$App->converteData(date('Y-m-d H:i:s'), 'enviodemail2')." atraves do ip: $_SERVER[REMOTE_ADDR].</div>
										<div style='width:480px; padding-bottom:25px; float:left;'>
											<div style='width:125px; height:30px; float:right;'><img src='".$App->Config("urlserver")."envioSMTP/images/rodapeLogo.png' width='125' height='30' border='0'>&nbsp;</div>

										</div>
									</div>
									<div style='width:551px; height:37px; float:left;'><img src='".$App->Config("urlserver")."envioSMTP/images/rodapeItem01.png'></div>
								</div>
							</div>
						</body>
					</html>
				";
			break;
			case 2:
				$categoria = "TRIAD | Falar com um dos corretores ".utf8_decode($_POST['nome']);

				$msg = "
					<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
					<html>
						<head>
							<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-2'>
							<style type='text/css'>* { margin:0; list-style:none; text-decoration:none; border:none; list-style-type:none; }</style>
						</head>
						<body style='margin:0; list-style:none; text-decoration:none; border:none; list-style-type:none; background:#FFF;'>
							<div style='width:551px; margin:0 auto;' align='center'>
								<div style='width:551px; margin:20px 0; float:left;'>
									<div style='width:551px; height:32px; background:url(".$App->Config("urlserver")."envioSMTP/images/topoItem01.png) no-repeat; float:left;'>&nbsp;</div>
									<div style='width:480px; padding:17px 24px 0 24px; background:#FFF; border:11px solid #e1e1e1; border-top:0; border-bottom:0; float:left;'>
										<div style='width:480px; float:left;'>
											<div style='width:288px; height:57px; background:url(".$App->Config("urlserver")."envioSMTP/images/topoLogo.png) no-repeat center; float:left;'>&nbsp;</div>	
											<div style='width:100px; padding-top:42px; float:right;' align='right'><a href='".$App->Config("urlserver")."' target='_blank' style='font-family:Arial; font-size:13px; text-decoration:none; font-weight:bolder; color:#9e9e9e;'>Ir para o site</a></div>
										</div>
										<div style='width:480px; height:24px; background:url(".$App->Config("urlserver")."envioSMTP/images/separador01.png) repeat-x center; float:left;'>&nbsp;</div>
										<div style='width:480px; height:140px; margin-bottom:34px; background:url(".$App->Config("urlserver")."envioSMTP/images/topoImagem.png) no-repeat center; float:left;'><img src='".$App->Config("urlserver")."envioSMTP/images/bannerMascara.png' width='480' height='141'></div>
										<div style='width:480px; float:left;' align='left'><font style='font-family:Arial; font-size:10px; color:#666666;'>".$App->converteData(date('Y-m-d H:i:s'), 'enviodemail')." &nbsp;|&nbsp; Mensagem automática</font></div>
										<div style='width:480px; height:24px; background:url(".$App->Config("urlserver")."envioSMTP/images/separador01.png) repeat-x center; float:left;'>&nbsp;</div>
										<div style='width:480px; padding-bottom:25px; float:left;' align='left'><font style='font-family:Arial; font-size:18px; font-weight:bold; text-align:center; color:#222222;'>Ola admin, o internauta ".$_POST['nome']." deseja contato de um corretor:</font></div>

										<div style='width:480px; padding-bottom:25px; float:left;' align='justify'><font style='font-family:Arial; font-size:13px; color:#666666;'>Segue abaixo as informações do usuário:</font></div>
										<div style='width:480px; padding-bottom:12px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Nome:</b> ".$_POST['nome']."</div>
										<div style='width:480px; padding-bottom:12px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Email:</b> ".$_POST['email']."</div>
										<div style='width:480px; padding-bottom:12px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Telefone:</b> ".$_POST['telefone']."</div>
										<div style='width:480px; padding-bottom:24px; font-family:Arial; font-size:14px; color:#999999; float:left;' align='justify'><b>Mensagem:</b> ".nl2br(utf8_decode($_POST['mensagem']))."</div>

										<div style='width:480px; padding-bottom:6px; margin-bottom:21px; border-bottom:1px solid #b6b6b6; font-family:Arial; font-size:11px; color:#666666; float:left;' align='justify'>Este contato foi enviado dia ".$App->converteData(date('Y-m-d H:i:s'), 'enviodemail2')." atraves do ip: $_SERVER[REMOTE_ADDR].</div>
										<div style='width:480px; padding-bottom:25px; float:left;'>
											<div style='width:125px; height:30px; float:right;'><img src='".$App->Config("urlserver")."envioSMTP/images/rodapeLogo.png' width='125' height='30' border='0'>&nbsp;</div>

										</div>
									</div>
									<div style='width:551px; height:37px; float:left;'><img src='".$App->Config("urlserver")."envioSMTP/images/rodapeItem01.png'></div>
								</div>
							</div>
						</body>
					</html>
				";
			break;
		}

		require 'envioSMTP/phpmailer/PHPMailerAutoload.php';
		$mail = new PHPMailer;
		$mail->SetLanguage("br");

		$eMailRemetente      = "noreply@asinfo.com.br";
		$eMailRemetenteSenha = "33190623";
		$nomeRemetente       = ($tipo==1 ? $_POST['nome'] : $_POST['nome']);
		$servidorSMTP        = "mail.asinfo.com.br";
		$assunto             = "$categoria";
		$msgFinal            = $msg;

		$mail->isSMTP();									// Set mailer to use SMTP
		$mail->Host = $servidorSMTP;  						// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;								// Enable SMTP authentication
		$mail->Username = $eMailRemetente;					// SMTP username
		$mail->Password = $eMailRemetenteSenha;				// SMTP password
		$mail->SMTPSecure = 'tcp';							// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 25;									// TCP port to connect to

		$mail->isHTML(true);
		$mail->Subject = "$categoria";
		$mail->Body = $msgFinal;
		$mail->setFrom($eMailRemetente, $nomeRemetente);

		$sql->setQuery(1, "SELECT * FROM contatos WHERE tipo='{$tipo}'");
		while($contato = $sql->Assoc(1)) { 
			$mail->addAddress($contato[email]);
		}

		echo ($mail->send() ? '<script type="text/javascript">window.location.href="'.$App->Config("urlserver").($tipo==1 ? "Contato-enviado/" : "Contato-enviado/").'";</script>' : "");
		exit;
	}
}

$App 	= new Application();
$sql 	= new MySQL($App->host, $App->usuario, $App->senha, $App->db);

/*************************************************
********* SELECIONA AS INFOS IMPORTANTES *********
**************** DE CONFIGURAÇÃO ****************/
$site	= $sql->oneRow('*', 'admin_config', "WHERE codigo=1");

/*
phpMyAdmin instalado em:
triadconstrucoes.com.br.previewc75.carrierzone.com/phpmyadmin/

Nome do usuário da interface de administração:
triad2017

Usuário do Banco de dados:
triadconst195812

Nome do Banco de dados:
phpmy1_triadconstrucoes_com_br

Documentação do phpMyAdmin*/

/*************************************************
************ ENVIA O(S) FORMULARIO(S) ************
*************************************************/
($_POST['acao']=="contato" ? $App->contato(1) : "");
($_POST['acao']=="corretor" ? $App->contato(2) : "");

foreach($_GET as $key => $value)	{ $$key=$value; }
foreach($_POST as $key => $value)	{ $$key=$value; }
?>