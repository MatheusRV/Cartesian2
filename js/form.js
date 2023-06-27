$(function(){
  /*----------OPEN FORM----------*/
    $('body').on('click', 'button.open-form', function(e){
      e.stopPropagation();
      
      var el = $(this);
      var id = el.attr('id');

      if(el.parent().hasClass('center')){
        if($('aside.edit-'+id).length > 0){
          $('aside.info-'+id).fadeOut(300, function(){
            $('aside.info-'+id).remove();
          });

          $('aside.edit-'+id).fadeIn(300);
        }
        else{
          var post = {id: id};
          var file = 'edit/'+el.attr('type_form');

          $('aside.info-'+id).fadeOut(300, function(){
            $('aside.info-'+id).remove();
          });

          getForm(post, file, el);
        }
      }
      else if(el.hasClass('navigate')){
        var post = {id: id, deleted: 1};
        var form = el.attr('form');
        
        el.closest('aside').fadeOut(300, function(){
          el.closest('aside').remove();
        });

        if(el.closest('section.form').find('aside.info-'+id).length > 0){
          $('aside.info-'+id).fadeIn(300);
        }
        else{
          getForm(post, 'info/'+form, el);
        }
      }
      else if(el.hasClass('to-edit')){
        if($('aside.'+id).length > 0){
          $('aside.'+id).fadeIn(300);
        }
        else if($('.edit-'+id).length > 0){
          $('aside.edit-'+id).fadeIn(300);
        }
        else{
          getForm({}, 'edit/'+id, el);
        }
      }
      else{
        if($('aside.'+id).length > 0){
          $('aside.'+id).fadeIn(300);
        }
        else if($('.new-'+id).length > 0){
          $('aside.new-'+id).fadeIn(300);
        }
        else{

          if($('[name=filter_description]').val() != undefined && $('[name=filter_category] option:selected').val() != undefined){
            var post = { description: $('[name=filter_description]').val(),
              id_category: $('[name=filter_category] option:selected').val() }; 
          }
          else{ var post = {}; }

          var file = 'new/'+id;

          getForm(post, file, el);
        }
      }

      $('nav').hide("slide", { direction: "left" });

      return false;
    });

    $('body').on('click', 'i.open-edit', function(e){
      e.stopPropagation();
      
      var el = $(this);
      var post = { id: el.closest('.header').attr('id') }
      var file = 'edit/note';

      if($('aside.edit-'+post.id).length > 0){
        $('aside.edit-'+post.id).fadeIn(300);
      }
      else{
        getForm(post, file, el);
      }
    });

    $('body').on('click', 'tbody tr', function(e){
      e.stopPropagation();

      var el = $(this);
      if(!el.hasClass('no-click')){
        var id = el.attr('id');
        
        if($('aside.'+id).length > 0){
          $('aside.'+id).fadeIn(300);
        }
        else if($('aside.info-'+id).length > 0){
          for(var i = 0; i < $('aside.info-'+id).length; i++){
            if($('aside.info-'+id).eq(i).hasClass('info2') == false){
              $('aside.info-'+id).eq(i).fadeIn(300);
            }
          }
        }
        else{
          if(el.hasClass('notification')){
            var post = { id: id };
          }
          else if(el.closest('table').attr('page') == 'deleted'){
            var post = { id: id, deleted: 1};
          }
          else{
            var post = { id: id };
          }
          var file = 'info/'+el.closest('tbody').attr('class');
          getForm(post, file, el);
        }

        $('nav').hide("slide", { direction: "left" });
      }
    });

    $('body').on('click', 'tbody tr td.delete', function(e){ e.stopPropagation(); });
  
    $('body').on('click', 'button.forward-ticket', function(e){
      e.stopPropagation();
      var el = $(this);

      var post = { forward: 1,
        parcel: el.attr('parcel'),
        folder: el.attr('path'),
        id: el.attr('id') }

      getForm(post, 'new/financial', el);
    });

    $('body').on('click', 'button.forward-budget', function(e){
      e.stopPropagation();
      var el = $(this);

      var post = { forward: 1,
        id_client: el.attr('id_client'),
        id: el.attr('id') }

      getForm(post, 'new/sale', el);
    });

    $('body').on('click', 'button.inline', function(e){
      e.stopPropagation();
      var el = $(this);
      var id = el.attr('id');

      var type = el.attr('type_form');
      var form = el.attr('form');

      if(type == 'info'){
        if(form == 'budgets_ucParticipant'){
          var post = { id: id, part: el.attr('part')}
        }
        else{
          var post = { id: id }
        }

        if($('aside.info-'+id+'-'+form).length > 0){
          $('aside.info-'+id+'-'+form).fadeIn(300);
        }
        else if(post != undefined){
          getForm(post, type+'/'+form, el);
        }
        else{ console.log("Error!"); }
      }

    });

    function getForm(post, file, el){
      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait');},
        url: 'ajax/form/'+file+'.php',
        type: 'POST',
        dataType: 'json',
        data: post
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        $('body').css('cursor', 'initial');

        if(data.success){
          $('section.form').append(data.result);

          $('.'+data.form).fadeIn(300);

          masks();

          if($('.text-edit').length > 0){
            tinymce.init({
              selector: '.text-edit',
              plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
              toolbar_mode: 'floating',
            });
          }
        }
        else if(data.reload){
          location.reload();
        }
        else{
          console.log('Error');
        }
      });/**/
    }
  /*------------------------------*/


  /*----------CLOSE FORM----------*/
    $('body').on('click', 'button.close-form, button.cancel-form', function(e){
      e.stopPropagation();
      
      var el = $(this);

      el.closest('aside').fadeOut(300);

      if(el.closest("aside").hasClass('remove-this')){
        el.closest("aside").fadeOut(300, function(){
          el.closest("aside").remove();
        })
      }

      return false;
    });

    $('body').on('mousedown', 'aside.container', function(e){
      e.stopPropagation();
      var el = $(this);

      if( (el.outerWidth()-6) > event.pageX ){
        if(el.hasClass('remove-this')){
          el.fadeOut(300, function(){
            el.remove();
          })
        }
        else{ el.fadeOut(300); }
      }
    });

    $('body').on('keyup', function(e){
      e.stopPropagation();

      var code = (e.keyCode ? e.keyCode : e.which);
      if(code == 27){
        if($('aside:visible').length > 0){
          for(var i = 0; i < $('aside:visible').length; i++){
            $('aside:visible').eq(i).fadeOut(300);
          }
        }
      }
    });

    $('body').on('mousedown', 'aside.container form, aside.container .outline', function(e){ e.stopPropagation(); });

    $('body').on('mousedown', 'aside.container .box, aside.container .outline', function(e){ e.stopPropagation(); });
  /*------------------------------*/


  /*----------REQUIRE PAGE----------*/
    $('body').on('click', 'button.require-page', function(e){
      e.stopPropagation();
      var el = $(this);

      requirePage(el.attr('id'), {});

      return false;
    });

    function requirePage(file, post){
      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
        url:'ajax/require/'+file+'.php',
        type: 'POST',
        dataType:'json',
        data:post
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        $('body').css('cursor', 'initial');

        if(data.success){
          $('section.outside .inside.require-result').html(data.result);

          $('section.outside .inside.require-result').fadeIn(300);

          get_table(0);
        }
        else if(data.reload){
          location.reload();
        }
        else{
          console.log('Error');
        }
      });/**/
    }
  /*------------------------------*/


  /*----------SLIDE INFO----------*/
    $('body').on('click', 'h3.slide-on', function(e){
      var el = $(this);

      if(el.parent().find('.slide').css('height') == '0px'){
        el.parent().find('.slide').css('height', 'auto');
        var height = el.parent().find('.slide').css('height');
        el.parent().find('.slide').css('height', '0px');

        el.parent().find('.slide').css('opacity', '1');
        el.parent().find('.slide').animate({ height: height }, 300);
        el.find('i').css('transform', 'rotate(-180deg)');
      }
      else{
        el.parent().find('.slide').animate({ height: 0 }, 300);
        el.find('i').css('transform', 'rotate(0deg)');
        setTimeout(function(){
          el.parent().find('.slide').css('opacity', '0');
        }, 300);
      }
    });
  /*------------------------------*/


  /*----------MESSAGE----------*/
    $('article.message-success').fadeToggle(300);

    setTimeout(function(){
      $('article.message-success').fadeToggle(300);
    }, 2000);
  /*------------------------------*/

    var windowReport;

  /*----------SUBMIT----------*/
    $('body').on('submit', 'form', function(e){
      e.stopPropagation();
      var el = $(this);

      var type = el.find('[type=submit]').attr('name').split('-')[0];
      var file = el.find('[type=submit]').attr('name').split('-')[1];

      if(file == 'filter_cash_flow'){
        
        $('tr.filter [name=filter_description]').val($('[name=new-form-filter_cash_flow] [name=name_description]').val());

        var qtd_checked = $('[name=new-form-filter_cash_flow] [name=category]:checked').length;
        var category_filter = $('[name=filter_category]');
        var header_category_filter = $('[name=header_filter_category]');
        var value_category = $('[name=new-form-filter_cash_flow] [name=category]:checked').val();

        for(var i = 0; i < $('[name=filter_category] option').length; i++){
          $('[name=filter_category] option').eq(i).removeAttr('selected');
        }

        if(qtd_checked == 1){
          header_category_filter.removeAttr('disabled');
          header_category_filter.css('color', '#ffffff');
          $('[name=filter_category] option[value="'+value_category+'"]').attr('selected',true);
          category_filter.removeAttr('disabled');
          category_filter.css('opacity', 1.0);
          category_filter.css('cursor', 'default');
          category_filter.attr('placeholder', "");
        }
        else if(qtd_checked > 0){
          $('[name=filter_category] option[value=0]').attr('selected',true);
          header_category_filter.attr('disabled',true);
          header_category_filter.css('color', '#fafafa');
          category_filter.attr('disabled',true);
          category_filter.css('opacity', 0.8);
          category_filter.css('cursor', 'not-allowed');
          category_filter.attr('placeholder', "Várias Categorias...");
        }
        else{
          header_category_filter.removeAttr('disabled');
          $('[name=filter_category] option[value=0]').attr('selected',true);
          header_category_filter.css('color', '#ffffff');
          category_filter.attr('disabled',false);
          category_filter.css('opacity', 1.0);
          category_filter.css('cursor', 'default');
          category_filter.attr('placeholder', "");
        }/**/
        get_table(0);
        return false;
      }

      if(type == 'edit' && file != 'budgets_variables'){
        var id = '-'+el.find('[name=id]').val();
      }
      else{
        var id = '';
      }

      var myForm = document.getElementById(type+'-form-'+file+id);
      formData = new FormData(myForm);
      formData.append(type+'-'+file, true);
      if(el.find('[type=submit]').hasClass('forward')){
        formData.set('forward', 1);
      }

      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
        url:'ajax/submit/'+file+'.php',
        processData: false,
        contentType: false,
        type: 'POST',
        dataType:'json',
        data:formData
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        $('body').css('cursor', 'initial');

        if(data.success){
          if(data.reload){
            location.reload();
          }

          if(file == 'budgets' && data.generate_report){
            $.ajax({
              beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
              url:'ajax/other/budget_generate_report.php',
              type: 'POST',
              dataType:'json',
              data: {id: el.find('[name=id]').val()}
            }).done(function(data){
              $('article.message-load').fadeOut(100); 
              $('body').css('cursor', 'initial');

              if(data.success){
                if(data.display != ''){
                  if(windowReport != undefined){
                    if(windowReport.location.href == data.display && !windowReport.closed){
                      console.log(windowReport);
                      windowReport.location.reload();
                      windowReport.blur();
                      windowReport.focus();
                    }
                    else{
                      windowReport = window.open(data.display, '_blank');
                      windowReport.focus();
                    }
                  }
                  else{
                    windowReport = window.open(data.display, '_blank');
                    windowReport.focus();
                  }
                }
              }

            });/**/
          }

          get_table(0);

          el.closest('aside').fadeOut(300, function(){
            el.closest('aside').remove();
          });        
        }
        else if(data.reload){
          location.reload();
        }
        else{
          el.find('p.error').html(data.message);
        }
      });/**/


      return false;
    });

    $('body').on('change', 'input[name=file-folder]', function(e){
      e.stopPropagation();
      var el = $(this);
      var id = el.attr('id');

      var myForm = document.getElementById('new-'+id);
      formData = new FormData(myForm);

      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
        url:'ajax/other/add_file.php',
        processData: false,
        contentType: false,
        type: 'POST',
        dataType:'json',
        data:formData
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        $('body').css('cursor', 'initial');

        if(data.success){
          if(data.reload){
            location.reload();
          }

          el.closest('.flex').prepend(data.result);

          for(var i = 0; i < el.closest('.flex').find('.container').length; i++) {
            el.closest('.flex').find('.container').eq(i).find('a.layer').addClass('visible');
            el.closest('.flex').find('.container').eq(i).find('label.layer').addClass('visible');
            el.closest('.flex').find('.container').eq(i).find('span.delete').addClass('visible');
          }

          el.closest('form').prepend('<input type="file" name="file-folder" id="'+id+'">');
          el.remove();
        }
        else if(data.reload){
          location.reload();
        }
        else{
          el.find('p.error').html(data.message);
        }
      });/**/
    });
  /*------------------------------*/


  /*----------TABLE----------*/
    if($('table').length > 0 || $('.notes-content').length > 0){
      get_table(0);
    }
    
    var timer;

    function data_table(){
      clearTimeout(timer);
      timer = setTimeout(function(){ get_table(0) }, 500);
    }

    var back_date = 0;
    var next_date = 0;

    function get_table(i){
      if($('table').length > 0){
        var file = $('table').eq(i).attr('class');
        if($('table').eq(i).attr('page') != undefined){
          if(file == 'cash_flow' || file == 'financial_dashboard'){
            var post = { pg: $('table').eq(i).attr('page'),
              filter_description: $('[name=filter_description]').val(),
              first_date: $('[name=first_date]').val(),
              last_date: $('[name=last_date]').val(),
              filter_category: $('[name=filter_category] option:selected').val(),
              filter_bank: $('[name=filter_bank] option:selected').val(),
              back_date: back_date,
              next_date: next_date
            };

            back_date = 0;
            next_date = 0;

            if($('[name=new-form-filter_cash_flow] [name=id_type_payment]').val() != undefined){ post.filter_id_type_payment = $('[name=new-form-filter_cash_flow] [name=id_type_payment]').val(); }

            if($('[name=new-form-filter_cash_flow] [name=category]').length > 0){
              var el2 = $('[name=new-form-filter_cash_flow] [name=category]');

              post.filter_category = '';
              for(var j = 0; j < el2.length; j++){
                if(el2.eq(j).is(':checked')){
                  post.filter_category += post.filter_category == '' ? el2.eq(j).val() : ' '+el2.eq(j).val();
                }
              }
            }/**/
          }
          else if(file == 'contacts_ucs'){
            var post = { pg: $('table').eq(i).attr('page'),
              filter_client: $('[name=filter_client]').val(),
              filter_uc_number: parseInt($('[name=filter_uc_number]').val()),
              filter_uc_class: $('[name=filter_uc_class] option:selected').val(),
            }
          }
          else if(file == 'budgets'){
            var post = { pg: $('table').eq(i).attr('page'),
              filter_client: $('[name=filter_client]').val(),
              filter_proposal: parseInt($('[name=filter_proposal]').val()),     
              first_date: $('[name=first_date]').val(),
              last_date: $('[name=last_date]').val(),
              back_date: back_date,
              next_date: next_date
            }

            back_date = 0;
            next_date = 0;     
          }
          else{
            var post = { pg: $('table').eq(i).attr('page') };
            if(file == 'contacts'){
              post.attr = $('table').eq(i).attr('type_r');
            }
          }
        }
      }
      else{
        var file = 'notes';
        var post = { pg: $('.notes-content').attr('page') };
      }

      if(file != undefined && file != ''){
        $.ajax({
          beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
          url:'ajax/table/'+file+'.php',
          type: 'POST',
          dataType:'json',
          data:post
        }).done(function(data){
          $('article.message-load').fadeOut(100); 
          $('body').css('cursor', 'initial');

          if(data.success){
            if($('table').length > 0){
              $('table tbody').eq(i).html(data.result);

              if(data.first_date != undefined){
                $('[name=first_date]').val(data.first_date);
              }
              if(data.last_date != undefined){
                $('[name=last_date]').val(data.last_date);
              }

              i++;
            }
            else{
              $('.notes-content').html(data.result);
            }

            if(data.chart_PayAndReceive != undefined && data.chart_PaidAndReceived != undefined){
              var chartPayAndReceive = Object.values(data.chart_PayAndReceive);
              var chartPaidAndReceived = Object.values(data.chart_PaidAndReceived);

              getChart(chartPayAndReceive[0], chartPayAndReceive[1], chartPaidAndReceived[0], chartPaidAndReceived[1]);
            }

            if($('table').length > i){
              get_table(i);
            }
          }
          else if(data.reload){
            location.reload();
          }
          else{
            $('table tbody').eq(i).html('<tr class="no-click"><td class="w100"><i>Nenhum registro encontrado.</i></td></tr>');
            if(data.first_date != undefined){
              $('[name=first_date]').val(data.first_date);
            }
            if(data.last_date != undefined){
              $('[name=last_date]').val(data.last_date);
            }
            
            i++;
            if($('table').length > i){
              setTimeout(function(){ get_table(i) }, 300);
            }
          }
        });/**/
      }
      else{
        i++;
        if($('table').length > i){
          setTimeout(function(){ get_table(i) }, 300);
        }
      }
    }

    function getChart(value_toPay, value_toReceive, value_Paid, value_Received){

      if($('canvas#toPayAndReceive').length > 0 && $('canvas#paidAndReceived').length > 0){

        const dataPayAndReceive = {
          labels: ['A Pagar', 'A Receber'],
          datasets: [
            {
              label: ['A Pagar','A Receber'],
              data: [value_toPay.toFixed(2),value_toReceive.toFixed(2)],
              backgroundColor: ['#ff6e6e','#36bf36'],
            }
          ]
        };

        const configPayAndReceive = {
          type: 'doughnut',
          data: dataPayAndReceive,
          options: {
            animation:{
              animateScale: true,
            },
            labels:{
              render: 'value',
              showZero: true,
              precision: 2
            }
          }
        };

        const dataPaidAndReceived = {
          labels: ['Pagos', 'Recebidos'],
          datasets: [
            {
              label: ['Pagos','Recebidos'],
              data: [value_Paid.toFixed(2),value_Received.toFixed(2)],
              backgroundColor: ['red','green'],
            }
          ]
        };

        const configPaidAndReceived = {
          type: 'doughnut',
          data: dataPaidAndReceived,
          options: {
            animation:{
              animateScale: true,
            },
            labels:{
              render: 'value',
              showZero: true,
              precision: 2
            }
          },
        };

        const ctxPayAndReceive = document.getElementById('toPayAndReceive').getContext('2d');
        const ctxPaidAndReceived = document.getElementById('paidAndReceived').getContext('2d');

        const chartPayAndReceive = new Chart(ctxPayAndReceive, configPayAndReceive);
        const chartPaidAndReceived = new Chart(ctxPaidAndReceived, configPaidAndReceived);
        
      }
    }
  /*------------------------------*/

  /*------------BUDGETS-----------*/
    $('body').on('keyup', '[name=filter_client], [name=filter_uc_number], [name=filter_proposal]', function(e){
      e.stopPropagation();
      var el = $(this);

      data_table();
    });

    $('body').on('change', '[name=filter_uc_class]', function(e){
      e.stopPropagation();
      var el = $(this);

      data_table();
    });

    $('body').on('keyup', '#painel_direction', function(e){
      e.stopPropagation();
      var el = $(this);

      //change here later
    });

  /*------------------------------*/

  /*----------FINANCIAL----------*/
    $('body').on('keyup', '[name=filter_description]', function(e){
      e.stopPropagation();
      var el = $(this);

      var file = $('[name=new-form-filter_cash_flow]');
      if(file != undefined){
        file.find('[name=name_description]').val(el.val());
      }
      data_table();
    });

    $('body').on('change', '[name=filter_category]', function(e){
      e.stopPropagation();
      var el = $(this);

      var file = $('[name=new-form-filter_cash_flow]');
      if(file != undefined){
        for(var i = 0; i < file.find('[name=category]').length; i++){
          file.find('[name=category]').eq(i).removeAttr('checked');
        }
        file.find('[name=category][value='+el.find('option:selected').val()+']').attr('checked', true);
      }
      data_table();
    });

    $('body').on('change', '[name=first_date], [name=last_date]', function(e){ data_table() });
    $('body').on('change', '[name=filter_bank]', function(e){
      e.stopPropagation();
      var el = $(this);

      if(el.find('option:selected').val() == 0){
        el.css('color', '#757575');
        if(el.attr('name') == 'filter_category'){
          el.find('option').eq(0).html('Selecionar categoria');
        }
        else{
          el.find('option').eq(0).html('Selecionar banco');
        }
      }
      else{
        el.css('color', 'black');
        el.find('option').eq(0).html('Todos');
      }
      

      data_table();
    });

    $('body').on('click', 'button.back-date', function(e){ back_date = 1; data_table(); });
    $('body').on('click', 'button.next-date', function(e){ next_date = 1; data_table(); });
  /*------------------------------*/


  /*----------MAKS----------*/
    masks();
    function masks(){
      $('input.cpf').mask("000.000.000-00");
      $('input.cnpj').mask("00.000.000/0000-00");
      $('[name=fone]').mask("(00) 0 0000-0000");
      $('[name=fone2]').mask("(00) 0000-0000");
      $('.money').mask("#.##0,00", {reverse: true});
      $('.number-float').mask("#.##0,00", {reverse: true});
      $('.number-int').mask("#.##0", {reverse: true});
      $('.degrees').mask("99#°", {reverse: true});
    }
  /*------------------------------*/


  /*----------FILE----------*/
    $('body').on('click', 'button.remove-item', function(e){
      e.stopPropagation();

      var el = $(this);

      if(el.hasClass('confirm')){
        var r = confirm('Tem certeza que deseja remover este item?');

        if(r){
          if(el.closest('ul.file-list').find('li').length == 3){
            el.closest('ul.file-list').find('li.alone').css('display', 'flex');
          }

          el.closest('li').remove();
        }
      }
      else{
        if(el.closest('ul.file-list').find('li').length == 3){
          el.closest('ul.file-list').find('li.alone').css('display', 'flex');
        }

        el.closest('li').remove();
      }

      return false;
    });

    $('body').on('click', 'button.transfer-item', function(e){
      e.stopPropagation();
      var el = $(this);

      var qtd = parseInt(el.closest('.file-content').find('.qtd-file-copy').val());
      qtd++;
      var reference = el.closest('.file-content').find('label.button').attr('reference');

      var input = '<input type="hidden" name="'+reference+'_copy_'+qtd+'" value="'+el.attr('file')+'">';
      var span = '<span>'+el.closest('li').find('a').html()+'</span>';

      var html = `<li class="copy">`+input+`<button class="remove-item"><i class="far fa-times-circle"></i></button>`+span+`<span class="line"></span></li>`;

      el.closest('.file-content').find('ul.file-list').eq(0).append(html);
      el.closest('.file-content').find('ul.file-list li.alone').css('display', 'none');
      el.closest('.file-content').find('.qtd-file-copy').val(qtd);

      return false; 
    });

    function verifyName(el, name){
      var li = el.closest('ul.file-list').find('li');

      for(var i = 0; i < li.length; i++){
        if(li.eq(i).find('span').html() == name){
          return false;
        }
      }

      return true;
    }

    $('body').on('change', '[type=file]', function(e){
      e.stopPropagation();

      var el = $(this);
      var qtd = parseInt(el.closest('.file-content').find('.qtd-file').val());
      var name_file = el.val().split('\\')[el.val().split('\\').length-1];
      var type_file = name_file.split('.')[name_file.split('.').length-1];
      var icon_file = '';

      if(type_file == 'png' || type_file == 'jpg' || type_file == 'jpeg'){
        icon_file = '<i class="far fa-file-image"></i> ';
      }
      else if(type_file == 'pdf'){
        icon_file = '<i class="far fa-file-pdf"></i> ';
      }
      else if(type_file == 'doc' || type_file == 'docx' || type_file == 'odt' || type_file == 'txt'){
        icon_file = '<i class="far fa-file-word"></i> ';
      }
      else if(type_file == 'csv' || type_file == 'ods' || type_file == 'xlsx' || type_file == 'xls' || type_file == 'xml'){
        icon_file = '<i class="far fa-file-excel"></i> ';
      }
      else if(type_file == 'zip' || type_file == 'rar' || type_file == 'tar' || type_file == 'z'){
        icon_file = '<i class="far fa-file-archive"></i> ';
      }
      
      if(icon_file == ''){
        name_file = '- '+name_file;
      }
      else{
        var first = true;
        var name = name_file;
        name_file = icon_file;
        for(var i = 0; i < (name.split('.').length-1); i++){
          if(first){
            name_file += name.split('.')[i];
            first = false;
          }
          else{
            name_file += '.'+name.split('.')[i];
          }
        }
      }

      el.closest('li').removeClass('hidden');

      var j = 1;
      var name = name_file;
      while(!verifyName(el, name)){
        name = name_file+' ('+j+')';
        j++;
      }
      name_file = name;

      var append = `<button class="remove-item"><i class="far fa-times-circle"></i></button>
        <span>`+name_file+`</span>
        <span class="line"></span>`;

      el.closest('li').append(append);

      var reference = el.closest('.file-content').find('label.button').attr('reference');
      var id = el.closest('.file-content').find('label.button').attr('id');
      qtd++;

      append = `<li class="hidden">
          <input type="file" name="`+reference+`_`+qtd+`" id="`+reference+id+`_`+qtd+`">
        </li>`;

      el.closest('ul.file-list').append(append);
      el.closest('.file-content').find('label.button').attr('for', reference+id+`_`+qtd);

      el.closest('ul.file-list').find('li.alone').css('display', 'none');

      el.closest('.file-content').find('.qtd-file').val(qtd);
      
      return false;
    });
  /*------------------------------*/


  /*----------NOTIFICATION----------*/
    $('body').on('click', 'button.remove-notification', function(e){
      e.stopPropagation();
      var el = $(this);

      removeNotification(el);
      return false;
    });

    function removeNotification(el){
      var post = { id: el.attr('id'),
        table: el.attr('table') };

      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); $('body').css('cursor', 'wait'); },
        url:'ajax/other/remove_notification.php',
        type: 'POST',
        dataType:'json',
        data: post
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        $('body').css('cursor', 'initial');

        if(data.success){
          get_table(0);
          el.closest('div').remove();
        }
        else if(data.reload){
          location.reload();
        }
        else{
          $('table tbody').html('<tr class="no-click"><td class="w100"><i>Nenhum registro encontrado.</i></td></tr>');
        }
      });/**/
    }
  /*------------------------------*/

  /*----------DELETE / RESTORE----------*/
    $('body').on('click', 'table button.delete', function(e){
      e.stopPropagation();
      var el = $(this);

      if(confirm('Deseja mesmo excluir?')){
        deleted(0, el.attr('id'), el.attr('ajax'));
      }
    });

    $('body').on('click', 'table button.restore, .commands .restore', function(e){
      e.stopPropagation();
      var el = $(this);

      if(confirm('Deseja mesmo restaurar?')){
        deleted(1, el.attr('id'), el.attr('ajax'));

        if(el.attr('ajax') == 'budgets'){
          location.reload();
        }
      }
    });

    function deleted(type, id, file){
      var post = { type: type, id: id }

      $.ajax({
        beforeSend:function(){ $('article.message-load').fadeIn(100); },
        url:'ajax/deleted/'+file+'.php',
        type: 'POST',
        dataType:'json',
        data: post
      }).done(function(data){
        $('article.message-load').fadeOut(100); 
        if(data.success){
          get_table(0);
        }
        else if(data.reload){
          location.reload();
        }
        else{
          el.find('p.error').html(data.message);
        }
      });
    }
  /*------------------------------*/ 
});