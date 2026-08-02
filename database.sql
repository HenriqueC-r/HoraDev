CREATE DATABASE IF NOT EXISTS HoraDev
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE HoraDev;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projetos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(120) NOT NULL,
    descricao TEXT,
    repositorio_url VARCHAR(255),
    demo_url VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_projetos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sessoes_tempo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    iniciado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    finalizado_em DATETIME NULL,
    duracao_segundos INT UNSIGNED NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessoes_tempo_projeto
        FOREIGN KEY (projeto_id) REFERENCES projetos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_sessoes_tempo_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE,
    INDEX idx_sessoes_usuario_ativa (usuario_id, finalizado_em),
    INDEX idx_sessoes_projeto (projeto_id)
);

CREATE TABLE IF NOT EXISTS comentarios_projeto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_comentarios_projeto
        FOREIGN KEY (projeto_id) REFERENCES projetos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comentarios_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE,
    INDEX idx_comentarios_projeto (projeto_id, criado_em)
);
