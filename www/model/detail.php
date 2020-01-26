<?php

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

?>