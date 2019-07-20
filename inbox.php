<?php
include('functions.php');
authorize();
?>

<html>
<head>
  <meta http-equiv="refresh" content="120" > 

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
  
  <link href="chat_styles.css" rel="stylesheet">
</head>
<body>
  <div id="sound"></div>
  <?php include('nav.php'); ?>
  <input type="hidden" id="chat_type" value="user">
  <input type="hidden" id="user_type" value="user">
  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content my-modal">
        <form action="create_room.php" method="POST">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Create Room</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->
          <div class="modal-body">

            <input type="hidden" name="final_users" id="final_users">
            <input type="hidden" name="modal_type" id="modal_type">
            <input type="hidden" name="room_id" id="room_id">
            <div class="form-group">
              <input type="text" name="title" class="form-control" placeholder="Title" required="required"<?php if($_SESSION['type'] != 'Admin') echo "readonly"; ?> >
            </div>
            <div class="form-group">
              <input type="text" name="description" class="form-control" placeholder="Description" required="required" <?php if($_SESSION['type'] != 'Admin') echo "readonly"; ?> >
            </div>

            <div class="form-group">
              <label for="sel1">Users:</label>
              <select class="form-control" id="list_users" <?php if($_SESSION['type'] != 'Admin') echo 'disabled="true"'; ?>>
                <option value="0">Select User</option>
                <?php 
                $users = get_all_clients();
                foreach($users as $user) {
                  echo "<option value=\"$user[0]\">$user[1]</option>";
                }
                ?> 
              </select>

            </div>
            <div class="form-group" id="selected_users">
              <p class="text-danger" id="already_selected">Already Selected</p>            


            </div>

          </div>

          <!-- Modal footer -->
          <div class="modal-footer modal-footer2">
            <button type="submit" class="btn btn-success">Create</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          <div class="modal-footer modal-footer1">
            <button type="submit" class="btn btn-success" >Save</button>
            <button type="button" class="btn btn-danger" onclick="delete_room();">Delete Room</button>

          </div>
        </form>
      </div>
    </div>
  </div>


  <div class="container chat-container">
    <div class="messaging">
      <div class="inbox_msg">  
        <!-- Rooms Panel -->
        <div class="users_list">
          <!-- Label -->
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Conversations</h4>
            </div>
            <?php if($_SESSION['type'] == 'Admin') {
              echo "
              <div class=\"srch_bar\">
              <button id=\"create_room\" class=\"btn btn-info btn-sm\" data-toggle=\"modal\" data-target=\"#myModal\">New Room</button>
              </div>";
            }
            ?>
          </div>
          <!-- Rooms List -->
          <div class="inbox_chat">

            <!-- Room Heading -->
            <div class="room toggle-slide" data-target="#admins">
              <p class="room_name">Admins</p>
            </div>
            <!-- Room Members -->
            <div id="admins" class="collapsed">
              <?php 
              $admins = get_all_admins();

              foreach($admins as $admin) {
                echo "
                <div id=\"member{$admin[0]}\" class=\"members\" onclick=\"load_msgs('{$admin[0]}', '{$admin[1]}');\">
                <div class=\"chat_list\">
                <div class=\"chat_people\">
                <div class=\"chat_img\"> <img src=\"https://ptetutorials.com/images/user-profile.png\" alt=\"Name\"> </div>
                <div class=\" noti\">
                <span id= \"noti{$admin[0]}\" class=\"noti{$admin[0]} badge badge-pill badge-danger notifcation\">0</span>
                </div>
                <div class=\"chat_ib state{$admin[0]}\">
                <h5> {$admin[1]} <span class=\"badge badge-success online_status\">Online</span></h5>
                </div>
                </div>
                </div>
                </div>";
              }
              ?>
            </div>

            <?php 
            if($_SESSION['type'] == 'Admin') {
              echo "
              <!-- Room Heading -->
              <div class=\"room\" data-toggle=\"collapse\" data-target=\"#members\">
              <p class=\"room_name\">All Users</p>
              </div>

              <!-- Room Members -->
              <div id=\"members\" class=\"collapse show\">";

              $clients = get_all_clients();
              foreach($clients as $client) {
                echo "
                <div id=\"member{$client[0]}\" onclick=\"load_msgs('{$client[0]}', '{$client[1]}');\" class=\"members\" >
                <div class=\"chat_list\">
                <div class=\"chat_people\">
                <div class=\"chat_img\"> <img src=\"https://ptetutorials.com/images/user-profile.png\" alt=\"name\"> </div>
                <div class=\" noti\">
                <span id= \"noti{$client[0]}\" class=\"noti{$client[0]} badge badge-pill badge-danger notifcation\">0</span>
                </div>
                <div class=\"chat_ib state{$client[0]}\">
                <h5>{$client[1]}<span class=\"badge badge-success online_status\">Online</span></h5>
                </div>
                </div>
                </div>
                </div>";
              }
              echo "</div>";
            }
            ?>
            <!-- Other Users -->
            <?php
            $partners = get_room_partners();
            if($_SESSION['type'] == 'User' && count($partners) > 0) {
              echo "
              <!-- Room Heading -->
              <div class=\"room\" data-toggle=\"collapse\" data-target=\"#other\">
              <p class=\"room_name\">Other Users <small>(In your rooms)</small></p>
              </div>
              <!-- Room Members -->
              <div id=\"other\" class=\"collapse show\">";
              

              foreach($partners as $partner) {
                echo "
                <div id=\"partner{$partner[0]}\" onclick=\"load_msgs('{$partner[0]}', '{$partner[2]}');\" class=\"members\" >
                <div class=\"chat_list\">
                <div class=\"chat_people\">
                <div class=\"chat_img\"> <img src=\"https://ptetutorials.com/images/user-profile.png\" alt=\"name\"> </div>
                <div class=\" noti\">
                <span id= \"noti{$partner[0]}\" class=\"noti{$partner[0]} badge badge-pill badge-danger notifcation\">0</span>
                </div>
                <div class=\"chat_ib state{$partner[0]}\">
                <h5>{$partner[2]}<span class=\"badge badge-success online_status\">Online</span></h5>
                </div>
                </div>
                </div>
                </div>";
                //}
              }
              echo "</div>";
            }
            ?>
            <!-- Room Heading -->
            <?php
            $rooms = get_all_rooms();
            if(count($rooms) > 0) {
              $n = count($rooms);
              echo "
              <div class=\"room\" data-toggle=\"collapse\" data-target=\"#rooms\">
              <p class=\"room_name\">Rooms ({$n})</p>

              </div>
              <!-- Room Members -->
              <div id=\"rooms\" class=\"collapse show\">";

              foreach($rooms as $room) {
                $room_last_message_id = get_room_last_message($room[0]);
                //$room_members = get_room_members($room[0]);
                //foreach ($room_members as $member) {
                echo "
                <input type=\"hidden\" id=\"room_last{$room[0]}\" value=\"{$room_last_message_id}\" >
                <div id=\"room{$room[0]}\" onclick=\"load_msgs_room('{$room[0]}', '{$room[1]}');\" class=\"members\" >
                <div class=\"chat_list\">
                <div class=\"chat_people\">
                <div class=\" noti\">
                <span id= \"noti_room{$room[0]}\" class=\"noti_room{$room[0]} badge badge-pill badge-danger notifcation_room\">0</span>
                </div>
                <div class=\"chat_ib\">
                <h5>{$room[1]}";
                if($_SESSION['type'] == 'Admin' || $room[2] == 'Moderator') {
                  echo "<button id=\"update_room{$room[0]}\"class=\"btn btn-sm btn-info room_options\" data-toggle=\"modal\" data-target=\"#myModal\"><i class='fas fa-cog'></i></button>";
                }
                else if ($room[2] == 'Moderator') {
                  echo '<span class="badge badge-info">Moderator</span>';
                }
                //echo '<script>console.log($("#room_last'.$room[0].'").val());</script>';

                echo "</h5>
                </div>
                </div>
                </div>
                </div>";
                //}
              }
              echo '</div>';              
            }
            ?>
            
          </div>
        </div>

        <!-- Message Panel -->
        <div class="mesgs">
          <div>
            <p id="reciver_info">No Open Conversation</p>
          </div>
          <input type="hidden" id="num_msgs" value="20"/>
          <input type="hidden" id="x" value="-1"/>
          <input type="hidden" id="name" value="Admin"/>
          <input type="hidden" id="last_message_id" value ="-1">
          <input type="hidden" id="last_message_sender" value ="-1">
          <div class="msg_history" id="msg_history">

          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" placeholder="Type a message" />
              <input type="hidden" id="user_id"/>
              <button onclick="send_message();" class="msg_send_btn" type="button"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<script src="inbox.js"></script>