create database nexa_ajuste;
use nexa_ajuste;

CREATE TABLE tecnico (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(120) NOT NULL,
    endereco   VARCHAR(255) NOT NULL,
    telefone1  VARCHAR(20)  NOT NULL,
    telefone2  VARCHAR(20)  NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE habilidade (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE tecnico_habilidade (
    tecnico_id    INT NOT NULL,
    habilidade_id INT NOT NULL,
    PRIMARY KEY (tecnico_id, habilidade_id),
    FOREIGN KEY (tecnico_id)    REFERENCES tecnico(id)    ON DELETE CASCADE,
    FOREIGN KEY (habilidade_id) REFERENCES habilidade(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO habilidade (nome) VALUES
('Redes'), ('Remoção de vírus'), ('Backup'), ('Formatação');