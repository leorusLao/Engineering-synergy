@extends('front-base')

@section('css.append')
<script src="/asset/js/bootstrap.min.js"></script>
@stop

@section('main-body')
<div class="container" style="margin-top:30px;">
        <div class="row">
            <div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">{{Lang::get('mowork.signup')}}</h3>
                    </div>
                    <div class="panel-body">
                        @if (Session::has('result'))
                        <div class="alert alert-danger text-center">
                          {{Session::get('result')}}
                        </div>
                        @endif
                        
                        @if (count($errors) > 0)
    					<div class="alert alert-danger text-center">
        				<ul>
            			@foreach ($errors->all() as $error)
                		<li>{{ $error }}</li>
            			@endforeach
        				</ul>
    					</div>
						@endif
                        
                        <form action='/signup' method = 'post' autocomplete='off' role=form onsubmit='return validateForm();'>
                            <fieldset>
                                <div id='step1'>
                                <div class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.username_required')}}" name="username" type="text" value="" id='username' />
                                </div>
                                <div class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.mobile_email')}}" name="username_confirmation" type="text" value="" id="username_confirmation" />
                                </div>
                                </div>
                                
                                <div id='step2'>
                                <div id='smsConfirm' class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.sms_validate')}}" name="sms" type="text" value=""  id='sms' />
                                </div>
                                
                                <div class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.set_password')}}" name="password" type="password" value=""  id='password' />
                                </div>
                                <div class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.password_confirm')}}" name="password_confirmation" type="password" value="" id='password_confirmation' />
                                </div>
                                </div>
                                 
                                <div id='next' class="form-group spacer-10">
								<p class="btn btn-lg btn-info btn-block" id='next_text' onclick='toggleNext()'>{{Lang::get('mowork.next_step')}}</p>
								</div>
                                
                                <div id='submit' class="form-group spacer-10">
								<input type="submit" name ="submit" class="btn btn-lg btn-info btn-block" value="{{Lang::get('mowork.signup')}}">
								</div>
								
								<div id='wechat' class="form-group input-group center-block">
 									<div class="col-xs-offset-3 col-xs-6">
 							 		<p class="btn btn-md btn-success btn-lg" onclick="wechatAuth()">{{Lang::get('mowork.wechat_signup')}}</p>
 									</div>
								</div>
								
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <input name='signupMethod' type='hidden' value='1' id='signupMethod'>
                                 
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="dialog" style="z-index:99999"> 
           <div class="modal-dialog">
		   <div class="modal-content">
			<div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">{{Lang::get('mowork.term_condition')}}</h4>
            </div>   
			<div class="modal-body">
			    @include("term-condition")
			</div>
			<div class="modal-footer">
            </div>
            <div  class="text-center" style="margin-top:-20px;">
             <button type="button" data-dismiss="modal" class="btn">{{Lang::get('mowork.close')}}</button>
            </div>
			</div>
		  </div>
	 </div>	
@stop

@section('footer.append')
 
<script type="text/javascript">
    $(function() {
    	$('#step2').css('display','none');
    	$('#submit').css('display','none');
        
    	(function(seconds) {
      	    var refresh,       
      	        intvrefresh = function() {
      	            clearInterval(refresh);
      	            refresh = setTimeout(function() {
      	               location.href = location.href;
      	            }, seconds * 1000);
      	        };

      	    $(document).on('keypress click', function() { intvrefresh() });
      	    intvrefresh();

      	 }(3600));
     });

  function toggleNext() {
	username = $.trim($('#username').val());
	username_confirmation = $.trim($('#username_confirmation').val());
	errors = '';
	isPhone = false;
	
	if(username != username_confirmation) {
		errors += "{{Lang::get('mowork.input_mismatch')}} \n";	
	}

	if (username.indexOf('@') !== -1 || username.length < 5) {
    	if(! validateEmail(username)){
    		errors += "{{Lang::get('mowork.invalid_email')}} \n";	
        }
    }
	else if (! validatePhone(username)) {
    	errors += "{{Lang::get('mowork.invalid_mobile')}} \n";	
	}
    else {
		isPhone = true;
    }

	if(errors.length > 0) {
		alert(errors);
		return false;
	}

	if(!isPhone) {
		$('#smsConfirm').hide();
		$('#signupMethod').val('2');
	}

    $('#next').css('display','none');
    $('#step1').css('display','none');
    $('#wechat').css('display','none');
	$('#step2').css('display','block');
	$('#submit').css('display','block');
	
  }
  
  function validateForm() {
    var errors = '';

      
	if( $('#smsConfirm').is(':visible') ){
		sms = $.trim($('#sms').val());
		if(sms.length < 5) {
		     errors += "{{Lang::get('mowork.sms_required')}} \n";
		}
	}
	 
    password = $.trim($('#password').val());
    if(password.length <  6) {
     
		errors += "{{Lang::get('mowork.password_too_short')}} \n";	
	}

    password_confirmation = $.trim($('#password_confirmation').val());
    if (password != password_confirmation ) {
     
		errors += "{{Lang::get('mowork.password_mismatch')}} \n";	
	}
	
	if(errors.length > 0) {
		alert(errors);
		return false;
	}
	return true;
    
  }

  function validatePhone(phone) {
	  
	var stripped = phone.replace(/[\(\)\.\-\ ]/g, '');
  	var phoneRegex = /^\d{10,16}$/; // Change this rege based on requirement
 
  	return phoneRegex.test(stripped); 
	 
  }

  function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email); 
  } 

  function wechatAuth(){
	  window.location.href = "http://www.mowork.cn/weixin/auth.php";
  }
  
  </script>
@stop
