$(function(){
  /*----------CONFIRM ACTION----------*/
    $('body').on('click', '[action=delete]', function(e){
      e.stopPropagation();

      return confirm('Deseja mesmo excluir?');
    });

    $('body').on('click', '[action=restore]', function(e){
      e.stopPropagation();

      return confirm('Deseja mesmo restaurar?');
    });
  /*------------------------------*/


  /*----------FORWARD----------*/
    $('body').on('click', 'button.forward', function(e){
      e.stopPropagation();

      var el = $(this);
      var r = confirm('Deseja mesmo encaminhar essa venda?');

      if(r){
        switch(el.attr('sector')){
          case 'purchases':
            var post = { id_sale: el.attr('id'),
              new_purchase: true };
            var file = 'purchase';
            break;

          case 'projects':
            var post = { id_sale: el.attr('id'),
              new_project: true };
            var file = 'project';
            break;

          case 'operational':
            var post = { id_sale: el.attr('id'),
              new_operational: true };
            var file = 'operational';
            break;

          default:
            return false;
        }

        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); },
          url:'ajax/submit/'+file+'.php',
          type: 'POST',
          dataType:'json',
          data:post
        }).done(function(data){
          $('article.message-load').fadeOut(100); 
          if(data.success){
            var sector = el.find('b').html();
            var title = sector.split(' ')[sector.split(' ').length-1];
            el.parent().append('<a class="forward" href="https://sistema.indusol.com.br/'+el.attr('sector')+'" title="Ir para '+title+'.">Encaminhado ao setor de <b>'+sector+'</b></a>');
            el.remove();
          }
          else if(data.reload){
            location.reload();
          }
          else{
            el.find('p.error').html(data.message);
          }
        });
      }

      return false;
    });
  /*------------------------------*/


  /*----------PARCELS----------*/
    $('body').on('click', '.parcel .icon .fa-plus-circle', function(e){
      e.stopPropagation();

      var el = $(this);
      if(el.attr('reference') != undefined && el.attr('reference') != ''){
        var reference = '_'+el.attr('reference');
      }
      else{
        var reference = '';
      }
      var parent = el.closest('.content-parcel');
      var qtd = parseInt(parent.find('[name=qtd_parcel'+reference+']').val());

      qtd++;

      var append = `<div class="parcel flex">
          <input class="money" type="text" name="value_parcel`+reference+`_`+qtd+`" autocomplete="off" value="000">
          <input type="date" name="date_parcel`+reference+`_`+qtd+`" autocomplete="off">

          <div class="icon" title="Remover"><i class="far fa-times-circle"></i></div>
        </div><!--parcel-->`;

      parent.append(append);
      parent.find('[name=qtd_parcel'+reference+']').val(qtd);

      $('.money').mask("#.##0,00", {reverse: true});

      return false;
    });

    $('body').on('click', '.add-inverter .fa-plus-circle', function(e){
      e.stopPropagation();

      var el = $(this);
      if(el.attr('reference') != undefined && el.attr('reference') != ''){
        var reference = '_'+el.attr('reference');
      }
      else{
        var reference = '';
      }

      if(el.attr('id') != undefined && el.attr('id') != ''){
        var id = el.attr('id');
      }
      else{
        var id = '';
      }

      var mask = false;
      var qtd = 0;
      var parent = el.closest('.content-parcel');
      if(parent.find('[name=qtd_inverter'+reference+']') != undefined && parent.find('[name=qtd_inverter'+reference+']').length > 0){
        qtd = parseInt(parent.find('[name=qtd_inverter'+reference+']').val());
      }
      else{ qtd = parseInt(parent.find('input.qtd-item').val()); }
      qtd++;

      if(id == 'sale'){
        var append = `<div class="flex">
            <select style="width: 50%" class="left inverter" name="id_inverter`+reference+`_`+qtd+`">`+el.closest('.flex').find('select.inverter').html()+`</select>
            <input style="width: calc(50% - 30px)" class="middle" type="text" name="inverter_power`+reference+`_`+qtd+`" autocomplete="off" placeholder="Potência">
            <div style="width: 30px" class="input right remove-inverter" title="Remover"><i class="far fa-times-circle"></i></div>
          </div><!--flex-->`;
      }
      else if(id == 'uc'){
        var append = `<div class="flex">
            <div class="w100 flex">
            <input style="width: 40%" class="left" type="text" name="uc_`+qtd+`" autocomplete="off" placeholder="UC">
            <input style="width: calc(60% - 30px)" class="middle" type="text" name="credits_`+qtd+`" autocomplete="off" placeholder="Créditos">
            <div style="width: 30px" class="input right remove-inverter" title="Remover"><i class="far fa-times-circle"></i></div>
          </div><!--flex-->`;
      }
      else{
        var append = `<div class="flex">
            <input style="width: calc(40% - 10px); margin-bottom: 5px;" class="left" type="text" name="inverter_description`+reference+`_`+qtd+`" autocomplete="off" placeholder="Modelo">
            <input style="width: calc(40% - 10px); margin-bottom: 5px;" class="middle" type="text" name="inverter_maker`+reference+`_`+qtd+`" autocomplete="off" placeholder="Fabricante">
            <input style="width: calc(20% - 10px); margin-bottom: 5px;" class="middle number-float" type="text" name="inverter_power`+reference+`_`+qtd+`" autocomplete="off" placeholder="Potência">
            <div style="width: 30px; margin-bottom: 5px;" class="input right remove-inverter" title="Remover"><i class="far fa-times-circle"></i></div>
          </div><!--flex-->`;
        mask = true;
      }

      parent.append(append);
      parent.find('.qtd-item').val(qtd);
      if(mask){ $('[name=inverter_power_2]').mask("#.##0,00", {reverse: true}); }

      return false;
    });

    $('body').on('click', '.parcel .icon .fa-times-circle', function(e){
      e.stopPropagation();

      var el = $(this);
      el.closest('.parcel').remove();

      return false;
    });

    $('body').on('click', '.flex .remove-inverter .fa-times-circle', function(e){
      e.stopPropagation();

      var el = $(this);
      el.closest('.flex').remove();

      return false;
    });
  /*------------------------------*/

  /*----------UCs----------*/
    $('body').on('change','#uc_generator', function(e){
      e.stopPropagation();

      var el = $(this);
      var selected = el.find('option:selected').val();
      var parent = el.closest('form').find('.ucs');

      if(parent.find('[name=id_participant]').val() == selected){
        parent.find('[name=uc_participant]').val('');
        parent.find('[name=uc_participant]').css('font-weight', '400');
        parent.find('[name=id_participant]').val('');
      }

      parent.find('.result-list').html('<li>Pesquise novamente...</li>');

      for(var i = 0; i < parent.find('.flex').length; i++){
        if(parent.find('.flex').eq(i).find('input').attr('id') == selected){
          parent.find('.flex').eq(i).remove();
        }
      }
    });

    $('body').on('click','.add-participant', function(e){
      e.stopPropagation();

      var el = $(this);
      var id_uc = el.closest('.search').find('[name=id_participant]').val();

      if(id_uc != '' && el.closest('.ucs').find('.uc-participants').find('input#'+id_uc).length < 1){
        var qtd = parseInt(el.closest('.search').find('[name=qtd_participants]').val());
        var uc = el.closest('.search').find('[name=uc_participant]').val();

        el.closest('.search').find('[name=qtd_participants]').val(qtd+1);

        var append = `<div class="w100 padding flex">
          <div class="remove-participant" title="Remover"><i class="far fa-times-circle"></i>  `+uc+`</div>
          <input type="hidden" name="uc_participant_`+(qtd+1)+`" value="`+id_uc+`">
        </div>`;

        el.closest('.ucs').find('.uc-participants').append(append);

        el.closest('.search').find('[name=uc_participant]').val('');
        el.closest('.search').find('[name=uc_participant]').css('font-weight', '400');
        el.closest('.search').find('[name=id_participant]').val('');
      }
    });

    $('body').on('click','.remove-participant', function(e){
      e.stopPropagation()

      var el = $(this);

      el.closest('.flex').remove();
    });

    $('body').on('change','[name=uc_generator], #uc_generator', function(e){
      e.stopPropagation();

      var el = $(this);

      var parent = el.closest('form').find('.ucs').find('.uc-participants');
      console.log('oi');
      if(el.find('option:selected').hasClass('a4')){
        el.closest('form').find('.ucs').slideUp(300);
        for(var i = 0; i < parent.find('.flex').length; i++){
          parent.find('.flex').eq(i).remove();
        }
        el.closest('form').find('#demand').fadeIn(200);
      }
      else{
        el.closest('form').find('#demand').fadeOut(200);
        el.closest('form').find('#demand').find('#contracted_demand').val("");
        el.closest('form').find('.ucs').slideDown(300);
      }
    });

    $('body').on('change','#id_class', function() {
      var el = $(this);

      var classe = el.find('option:selected').val();

      if(classe == 11 || classe == 12){
        el.closest('form').find('#a4_values').slideDown(300);
        el.closest('form').find('#reduced_tax').slideUp(300);
      }
      else{
        el.closest('form').find('#reduced_tax').slideDown(300);
        el.closest('form').find('#reduced_tax').find('#less_tax').val(0);
        el.closest('form').find('#a4_values').find('#demand_fp').val("");
        el.closest('form').find('#a4_values').find('#demand_p').val("");
        el.closest('form').find('#a4_values').find('#value_fp').val("");
        el.closest('form').find('#a4_values').find('#value_p').val("");
        el.closest('form').find('#a4_values').find('#consumption_fp').val("");
        el.closest('form').find('#a4_values').find('#consumption_p').val("");
        el.closest('form').find('#a4_values').slideUp(300);
      }
    });

    $('body').on('click','.pre_result button',function(e){
      e.stopPropagation();

      var el = $(this);
      var type = el.closest('form').attr('name').split('-')[0];
      var file = el.closest('form').attr('name').split('-')[2];
      if(type == 'edit' && file != 'budgets_variables'){
        var id = '-'+el.closest('form').find('[name=id]').val();
      }
      else{
        var id = '';
      }

      var where;

      if(el.attr('id') == 'pre-calculate-value'){
        where = 'price';
      }
      else if(el.attr('id') == 'pre-calculate-suggested-power'){
        where = 'suggested-power';
      }

      if(file == 'budgets' && type != 'info'){
        var myForm = document.getElementById(type+'-form-'+file+id);
        formData = new FormData(myForm);
        formData.append(type+'-'+file, true);
        formData.append("where", where);

        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait');},
          url:'ajax/other/calculate_budgets.php',
          processData: false,
          contentType: false,
          type: 'POST',
          dataType:'json',
          data:formData
        }).done(function(data){
          $('article.message-load').fadeOut(100); 
          $('body').css('cursor', 'initial');

          var text = '<b>Resultado: </b>';

          if(data.success && data.result != undefined){
            if(where == 'price'){
              text = text+(data.result.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
            }
            else if(where == 'suggested-power'){
              text = text+data.result.toLocaleString("pt-br",{minimumFractionDigits: 1, maximumFractionDigits: 2})+'kWp';
            }
          }
          else{
            text = text+'<i>indeterminado</i>';
          }
          el.closest('.pre_result').find('#'+where+'-result').html(text);
        });/**/
      }
    });

    $('body').on('keyup','#gen_change',function(e){
      e.stopPropagation();

      var el = $(this);
      var total = 0;

      for(var i = 1; i <= 12; i++){
        if($('[name=generation_'+i+']').val() != ''){
          total += parseFloat($('[name=generation_'+i+']').val());
        }
      }

      total = total*100;
      var percent = total.toFixed(2);

      var p = document.getElementById('percentTotal');
      p.innerHTML = 'Total: '+percent+'%';
    });

    $('body').on('click','.table.select-row table tbody tr',function(e){
      e.stopPropagation();
      var el = $(this);
      var id = el.attr('id');
      var tableID = el.closest('table').attr('id');

      el.toggleClass('selected-row');

      var tables = el.closest('.table');
      for(var j = 0; j < tables.find('table').length; j++){
        if(tables.find('table').eq(j).attr('id') != tableID){
          if(tables.find('table').eq(j).find('tbody tr#'+id).length > 0){
            tables.find('table').eq(j).find('tbody tr#'+id).toggleClass('selected-row');
          }
        }
      }
    });
    
    $('body').on('mouseover', '.table.over.select-row table tbody tr', function(){
      var el = $(this);
      var id = el.attr('id');
      var tableID = el.closest('table').attr('id');
      var tables = el.closest('.table');

      for(var j = 0; j < tables.find('table').length; j++){
        if(tables.find('table').eq(j).attr('id') != tableID){
          if(tables.find('table').eq(j).find('tbody tr#'+id).length > 0){
            tables.find('table').eq(j).find('tbody tr#'+id).css('background-color', '#80d982');
          }
        }
      }
    });

    $('body').on('mouseout', '.table.over.select-row table tbody tr', function(){
      var el = $(this);
      var id = el.attr('id');
      var tableID = el.closest('table').attr('id');
      var tables = el.closest('.table');

      for(var j = 0; j < tables.find('table').length; j++){
        if(tables.find('table').eq(j).attr('id') != tableID){
          if(tables.find('table').eq(j).find('tbody tr#'+id).length > 0){
            if(id.split("_")[1] % 2 == 0){
              tables.find('table').eq(j).find('tbody tr#'+id).css('background-color', '#ACD1FF');
            }
            else{
              tables.find('table').eq(j).find('tbody tr#'+id).css('background-color', '#fff');
            }
          }
        }
      }
    });

    function applyResizableAgain(id){
      if($('table.resizable#'+id).length > 0){
        $('table.resizable#'+id).colResizable({
          resizeMode: 'fit',
          minWidth: 120,
          partialRefresh: true,
          headerOnly: false,
          liveDrag: true,
        });
      }
    }

    $('body').on('dblclick', 'table.resizable thead th', function(e){
      e.stopPropagation();
      var el = $(this);

      el.closest('thead').toggleClass('res-allowed');

      if(el.closest('thead').hasClass('res-allowed')){
        el.animate({
          "width": "+=10px",
        },500).animate({
          "width": "-=20px",
        },500).animate({
          "width": "+=10px",
        },500);
        applyResizableAgain(el.closest('.resizable').attr('id'));
      }
      else{
        el.closest('table.resizable').colResizable({disable:true});
        el.closest('thead').animate({
          "height": "+=10px",
        },500).animate({
          "height": "-=30px",
        },500).animate({
          "height": "60px",
        },500);
        applyResizableAgain(el.closest('.resizable').attr('id'));
      }
    });

    $('body').on('mouseup', '.JCLRgrip', function(e){
      var el = $(this);

      var index = el.index();
      var width = el.closest('.table').find('.resizable thead').find('th').eq(index).css('width');
      var width2 = el.closest('.table').find('.resizable thead').find('th').eq(index+1).css('width');
      
      for(var j = 0; j < el.closest('.table').find('.resizable tbody tr').length; j++){
        el.closest('.table').find('.resizable tbody tr').eq(j).find('td').eq(index).css('width', width);
        el.closest('.table').find('.resizable tbody tr').eq(j).find('td').eq(index+1).css('width', width2);
      }
    });
  /*------------------------------*/


  /*----------FOLDER----------*/
    toggleAll(0);
    function toggleAll(time){
      for(var i = 0; i < $('section.outside.folder .inside .flex').length; i++){
        if(!$('section.outside.folder .inside .flex').eq(i).closest('.outside').hasClass('home')){
          $('section.outside.folder .inside .flex').eq(i).slideToggle(time);
        }
      }
    }

    function toggleThis(el){
      if(el.closest('.outside').hasClass('left')){
        var id = el.closest('.outside').attr('id');

        if(el.closest('main').find('section.outside.right#'+id+' h4 .edit-files').length > 0 && el.closest('main').find('section.outside.right#'+id+' h4 .edit-files').hasClass('actived')){
          return;
        }

        el.closest('main').find('section.outside.right#'+id+' .flex').slideToggle(300);
        var i = el.closest('main').find('section.outside.right#'+id+' h4 i.fa-chevron-down');
        if(i.hasClass('rotate')){
          i.css('transform', 'rotate(0)');
          i.removeClass('rotate');
        }
        else{
          i.css('transform', 'rotate(-180deg)');
          i.addClass('rotate');
        }
      }
      else if(el.closest('.outside').hasClass('right')){
        var id = el.closest('.outside').attr('id');

        if(el.closest('main').find('section.outside.left#'+id+' h4 .edit-files').length > 0 && el.closest('main').find('section.outside.left#'+id+' h4 .edit-files').hasClass('actived')){
          return;
        }

        el.closest('main').find('section.outside.left#'+id+' .flex').slideToggle(300);

        var i = el.closest('main').find('section.outside.left#'+id+' h4 i.fa-chevron-down');
        if(i.hasClass('rotate')){
          i.css('transform', 'rotate(0)');
          i.removeClass('rotate');
        }
        else{
          i.css('transform', 'rotate(-180deg)');
          i.addClass('rotate');
        }
      }

      el.closest('.inside').find('.flex').slideToggle(300);
      var i = el.find('i.fa-chevron-down');
      if(i.hasClass('rotate')){
        i.css('transform', 'rotate(0)');
        i.removeClass('rotate');
      }
      else{
        i.css('transform', 'rotate(-180deg)');
        i.addClass('rotate');
      }
    }

    $('body').on('click', '.inside h4', function(e){
      e.stopPropagation();
      var el = $(this);

      if(el.find('.edit-files').length == 0 || !el.find('.edit-files').hasClass('actived')){
        toggleThis(el);
      }
    });

    $('body').on('click', '.inside h4 .edit-files', function(e){
      e.stopPropagation();
      var el = $(this);

      if(!el.closest('.inside').find('.flex').is(':visible')){
        toggleThis(el.closest('h4'));
      }

      if(el.hasClass('actived')){
        el.removeClass('actived');
        el = el.closest('.inside').find('.flex');

        for(var i = 0; i < el.find('.container').length; i++) {
          el.find('.container').eq(i).find('a.layer').removeClass('visible');
          el.find('.container').eq(i).find('label.layer').removeClass('visible');
          el.find('.container').eq(i).find('span.delete').removeClass('visible');

          if(el.find('.container').eq(i).hasClass('hidden')){
            el.find('.container').eq(i).css('display', 'none');
          }
        }
      }
      else{
        el.addClass('actived');
        el = el.closest('.inside').find('.flex');

        for(var i = 0; i < el.find('.container').length; i++) {
          el.find('.container').eq(i).find('a.layer').addClass('visible');
          el.find('.container').eq(i).find('label.layer').addClass('visible');
          el.find('.container').eq(i).find('span.delete').addClass('visible');

          if(el.find('.container').eq(i).hasClass('hidden')){
            el.find('.container').eq(i).css('display', 'block');
          }
        }
      }
    });

    $('body').on('click', '.file-inside span.delete', function(e){
      e.stopPropagation();
      var el = $(this);
      var r = confirm('Deseja mesmo remover este arquivo? Ele será removido após a confirmação.');

      if(r){
        el.closest('.file-outside').addClass('removed');

        var post = { id: el.closest('.file-inside').attr('id'),
          file: el.closest('.file-outside').attr('title'),
          path: el.closest('.container').attr('path') }

        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); },
          url:'ajax/other/remove_file.php',
          type: 'POST',
          dataType:'json',
          data:post
        }).done(function(data){
          $('article.message-load').fadeOut(100); 
          if(data.success){
            el.closest('.container').remove();
          }
          else if(data.reload){
            location.reload();
          }
          else{
            el.find('p.error').html(data.message);
          }
        });
      }
    });
  /*------------------------------*/


  /*----------FORMAT VALUES----------*/
    $('body').on('keyup', '[name=new-form-financial] [name=value], [name=edit-form-financial] [name=value], [name=new-form-financial] [name=discount], [name=edit-form-financial] [name=discount], [name=new-form-financial] [name=fine], [name=edit-form-financial] [name=fine]', function(e){
      e.stopPropagation();
      var el = $(this);

      if(el.closest('form').find('[name=value]').length > 0){
        var value = el.closest('form').find('[name=value]').val();
        value = value.replace('.', '');
        value = value.replace(',', '.');
        value = parseFloat(value);
      }
      else{
        value = 0;
      }

      if(el.closest('form').find('[name=discount]').length > 0){
        var discount = el.closest('form').find('[name=discount]').val();
        discount = discount.replace('.', '');
        discount = discount.replace(',', '.');
        discount = parseFloat(discount);
      }
      else{
        discount = 0;
      }


      if(el.closest('form').find('[name=fine]').length > 0){
        var fine = el.closest('form').find('[name=fine]').val();
        fine = fine.replace('.', '');
        fine = fine.replace(',', '.');
        fine = parseFloat(fine);
      }
      else{
        fine = 0;
      }

      total = value - discount + fine;

      if(total < 0){
        total = 0;
      }

      el.closest('form').find('[name=value_payment]').val(total.toLocaleString('pt-BR', {useGrouping: true, minimumFractionDigits: 2}));
    });
  /*------------------------------*/


  /*----------VERIFY FILES----------*/
    $('body').on('click', '.search ul.result-list li', function(e){
      e.stopPropagation();

      var el = $(this);

      verifyFiLes(el.closest('form'));
    });

    $('body').on('change', '[name=folder]', function(e){
      e.stopPropagation();

      var el = $(this);

      verifyFiLes(el.closest('form'));
    });


    function verifyFiLes(el){
      if(el.find('[name=folder]').length > 0){
        setTimeout(function(){
          var post = { id_sale: el.find('[name=id_sale]').val(),
            folder: el.find('[name=folder] option:selected').val() };

          if(post.folder == '000'){
            el.find('.is-ticket ul.file-list.other-files').html('');
            el.find('.is-receipt ul.file-list.other-files').html('');
            return;
          }

          if(post.id_sale != 0){
            $.ajax({
              beforeSend:function(){ $('article.message-load').fadeIn(100); },
              url:'ajax/search/files.php',
              type: 'POST',
              dataType:'json',
              data:post
            }).done(function(data){
              $('article.message-load').fadeOut(100); 
              if(data.success){
                el.find('.is-ticket ul.file-list.other-files').html(data.ticket);
                el.find('.is-receipt ul.file-list.other-files').html(data.receipt);

                var li = el.find('.is-ticket ul.file-list').eq(0).find('li.copy');
                if(li.length > 0){
                  for(var i = 0; i < li.length; i++){
                    li.eq(i).remove();
                  }
                }
                li = el.find('.is-ticket ul.file-list').eq(0).find('li');
                if(li.length == 2){
                  el.find('.is-ticket ul.file-list').eq(0).find('li.alone').css('display', 'block');
                }

                li = el.find('.is-receipt ul.file-list').eq(0).find('li.copy');
                if(li.length > 0){
                  for(var i = 0; i < li.length; i++){
                    li.eq(i).remove();
                  }
                }
                li = el.find('.is-receipt ul.file-list').eq(0).find('li');
                if(li.length == 2){
                  el.find('.is-receipt ul.file-list').eq(0).find('li.alone').css('display', 'block');
                }
              }
              else if(data.reload){
                location.reload();
              }
              else{
                console.log('Error');
              }
            });
          }
          else{
            el.find('.is-ticket ul.file-list.other-files').html('');
            el.find('.is-receipt ul.file-list.other-files').html('');

            var li = el.find('.is-ticket ul.file-list').eq(0).find('li.copy');
            if(li.length > 0){
              for(var i = 0; i < li.length; i++){
                li.eq(i).remove();
              }
            }
            li = el.find('.is-ticket ul.file-list').eq(0).find('li');
            if(li.length == 2){
              el.find('.is-ticket ul.file-list').eq(0).find('li.alone').css('display', 'block');
            }

            li = el.find('.is-receipt ul.file-list').eq(0).find('li.copy');
            if(li.length > 0){
              for(var i = 0; i < li.length; i++){
                li.eq(i).remove();
              }
            }
            li = el.find('.is-receipt ul.file-list').eq(0).find('li');
            if(li.length == 2){
              el.find('.is-receipt ul.file-list').eq(0).find('li.alone').css('display', 'block');
            }
          }
        }, 100);
      }
    }
  /*------------------------------*/ 
});