CREATE DATABASE test_task CHARACTER SET utf8 COLLATE utf8_general_ci;

USE test_task;

create table texts(
  id bigint auto_increment primary key,
  unique_key varchar(100) not null,
  value      longtext     not null,
  constraint unique_key_idx  unique (unique_key)
);
