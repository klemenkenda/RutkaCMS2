create table pages (
    id int auto_increment primary key,
    ts timestamp default current_timestamp on update current_timestamp,
    pid int not null,
    title varchar(100),
    content text,
    description varchar(255),
    keywords varchar(255)
)