<?php
include('functions.php');
authorize();
?>

<html>
<head>

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
              <input type="text" name="title" class="form-control" placeholder="Title" required="required">
            </div>
            <div class="form-group">
              <input type="text" name="description" class="form-control" placeholder="Description" required="required">
            </div>

            <div class="form-group">
              <label for="sel1">Users:</label>
              <select class="form-control" id="list_users">
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
              <div id=\"members\" class=\"collapse\">";

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
              <div id=\"rooms\" class=\"collapse\">";

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
                if($_SESSION['type'] == 'Admin') {
                  echo "<button id=\"update_room{$room[0]}\"class=\"btn btn-sm btn-info room_options\" data-toggle=\"modal\" data-target=\"#myModal\"><i class='fas fa-cog'></i></button>";
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
              <div id=\"other\" class=\"collapse\">";
              

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
          </div>
        </div>

        <!-- Message Panel -->
        <div class="mesgs">
          <div>
            <p id="reciver_info">No Open Conversation</p>
          </div>
          <input type="hidden" id="num_msgs" value="50"/>
          <input type="hidden" id="x" value="-1"/>
          <input type="hidden" id="name" value="Admin"/>
          <input type="hidden" id="last_message_id" value ="-1">
          <input type="hidden" id="last_message_sender" value ="-1">
          <div class="msg_history">

          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" placeholder="Type a message" />
              <input type="hidden" id="user_id"/>
              <button onclick="send_message();" class="msg_send_btn" type="button"><i class="fa fa-plane" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<script>  
  function playSound(filename){
    var mp3Source = '<source src="' + filename + '.mp3" type="audio/mpeg">';
    var oggSource = '<source src="' + filename + '.ogg" type="audio/ogg">';
    var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3">';
    document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
  }

  $(document).ready(function() {
    $('#already_selected').hide();
    var final_users = [];
    $('.online_status').hide();
    
    get_users_status();
    get_unread_count(false);
    get_unread_count_room(false);

    $(window).on('shown.bs.modal', function() {
      //console.log("shown");
      var type = $('#modal_type').val();
      if(type == "update") {
        $('.modal-footer1').show();
        $('.modal-footer2').hide();
        $('.modal-title').text("Update Room");

        var room_id = $('#room_id').val();
        $.ajax({
          url:"get_room_details.php",
          method:"POST",
          data: {id:room_id},
          success:function(data){
            data = JSON.parse(data);
            $("input[name='title']").val(data[0][0]);
            $("input[name='description']").val(data[0][1]);
            final_users = [];
            for(i = 0; i < data.length; i++) {
              if(data[i][3] != null) { 
                final_users.push(data[i][3]);
                $('#final_users').val(final_users);
                var out = '<div class="row">              <div class="col-sm-1 text-center">                <a href="#" id="db'+data[i][3]+'" class="btn btn-danger btn-cross btn-sm"><i class="fas fa-times"></i></a>              </div>              <div class="col-sm-8">                '+data[i][4]+'              </div>            </div>';

                document.getElementById("selected_users").innerHTML +=out;  
                $(document).on('click',"#db"+data[i][3],function(){
                  //$('#already_selected').hide();
                  $(this).parent().parent().remove();
                  var id = $(this).attr('id');
                  id = id.replace("db","");
                  var index = final_users.indexOf(id);
                  if (index > -1) {
                   final_users.splice(index, 1);
                 }
                 $('#final_users').val(final_users);
               });
              }
            }

          }
        });

      }
      else if (type == "create") {
        $('.modal-footer2').show();
        $('.modal-footer1').hide();
        $('.modal-title').text("Create Room");
        final_users = [];
        $('#final_users').val("");
      }
    });

    $('#create_room').click(function() {
      clear_modal();
      $('#modal_type').val("create");
    });

    $('.room_options').click(function() {
      clear_modal();
      var id = $(this).attr('id');
      id = id.replace("update_room", "");
      $('#room_id').val(id);
      $('#modal_type').val("update");
    });

    $('#list_users').change(function(){
      //$('#already_selected').hide();
      var value = document.getElementById("list_users").value;
      if(value != '0') {
        if(final_users.indexOf(value) == -1) {
          var text = $("#list_users option:selected").text();
          final_users.push(value);
          $('#final_users').val(final_users);
          var out = '<div class="row">              <div class="col-sm-1 text-center">                <a href="#" id="db'+value+'" class="btn btn-danger btn-cross btn-sm"><i class="fas fa-times"></i></a>              </div>              <div class="col-sm-8">                '+text+'              </div>            </div>';

          document.getElementById("selected_users").innerHTML +=out;  
          $(document).on('click',"#db"+value,function(){
            //$('#already_selected').hide();
            $(this).parent().parent().remove();
            var index = final_users.indexOf(value);
            if (index > -1) {
             final_users.splice(index, 1);
           }
           $('#final_users').val(final_users);
         });

        }
        else
        {
          //console.log("hello");
          $('#already_selected').show();
        }
      }
      $('#list_users').val("0");
    });

    $('.toggle-slide').click(function(){
      var target = $(this).attr('data-target');
      $(target).slideToggle();
    });

    setInterval(function(){
      update_last_activity();
      get_users_status();
    }, 5000);

    setInterval(function(){
      get_unread_count();
      get_unread_count_room();
      load_msgs3();
    }, 1000);

    $('.write_msg').on("keyup", function(e) {
      if (e.keyCode == 13) {
        send_message();
      }
    });
    //load_msgs2();
  });

function clear_modal() {
  $("input[name='title']").val("");
  $("input[name='description']").val("");
  final_users = [];
  $('#final_users').val("");
  document.getElementById("selected_users").innerHTML = "";
}

function delete_room() {
  if(confirm('Are you sure you want to delete this room?')){
    var room_id = $('#room_id').val();
    $.ajax({
      url:"delete_room.php",
      method:"POST",
      data: {room_id: room_id},
      success:function(data){
        location.reload();
      }
    });
  }
}

function fetch_user() {
  $.ajax({
    url:"fetch_user.php",
    method:"POST",
    success:function(data){
      $('#user_details').html(data);
    }
  })
}

function update_last_activity() {
  $.ajax({
    url:"update_last_activity.php",
    success:function(){

    }
  })
}

function get_users_status() {
  $.ajax({
    url:"get_users_status.php",
    method:"POST",
    success:function(data){
      $('.online_status').hide();
        //console.log(data);
        var data = JSON.parse(data);
        for (i = 0; i < data.length; i++){
          id = ".state";
          id += data[i].user_id;
          $(id).find("span").show();
        }
      }
    })
}

function get_unread_count(play = true) {
  $.ajax({
    url:"get_unread_count.php",
    method:"POST",
    success:function(data){
      $('.notifcation').hide();    
      var all = $(".notifcation").map(function() {
        return this.id;
      }).get();


      var data = JSON.parse(data);
      //console.log(all);
      for (i = 0; i < data.length; i++) {
        var id = data[i].from_user_id;
        var c = data[i]. count;
        var name = 'noti' + id;
        var index = all.indexOf(name);
        if (index > -1) {
          all.splice(index, 1);
        }
        var old_value = $('#noti' + id).text();
        $('#noti' + id).text(c);
        $('#noti' + id).show();
        var new_value = $('#noti' + id).text();
        if(new_value > old_value && play)
        {
          //console.log("Playing Sound");

          playSound('alert');
          //alert("message_recieved");
        }
      }
      for (i = 0; i < all.length; i++) {
        $('#' + all[i]).text("0");
      }


    }
  });
}

function get_unread_count_room(play = true) {
  $('.notifcation_room').hide();

  var all = $(".notifcation_room").map(function() {
    return this.id;
  }).get();

  var ids = [];
  for (i = 0; i < all.length; i++)
  {
    var id = all[i].replace("noti_room","")
    //console.log($('#room_last'+id).val());
    ids.push(id);
  }
  

  $.ajax({
    url:"get_unread_count_room.php",
    method:"POST",
    data: {ids: ids},
    success:function(data){
      //console.log(data);

      var data = JSON.parse(data);      
      for (i = 0; i < data.length; i++) {
        var id = ids[i];
        var c = data[i];

        var name = 'noti_room' + id;
        var index = all.indexOf(name);
        if (index > -1) {
          all.splice(index, 1);
        }

        var old_value = $('#noti_room' + id).text();
        $('#noti_room' + id).text(c);
        //$('#noti_room' + id).show();
        var new_value = $('#noti_room' + id).text();
        if(new_value > 0){
          //console.log(old_value);
          $('#noti_room' + id).show();
        }
        if(new_value > old_value && play)
        {
          //console.log("Playing Sound");
          playSound('alert');
          //alert("message_recieved");
        }
      }
      for (i = 0; i < all.length; i++) {
        $('#' + all[i]).text("0");

      }
    }
  });
  
}

function load_msgs_room(x, name) {
  $('#x').val(x);
  $('#name').val(name);
  $('#chat_type').val('room');

  load_msgs2();
}

function load_msgs(x, name) {
  $('#x').val(x);
  $('#name').val(name);
  $('#chat_type').val('user');

  load_msgs2();
}

function load_msgs2()  {
  var type = $('#chat_type').val(); //type  user or room
  var name = $('#name').val();      //name  user or room
  $('#reciver_info').html(name);

  var n = $('#num_msgs').val();     //number of messages
  var x = $('#x').val();            //id    user or room
  $.ajax({
    url:"get_messages.php",
    method:"POST",
    data: {id:x, num:n, type:type},
    success:function(data) {
      //console.log(data);
      
      var msgs = JSON.parse(data);
      $('.msg_history').empty();
      $('#user_id').val(x);       //for sending purposes

      for (i = msgs.length-1; i >= 0; i--)
      {
        var html = "";
        if(type == 'user') {
          if(msgs[i][2] == x) {
            html = '<div class="outgoing_msg"><div class="sent_msg"> <p>' + msgs[i][0] + '</p><span class="time_date time_date_sent">' + msgs[i][1] + '</span></div></div>';
          }
          else {
            html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><p>' + msgs[i][0] + ' </p><span class="time_date time_date_recv"> '+ msgs[i][1] +'</span></div></div></div>';
          }
          $('#last_message_id').val(msgs[0][3]);

        } else {
          if(msgs[i][2] != x) {

            html = '<div class="outgoing_msg"><div class="sent_msg"> <p>' + msgs[i][0] + ' </p><span class="time_date time_date_sent">' + msgs[i][1] + '</span></div></div>';
          }
          else {
            if(i < msgs.length-1 && msgs[i][4] == msgs[i+1][4]) {
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><p>' + msgs[i][0] + ' </p><span class="time_date time_date_recv"> '+ msgs[i][1] + ' </span></div></div></div>';
            }
            else 
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><span class="badge badge-pill badge-info username">'+msgs[i][4]+'</span><br/><p>' + msgs[i][0] + ' </p><span class="time_date time_date_recv"> '+ msgs[i][1] + ' </span></div></div></div>';
          }
          //$('#last_message_id').val(msgs[0][5]);
          $('#last_message_sender').val(msgs[0][3]);
          $('#room_last' + x).val(msgs[0][5]);

        }

        $('.msg_history').append(html);
      }
      //console.log($("#room_last" + x).val());
      
    }
  })
}

function load_msgs3() {
  var type = $('#chat_type').val(); //type  user or room
  var name = $('#name').val();      //name  user or room
  //$('#reciver_info').html(name);

  //var n = $('#num_msgs').val();     //number of messages
  var x = $('#x').val();            //id    user or room
  var message_id = $('#last_message_id').val();
  //console.log(message_id);

  $.ajax({
    url:"get_new_messages.php",
    method:"POST",
    data: {id:x, type:type, message_id: message_id},
    success:function(data) {
      //console.log(data);
      
      var msgs = JSON.parse(data);
      //$('.msg_history').empty();
      //$('#user_id').val(x);       //for sending purposes

      for (i = msgs.length-1; i >= 0; i--)
      {
        var html = "";
        if(type == 'user') {
          if(msgs[i].to_user_id == x) {
            html = '<div class="outgoing_msg"><div class="sent_msg"> <p>' + msgs[i].message + '</p><span class="time_date time_date_sent">' + msgs[i].time + '</span></div></div>';
          }
          else {
            html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><p>' + msgs[i].message + ' </p><span class="time_date time_date_recv"> '+ msgs[i].time +'</span></div></div></div>';
            playSound('alert');

          }
          $('#last_message_id').val(msgs[0].id);
        } else {
          //console.log(msgs[i]);
          //console.log(x);
          
          if(msgs[i].to_chat_room_id != x) {

            html = '<div class="outgoing_msg"><div class="sent_msg"> <p>' + msgs[i].message + ' </p><span class="time_date time_date_sent">' + msgs[i].time + '</span></div></div>';
          }
          else {
            var val = $('#last_message_sender').val();
            //console.log(msgs[i].from_user_id);
            //console.log(val);
            if(msgs[i].from_user_id == val) {
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><p>' + msgs[i].message + ' </p><span class="time_date time_date_recv"> '+ msgs[i].time + ' </span></div></div></div>';
            }
            else 
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><span class="badge badge-pill badge-info username">'+msgs[i].name+'</span><br/><p>' + msgs[i].message + ' </p><span class="time_date time_date_recv"> '+ msgs[i].time + ' </span></div></div></div>';
            playSound('alert');

          }
          
          
          //$('#last_message_id').val(msgs[0].message_id);
          $('#last_message_sender').val(msgs[0].from_user_id);
          $('#room_last' + x).val(msgs[0].message_id);
        }
        $('.msg_history').append(html);
      }
      //console.log($("#room_last" + x).val());   
       
    }
  })
}

function send_message() {
  var msg = $('.write_msg').val();
  var to_id = $('#user_id').val();
  var type = $('#chat_type').val();
  if(msg.length > 0){
    $.ajax({
      url:"send_message.php",
      method:"POST",
      data: {message:msg, to_user:to_id, type: type},
      success:function(data){

      }
    })
    $('.write_msg').val("");
  }
}

</script>