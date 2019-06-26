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
  
  <!------ Include the above in your HEAD tag ---------->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
  <link href="chat_styles.css" rel="stylesheet">
  <script type="text/javascript">
    function over(x) {
      x.style.backgroundColor = "#75c3dc";
      x.style.textDecoration = "underline";

    }
    function out(x) {
      x.style.backgroundColor = "lightblue";
      x.style.textDecoration = "none";

    }
  </script>
</head>
<body>
  <?php include('nav.php'); ?>
  
  <div class="container">
    <div class="messaging">
      <div class="inbox_msg">  
        <!-- Rooms Panel -->
        <div class="users_list">
          <!-- Label -->
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Rooms</h4>
            </div>
            <?php if($_SESSION['type'] == 'Admin') {
              echo "
              <div class=\"srch_bar\">
              <button class=\"btn btn-info btn-sm\">New</button>
              </div>";
            }
            ?>
          </div>
          <!-- Rooms List -->
          <div class="inbox_chat">
            <!-- Room Heading -->
            <div class="room" onmouseover="over(this);" onmouseout="out(this);" data-toggle="collapse" data-target="#admins">
              <p class="room_name">Admins</p>
            </div>
            <!-- Room Members -->
            <div id="admins" class="collapse">
                <div class="members">
                  <div class="chat_list">
                    <div class="chat_people">
                      <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                      <div class="chat_ib">
                        <h5>Sunil Rajput <span class="chat_date">Online</span></h5>
                      </div>
                    </div>
                  </div>
                </div>
             <!--  <div class="members">
                <div class="chat_list">
                  <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="chat_ib">
                      <h5>Sunil Rajput <span class="chat_date">Online</span></h5>
                    </div>
                  </div>
                </div>
              </div>
              <div class="members">
                <div class="chat_list">
                  <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="chat_ib">
                      <h5>Sunil Rajput <span class="chat_date">Online</span></h5>
                    </div>
                  </div>
                </div>
              </div> -->
            </div>

            <?php 
              if($_SESSION['type'] == 'Admin') {
                echo "
                  <!-- Room Heading -->
                  <div class=\"room\" onmouseover=\"over(this);\" onmouseout=\"out(this);\" data-toggle=\"collapse\" data-target=\"#members\">
                    <p class=\"room_name\">All Users</p>
                  </div>
                  <!-- Room Members -->
                  <div id=\"members\" class=\"collapse\">
                    <div class=\"members\">
                      <div class=\"chat_list\">
                        <div class=\"chat_people\">
                          <div class=\"chat_img\"> <img src=\"https://ptetutorials.com/images/user-profile.png\" alt=\"sunil\"> </div>
                          <div class=\"chat_ib\">
                            <h5>Sunil Rajput <span class=\"chat_date\">Online</span></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>";
              }
            ?>

            <!-- Room Heading -->
            <div class="room" onmouseover="over(this);" onmouseout="out(this);" data-toggle="collapse" data-target="#members2">
              <p class="room_name">Chat Room 1</p>
              <button class="room_options btn btn-info btn-sm">Edit</button>
            </div>
            <!-- Room Members -->
            <div id="members2" class="collapse">
              <div class="members">
                <div class="chat_list">
                  <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="chat_ib">
                      <h5>Sunil Rajput <span class="chat_date">Online</span></h5>
                    </div>
                  </div>
                </div>
              </div>
              <div class="members">
                <div class="chat_list">
                  <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="chat_ib">
                      <h5>Sunil Rajput <span class="chat_date">Online</span></h5>
                    </div>
                  </div>
                </div>
              </div>
              <div class="members">
                <div class="chat_list">
                  <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="chat_ib">
                      <h5>Sunil Rajput <span class="chat_date">Online</span></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Chat Panel -->
       <!--  <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Recent</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar"  placeholder="Search" >
                <span class="input-group-addon">
                  <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span>
              </div>
            </div>
          </div>
          <div class="inbox_chat">
            <div class="chat_list active_chat">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                  <p>Test, which is a new approach to have all solutions 
                    astrology under one roof.
                  </p>
                </div>
              </div>
            </div>
            <div class="chat_list">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                  <p>Test, which is a new approach to have all solutions 
                    astrology under one roof.
                  </p>
                </div>
              </div>
            </div>
            <div class="chat_list">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                  <p>Test, which is a new approach to have all solutions 
                    astrology under one roof.
                  </p>
                </div>
              </div>
            </div>
            <div class="chat_list">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                  <p>Test, which is a new approach to have all solutions 
                    astrology under one roof.
                  </p>
                </div>
              </div>
            </div>
            <div class="chat_list">
              <div class="chat_people">
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>Sunil Rajput <span class="chat_date">Dec 25</span></h5>
                  <p>Test, which is a new approach to have all solutions 
                    astrology under one roof.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <!-- Message Panel -->
        <div class="mesgs">
          <div>
            <p id="reciver_info">Hello</p>
          </div>
          <hr/>
          <div class="msg_history">
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test which is a new approach to have all
                    solutions
                  </p>
                  <span class="time_date"> 11:01 AM    |    June 9</span>
                </div>
              </div>
            </div>
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Test which is a new approach to have all
                  solutions
                </p>
                <span class="time_date"> 11:01 AM    |    June 9</span> 
              </div>
            </div>
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>
                    Test, which is a new approach to have
                  </p>
                  <span class="time_date"> 11:01 AM    |    Yesterday</span>
                </div>
              </div>
            </div>
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Apollo University, Delhi, India Test

                </p>
                <span class="time_date"> 11:01 AM    |    Today</span> 
              </div>
            </div>
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>We work directly with our designers and suppliers,
                    and sell direct to you, which means quality, exclusive
                    products, at a price anyone can afford.
                  </p>
                  <span class="time_date"> 11:01 AM    |    Today</span>
                </div>
              </div>
            </div>
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Apollo University, Delhi, India Test

                </p>
                <span class="time_date"> 11:01 AM    |    Today</span>
              </div>
            </div>
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" placeholder="Type a message" />
              <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

<script>  
  $(document).ready(function() {

  //fetch_user();

  setInterval(function(){
    update_last_activity();
  //fetch_user();
}, 5000);

  function fetch_user()
  {
    $.ajax({
      url:"fetch_user.php",
      method:"POST",
      success:function(data){
        $('#user_details').html(data);
      }
    })
  }

  function update_last_activity()
  {
    $.ajax({
      url:"update_last_activity.php",
      success:function(){

      }
    })
  }

});  

</script>