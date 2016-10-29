<?php

// DB接続処理
define('DSN','mysql:host=localhost;dbname=dropzone;charset=utf8');
define('USER','root');
define('PASSWORD','root');

function connectDb(){
  try{
    return new PDO(DSN,USER,PASSWORD);
  } catch(PDOException $e){
    echo $e->getMessage();
    exit;
  }
}