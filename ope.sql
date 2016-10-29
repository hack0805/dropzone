CREATE DATABASE dropzone;

use dropzone;

-- postsテーブルの作成 --
CREATE TABLE posts (
  id int(11) NOT NULL AUTO_INCREMENT primary key,
  name varchar(100) NOT NULL,
  pic_name varchar(100) NOT NULL,
  picture varchar(255) NOT NULL,
  created datetime
);