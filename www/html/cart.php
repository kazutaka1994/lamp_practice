<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();
//ログインしているかどうかの確認
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//user_id,name,password,typeの取得
$user = get_login_user($db);
//user_idに対応するitem_id,name,price,stock,status,image,cart_id,user_id,amountの取得
$carts = get_user_carts($db, $user['user_id']);
//$cartsに入っている$price*$stockの総和
$total_price = sum_carts($carts);
//$tokenと$_SESSION('token')のセット
$token = get_csrf_token();
header('X-FRAME-OPTIONS: DENY');

include_once '../view/cart_view.php';