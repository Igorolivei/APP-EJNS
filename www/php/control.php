<?php
include 'connect.php';
include 'classes/stdObject.php';
//To allow Ajax requests 
header("Access-Control-Allow-Origin: *");

$oParams = json_decode($_GET['oParams']);
$oRetorno = new stdObject();
$oRetorno->status = 0;

switch ($oParams->method) {
	//try {
	//LOGIN
	case 'autenticar':

		$login = strtolower($oParams->login);
		$senha = md5($oParams->senha);
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
				WHERE ul.login = :l and ul.senha = :s LIMIT 1";
		$resultLogin = $pdo->prepare($sql);
		$resultLogin->bindParam(':l', $login);
		$resultLogin->bindParam(':s', $senha);
		$resultLogin->execute();

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

	case 'comparaSenhaAtual':

		$usuario = $oParams->id_usuario;
		$senha   = md5($oParams->senha);
		$sql = "SELECT exists(
					SELECT 1 FROM usuario_login ul 
					WHERE ul.id_usuario = :u and ul.senha = :s)
				  AS existe";
		$resultSenha = $pdo->prepare($sql);
		$resultSenha->bindParam(':u', $usuario);
		$resultSenha->bindParam(':s', $senha);
		$resultSenha->execute();

		$row = $resultSenha->fetch();

		if (!empty($row)) {

			if ($row['existe']) {

				$oRetorno->existe_senha = true;
			} else {

				$oRetorno->existe_senha = false;
			}
		} else {
			$oRetorno->status = 1;
			$oRetorno->msg    = "Usuário não encontrado";
		}

		echo json_encode($oRetorno);
	break;

	case 'alteraSenha':

		$usuario    = $oParams->id_usuario;
		$senha_nova = md5($oParams->senha_nova);
		$senha_atual = md5($oParams->senha_atual);
		$sql = "UPDATE usuario_login SET senha = :sn WHERE id_usuario = :u AND senha = :sa";

		$updateSenha = $pdo->prepare($sql);
		$updateSenha->bindParam(':u', $usuario);
		$updateSenha->bindParam(':sn', $senha_nova);
		$updateSenha->bindParam(':sa', $senha_atual);
		$status = $updateSenha->execute();

		if ($status) {

			$oRetorno->msg = "Senha alterada com sucesso";
		} else {

			$oRetorno->status = 1;
			$oRetorno->msg    = "Erro ao alterar senha. Contate o administrador.";
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

		$oRetorno->aSetores = $aSetores;

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
		$oRetorno->aSetores = $aSetores;

		echo json_encode($oRetorno);
	break;

	//EQUIPE


	//USUARIO
	case 'getConselheiroUsuario':
	
	break;
	
	case 'getUsuarioConselheiro':
	
		$id_conselheiro = $oParams->id_conselheiro;

		$sql = "SELECT 
				u.id_usuario, 
				u.nome, 
				tu.id_tipousuario, 
				tu.descricao as descricao_tipousuario, 
				e.id_equipe, 
				e.descr as nome_equipe
				FROM usuario u
		   			INNER JOIN equipe e ON e.id_equipe = u.id_equipe
		   			INNER JOIN tipo_usuario tu ON tu.id_tipousuario = u.id_tipousuario
				WHERE tu.id_tipousuario <> 1 
					and e.id_equipe in (select id_equipe from usuario where id_usuario = $id_conselheiro)";

		$resultEquipistas = $pdo->query($sql);
		$aEquipistas = Array();
		while ($row = $resultEquipistas->fetch()) 
		{
			$equipista = new stdObject();
			$equipista->id_equipista = $row['id_usuario'];
			$equipista->nome = $row['nome'];
			$equipista->id_tipousuario = $row['id_tipousuario'];
			$equipista->tipo_usuario =  $row['descricao_tipousuario'];
		 	$aEquipistas[] = $equipista; 
		}
		$oRetorno->aEquipistas = $aEquipistas;

		echo json_encode($oRetorno);
	break;

	case 'getConselheiroEquipe':
	
	break;
	
	case 'getViceEquipe':

	break;

	case 'setViceEquipe':

	break;

	
	//TIPO USUARIO
	case 'getTipoUsuario':

		$sql = "SELECT * FROM tipo_usuario";
		$resultTipoUsuario = $pdo->query($sql);
		$aTipoUsuario = Array();
		while ($row = $resultTipoUsuario->fetch()) 
		{
			$tipoUsuario = new stdObject();
			$tipoUsuario->id_tipousuario = $row['id_tipousuario'];
			$tipoUsuario->descricao      = $row['descricao'];
			$tipoUsuario->le_respostas   = $row['le_respostas'];
			$tipo_usuario->responde 	 = $row['responde'];
		 	$aTipoUsuario[] = $tipoUsuario; 
		}
		$oRetorno->aTipoUsuario = $aTipoUsuario;

		echo json_encode($oRetorno);
	break;

	//QUESTIONARIO

	case 'getQuestionarioPessoaData':

		$idUsuario = $oParams->id_usuario;
		$data  	   = $oParams->data;

		$sql = "SELECT 
				 	questao,
				 	resposta
				FROM usuario_questionario 
				WHERE id_usuario    = $idUsuario and 
					  data_resposta = '$data'";

		$resultQuestionario = $pdo->query($sql);

		$aResultadoQuestionario = array();
		$aResultadoQuestionario = $resultQuestionario->fetchAll();
		
		if (!empty($aResultadoQuestionario)) {

			$aRespostas = Array();
			
			foreach ($aResultadoQuestionario as $row) {
				
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

	//EMAIL

	case 'enviarUsuarioPorEmail':
		$email = $oParams->email;
		$subject = "PPV de ".$nome_equipista.".";
		$message = "Teste";
		mail($email, $subject, $message);
	break;

	//DEFAULT - Não definido
	default:

		echo "Erro ao buscar as informações. Contate o administrador.";
		//throw new Exception("Erro ao buscar as informações. Contate o administrador.", 1);
	break;
	
	/*} catch (Exception $e) {

		throw new Exception("Erro ao buscar as informações. Contate o administrador. ($e)", 1);
	}*/
}
?>