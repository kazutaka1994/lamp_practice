<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細一覧</title>
  <link rel="stylesheet" href="<?php print h(STYLESHEET_PATH . 'detail.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
    <div class ="top">
        <h1>購入明細一覧</h1>
        <div class="order">
            <table class="table table-sm table-dark">
                <thead>
                    <tr>
                        <th>注文番号</th>
                        <th>購入日時</th>
                        <th>合計金額</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php print h($order['order_id']); ?></td>
                        <td><?php print h($order['created']); ?></td>
                        <td><?php print h(number_format($order['SUM(details.price * details.amount)']))?>円</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    
    <?php if(count($details) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入時の商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($details as $detail){ ?>
          <tr>
            <td><?php print h($detail['name']); ?></td>
            <td><?php print h(number_format($detail['price'])); ?>円</td>
            <td><?php print h($detail['amount']); ?></td>
            <td><?php print h(number_format($detail['details.price * details.amount'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細がありません</p>
    <?php } ?> 
  </div>
</body>
</html>