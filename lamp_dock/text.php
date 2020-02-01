htmlディレクトリの中に
order.php
detail.php
を作成する

viewディレクトリの中に
order_view.php
detail_view.php
を作成する

html/assets/cssディレクトリの中に
order.css
detail.css
を作成する

modelのorder.phpにログインしているユーザーのordersを取得するget_user_orders関数を追加
modelのorder.phpにorder_idごとの合計金額を計算し値を返すsum_order関数を追加
modelのdetail.phpにログインしているユーザーのdetailsを取得するget_user_details関数を追加

order_view.php
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'order.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>該当の注文の合計金額</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order){ ?>
          <tr>
            <td><img src="<?php print h(IMAGE_PATH . $order['order_id']);?>" ></td>
            <td><?php print h($order['created']); ?></td>
            <td><?php print h(number_format($total_price)); ?>円</td>
            <td>
              <form method="post" action="detail.php">
                <input type="submit" value="購入明細表示" class="btn btn-secondary">
                <input type="hidden" name="order_id" value="<?php print h($cart['order_id']); ?>">
                <input type="hidden" name="token" value="<?php print h($token); ?>" >
              </form>
            </td>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入した商品はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>