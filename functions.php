<?php

open_session($user_id, $type, $status){
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['type'] = $type;
    $_SESSION['status'] = $status;
}

authorize(){
    session_start();
    var type = $_SESSION['type'];
    switch(type){
        case 0:
        break;
        case 1:
        break;
        case 2:
        break;
        case 3:
        break;:
        default:
        break;
    }
}


?>