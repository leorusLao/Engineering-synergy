$(function(){

  var heightHeader = $('#gHeader').outerHeight();
  var heightWindow =$(window).height();
  var heightVideo = heightWindow-heightHeader;

  $('#mainVideo').css('height',heightVideo+'px');

  //console.log(heightVideo);


  //mainMenu
  var $mainMenu = $('#main .mainMenu ul li a');
  var $mainCont = $('#main .mainCont ul li');

  function mainMenu() {
    $mainMenu.on('mouseover',function(){
      $mainMenu.removeClass('hover');
      $mainCont.removeClass('hover');
      $(this).addClass('hover');
      var menuId = $(this).attr('href');
      $(menuId).addClass('hover');   
    });
  }

var timer = false;
$(window).scroll(function() {
    if (timer !== false) {
        clearTimeout(timer);
        
    }
    timer = setTimeout(function() {
        //console.log('scroll');
        mainMenu();
    }, 200);
});

  $mainMenu.on('click',function(){
    return false;
  });

});


$(window).load(function() {


     $("#techNews ul li").heightLine({
       minWidth:751
     });


});

