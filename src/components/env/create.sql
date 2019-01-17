create table env (
    id int not null auto_increment primary key,
    ts timestamp default current_timestamp on update current_timestamp,
    varname varchar(50) not null,
    val varchar(255),
    unique key unique_varname (varname)
);