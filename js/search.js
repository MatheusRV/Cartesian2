$(function(){
  /*----------SEARCH----------*/
    var timer;

    $('body').on('keyup', '#search', function(e){
      e.stopPropagation();

      var code = (e.keyCode ? e.keyCode : e.which);
      if(code != 9 && code != 16 && code != 17 && code != 18 && code != 20 && code != 13 && code != 27){

        var el = $(this);
        var search = el.val();
        var file = el.attr('ajax');

        if(el.attr('type_s') != undefined){
          var type = el.attr('type_s');
        } 
        else{
          var type = '';
        }

        if(file == 'equipament'){
          var other_id = el.closest('form').find('[name=id_provider] option:selected').attr('value');
        }
        else{
          var other_id = 0;
        }

        el.css('font-weight', '400');
        el.closest('.search').find('.result').val('');

        if(el.attr('name') == 'name_client' && el.closest('form').find('#uc_generator') != undefined){
          el.closest('form').find('#uc_generator').html('<option value="0">--</option>');
          el.closest('form').find('#uc_generator').css('cursor', 'not-allowed');
          el.closest('form').find('#uc_generator').attr('disabled', true);
          el.closest('form').find('[name=uc_participant]').val('');
          el.closest('form').find('[name=uc_participant]').css('cursor', 'not-allowed');
          el.closest('form').find('[name=uc_participant]').attr('disabled', true);
          el.closest('form').find('.add-participant').attr('disabled', true);

          var parent = el.closest('form').find('.uc-participants');
          parent.find('.flex').remove();
          parent.find('.result-list').html('<li>Nenhum resultado encontrado!</li>');
        }

        if(el.attr('name') == 'uc_participant' && el.closest('form').find('[name=id_client]').val() != undefined && el.closest('form').find('#uc_generator option:selected').val() != undefined){
          type = el.closest('form').find('[name=id_client]').val();
          other_id = el.closest('form').find('#uc_generator option:selected').val();
        }
        
        clearTimeout(timer);
        timer = setTimeout(function(){
          $.ajax({
            beforeSend:function(){},
            url:'ajax/search/'+file+'.php',
            type:'POST',
            dataType:'json',
            data:{search: search, type: type, other_id}
          }).done(function(data){
            if(data.success){
              el.closest('.search').find('ul.result-list').html(data.result);
            }
            else if(data.reload){
              location.reload();
            }
            else{
              console.log('Error');
            }
          });
        }, 500);
      }

      return false;
    });
  /*------------------------------*/


  /*----------LIST----------*/
    $('body').on('click', '.search ul.result-list li', function(e){
      e.stopPropagation();

      var el = $(this);
      var name = el.html();
      var value = el.val();

      if(!el.hasClass('alone')){
        el.closest('.search').find('#search').val(name);
        el.closest('.search').find('#search').css('font-weight', '500');
        el.closest('.search').find('.result').val(value);
        if(el.closest('.search').find('.result').attr('id') == 'budgets_client'){
          el.closest('form').find('#uc_generator').css('cursor','initial');
          el.closest('form').find('#uc_generator').removeAttr('disabled');
          el.closest('form').find('[name=uc_participant]').css('cursor','initial');
          el.closest('form').find('[name=uc_participant]').removeAttr('disabled');
          el.closest('form').find('.add-participant').removeAttr('disabled');

          clearTimeout(timer);
          timer = setTimeout(function(){
            $.ajax({
              beforeSend:function(){},
              url:'ajax/search/contact_ucs.php',
              type:'POST',
              dataType:'json',
              data:{search: value}
            }).done(function(data){
              if(data.success){
                el.closest('form').find('#uc_generator').html(data.result);
              }
              else if(data.reload){
                location.reload();
              }
              else{
                console.log('Error');
              }
            });
          }, 500);
        }
      }

      verifySale();
    });
  /*------------------------------*/  


  /*----------VERIFY SALE----------*/
    function verifySale(){
      for(var i = 0; i < $('[name=id_sale]').length; i++){
        if($('[name=id_sale]').eq(i).attr('value') == 0){
          $('.have-sale').eq(i).fadeOut(100);
          $('.have-sale').eq(i).find('select').attr('required', false);
        }
        else{
          $('.have-sale').eq(i).fadeIn(100);
          $('.have-sale').eq(i).find('select').attr('required', true);
        }
      }
    }
  /*------------------------------*/


  /*----------PROVIDER----------*/
    $('body').on('change', '[name=id_provider]', function(e){
      e.stopPropagation();
      var el = $(this);

      var post = { search: 'N8o s5e2a2r5c8h t7h8i7s t5e3x2t1.5.9.8',
        type: 10,
        other_id: el.find('option:selected').val() }

      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); },
        url:'ajax/search/equipament.php',
        type: 'POST',
        dataType:'json',
        data:post
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        if(data.success){
          for(var i = 0; i < el.closest('form').find('select.inverter').length; i++){
            el.closest('form').find('select.inverter').eq(i).html(data.result);
          }
        }
        else if(data.reload){
          location.reload();
        }
        else{
          console.log('Error')
        }
      });
    });
  /*------------------------------*/



    $('body').on('change', '.inverter', function(e){
      e.stopPropagation();
      var el = $(this);


      if(el.closest('form').attr('name') == 'edit-form-sale' || el.closest('form').attr('name') == 'new-form-sale'){

        var post = { search: el.find('option:selected').val() };

        var index = el.attr('name').split('_')[2];

        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); },
          url:'ajax/search/equipament_power.php',
          type: 'POST',
          dataType:'json',
          data:post
        }).done(function(data){
          $('article.message-load').fadeOut(100); 
          if(data.success){
            if(data.result !== false && data.result != 0){
              el.closest('.flex').find('.middle').val(data.result);
              el.closest('.flex').find('.middle').attr('disabled', true);
              el.closest('.flex').find('[type=hidden]').val(data.result);
              el.closest('.flex').find('[type=hidden]').attr('name', 'inverter_power_'+index);
            }
            else{
              el.closest('.flex').find('.middle').val(0);
              el.closest('.flex').find('.middle').attr('disabled', false);
              el.closest('.flex').find('[type=hidden]').val(0);
              el.closest('.flex').find('[type=hidden]').removeAttr('name');
            }
          }
          else if(data.reload){
            location.reload();
          }
          else{
            console.log('Error')
          }
        });

      }
    });
});