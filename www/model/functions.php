<?php

//var_dumpの関数
function dd($var){
  var_dump($var);
  exit();
}

//$urlに飛ぶ関数
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

//getの値を受け取る関数
function get_get($name){
  //$_GET[$name]に値が入っていたら
  if(isset($_GET[$name]) === true){
    //$_GET[$name]を返す
    return $_GET[$name];
  };
  //入っていなかったら空の値を返す
  return '';
}     

function get_file($name){
  if(isset($FILES[$name]) === ture){
    return $_FILES[$name];
  };
  return "";
}

function get_session($name){
  if(isset($_SESSION[$name])  === ture){
    return $_SESSION[$name];
  };
  return "";
}

function set_session($name, $value){
  $_SESSION[$name] = $value;
}

function set_error($error){
  $_SESSION['__errors'][] = $error;
}

function get_errors(){
  $errors = get_session('__errors');
  if($errors === ""){
    return array();
  }
  set_session('__errors',array());
  return $errors;
}

//$_SESION['__errors']が入っていて、かつ、$_SESSION['__errors']が複数ある
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

//sessionにmessageを入れている
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages', array());
  return $messages;
}

function is_logined(){
  //$_SESSION['user_id']が入っていたら$_SESSION[user_id']を返し
  //空文字以外を返す※よくわからない
  return get_session('user_id') !== '';
}


function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}


//文字数の最小値と最大値
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}


function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

function h($sanitize){
  return htmlspecialchars($sanitize, ENT_QUOTES, 'UTF-8');
}
