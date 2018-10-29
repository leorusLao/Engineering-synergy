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
                                <div id='accountExist' class="text-center text-danger margin-b20"></div><br>
                                <div class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.username_required')}}"
                                      name="username" type="text" value="" id='username' />
                                </div>
                                
                                <div id='next' class="form-group spacer-10">
								  <input class="btn btn-lg btn-info btn-block" id='next_step' onclick='checkExistedAccount();' value="{{Lang::get('mowork.next_step')}}">
								</div>
                                </div>
                            	
                                <div id='step2'>
                                <div id='smsConfirm' class="form-group input-group">
                                    <div class="input-group-addon">
                                        <i class="livicon" data-name="key" data-size="18" data-c="#000" data-hc="#000" data-loop="true"></i>
                                    </div>
                                    <input class="form-control" placeholder="{{Lang::get('mowork.sms_email_validate')}}" name="sms" type="text" value=""  id='sms' />
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
                                   
                                <div id='submit' class="form-group spacer-10">
								<input type="submit" name ="submit" class="btn btn-lg btn-info btn-block" value="{{Lang::get('mowork.signup')}}">
								</div>
								
								<div id='wechat' class="form-group input-group center-block text-center">
 									 
 							 		<div class="btn btn-success btn-lg" onclick="wechatAuth()">{{Lang::get('mowork.wechat_signup')}}</div>
 									 
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
    	$('#accountExist').css('display', 'none');
    	 
     });

  
  function toggleNext() {
	username = $.trim($('#username').val());
     
	errors = '';
	isPhone = false;
	 
	if(! validateEmail(username)  && ! validatePhone(username)){
 		errors += "{{Lang::get('mowork.invalid_email_mobile')}} \n";	
        alert(errors);
        return false;
    }
   
 	if(validatePhone(username)){
		 url = '{{url("/mobile-check-code")}}';
	} else {
		 url = '{{url("/email-check-code")}}'
	     $('#signupMethod').val('2');
	}    
	 
	  
		 $.ajax({
		        type:"GET",
		        url : url,
		        data : { mobile: username },
		        dataType: 'json',
		        success : function(result) {
		        	
		        	var reasonCode = '';
		        	for(var ii in result){
			        	if(ii == 'resasonCode'){
			        		reasonCode =  result[ii];
			        		break;
				        }
		        		 
		        	}
		        },
		        error: function() {
		            //do logic for error
		        }
		 });
    
   
	if(errors.length > 0) {
		alert(errors);
		return false;
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
		if(sms.length < 4) {
		     errors += "{{Lang::get('mowork.sms_required')}} \n";
		}
	} else {
      return false;
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

  function checkExistedAccount() {
	  $('#next').css('display', 'block');
	  $('#accountExist').css('display', 'none');
	  username = $.trim($('#username').val());
	  if(username == '' || username == null) return; 

      existedAccount = false;
		 
	  $.ajax({
	        type:"GET",
	        url : '/account-existed-check',
	        data : { username: username },
	        async: false,
	        dataType: 'json',
	        success : function(result) {
	       	    
	        	for(var ii in result){
	        		 
			        if(result[0] == 'existedAccount') {
		        		    
		        		   error = "{{Lang::get('mowork.existed_account')}}";
  
		        		   $('#accountExist').text(error);
		        		   $('#accountExist').css('display', 'block');
 	 	      	       	   // alert(error);  
 	 	      	           existedAccount = true;  
		        		   break;
			        } else {
			        	   $('#accountExist').css('display', 'none');
			        }
	        	}
	        },
	        error: function() {
	            //do logic for error
	        	 alert('failed');
	        }
	   });

       if( !existedAccount ) {
	      toggleNext();
       }
  	 
  }
  </script>
@stop
