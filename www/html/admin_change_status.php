<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$token = get_post('token');
if (is_valid_csrf_token($token) === false){
  set_error('不正なアクセスです');
  redirect_to(ADMIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
//非公開を公開にする
if($changes_to === 'open'){
  //statusを1に変更
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
//公開を非公開にする
}else if($changes_to === 'close'){
  //statusを0に変更
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
  //postのvalueがopen,close以外だった場合の処理
}else {
  set_error('不正なリクエストです。');
}


redirect_to(ADMIN_URL);