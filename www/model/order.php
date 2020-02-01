<?php

function get_user_orders($db,$user){
    $sql = "
      SELECT
        orders.order_id,
        orders.created,
        SUM(details.price * details.amount)
      FROM
        orders
      JOIN
        details
      ON
        orders.order_id = details.order_id
      ";
    $params = array();

    if(is_admin($user) === FALSE){
      $sql .="
        WHERE
          orders.user_id = :user_id
      ";
      $params[':user_id'] = $user['user_id'];  
    }
    $sql .= "
      GROUP BY 
        orders.order_id
      ";

  return fetch_all_query($db, $sql , $params);
}

function get_admin_orders($db){
    $sql = "
      SELECT
        orders.order_id,
        orders.created,
        SUM(details.price * details.amount)
      FROM
        orders
      JOIN
        details
      ON
        orders.order_id = details.order_id
      GROUP BY 
        orders.order_id
    ";
  return fetch_all_query($db, $sql);
}

function insert_order($db, $user_id){
    $sql =
        'INSERT INTO orders(user_id)
        VALUES(:user_id)';
    return execute_query($db, $sql, array(':user_id' => $user_id));
}

?>