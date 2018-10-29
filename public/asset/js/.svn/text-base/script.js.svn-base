$(function(){

  var headerHeight = $('#gHeader').height();
  $('body').css('paddingTop',headerHeight+'px');

  //Back to Page top
  $(window).scroll(function() {
      if($(this).scrollTop() != 0) {
          $('#goTop').fadeIn();    
      } else {
          $('#goTop').fadeOut();
      }
  });

  //console.log(headerHeight);

  //Smooth Scroll
  $('a[href^=#].smooth').on('click',function(){
      var speed = 900;
      var href= $(this).attr("href");
      var target = $(href == "#" || href == "" ? 'html' : href);
      var position = target.offset().top-headerHeight;
      $('body,html').animate({scrollTop:position}, speed, 'swing');
      return false;
  });

  $('a.active').on('click',function(){
    return false;
  });

  $('#jobs .job-list h3').on('click',function(){
        if ($(this).next('.text').is(':hidden')) {
            $(this).addClass('open');
        } else {
            $(this).removeClass('open');
        }
        
        $(this).next('.text').slideToggle('slow');
        return false;
  });

  //Initialize pop up 
  $('.openPopupInline').magnificPopup({
    type:'inline',
    midClick: true
  });

  $(document).on('click', '.popup-modal-dismiss', function (e) {
    e.preventDefault();
    $.magnificPopup.close();
  });

  $('.openPopupSingle').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    //mainClass: 'mfp-img-mobile',
    image: {
      verticalFit: true
    }    
  });

  $('.web-slider').bxSlider({
      auto: true,
      pause: 4000,
      pager: false,
      controls: false,
      slideMargin: 0,
   });

  $('.login-slider').bxSlider({
  auto: true,
   pause: 4000,
   pager: false,
   slideWidth: 180,
   minSlides: 2,
   maxSlides: 4,
   moveSlides: 1,
   slideMargin: 55
  });




  obj = $('.advert-slider').bxSlider({
          auto: true,
          pause: 4000,
          onSliderLoad: function () {
              timeout_id = setTimeout(null , 10000);
          },
          onSlideBefore: function () {
              clearTimeout(timeout_id);
          },
          onSlideAfter: function () {
              timeout_id = setTimeout(TimeoutFunc , 3000);
          }
      });
  function TimeoutFunc(){
      obj.startAuto();
  }


/*
  var nav = $('#gHeader');
    var navTop = nav.offset().top+500;
    var navHeight = nav.height();
    var showFlag = false;
    nav.css('top', -navHeight+'px');

    $(window).scroll(function () {
        var winTop = $(this).scrollTop();
        //console.log(winTop);
        if (winTop >= navHeight+250) {
            if (showFlag == false) {
                showFlag = true;
                nav
                    .addClass('fixed')
                    .stop().animate({'top' : '0px'}, 400);
            }
        } else if (winTop <= navTop) {
            if (showFlag) {
                showFlag = false;
                nav.stop().animate({'top' : -navHeight+'px'}, 400, function(){
                    nav.removeClass('fixed');
                });
            }
        }
    });*/

});


$(window).load(function() {

  $("#web .why-select .ws-benefits ul li").heightLine({
    minWidth:751
  });

  $("#web .voice ul li").heightLine({
    minWidth:751
  });
  $("#web .voice ul li .comment").heightLine({
    minWidth:751
  });
  $("#web .web-series .pricing-plan1.priceHeight").heightLine({
    minWidth:751
  });
});