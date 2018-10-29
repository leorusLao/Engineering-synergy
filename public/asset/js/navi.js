
$(document).ready(function () {
	var $spLangWrap = $('#spLangWrap');
	var $spLangBtn = $('#spLangBtn');
	var $spMenuWrap = $('#spMenuWrap');
	var $spMenuBtn = $('#spMenuBtn');

	$spLangBtn.on('click', function(){
		$spMenuWrap.removeClass('is-active');
		$spMenuBtn.removeClass('is-active');
    	$(this).toggleClass('is-active');
    	$spLangWrap.toggleClass('is-active');
    	if ($spLangBtn.hasClass('is-active')||$spMenuBtn.hasClass('is-active')) {
    		stopScroll();
    	} else {
    		startScroll();
    	}
  	});

  	$spMenuBtn.on('click', function(){
  		$spLangWrap.removeClass('is-active');
		$spLangBtn.removeClass('is-active');
    	$(this).toggleClass('is-active');
    	$spMenuWrap.toggleClass('is-active');
    	if ($spLangBtn.hasClass('is-active')||$spMenuBtn.hasClass('is-active')) {
    		stopScroll();
    	} else {
    		startScroll();
    	}
  	});

  	function stopScroll() {
		$('html,body').on('touchmove.noScroll', function(e) {
        	e.preventDefault();
        });
	}

	function startScroll() {
		$('html,body').off('.noScroll');
	}

/*
	var $header = $('#gHeader .container');
	var $spMenu = $('#spMenuWrap');
	var navi01 = '<div id="spMenuBar"><div id="spMenuBtn"><span class="line line_01"></span><span class="line line_02"></span><span class="line line_03"></span></div></div>';
	

	var navi02 = $('#gHeader .container .hdCont .hdInfo').prop('outerHTML');
	var navi03 = '<div class="hdCont">'+navi02+'</div>';
	var navi04 = $('#hdSearch').prop('outerHTML');
	var navi05 = $('#footer .ftMenu').html();
	var naviSp = navi03+navi04+navi05;


	$header.append(navi01);
	$spMenu.append(naviSp);
*/
});