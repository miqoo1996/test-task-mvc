CREATE DATABASE test_task CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE test_task;

CREATE TABLE texts (
    id bigint auto_increment primary key,
    unique_key varchar(100) not null,
    value longtext not null,
    CONSTRAINT unique_key_idx UNIQUE (unique_key)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;