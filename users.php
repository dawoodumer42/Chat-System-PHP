<?php

function get_all_users() {
    $con = mysqli_connect("localhost","root","","chat_system");

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $sql="SELECT id, name, email, created_at, status, type FROM users";

    if ($result=mysqli_query($con,$sql))
    {
        while($row=mysqli_fetch_row($result)) {
            $user = new stdClass();
            $user->id = $row[0];
            $user->name = $row[1];
            $user->email = $row[2];
            $user->joined = $row[3];
            $user->actions = "";   
            
            $action_approve = "<a href=\"#\" class=\"view\" title=\"Approve\" data-toggle=\"tooltip\"><span class=\"badge badge-success\">Approve</span></a>\n";
            $action_block = "<a href=\"#\" class=\"view\" title=\"Danger\" data-toggle=\"tooltip\"><span class=\"badge badge-danger\">Block</span></a>\n";
            $action_unblock = "<a href=\"#\" class=\"view\" title=\"Unblock\" data-toggle=\"tooltip\"><span class=\"badge badge-success\">Unblock</span></a>\n";
            
            switch($row[4]) {
                case 0:
                    $user->status = 'Blocked';
                    $user->actions .= $action_unblock;
                        
                    break;
                case 1:
                    $user->status = 'Active';
                    $user->actions .= $action_block;
    
                    break;
                case 2:
                    $user->status = 'Pending Approval';
                    $user->actions .= $action_approve;
                    $user->actions .= $action_block;
                    break;
                case 3:
                    $user->status = 'Email Not Verified';
                    $user->actions .= $action_block;
                    break;
            }
    
            $user->type = $row[5];
    
            $users[] = $user;
    
            // echo "EMP ID :{$row[0]}  <br> ".
            // "EMP NAME : {$row[1]} <br> ".
            // "EMP SALARY : {$row[2]} <br> ".
            // "--------------------------------<br>";
        }
        mysqli_free_result($result);
    }
    //var_dump($users);
    mysqli_close($con);
    return $users;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>


    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round"> -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


</head>
<body>
    <?php include('nav.php'); ?>
    <br/>
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "pagingType": "full_numbers"
            } );
        } )
    </script>
    <div class="container-fluid bg-light">
            <br/>
            <table class="table table-bordered" id="example">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name </th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $users = get_all_users();
                        //var_dump($users);
                        foreach($users as $user) {
                            echo '<tr>';
                            echo '<td>'. $user->id .'</td>';
                            echo '<td>'. $user->name .'</td>';
                            echo '<td>'. $user->email .'</td>';
                            echo '<td>'. $user->joined .'</td>';
                            echo '<td>'. $user->status .'</td>';
                            echo '<td>'. $user->type .'</td>';
                            echo '<td>'. $user->actions .'</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
    </div>
</body>
</html>