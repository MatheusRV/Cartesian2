$(function(){
  $('body').on('click', '.commands .bt:not(#empty)', function(e){
    e.stopPropagation();
    var el = $(this);
    var action = el.attr('id');
    console.log(action);
  });

  $('body').on('click', '.controls .toggle', function(e){
    $(this).toggleClass('active');
  });
});