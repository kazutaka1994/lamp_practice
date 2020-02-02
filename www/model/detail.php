<?php

function get_order_details($db, $order_id){
    $sql = "
    SELECT
      items.name,
      details.price,
      details.amount,
      details.price * details.amount
    FROM
      details
    JOIN
      items
    ON
      details.item_id = items.item_id
    WHERE
      details.order_id = :order_id
    ";
    return fetch_all_query($db, $sql , array(':order_id' => $order_id)); 
}

function get_order($db, $order_id){
    $sql = "
      SELECT
        orders.order_id,
        orders.created,
        SUM(details.price * details.amount),
        orders.user_id
      FROM
        orders
      JOIN
        details
      ON
        orders.order_id = details.order_id
      WHERE
        orders.order_id = :order_id
      GROUP BY 
        orders.order_id
    ";
  return fetch_query($db, $sql , array(':order_id' => $order_id));
}

function insert_details($db, $order_id, $carts){
    foreach($carts as $cart){
        if(insert_detail($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']) === false){
            set_error($cart['name'] . 'の購入に失敗しました。');
            return false;
        }
    }
    return true;
}
function insert_detail($db, $order_id, $item_id, $price, $amount){
    $sql = 
        'INSERT INTO details(order_id, item_id, price, amount)
        VALUES(:order_id, :item_id, :price, :amount)';
    return execute_query(
        $db,
        $sql,
        array(
            ':order_id' => $order_id,
            ':item_id' => $item_id,
            ':price' => $price,
            ':amount' => $amount
        )
    );
}

function can_watch_order($login_user, $order_user_id){
  if(is_admin($login_user) === TRUE){
    return TRUE;
  }
  if($login_user['user_id'] === $order_user_id){
    return TRUE;
  }
  set_error('この注文はあなたのものではありません');
  return FALSE;
  
  
  
}
?>