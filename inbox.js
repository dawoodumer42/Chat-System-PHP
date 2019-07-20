function playSound(filename){
  var mp3Source = '<source src="' + filename + '.mp3" type="audio/mpeg">';
  var oggSource = '<source src="' + filename + '.ogg" type="audio/ogg">';
  var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3">';
  document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
}

$(document).ready(function() {
  $(".type_msg").hide();
  $('#already_selected').hide();
  $('.online_status').hide();

  var final_users = [];

  get_users_status();
  get_unread_count(false);
  get_unread_count_room(false);

  $(window).on('shown.bs.modal', function() {
    var type = $('#modal_type').val();
    if(type == "update") {
      $('.modal-footer1').show();
      $('.modal-footer2').hide();
      $('.modal-title').text("Update Room");

      var room_id = $('#room_id').val();

      var is_moderator = false;
      $.ajax({
        url:"get_room_user_type.php",
        method: "POST",
        async: false,
        data: {room_id: room_id},
        success:function(data){
          var type = JSON.parse(data);
          if(type['type'] == 'Moderator')
            is_moderator = true;
        }
      });

      $.ajax({
        url:"get_room_details.php",
        method:"POST",
        data: {id:room_id},
        success:function(data){
            //console.log(room_id);
            //console.log(data);
            data = JSON.parse(data);
            $("input[name='title']").val(data[0][0]);
            $("input[name='description']").val(data[0][1]);
            final_users = [];
            for(i = 0; i < data.length; i++) {
              if(data[i][3] != null) { 
                final_users.push(data[i][3]);
                $('#final_users').val(final_users);
                var type;

                var action_promote = '</div><div class="col-sm-3"><a id="promote'+data[i][2]+'" href="#"><i class=\"badge badge-info\">Make Moderator</i></a ></div></div>';
                var action_demote = '</div><div class="col-sm-3"><a id="demote'+data[i][2]+'" href="#"><i class=\"badge badge-danger\">Remove Moderator</i></a ></div></div>';

                var out = '<div class="row"><div class="col-sm-1 text-center"><a href="#" id="db'+data[i][3]+'" class="btn btn-danger btn-cross btn-sm"><i class="fas fa-times"></i></a></div><div class="col-sm-6">'+data[i][4];
                console.log(is_moderator);
                if(!is_moderator) {
                  if(data[i][5] == 'User')
                    out = out + action_promote;
                  else
                    out = out + action_demote;
                }

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

                $(document).on('click', "#promote"+data[i][2], function(){
                  var id = $(this).attr('id');
                  id = id.replace("promote","");
                  promote(id);
                });

                $(document).on('click', "#demote"+data[i][2], function(){
                  var id = $(this).attr('id');
                  id = id.replace("demote","");
                  demote(id);
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
          var out = '<div class="row"><div class="col-sm-1 text-center"><a href="#" id="db'+value+'" class="btn btn-danger btn-cross btn-sm"><i class="fas fa-times"></i></a></div><div class="col-sm-6">'+text+'</div><div class="col-sm-3"></div></div>';

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

  function promote(id) {
  //alert(id);
  $.ajax({
    url:"promote.php",
    method:"POST",
    data: {id: id},
    success:function(data){
      location.reload();
    }
  })
}
function demote(id) {
  //alert(id);
  $.ajax({
    url:"demote.php",
    method:"POST",
    data: {id: id},
    success:function(data){
      location.reload();
    }
  })
}

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
  $('#num_msgs').val(20);
  load_msgs2();
}

function load_msgs(x, name) {
  $('#x').val(x);
  $('#name').val(name);
  $('#chat_type').val('user');
  $('#num_msgs').val(20);
  load_msgs2();
}

function load_msgs2()  {

  $(".type_msg").show();

  var type = $('#chat_type').val(); //type  user or room
  var name = $('#name').val();      //name  user or room
  $('#reciver_info').html(name);

  var n = $('#num_msgs').val();     //number of messages
  var x = $('#x').val();            //id    user or room
  //console.log(n);
  var show_trash = false;
  if(type != 'user') {
    $.ajax({
      url:"get_room_user_type.php",
      method: "POST",
      data: {room_id: x},
      success:function(data){
        //console.log(data);
        var type = JSON.parse(data);
        if(type['type'] == 'Moderator'  || type['type'] == 'Admin')
          show_trash = true;
      }
    });
  }

  $.ajax({
    url:"get_messages.php",
    method:"POST",
    data: {id:x, num:n, type:type},
    success:function(data) {
      //console.log(data);
      
      var msgs = JSON.parse(data);
      $('.msg_history').empty();
      $('.msg_history').append('<div class="view_more"><a href="#" onclick="load_more();">View More...</a></div>');
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
          var trash = '';
          if(show_trash)
            trash = '<a href="#" id="del_msg'+msgs[i][5]+'" onclick="del_msg('+msgs[i][5]+');"><i class="fa fa-trash" aria-hidden="true"></i></a>';  
          
          if(msgs[i][2] != x) {
            html = '<div class="outgoing_msg"><div class="sent_msg"> <p>' + msgs[i][0] + ' </p><span class="time_date time_date_sent">' + msgs[i][1] + '</span>'+trash+'</div></div>';
          }
          else {
            if(i < msgs.length-1 && msgs[i][4] == msgs[i+1][4]) {
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><p>' + msgs[i][0] + ' </p><span class="time_date time_date_recv"> '+ msgs[i][1] + ' </span>'+trash+'</div></div></div>';
            }
            else 
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><span class="badge badge-pill badge-info username">'+msgs[i][4]+'</span><br/><p>' + msgs[i][0] + ' </p><span class="time_date time_date_recv"> '+ msgs[i][1] + ' </span>'+trash+'</div></div></div>';
          }
          //$('#last_message_id').val(msgs[0][5]);
          $('#last_message_sender').val(msgs[0][3]);
          $('#room_last' + x).val(msgs[0][5]);

        }

        $('.msg_history').append(html);
        var element = document.getElementById("msg_history");
        element.scrollTop = element.scrollHeight;
        // $(document).on('click', "#del_msg"+msgs[i][5], function(){
        //   var id = $(this).attr('id');
        //   id = id.replace("del_msg","");
        //   del_msg(id);
        // });
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

  var show_trash = false;
  if(type != 'user') {
    $.ajax({
      url:"get_room_user_type.php",
      method: "POST",
      data: {room_id: x},
      success:function(data){
        //console.log(data);
        var type = JSON.parse(data);
        if(type['type'] == 'Moderator'  || type['type'] == 'Admin')
          show_trash = true;
      }
    });
  }

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
          var trash = '';
          if(show_trash)
            trash = '<a href="#" id="del_msg'+msgs[i].message_id+'" onclick="del_msg('+msgs[i].message_id+');"><i class="fa fa-trash" aria-hidden="true"></i></a>';  
          //console.log(msgs[i]);
          //console.log(x);
          
          if(msgs[i].to_chat_room_id != x) {

            html = '<div class="outgoing_msg"><div class="sent_msg"> <p>' + msgs[i].message + '</p><span class="time_date time_date_sent">' + msgs[i].time + '</span>'+trash+'</div></div>';
          }
          else {
            var val = $('#last_message_sender').val();
            //console.log(msgs[i].from_user_id);
            //console.log(val);
            if(msgs[i].from_user_id == val) {
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><p>' + msgs[i].message + ' </p><span class="time_date time_date_recv"> '+ msgs[i].time + ' </span>'+trash+'</div></div></div>';
            }
            else 
              html = '<div class="incoming_msg"><div class="received_msg"><div class = "received_withd_msg"><span class="badge badge-pill badge-info username">'+msgs[i].name+'</span><br/><p>' + msgs[i].message + ' </p><span class="time_date time_date_recv"> '+ msgs[i].time + ' </span>'+trash+'</div></div></div>';
            playSound('alert');

          }
          
          
          //$('#last_message_id').val(msgs[0].message_id);
          $('#last_message_sender').val(msgs[0].from_user_id);
          $('#room_last' + x).val(msgs[0].message_id);
        }
        $('.msg_history').append(html);
        var element = document.getElementById("msg_history");
        element.scrollTop = element.scrollHeight;
        // $(document).on('click', "#del_msg"+msgs[i][5], function(){
        //   var id = $(this).attr('id');
        //   id = id.replace("del_msg","");
        //   del_msg(id);
        // });
      }
      //console.log($("#room_last" + x).val());   

    }
  })
}

function del_msg(id) {
  $.ajax({
    url:"del_msg.php",
    method:"POST",
    data: {id: id},
    success:function(data){
      load_msgs2();
    }
  });
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

function load_more() {

  var n = $('#num_msgs').val();     //number of messages
  $('#num_msgs').val(parseInt(n)+  10);     //number of messages
  load_msgs2();
  var myDiv = document.getElementById("msg_history");
  myDiv.scrollTop = 0;
}
