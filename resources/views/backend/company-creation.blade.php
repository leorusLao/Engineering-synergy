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

<form action="/dashboard/company-creation"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'>

<fieldset>
<style type="text/css">
	.completion {
		z-index:99;
		overflow: auto;
		position: absolute;
		cursor: pointer;
		list-style-type:none;
		top:34px;
		background-color: #E6E6FA;
		border:1px solid #CCC;
		display:none;
	}
	.completion li {padding:2px 12px;}
	.completion li:hover {background-color: #CCC;}
</style>
<div class="col-xs-12 col-sm-6">
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.company_name')}}</i> *
</div>
<input class="form-control" name="company_name" type="text" value="" placeholder="{{Lang::get('mowork.company_holder')}}" id="company" />
	<div class="completion"></div>
</div>



<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.biz_number')}}</i>
</div>
<input class="form-control" name="reg_no" type="text" value="" id="reg_no"  />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.biz_des')}}</i>
</div>
<textarea class="form-control" name="biz_des" id="biz_des" rows="2"></textarea>
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="cellphone" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.legal_person')}}</i>
</div>
<input class="form-control" name="ceo" type="text" value="" placeholder="{{Lang::get('mowork.legalperson_holder')}}"  id="legal_person" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="cellphone" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.ceo')}}</i>
</div>
<input class="form-control" name="ceo" type="text" value="" id="ceo" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.phone')}}</i> *
</div>
<input class="form-control" name="phone" type="tel" value="" id="phone" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.fax')}}</i>
</div>
<input class="form-control" name="fax" type="tel" value="" id="fax" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="mail" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.email')}}</i> *
</div>
<input class="form-control" name="email" type="email" value="" id="email"  />
</div>
 
</div>
<div class="col-xs-12 col-sm-6">

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.industry')}}</i> *
</div>
{{ Form::select('industry', $companyIndustryList,'', array('class' => 'form-control','id' => 'industry')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon  required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.company_type')}}</i> *
</div>
{{ Form::select('company_type', $companyTypeList,'', array('class' => 'form-control','id' => 'company_type')) }}
</div>

@if($domain == 'www.mowork.cn')
<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.bu')}} *</i>
</div>

<select name="buid" class="form-control" id="buid">
<option value="0"></option>
@foreach($bulist as $bu)
 <option value="{{$bu->bu_id}}">{{$bu->bu_name}}</option>
@endforeach
</select>
</div>
  
@endif

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.country')}}</i>
</div>
{{ Form::select('country', $countryList, $countryId, array('class' => 'form-control','id' => 'country')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.province_name')}}</i> *
</div>
{{ Form::select('province', $provinceList, $provinceId, array('class' => 'form-control','id' => 'province')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.city')}}</i> *
</div>

{{ Form::select('city', $cityList, $cityId, array('class' => 'form-control','id' => 'city')) }}
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="address-book" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.address')}}</i>
</div>
<input class="form-control" name="address" type="text" value="" id="address" />
</div>

<div class="form-group input-group">
<div class="input-group-addon">
<i class="livicon required" data-name="doc-portrait" data-size="18" data-c="#000" data-hc="#000" data-loop="true">{{Lang::get('mowork.postcode')}}</i>
</div>
<input class="form-control" name="postcode" type="text" value="" id="postcode" />
</div>
 
 <input name="_token" type="hidden" value="{{ csrf_token() }}">


  {{ Form::submit(Lang::get('mowork.submit'),array('name' => 'submit','class' => 'btn hidden','id' =>'sbmtbtn')) }}
</fieldset>
</form>

<div class="col-sm-6 col-sm-offset-3">


<div id="license" class="margin-b20" @if($licenseImg) style="display: none; margin-top:40px" @endif>
<form action="{{ url('/upload/license') }}" class="dropzone" id="mydropzone" style="min-height: 50px;margin-top:-10px">
<input name="_token" value="{{ csrf_token() }}" type="hidden">
</form>
</div>

<div class="btn btn-lg btn-info margin-t10" id="btn1">{{Lang::get('mowork.create')}}</div>

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

		// 公司名称补全
		var ww = $('.completion').prev('input').width();
		$('.completion').css('width', ww + 24 + 'px');
		var value = null;
		var res = null;

		$('.completion').prev('input').bind('input propertychange',function() {
			var val = $(this).val();
			// 中文输入法输入 还没添加到input框时是空格
			if(val == '' || val == ' ' || val == value + ' ' || val == value) {
				return;
			}

			if(val == '' || val == ' ')
			{
				$('.completion').hide();
				$('.completion').html('');
				res = null;
			}
			$('#reg_no').val('');
			$('#address').val('');
			$('#biz_des').text('');
			$('#legal_person').val('');

			value = val;
			$.ajax({
				url: '/dashboard/company-completion',
				type:'post',
				dataType:'json',
				data:{
					_token:'{{csrf_token()}}',
					companyName:val
				},
				success:function(data){
					if(data) {
						res = data;
						var html = '';
						for(var i in data)
						{
							html += '<li>' + i + '</li>';
						}

						$('.completion').show();
						$('.completion').html(html);
					}
				},
				error:function(){
					$('.completion').hide();
					$('.completion').html('');
					res = null;
					$('#reg_no').val('');
					$('#address').val('');
					$('#biz_des').text('');
					$('#legal_person').val('');
				}
			});
		});


		$(document).on('click', '.completion li', function(){
			if(res == null) {
				return;
			}
			$('.completion').hide();
			var name = $(this).text();
			$('.completion').prev('input').val(name);
			$.ajax({
				url: 'company-info-completion',
				type:'post',
				dataType:'json',
				data:{
					_token:'{{csrf_token()}}',
					name:name
				},
				success:function(data){
					$('#reg_no').val(data.reg_no);
					$('#address').val(data.address);
					$('#biz_des').text(data.biz_des);
					$('#legal_person').val(data.ceo);
				},
				error:function() {
					$('#reg_no').val('');
					$('#address').val('');
					$('#biz_des').text('');
					$('#legal_person').val('');
				}
			});
		});


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