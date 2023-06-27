$(function(){
  /*----------OPEN / CLOSE MENU----------*/
  $('body').on('click', '.btn-menu', function(e){
  	e.stopPropagation();
  	
  	$('nav').toggle("slide");
  });

  $('body').on('click', 'nav', function(e){ e.stopPropagation(); });

  $('html,body').on('click', function(e){
  	e.stopPropagation();

  	if($('nav').is(':visible') == true){
  	  $('nav').toggle("slide");
  	}
  });
  /*------------------------------*/


  
  /*----------OPEN / CLOSE SUBOPTION----------*/
  $('body').on('click', '#open-down', function(e){
    e.stopPropagation();
    
    var el = $(this);
    
    if(!el.parent().hasClass('fixed')){
      if(el.parent().hasClass('open')){
        el.parent().find('.suboption').slideUp();

        el.parent().removeClass('open');
        el.parent().removeClass('opened');
      }
      else{
        for(var i = 0; i < el.parent().parent().find('li.open').length; i++){
          $('li.open').find('.suboption').slideUp();

          $('li.open').removeClass('open');
          $('li.opened').removeClass('opened');
        }

        el.parent().find('.suboption').slideDown();

        el.parent().addClass('open');
        el.parent().addClass('opened');
      }
    }
  });

  $('body').on('click', 'nav', function(e){
    e.stopPropagation();

    for(var i = 0; i < $('li.open').length; i++){
      $('li.open').find('.suboption').slideUp();

      $('li.open').removeClass('open');
      $('li.opened').removeClass('opened');
    }
  });

  $('html,body').on('click', function(e){
    e.stopPropagation();

    for(var i = 0; i < $('li.open').length; i++){
      $('li.open').find('.suboption').slideUp();

      $('li.open').removeClass('open');
      $('li.opened').removeClass('opened');
    }
  });
  /*------------------------------*/
	
});