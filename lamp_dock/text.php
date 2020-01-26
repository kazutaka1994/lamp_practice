<?php
//modelのcart.phpに
//①ordersテーブルへuser_idをインサートする関数(insert_order関数)
function insert_order($db, $user['user_id']){
    $sql =
        'INSERT INTO orders(user_id)
        VALUES(:user_id)';
    return execute_query($db, $sql, array(':user_id'=>$user['user_id']));
}

//②detailsテーブルへのorder_id,item_id,price,amountのインサートする関数(insert_details関数,insert_detail関数)
//を追加
function insert_details($db, $order_id, $carts){
    foreach($carts as $cart){
        if(insert_detail($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']) === false){
            set_error($cart['name'] . 'の購入に失敗しました。');
        }
    }
}
function insert_detail($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']){
    $sql = 
        'INSERT INTO details(order_id, item_id, price, amount)
        VALUES(:order_id, :item_id, :price, :amount)';
    return execute_query($db, $sql, array(':order_id'=>$order_id, ':item_id'=>$cart['item_id'], ':price'=>$cart['price']), ':amount'=>$cart['amount']);
}

//modelのcart.phpのpurchase_carts関数に
//①トランザクションの開始
//②ordersテーブルへuser_idをインサートする関数(insert_order関数)
//③lastInsertIdでorder_idの取得
//④detailsテーブルへのorder_id,item_id,price,amountのインサート(insert_details関数)
//⑤コミット処理
//を追加
//カートの中身を購入
function purchase_carts($db, $user['user_id'], $carts){
    //カートに商品が入っていない
    if(validate_cart_purchase($carts) === false){
      return false;
    }
    $db->beginTransaction();
    insert_order($db, $user['user_id'])
    //カートに商品が入っている
    foreach($carts as $cart){
      //item_stockの更新
      if(update_item_stock(
          $db, 
          $cart['item_id'], 
          $cart['stock'] - $cart['amount']
        ) 
        or
        insert_detail(
            $db,
            $order_id,
            $cart['item_id'], $cart['price'], $cart['amount']
        )
        //例外が発生した場合
        === false){
        set_error($cart['name'] . 'の購入に失敗しました。');
      }
      //detailsに情報をインサート
    }
    //ユーザーのカートの削除
    delete_user_carts($db, $carts[0]['user_id']);
    $db->commit();
}

?>


modelのdb.phpのexecute_query関数のcatch部分にロールバック処理を追加
※これではエラーになってしまう場合、新たにexecute_transaction_query関数を作成する


