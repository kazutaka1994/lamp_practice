<?php

function insert_order($db, $user_id){
    $sql =
        'INSERT INTO orders(user_id)
        VALUES(:user_id)';
    return execute_query($db, $sql, array(':user_id' => $user_id));
}

?>