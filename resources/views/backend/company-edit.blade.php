@extends('backend-base') 

@section('css.append')

@stop
 
@section('content')
<div class="col-xs-12 col-sm-10 col-sm-offset-1">

@if(Session::has('result'))
 
	 <h4 class="text-warning text-center">
          {{Session::get('result')}}
     </h4>
 
@endif

<form action="/dashboard/company-edit/{{$token}}/{{$company_id}}"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
 
<fieldset>
<div class="col-xs-12 col-sm-6">
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.company_name')}}</i>
</div>
<input class="form-control" name="company_name" type="text" value="{{isset($row->company_name)?$row->company_name:''}}" placeholder="{{Lang::get('mowork.company_holder')}}" id="company" />
</div>
  
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.biz_number')}}</i>
</div>
<input class="form-control" name="reg_no" type="text" value="{{isset($row->reg_no) ? $row->reg_no:''}}" id="reg_no"  />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.biz_des')}}</i>
</div>
<textarea class="form-control" name="biz_des" id="biz_des" rows="2">{{isset($row->biz_des)? $row->biz_des: ''}}</textarea>
</div>  
  
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="cellphone" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.legal_person')}}</i>
</div>
<input class="form-control" name="ceo" type="text" value="{{isset($row->legal_person)?$row->legal_person:''}}" placeholder="{{Lang::get('mowork.legalperson_holder')}}"  id="legal_person" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="cellphone" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.ceo')}}</i>
</div>
<input class="form-control" name="ceo" type="text" value="{{isset($row->ceo)?$row->ceo:''}}" id="ceo" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.phone')}}</i>
</div>
<input class="form-control" name="phone" type="tel" value="{{isset($row->phone)?$row->phone:''}}" id="phone" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.fax')}}</i>
</div>
<input class="form-control" name="fax" type="tel" value="{{isset($row->fax)?$row->fax:''}}" id="fax" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.email')}}</i>
</div>
<input class="form-control" name="email" type="email" value="{{isset($row->email)?$row->email:''}}" id="email"  />
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.wechat_pub_acct')}}</i>
</div>
<input class="form-control" name="wechat_pub_acct" type="text" value="{{isset($row->wechat_pub_acct)?$row->wechat_pub_acct:''}}" id="wechat_pub_acct" />
</div>
 
</div>
<div class="col-xs-12 col-sm-6">
   
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.industry')}}</i>
</div>
{{ Form::select('industry', $companyIndustryList,isset($row->industry)?$row->industry:'', array('class' => 'form-control','id' => 'industry')) }}
</div>
 
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.company_type')}}</i>
</div>
{{ Form::select('company_type', $companyTypeList,isset($row->company_type)?$row->company_type:'', array('class' => 'form-control','id' => 'company_type')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.country')}}</i>
</div>
{{ Form::select('country', $countryList, $countryId, array('class' => 'form-control','id' => 'country')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.province_name')}}</i>
</div>
{{ Form::select('province', $provinceList, $provinceId, array('class' => 'form-control','id' => 'province')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.city')}}</i>
</div>
 
{{ Form::select('city', $cityList, $cityId, array('class' => 'form-control','id' => 'city')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="address-book" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.address')}}</i>
</div>
<input class="form-control" name="address" type="text" value="{{isset($row->address)?$row->address:''}}" id="address" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.postcode')}}</i>
</div>
<input class="form-control" name="postcode" type="text" value="{{isset($row->postcode)?$row->postcode:''}}" id="postcode" />
</div>


@if($domain == 'www.mowork.cn')
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.bu')}}</i>
</div>

<select name="buid" class="form-control" id="buid">
<option value="0"></option>
@foreach($bulist as $bu)
 <option value="{{$bu->bu_id}}" @if(isset($row->domain_id) && $row->domain_id == $bu->bu_id) selected @endif>{{$bu->bu_name}} - {{$bu->bu_site}}</option>
@endforeach
</select>
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.bu_verify')}}</i>
</div>
<input class="form-control" name="verify_code" type="text" value="" id="veryfiycode" />
</div>  
 <div class="text-center">{{Lang::get('mowork.bu_notice')}}</div> 
</div>
@endif
 <input name="_token" type="hidden" value="{{ csrf_token() }}">


  {{ Form::submit(Lang::get('mowork.submit'),array('name' => 'submit','class' => 'btn hidden','id' =>'sbmtbtn')) }}
</fieldset>
</form>

<div class="col-sm-6 col-sm-offset-3">
@if($licenseImg)
<div id="licenseImg" class="margin-b20">
 
	<span><a href='#licenseshow' data-toggle="modal" >{{Lang::get('mowork.show')}}{{Lang::get('mowork.biz_license')}}</a></span>
	<span style="margin-left:20px">{{Lang::get('mowork.tick_checkbox')}}: {{Lang::get('mowork.update_license')}}<input type='checkbox' id='updateLicense' name='updateLicense' style="margin-top:-5px;margin-left:5px;">
    </span>
   
</div>
@elseif(isset($row->company_name))
<div class="text-center text-danger margin-b20">{{Lang::get('mowork.license_warning')}}</div>
@endif

<div id="license" class="margin-b20" @if($licenseImg) style="display: none; margin-top:40px" @endif>
<form action="{{ url('/upload/license') }}" class="dropzone" id="mydropzone" style="min-height: 50px;margin-top:-10px">
<input name="_token" value="{{ csrf_token() }}" type="hidden">
</form>
</div>
  
<div class="btn btn-lg btn-info margin-t10" id="btn1">{{empty($row)?Lang::get('mowork.create'):Lang::get('mowork.update')}}</div>

</div>
</div>


<div class="modal fade" id="licenseshow" style="z-index: 9999">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.biz_license')}}</h4>
			</div>
			<div class="modal-body pull-center text-center">
 				<img src="{{$licenseImg}}">
			</div>
			<div class="modal-footer"></div>
			<div class="text-center"
				style="margin-top: -10px; margin-bottom: 10px">
				<button type="button" data-dismiss="modal" class="btn-warning">X</button>
			</div>
		</div>
	</div>
</div>
@stop 
@section('footer.append')
<link media="all" type="text/css" rel="stylesheet" href="/asset/dropzone4/dropzone.css">
<script type="text/javascript" src="/asset/dropzone4/dropzone.js"></script>
 
<script type="text/javascript">
$(function(){
    $('#me8').addClass('active');   
    $('#updateLicense').click(function() {
     	 if($(this).is(':checked')){
     	  	 $('#license').show();
     	 }
     	 else{
     		 $('#license').hide(); 
     	 }
    });
    
    $('#country').change(function() {
    	  
    	 $.ajax({
	        type:"GET",
	        url : '/dropdown',
	        data : { country: $(this).val() },
	        dataType: 'json',
	        success : function(result) {
	        	 
	        	var options = '';
	        	for(var ii in result){
	        		options += "<option value='" + ii + "'>" + result[ii] + "</option>";
	        	}
	        	 
	          $('#province').html(options);
	          $('#city').html('');
	        },
	        error: function() {
		         
	            //do logic for error
	        }
	    });
	    
	 });
	 
	 
	  $('#province').change(function() {
    	 $.ajax({
	        type:"GET",
	        url : '{{url("/dropdown")}}',
	        data : { province: $(this).val() },
	        dataType: 'json',
	        success : function(result) {
	        	
	        	var options = '';
	        	for(var ii in result){
	        		options += "<option value='" + ii + "'>" + result[ii] + "</option>";
	        	}
	        	 
	          $('#city').html(options);
	        },
	        error: function() {
	            //do logic for error
	        }
	    });
	    
	   }); 	    


	  $('#mydropzone').click(function(event){
	      	event.preventDefault();
	  	  });
	     
	  Dropzone.options.mydropzone={
	    	maxFiles: 1, 
	      	maxFilesize: 4,
	      	acceptedFiles: ".jpg,.gif,.png",
	          addRemoveLinks: true,
	          dictDefaultMessage: "<span class='text-warning'>{{Lang::get('mowork.upload_license')}}</span>",
	          dictFileTooBig: "{{Lang::get('mowork.image_too_big')}}",
	          dictRemoveFile: "{{Lang::get('mowork.cancel_image')}}",
	          dictInvalidFileType: "{{Lang::get('mowork.image_type_error')}}",
	          dictMaxFilesExceeded: "{{Lang::get('mowork.exceed_max_files')}}",
	          init: function() {
	        
	        this.on("maxfilesexceeded", function(file){
	      	 
	             this.removeFile(file);
	        });	
	         
	        this.on("error", function(file, responseText) {
	             alert(responseText);
	             
	             console.log(file);
	        });
	        
	        this.on("success", function(file, responseText) {
	            
	            console.log(file);
	       });
	        
	      },

	      
	      removedfile: function(file) {
	     	 
	          var name = file.name;  
	          
	        	$.ajax({
	          	type: 'POST',
	          	url: "{{url('/relink')}}",
	          	 
	          	 data: {
	                   fname: name,//fullpath for this uploaded file to be deleted
	                   _token: "{{ csrf_token() }}" 
	              },
	              success: function( data ) {
	              },
	              error: function(xhr, status, error) {
	                   alert(error);
	              },
	              dataType: 'html'  //use type html rather than json in order to post token 
	      	});
	      	 
	  		var _ref;
	  		return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
	        }
	     }

});
 
function validateForm() {
    var errors = '';

    
    company_name = $.trim($('#company').val());
 	
	if(company_name.length < 1) {
		errors += "{{Lang::get('mowork.company_required')}} \n";	
	}

	phone = $.trim($('#phone').val());
	 	
	if(phone.length < 1) {
			errors += "{{Lang::get('mowork.phone_required')}} \n";	
	}

	if(phone.length > 0 ) {
	   	if(! validatePhone(phone) ) {
	   		errors += "{{Lang::get('mowork.invalid_phone')}} \n";	
	     }
	}

	email = $.trim($('#email').val());

	if(email.length > 0){
		
		if(! validateEmail(email) ){
			errors += "{{Lang::get('mowork.invalid_email')}} \n";	
		}
	}
	else {
		errors += "{{Lang::get('mowork.email_required')}} \n";	
	}

    industry = $('#industry').val();
    if(industry < 1) {
    	errors += "{{Lang::get('mowork.industry_required')}} \n";	
    }

    company_type = $('#company_type').val();
    if(company_type < 1) {
    	errors += "{{Lang::get('mowork.companytype_required')}} \n";	
    }
    
	province = $.trim($('#province').val());

	if(province  < 1){
	 	errors += "{{Lang::get('mowork.province_required')}} \n";	
	}

	city = $.trim($('#city').val());
   
	if(city  < 1){
	 	errors += "{{Lang::get('mowork.city_required')}} \n";	
	}

	if('{{$domain}}' == 'www.mowork.cn'){ 
	   buid = $('#buid').val();
   
	   if(buid < 1) {
		 errors += "{{Lang::get('mowork.bu_required')}} \n";
	   }
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

  $( "#btn1" ).click(function() {
      $("#btnId").val(1);
	  $( "#sbmtbtn" ).click();
	  return false;
  });

</script>
 
@stop