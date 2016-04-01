<?php
include 'connect.php';
include 'classes/stdObject.php';
//To allow Ajax requests 
header("Access-Control-Allow-Origin: *");

$oParams = json_decode($_GET['oParams']);
//echo $_GET['oParams'];

$oRetorno = new stdObject();
$oRetorno->status = 0;
$method = "getSetores";

switch ($oParams->method) {
	
	//try {
	//LOGIN
	case 'autenticar':

		$sql = "SELECT
					u.id_usuario, 
					u.nome as nome,
					u.data_nascimento as data_nasc,
					tu.id_tipousuario as id_tipousuario,
					tu.descricao      as tipo_usuario,
					tu.responde,
					tu.le_respostas,
					e.descr as equipe,
					s.descr as setor
				FROM usuario_login ul
					INNER JOIN usuario u on u.id_usuario = ul.id_usuario
					INNER JOIN equipe  e on e.id_equipe  = u.id_equipe
					INNER JOIN setor   s on s.id_setor   = e.id_setor
					INNER JOIN tipo_usuario tu on tu.id_tipousuario = u.id_tipousuario
				WHERE ul.login = '".$oParams->login."' and ul.senha = '".md5($oParams->senha)."' LIMIT 1";

		$resultLogin = $pdo->query($sql);
		$row = $resultLogin->fetch();

		if (!empty($row)) {
			$oUsuario = new stdObject();
			$oUsuario->id_usuario       = $row['id_usuario'];
			$oUsuario->nome             = $row['nome'];
			$oUsuario->data_nasc        = $row['data_nasc'];
			$oUsuario->id_tipousuario   = $row['id_tipousuario'];
			$oUsuario->tipo_usuario     = $row['tipo_usuario'];
			$oUsuario->perm_responde    = $row['responde'];
			$oUsuario->perm_le_resposta = $row['le_respostas'];
			$oUsuario->nome_equipe      = $row['equipe'];
			$oUsuario->setor            = $row['setor'];

			$oRetorno->usuario = $oUsuario;
		} else {
			$oRetorno->status = 1;
			$oRetorno->msg    = "Usuário não encontrado";
		}

		echo json_encode($oRetorno);
	break;

	//SETOR
	case 'getSetores':
		
		$sql = "SELECT * FROM setor";
 		$resultSetores = $pdo->query($sql);

		$aSetores = Array();
		while ($row = $resultSetores->fetch()) 
		{
			$setor = new stdObject();
			$setor->id_setor  = $row['id_setor'];
			$setor->descricao = $row['descr'];
		 	$aSetores[] = $setor; 
		}

		$oRetorno->setores = $aSetores;

		echo json_encode($oRetorno);
	break;
	
	case 'getSetorPorEquipe':

		$sql = "SELECT * 
				FROM setor s 
					INNER JOIN equipe e on e.id_setor = s.id_setor
				WHERE e.id_equipe = $oParams->id_equipe";
 		$result = $pdo->query($sql);

		$aSetores = Array();
		while ($row = $result->fetch()) 
		{
			$setor->id_setor  = $row['id_setor'];
			$setor->descricao = $row['descr'];
		 	$aSetores[] = $setor; 
		}
		echo json_encode($aSetores);
		//print_r($aSetores);
	break;

	//EQUIPE


	//USUARIO
	case 'getLiderUsuario':
	
	break;
	
	case 'getLiderEquipe':
	
	break;
	
	case 'getViceEquipe':

	break;

	case 'setViceEquipe':

	break;
	
	//TIPO USUARIO
	case 'getTipoUsuario':

	break;

	//QUESTIONARIO

	case 'getQuestionarioPessoaData':

		$idUsuario = $oParams->id_usuario;
		$data  	   = $oParams->data;

		$sql = "SELECT 
				 	questao,
				 	resposta
				FROM usuario_questionario 
				WHERE id_usuario = $id_usuario and 
					  data = $data";

		$resultQuestionario = $pdo->query($sql);

		if ($result) {
			
			$aRespostas = Array();
			while ($row = $resultQuestionario->fetch()) 
			{
				$resposta = new stdObject();
				$resposta->questao  = $row['questao'];
				$resposta->resposta = $row['resposta'];
			 	$aRespostas[] = $resposta; 
			}

			$oRetorno->respostas = $aRespostas;
		} else {
			$oRetorno->status = 1;
		}

		echo json_encode($oRetorno);
	break;

	case 'salvarQuestionario':

		$aRespostas = json_decode($oParams->aRespostas);
		$idUsuario  = $oParams->id_usuario;
		$data 		= $oParams->data;
		foreach ($aRespostas as $oResposta) {
			$questao  = $oResposta->questao;
			$resposta = $oResposta->resposta;
			$sql  = "INSERT INTO usuario_questionario (id_usuario, questao, resposta, data_resposta) VALUES (".$idUsuario.", ".$questao.", ".$resposta.", '".$data."')";
			$erro = $pdo->query($sql);
		}

		if (!$erro) {
			$oRetorno->status = 1;
			$oRetorno->msg 	  = "Erro na inserção dos dados.".$pdo->error;
		}

		echo json_encode($oRetorno);
	break;

	//AVISO

	case 'getAvisosAtivos':

	break;

	case 'cadastraAviso':

	break;

	//DEFAULT - Não definido
	default:

		throw new Exception("Erro ao buscar as informações. Contate o administrador.", 1);
	break;
	
	/*} catch (Exception $e) {

		throw new Exception("Erro ao buscar as informações. Contate o administrador. ($e)", 1);
	}*/
}
?>