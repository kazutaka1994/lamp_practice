<?php
require_once '../conf/const.php';
require_once  MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'detail.php';
session_start();
//ログインしているかどうかの確認
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//user_id,name,password,typeの取得
$user = get_login_user($db);
//order.phpからGET送信されたorder_idの
$order_id = get_get('order_id');
//注文の各明細ごとの商品名 購入時の商品価格 購入数 小計を取得する
$details = get_order_details($db, $order_id);
//order.phpからGET送信されたorder_idの
//注文の注文番号、購入日時、合計金額をget_order関数で取得する。
$order = get_order($db, $order_id);
//$tokenと$_SESSION('token')のセット
$token = get_csrf_token();
//X-FRAMEを無効化
header('X-FRAME-OPTIONS: DENY');
include_once '../view/detail_view.php';