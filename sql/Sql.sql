CREATE DATABASE IF NOT EXISTS hw2;
USE hw2;

CREATE TABLE utenti (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(16) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    data_registrazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_accesso DATETIME,
    avatar VARCHAR(255) DEFAULT '/assets/images/avatar.png',
    karma_post INTEGER DEFAULT 0,
    karma_commenti INTEGER DEFAULT 0,
    bio TEXT,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE post (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NULL,
    reddit_id VARCHAR(20) NOT NULL UNIQUE,
    subreddit VARCHAR(50) NOT NULL,
    titolo VARCHAR(500) NOT NULL,
    autore VARCHAR(100) NOT NULL,
    contenuto TEXT,
    tipo_contenuto VARCHAR(10) NULL,
    url VARCHAR(500),
    thumbnail VARCHAR(500),
    immagine_path VARCHAR(255),
    voto INTEGER DEFAULT 0,
    data_salvataggio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utenti(id) ON DELETE CASCADE
);

CREATE TABLE commenti (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    contenuto TEXT NOT NULL,
    data_commento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES utenti(id) ON DELETE CASCADE
);

CREATE TABLE voti_utenti (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    tipo_voto TINYINT NOT NULL,
    data_voto TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utenti(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_post_vote (user_id, post_id)
);

CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO utenti (username, password, email, avatar, bio, is_admin) 
VALUES (
    'will',
    '$2y$10$.e5qFQsmVSfvzL4PUa.RO.B/Inh/e8UnGwLO7USaSXI5epFCOO6Je',
    'prova@gmail.com',
    '/assets/images/img.png',
    'test bio',
    TRUE
);