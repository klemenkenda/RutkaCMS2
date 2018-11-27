CREATE TABLE env (
    id int not null auto_increment,
    ts timestamp,
    varname varchar(50) not null,
    val varchar(255),
    primary key (id),
    unique key unique_varname (varname)
);