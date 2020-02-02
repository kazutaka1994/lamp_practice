<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';
session_start();
//ログインしているかどうかの確認
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//user_id,name,password,typeの取得
$user = get_login_user($db);
// item_id,name,stock,price,image,statusの取得
$items = get_open_items($db);
//$tokenと$_SESSION('token')のセット
$token = get_csrf_token();
//X-FRAMEを無効化
header('X-FRAME-OPTIONS: DENY');
include_once '../view/index_view.php';