DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
	id_user INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(32) NOT NULL,
	username VARCHAR(16) NOT NULL,
	email VARCHAR(48) NOT NULL,
	birthdate DATE NOT NULL,
	password CHAR(32) NOT NULL,
	status INT NOT NULL DEFAULT 1
);

INSERT INTO users (name, username, email, birthdate, password)
VALUES('songo', 'songo', 'songo@gmail.com', '2002-03-12', md5('enti'));

CREATE TABLE messages (
	id_message INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	message VARCHAR(240) NOT NULL,
	post_time DATETIME NOT NULL,
	is_response BOOLEAN DEFAULT FALSE,
	status INT NOT NULL DEFAULT 1,
	id_user INT UNSIGNED NOT NULL,
	FOREIGN KEY (id_user) REFERENCES users(id_user)
);

CREATE TABLE likes (
	id_like INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	like_count INT NOT NULL DEFAULT 0,
	id_user INT UNSIGNED NOT NULL,
	FOREIGN KEY (id_message) REFERENCES messages(id_message)
);
