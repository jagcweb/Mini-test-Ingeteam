CREATE DATABASE IF NOT EXISTS ingeteam;
use test_ingeteam;

CREATE TABLE IF NOT EXISTS users (
    id int(255) not null auto_increment,
    name varchar(50) not null,
    surname varchar(50) not null,
    email varchar(255) not null,
    password varchar(255) not null,
    rol varchar(5) not null,
    image varchar(255),
    created_at datetime,
    updated_at datetime,
    CONSTRAINT pk_id PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE IF NOT EXISTS tasks (
    id int(255) not null auto_increment,
    user_id int(255) not null,
    title varchar(100) not null,
    description text not null,
    priority varchar(50) not null,
    created_at datetime,
    updated_at datetime,
    CONSTRAINT pk_id PRIMARY KEY (id),
    CONSTRAINT fk_user_task FOREIGN KEY (user_id) REFERENCES users(id)
)ENGINE=InnoDb;



