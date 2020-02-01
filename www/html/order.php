<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/order.php';
session_start();
//ログインしているかどうかの確認
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//データベースに接続
$db = get_db_connect();
//user_id,name,password,typeの取得
$user = get_login_user($db);
//ログインしたユーザーが管理者の場合
//全てのユーザーの各注文番号 購入日時 該当の注文の合計金額を取得する。
//管理者以外の場合
//ログインしているユーザーの各注文番号 購入日時 該当の注文の合計金額を取得する。
$orders = get_user_orders($db,$user);
//$tokenと$_SESSION('token')のセット
$token = get_csrf_token();
//X-FRAMEを無効化
header('X-FRAME-OPTIONS: DENY');
include_once '../view/order_view.php';