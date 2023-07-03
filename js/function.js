$(function(){
  /*-----------------POSITION----------------*/
    var lastPosition = getPosition();

    function setPosition(x, y){
      if(x > 85){ $('.map #point').addClass('right'); }
      else{ $('.map #point').removeClass('right'); }
      if(y > 95){ $('.map #point').addClass('up'); }
      else{ $('.map #point').removeClass('up'); }
      $('.map #point').attr('style', 'bottom: '+y+'%; left: '+x+'%;');
      setLabel((Math.round(x * 100)/100), (Math.round(y * 100)/100));
      att();
      lastPosition = getPosition();
    }

    function move(axis, qtd){
      var {x, y} = getPosition();

      if(axis == 'x' && parseFloat(parseFloat(qtd) + parseFloat(x)) >= 0 && parseFloat(parseFloat(qtd) + parseFloat(x)) <= 100) x = parseFloat(parseFloat(qtd) + parseFloat(x));
      else if(axis == 'y' && parseFloat(parseFloat(qtd) + parseFloat(y)) >= 0 && parseFloat(parseFloat(qtd) + parseFloat(y)) <= 100) y =  parseFloat(parseFloat(qtd) + parseFloat(y));

      setPosition(x,y);
    }

    function resetPosition(){ setPosition(0,0); }

    function finalPosition(){ setPosition(100, 100); }

    function setLabel(x, y){
      $('.map #point').attr('data-x', x+'%');
      $('.map #point').attr('data-y', y+'%');
    }

    function getPosition(){
      return position = {x: $('.map #point').attr('data-x').slice(0, -1),
        y: $('.map #point').attr('data-y').slice(0, -1)};
    }
  /*------------------------------------------*/


  /*-----------------MQTT----------------*/
    function delay(time) {
      var d1 = new Date();
      var d2 = new Date();
      while (d2.valueOf() < d1.valueOf() + time) {
        d2 = new Date();
      }
    }

    var brokerStatus = false; var brokerChecked = false;

    function brokerIsConnected(){
      $.ajax({
        beforeSend:function(){ brokerChecked = false; },
        url: 'ajax/mqtt/check_connection.php',
        type: 'GET',
        dataType: 'json',
      }).done(function(data){
        if(data.success){
          if(!brokerStatus){
            $('section.home').removeClass('disabled');
            $('.map #point').draggable('enable');
            $('article.broker-disconnected').fadeOut(100);
            $('article.broker-connected').fadeIn(500).fadeOut(500);
            brokerStatus = true;
          }
        }
        else if(brokerStatus && (!data.success || data == undefined || !data.isArray())){
          $('.map #point').draggable('disable');
          $('section.home').addClass('disabled');
          $('article.broker-disconnected').fadeIn(300);
          brokerStatus = false;
        }
        brokerChecked = true;
        return data.success;
      }).fail(function(data){
        $('.map #point').draggable('disable');
        $('section.home').addClass('disabled');
        $('article.broker-disconnected').fadeIn(300);
        brokerStatus = false;
        brokerChecked = true;
      });
      return false;
    }

    function subscribe(topic, qos = 2){
      $.ajax({
        beforeSend:function(){ $('body').css('cursor', 'wait');},
        url: 'ajax/mqtt/subscribe.php',
        type: 'POST',
        dataType: 'json',
        data: {topic: topic, qos: qos}
      }).done(function(data){
        $('body').css('cursor', 'initial');
        if(data.success){
          if(data.topic != false && data.message != false){
            if(data.topic == "x"){ setPosition(data.message, lastPosition.y); }
            else if(data.topic == "y"){ setPosition(lastPosition.x, data.message); }
            else if(data.topic == "bt/left" || data.topic == "bt/up" || data.topic == "bt/down" || data.topic == "bt/right"){
              var axis = "";
              var qtd = $('.controls .velocity .vel.active').text();
              if(data.topic == 'bt/left' || data.topic == 'bt/down') qtd = -qtd;
              
              if(data.topic == 'bt/left' || data.topic == 'bt/right'){ axis = 'x'; }
              else if(data.topic == 'bt/up' || data.topic == 'bt/down'){ axis = 'y'; }
              else return false;
              move(axis, qtd);
            }
            else return false;
          }
        }
      });/**/
    }

    function publish(topic, message, qos = 2){
      var post = {topic: topic, message: message, qos: qos};
      $.ajax({
        beforeSend:function(){ $('body').css('cursor', 'wait');},
        url: 'ajax/mqtt/publish.php',
        type: 'POST',
        dataType: 'json',
        data: post
      }).done(function(data){
        $('body').css('cursor', 'initial');

        if(data.success){ 
          
        }
        else{
          console.log('Error');
        }
      });/**/
    }

    function att(){
      if(brokerStatus){
        var {x, y} = getPosition();
        var post = {x: x, y: y};
        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
          url: 'ajax/submit/position.php',
          type: 'POST',
          dataType: 'json',
          data: post
        }).done(function(data){
          $('article.message-load').fadeOut(100);
          $('body').css('cursor', 'initial');
        });/**/

        if(x != lastPosition.x){ publish('x', x); delay(100); }
        if(y != lastPosition.y){ publish('y', y); }
      }
      else{
        setPosition(lastPosition.x, lastPosition.y);
      }
    }
  /*------------------------------------------*/


  /*-----------------CONTROLS----------------*/
    $('body').on('mousedown', '.controls .commands .bt:not(#empty)', function(e){
      e.stopPropagation();
      var el = $(this);
      
      if(!el.closest('section').hasClass('disabled')){
        var action = el.attr('id');
        var qtd = el.closest('.controls').find('.velocity .vel.active').text();
        var topic = '';
        if(action == 'left' || action == 'down') qtd = -qtd;
        
        if(action == 'left' || action == 'right'){ topic = 'x'; }
        else if(action == 'up' || action == 'down'){ topic = 'y'; }
        else return false;

        move(topic, qtd);
      }
    });

    var intervalBt;
    $('body').on('click', '.controls .buttons .toggle', function(e){
      var el = $(this);
      
      if(!el.closest('section').hasClass('disabled')){
        el.toggleClass('active');
        if(el.hasClass('active')){ intervalBt = setInterval(function(){ subscribe('bt/#', 0); }, 1000); publish('joystick', 1, 2); }
        else{ clearInterval(intervalBt); publish('joystick', 0, 2); }
      }
    });

    $('body').on('click', '.controls .buttons .reset', function(e){
      e.stopPropagation();
      var el = $(this);
      if(!el.closest('section').hasClass('disabled')){ setPosition(0,0); }
    });

    $('body').on('click', '.controls .buttons .add-point', function(e){
      e.stopPropagation();
      var el = $(this);
      
      if(!el.closest('section').hasClass('disabled')){
        var post = getPosition();
        post.forward = 1;

        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait');},
          url: 'ajax/form/new/points.php',
          type: 'POST',
          dataType: 'json',
          data: post
        }).done(function(data){
          $('article.message-load').fadeOut(100); 
          $('body').css('cursor', 'initial');

          if(data.success){
            $('section.form').append(data.result);

            $('.'+data.form).fadeIn(300);
          }
          else if(data.reload){
            location.reload();
          }
          else{
            console.log('Error');
          }
        });/**/
      }
    });
    $('body').on('click', '.controls .velocity .vel', function(e){
      var el = $(this);
      if(!el.closest('section').hasClass('disabled')){
        if(el.hasClass('active')){ el.toggleClass('active'); }
        else{
          el.parent().find('.vel').each(function(index){
            $(this).removeClass('active');
          })
          el.addClass('active');
        }
      }
    });

    $(window).ready(function(){
      if($("section.home").length > 0){
        $(".map #point").draggable({
          cursor: 'move',
          snap: 'inner',
          containment: [$('.map').offset().left, $('.map').offset().top - 19.2, $('.map').offset().left + $('.map').width(), $('.map').offset().top + $('.map').height() - 19.2],
          drag: function(ev, ui){
            var coordX = Math.round( ((ui.position.left/$('.map').width())*100) * 100) / 100;
            var coordY = (1 - ((ui.position.top+19.2)/$('.map').height())) * 100;
            
            if(coordX > 85){ $('.map #point').addClass('right'); }
            else{ $('.map #point').removeClass('right'); }
            if(coordY > 95){ $('.map #point').addClass('up'); }
            else{ $('.map #point').removeClass('up'); }
            
            setLabel((Math.round(coordX * 100)/100), (Math.round(coordY * 100)/100));
          },
          stop: function(ev, ui){
            var coordX = Math.round( ((ui.position.left/$('.map').width())*100) * 100) / 100;
            var coordY = (1 - ((ui.position.top+19.2)/$('.map').height())) * 100;
            if(coordX >= 0 && coordX <= 100 && coordY >= 0 && coordY <= 100){ setPosition((Math.round(coordX * 100)/100), (Math.round(coordY * 100)/100)); }
          }
        });

        $(window).resize(function(){
          $(".map #point").draggable("option", "containment", [$('.map').offset().left, $('.map').offset().top - 19.2, $('.map').offset().left + $('.map').width(), $('.map').offset().top + $('.map').height() - 19.2]);
        });

        $(document).on('keydown', function(e){
          if($('section.home').length == 1 && $('aside:visible').length == 0 && !$('section.home').hasClass('disabled')){
            if(e.keyCode == 37 || e.keyCode == 65){ move('x', -($('.controls .velocity .vel.active').text())); } //arrow left
            else if(e.keyCode == 38 || e.keyCode == 87){ move('y', ($('.controls .velocity .vel.active').text())); } //arrow up
            else if(e.keyCode == 39 || e.keyCode == 68){ move('x', ($('.controls .velocity .vel.active').text())); } //arrow right
            else if(e.keyCode == 40 || e.keyCode == 83){ move('y', -($('.controls .velocity .vel.active').text())); } //arrow down
            else if(e.keyCode == 74){ $('.controls .buttons .toggle').toggleClass('active'); } // r
            else if(e.keyCode == 82){ resetPosition(); } // r
            else if(e.keyCode == 70){ finalPosition(); } // r
            else if(e.keyCode == 107){  // +
              var i = $('.controls .velocity .vel.active').index();
              if(i < ($('.controls .velocity .vel').length)-1){
                $('.controls .velocity .vel').eq(i).removeClass('active');
                $('.controls .velocity .vel').eq(i+1).addClass('active');
              }
            }
            else if(e.keyCode == 109){  // +
              var i = $('.controls .velocity .vel.active').index();
              if(i > 0){
                $('.controls .velocity .vel').eq(i).removeClass('active');
                $('.controls .velocity .vel').eq(i-1).addClass('active');
              }
            }
          }
        })

        brokerIsConnected();

        window.setInterval(function(){ if(brokerChecked){ brokerIsConnected(); } }, 5000);
        window.setInterval(function(){ if(brokerStatus){subscribe("x", 1); subscribe("y", 1); } }, 7500);
      }
    });
  /*------------------------------------------*/
});