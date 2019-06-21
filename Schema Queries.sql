DROP TABLE IF EXISTS chat_room_messages;
DROP TABLE IF EXISTS chat_room_users;
DROP TABLE IF EXISTS chat_rooms;
DROP TABLE IF EXISTS user_activities;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(35) NOT NULL UNIQUE,
    password VARCHAR(60) NOT NULL,
    name VARCHAR(30) NOT NULL,
    avtivation_code VARCHAR(60) NOT NULL,
    email_verified_at TIMESTAMP,
    approved_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status INT DEFAULT 3,
    /*
		status values
        0	Block
        1	Active
        2	Not Approved
        3	Not Verified
    */
    type VARCHAR(15) NOT NULL
);

CREATE TABLE messages (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    message VARCHAR(500) NOT NULL,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` VARCHAR (20) NOT NULL DEFAULT 'Unread',
    FOREIGN KEY (from_user_id) REFERENCES users(id),
    FOREIGN KEY (to_user_id) REFERENCES users(id)
);


CREATE TABLE user_activities (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    stauts VARCHAR (20) NOT NULL,
    `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE chat_rooms (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(30),
    description VARCHAR(100),
	created_by INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE chat_room_users (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    chat_room_id INT NOT NULL,
    user_id INT NOT NULL,
    added_by INT NOT NULL,
    joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    kicked_at TIMESTAMP,
    type VARCHAR(15) NOT NULL DEFAULT 'User',
    FOREIGN KEY (chat_room_id) REFERENCES chat_rooms(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (added_by) REFERENCES users(id)
);

CREATE TABLE chat_room_messages (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    message VARCHAR(500) NOT NULL,
    from_user_id INT NOT NULL,
    to_chat_room_id INT NOT NULL,
    `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` VARCHAR (20) NOT NULL DEFAULT 'Unread',
    FOREIGN KEY (from_user_id) REFERENCES users(id),
    FOREIGN KEY (to_chat_room_id) REFERENCES chat_rooms(id)
)