create table users (
    id int not null auto_increment primary key,
    ts timestamp default current_timestamp on update current_timestamp,
    name varchar(100),
    password varchar(100),
    su boolean,
    permissions json
);

create table auth (
    id int not null auto_increment primary key,
    ts timestamp default current_timestamp on update current_timestamp,
    token varchar(100),
    user_id int
);
