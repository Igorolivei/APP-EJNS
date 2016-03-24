CREATE DATABASE "EJNS"
  WITH OWNER = postgres
       ENCODING = 'LATIN1'
       TABLESPACE = pg_default
       LC_COLLATE = 'Portuguese_Brazil.1252'
       LC_CTYPE = 'Portuguese_Brazil.1252'
       CONNECTION LIMIT = -1;

CREATE SCHEMA app_questionario
  AUTHORIZATION postgres;

CREATE TABLE app_questionario.tipo_usuario (
  id_tipousuario SERIAL NOT NULL,
  descricao VARCHAR NOT NULL,
  responde BOOL NOT NULL,
  le_respostas BOOL NOT NULL,
  PRIMARY KEY(id_tipousuario)
);

-- #CONSELHEIRO
--INSERT INTO app_questionario.tipo_usuario VALUES (nextval('app_questionario.tipo_usuario_id_tipousuario_seq'), 'Conselheiro', 'f', 't');
-- #VICE
--INSERT INTO app_questionario.tipo_usuario VALUES (nextval('app_questionario.tipo_usuario_id_tipousuario_seq'), 'Vice', 't', 't');
-- #RESPONSÁVEL
--INSERT INTO app_questionario.tipo_usuario VALUES (nextval('app_questionario.tipo_usuario_id_tipousuario_seq'), 'Responsável', 't', 'f');
-- #TESOUREIRO
--INSERT INTO app_questionario.tipo_usuario VALUES (nextval('app_questionario.tipo_usuario_id_tipousuario_seq'), 'Tesoureiro', 't', 'f');
-- #SOCIAL
--INSERT INTO app_questionario.tipo_usuario VALUES (nextval('app_questionario.tipo_usuario_id_tipousuario_seq'), 'Social', 't', 'f');
-- #EQUIPISTA
--INSERT INTO app_questionario.tipo_usuario VALUES (nextval('app_questionario.tipo_usuario_id_tipousuario_seq'), 'Equipista', 't', 'f');

CREATE TABLE app_questionario.setor (
  id_setor SERIAL NOT NULL,
  descr VARCHAR NOT NULL,
  PRIMARY KEY(id_setor)
);

--INSERT INTO app_questionario.setor VALUES (nextval('app_questionario.setor_id_setor_seq'), 'Natal');

CREATE TABLE app_questionario.equipe (
  id_equipe SERIAL NOT NULL,
  descr VARCHAR NOT NULL,
  id_setor INTEGER NOT NULL,
  PRIMARY KEY(id_equipe),
  FOREIGN KEY(id_setor)
    REFERENCES app_questionario.setor(id_setor)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

--INSERT INTO app_questionario.equipe VALUES (nextval('app_questionario.equipe_id_equipe_seq'), 'Nossa Senhora da Apresentação - Equipe 02', 1);

CREATE TABLE app_questionario.usuario (
  id_usuario SERIAL NOT NULL,
  nome VARCHAR NOT NULL,
  data_nascimento DATE NOT NULL,
  tipo_usuario INTEGER NOT NULL,
  id_equipe INTEGER NULL,
  ativo BOOL NOT NULL,
  PRIMARY KEY(id_usuario),
  FOREIGN KEY(tipo_usuario)
    REFERENCES app_questionario.tipo_usuario(id_tipousuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(id_equipe)
    REFERENCES app_questionario.equipe(id_equipe)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

--INSERT INTO app_questionario.usuario VALUES (nextval('app_questionario.usuario_id_usuario_seq'), 'Jarbson', '1995-05-14', 1, 1);
--INSERT INTO app_questionario.usuario VALUES (nextval('app_questionario.usuario_id_usuario_seq'), 'Marise Guipson Vasconcelos', '1995-05-14', 3, 1);

CREATE TABLE app_questionario.usuario_login (
  id_usuariologin SERIAL NOT NULL,
  login VARCHAR NOT NULL,
  senha VARCHAR NOT NULL,
  id_usuario INTEGER NOT NULL,
  PRIMARY KEY(id_usuariologin),
  FOREIGN KEY(id_usuario)
    REFERENCES app_questionario.usuario(id_usuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);

--INSERT INTO app_questionario.usuario_login VALUES (nextval('app_questionario.usuario_login_id_usuariologin_seq'), 'mahvasc', '123', 1);
--INSERT INTO app_questionario.usuario_login VALUES (nextval('app_questionario.usuario_login_id_usuariologin_seq'), 'jarbson', '123', 2);

CREATE TABLE app_questionario.usuario_questionario (
  id_usuarioquestionario SERIAL NOT NULL,
  id_usuario INTEGER NOT NULL,
  questao INTEGER NOT NULL,
  resposta INTEGER NOT NULL,
  data_resposta DATE NOT NULL,
  PRIMARY KEY(id_usuarioquestionario),
  FOREIGN KEY(id_usuario)
    REFERENCES app_questionario.usuario(id_usuario)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
);



