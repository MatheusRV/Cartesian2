$(function(){
  /*-----------------CONTROLS----------------*/
    $('body').on('mousedown', '.controls .commands .bt:not(#empty)', function(e){
      e.stopPropagation();
      var el = $(this);
      var action = el.attr('id');
      var qtd = el.closest('.controls').find('.velocity .vel.active').text();
      var topic = '';
      
      if(action == 'left' || action == 'down') qtd = -qtd;
      
      if(action == 'left' || action == 'right'){ topic = 'x'; }
      else if(action == 'up' || action == 'down'){ topic = 'y'; }
      else topic = '';

      publish(topic, qtd);
      move(topic, qtd);
    });

    $('body').on('click', '.controls .buttons .toggle', function(e){
      $(this).toggleClass('active');
      publish('joystick', $(this).hasClass('active'));
    });

    $('body').on('click', '.controls .buttons .reset', function(e){
      setPosition(0,0);
    });

    $('body').on('click', '.controls .velocity .vel', function(e){
      var el = $(this);
      if(el.hasClass('active')){ el.toggleClass('active'); }
      else{
        el.parent().find('.vel').each(function(index){
          $(this).removeClass('active');
        })
        el.addClass('active');
      }
    });

    $(".map #point").draggable({
      cursor: 'move',
      snap: 'inner',
      containment: [$('.map').offset().left, $('.map').offset().top - 19.2, $('.map').offset().left + $('.map').width(), $('.map').offset().top + $('.map').height() - 19.2],
      drag: function(ev, ui){
        var coordX = Math.round( ((ui.position.left/$('.map').width())*100) * 100) / 100;
        var coordY = (1 - ((ui.position.top+19.2)/$('.map').height())) * 100;
        setLabel((Math.round(coordX * 100)/100), (Math.round(coordY * 100)/100));
      },
      stop: function(ev, ui){
        var coordX = Math.round( ((ui.position.left/$('.map').width())*100) * 100) / 100;
        var coordY = (1 - ((ui.position.top+19.2)/$('.map').height())) * 100;
        if(coordX >= 0 && coordX <= 100 && coordY >= 0 && coordY <= 100){ setPosition(coordX, coordY); }
      }
    });

    $(window).resize(function(){
      $(".map #point").draggable("option", "containment", [$('.map').offset().left, $('.map').offset().top - 19.2, $('.map').offset().left + $('.map').width(), $('.map').offset().top + $('.map').height() - 19.2]);
    });
  /*------------------------------------------*/


  /*-----------------MQTT----------------*/
    function publish(topic, message){
      var post = {topic: 'Embarcados/'+topic, message: message};
      $.ajax({
        beforeSend:function(){ $('body').css('cursor', 'wait');},
        url: 'ajax/mqtt/publish.php',
        type: 'POST',
        dataType: 'json',
        data: post
      }).done(function(data){
        $('body').css('cursor', 'initial');

        if(data.success && data.qtd != undefined){ 
          
        }
        else{
          console.log('Error');
        }
      }).fail(function(data){
        $('body').css('cursor', 'initial');
        console.log("Erro ao enviar requisição.");
      });/**/
    }
  /*------------------------------------------*/


  /*-----------------POSITION----------------*/
    function setPosition(x, y){
      $('.map #point').attr('style', 'bottom: '+y+'%; left: '+x+'%;');
      setLabel((Math.round(x * 100)/100), (Math.round(y * 100)/100));
    }

    function move(axis, qtd){
      var {x, y} = getPosition();

      console.log('Antes: '+x+', '+y);
      if(axis == 'x' && parseFloat(parseFloat(qtd) + parseFloat(x)) >= 0 && parseFloat(parseFloat(qtd) + parseFloat(x)) <= 100) x = parseFloat(parseFloat(qtd) + parseFloat(x));
      else if(axis == 'y' && parseFloat(parseFloat(qtd) + parseFloat(y)) >= 0 && parseFloat(parseFloat(qtd) + parseFloat(y)) <= 100) y =  parseFloat(parseFloat(qtd) + parseFloat(y));
      console.log('Depois: '+x+', '+y);

      $('.map #point').attr('style', 'bottom: '+y+'%; left: '+x+'%;');
      setLabel(x,y);
    }

    function resetPosition(){
      $('.map #point').attr('style', 'bottom: 0%; left: 0%;');
      setLabel(0,0);
    }

    function setLabel(x, y){
      $('.map #point').attr('data-x', x+'%');
      $('.map #point').attr('data-y', y+'%');
    }

    function getPosition(){
      return position = {x: $('.map #point').attr('data-x').slice(0, -1),
        y: $('.map #point').attr('data-y').slice(0, -1)};
    }
  /*------------------------------------------*/
});