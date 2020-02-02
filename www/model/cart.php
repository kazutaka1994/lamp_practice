<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'order.php';
require_once MODEL_PATH . 'detail.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  return fetch_all_query($db, $sql , array('user_id' => $user_id));
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";

  return fetch_query($db, $sql, array(':user_id' => $user_id, ':item_id' => $item_id));

}

function add_cart($db, $item_id, $user_id) {
  //$cartに入っていなかったらfalseを返してcartにinsertする
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $item_id, $user_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";

  return execute_query($db, $sql, array(':item_id' => $item_id,'user_id' => $user_id,':amount' => $amount));
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  return execute_query($db, $sql, array(':amount' => $amount, ':cart_id' => $cart_id));
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  return execute_query($db, $sql, array(':cart_id' => $cart_id));
}

//カートの中身を購入
function purchase_carts($db, $carts){
  //カートに商品が入っていない
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //カートに商品が入っている
 $db->beginTransaction();
  //item_stockの更新
  if(update_item_stocks($db, $carts)=== false){
      $db->rollback();
      return false;
  }
  //ordersに情報をインサート
  if(insert_order($db, $carts[0]['user_id']) === false){
    $db->rollback();
    return false;
  }
  $order_id = $db->lastInsertId();
  //detailsに情報をインサート
  if(insert_details($db, $order_id, $carts) === false){
    $db->rollback();
    return false;
  }
  //ユーザーのカートの削除
  if(delete_user_carts($db, $carts[0]['user_id']) === false){
    $db->rollback();
    return false;
  }
  $db->commit();
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  execute_query($db, $sql, array(':user_id' => $user_id));
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

