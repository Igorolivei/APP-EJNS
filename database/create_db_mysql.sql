CREATE DATABASE EJNS;

CREATE TABLE IF NOT EXISTS tipo_usuario (
  id_tipousuario INT UNSIGNED NOT NULL AUTO_INCREMENT,
  descricao VARCHAR(200) NOT NULL,
  responde BOOL NOT NULL,
  le_respostas BOOL NOT NULL,
  PRIMARY KEY(id_tipousuario)
);

-- #CONSELHEIRO
--INSERT INTO tipo_usuario (descricao, responde, le_respostas) VALUES ('Conselheiro', false, true);
-- #VICE
--INSERT INTO tipo_usuario (descricao, responde, le_respostas) VALUES ('Vice', true, true);
-- #RESPONSÁVEL
--INSERT INTO tipo_usuario (descricao, responde, le_respostas) VALUES ('Responsável', true, false);
-- #TESOUREIRO
--INSERT INTO tipo_usuario (descricao, responde, le_respostas) VALUES ('Tesoureiro', true, false);
-- #SOCIAL
--INSERT INTO tipo_usuario (descricao, responde, le_respostas) VALUES ('Social', true, false);
-- #EQUIPISTA
--INSERT INTO tipo_usuario (descricao, responde, le_respostas) VALUES ('Equipista', true, false);

CREATE TABLE IF NOT EXISTS setor (
  id_setor INT UNSIGNED NOT NULL AUTO_INCREMENT,
  descr VARCHAR(200) NOT NULL,
  PRIMARY KEY(id_setor)
);

--INSERT INTO setor (descr) VALUES ('Natal');

CREATE TABLE IF NOT EXISTS equipe (
  id_equipe INT UNSIGNED NOT NULL AUTO_INCREMENT,
  descr VARCHAR(200) NOT NULL,
  id_setor INT UNSIGNED NOT NULL,
  PRIMARY KEY(id_equipe),
  FOREIGN KEY(id_setor)
    REFERENCES setor(id_setor)
);

--INSERT INTO equipe (descr, id_setor)  VALUES ('Nossa Senhora da Apresentação - Equipe 02', 1);

CREATE TABLE usuario (
  id_usuario INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(250) NOT NULL,
  data_nascimento DATE NOT NULL,
  id_tipousuario INT UNSIGNED NOT NULL,
  id_equipe INT UNSIGNED NULL,
  ativo BOOL NOT NULL,
  PRIMARY KEY(id_usuario),
  FOREIGN KEY(id_tipousuario)
    REFERENCES tipo_usuario(id_tipousuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(id_equipe)
    REFERENCES equipe(id_equipe)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

--INSERT INTO usuario (nome, data_nascimento, id_tipousuario, id_equipe, ativo) VALUES ('Jarbson', '1995-05-14', 1, 1, true);
--INSERT INTO usuario (nome, data_nascimento, id_tipousuario, id_equipe, ativo) VALUES ('Marise Guipson Vasconcelos', '1995-05-14', 3, 1, true);

CREATE TABLE usuario_login (
  id_usuariologin INT UNSIGNED NOT NULL AUTO_INCREMENT,
  login VARCHAR(20) NOT NULL,
  senha VARCHAR(32) NOT NULL,
  id_usuario INT UNSIGNED NOT NULL,
  PRIMARY KEY(id_usuariologin),
  FOREIGN KEY(id_usuario)
    REFERENCES usuario(id_usuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

--INSERT INTO usuario_login (login, senha, id_usuario) VALUES ('jarbson', '202cb962ac59075b964b07152d234b70', 1);
--INSERT INTO usuario_login (login, senha, id_usuario) VALUES ('mahvasc', '202cb962ac59075b964b07152d234b70', 2);

CREATE TABLE usuario_questionario (
  id_usuarioquestionario INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_usuario INT UNSIGNED NOT NULL,
  questao INT NOT NULL,
  resposta INT NOT NULL,
  data_resposta DATE NOT NULL,
  PRIMARY KEY(id_usuarioquestionario),
  FOREIGN KEY(id_usuario)
    REFERENCES usuario(id_usuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);


CREATE TABLE IF NOT EXISTS aviso (
  id_aviso INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo TEXT NOT NULL,
  texto TEXT NOT NULL,
  id_usuario INT UNSIGNED NOT NULL,
  id_equipe INT UNSIGNED NOT NULL,
  ativo BOOL NOT NULL,
  PRIMARY KEY(id_aviso),
  FOREIGN KEY(id_equipe)
    REFERENCES equipe(id_equipe),
  FOREIGN KEY(id_usuario)
    REFERENCES usuario(id_usuario)
);

--INSERT INTO AVISO (texto, id_equipe, ativo) VALUES ('Bem-vindo!', 1, true);

CREATE TABLE usuario_aviso (
  id_usuarioaviso INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_usuario INT UNSIGNED NOT NULL,
  id_aviso INT UNSIGNED NOT NULL,
  PRIMARY KEY(id_usuarioaviso),
  FOREIGN KEY(id_usuario)
    REFERENCES usuario(id_usuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(id_aviso)
    REFERENCES aviso(id_aviso)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);
