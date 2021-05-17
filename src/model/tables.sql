CREATE TABLE Materia(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(255),
    color varchar(16)
);

CREATE TABLE professor(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(255) not null,
    dataNasc date not null,
    blocked text
);

CREATE TABLE MateriaLista(
    professor_id int,
    materia_id int,
    CONSTRAINT FK_professormateriaLista FOREIGN KEY (professor_id) REFERENCES professor(id),
    CONSTRAINT FK_materiaprofessor FOREIGN KEY (materia_id) REFERENCES Materia(id)
);

CREATE TABLE curso(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(255)
);

CREATE TABLE turma(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(255),
    curso_id int,
    ano YEAR,

    CONSTRAINT FK_turmacurso FOREIGN KEY (curso_id) REFERENCES curso(id)
);
CREATE TABLE horario(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(255) not null UNIQUE,
    turma_id int,
    professor_id int,
    materia_id int,

    CONSTRAINT FK_professorhorario FOREIGN KEY (professor_id) REFERENCES Professor(id),
    CONSTRAINT FK_turmahorario FOREIGN KEY (turma_id) REFERENCES Turma(id),
    CONSTRAINT FK_materiahorario FOREIGN KEY (materia_id) REFERENCES Materia(id)
);

CREATE TABLE `User` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastPasswordReset` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO Professor(nome,dataNasc) VALUES 
("Thyago Salva",'2001-12-15'),
("Iva Pra",'1995-04-12');

INSERT INTO Materia(nome) VALUES
("Programação Web"),
("Interface Web"),
("Banco de dados"),
("Algoritimos");


INSERT INTO MateriaLista(professor_id, materia_id) VALUES
(1,1),
(1,4),
(1,3),
(2,3);

INSERT INTO curso(nome) values
("Informatica para Internet"),
("Agropecuaria"),
("Viticultura e Enologia"),
("Meio Ambiente");

INSERT INTO turma(nome, curso_id,ano) values
("Info 1", 1, 2020),
("Info 2", 1, 2019),
("Info 3", 1, 2018),
("Agro 1", 2, 2020);

INSERT INTO horario(nome,turma_id,professor_id,materia_id) values
('seg2',1,1,1),
('seg3',1,1,1);

CREATE TABLE Grade(
    id int PRIMARY KEY AUTO_INCREMENT,
    entranceTime TIME,
    exitTime TIME
);
INSERT INTO Grade(entranceTime,exitTime) values
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00'),
('12:10:00','12:20:00');
