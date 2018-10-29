@extends('popup-base')
  
@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3 margin-t20">
    
    <h4 class="text-center margin-b20">{{Lang::get('mowork.assign_user_role')}}</h4>
    @if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	<script type="text/javascript">
	 window.opener.location.reload();
    </script>
 	@endif 
	  
    <div class="text-center pull-center">
    <form action='/dashboard/account-management/role-assignment/{{$token}}/{{$id}}' method='post'
					autocomplete='off' role=form>
					 
				   	 
				 	<div class="form-group text-center">
					    
						{{Lang::get('mowork.please_select')}}
					 		 
					</div>
					 	 
					<div class="form-group">
					 
					<select name="role">
					 @foreach($roleList as $key => $val)
					  <option value="{{$key}}" @if($key == $row->role_id) selected @endif>{{$val}}</option>
					 @endforeach
					</select>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
					</div>
					 
			 		<div class="form-group margin-l20">
						<input type="submit" class="btn-info btn-sm" name="submit"
						 	value="{{Lang::get('mowork.update')}}">
					</div>
					<input name="_token" type="hidden" value="{{ csrf_token() }}">
					 
				</form>
         </div>
	             <div class="clearfix"></div>
	               
                 <div class="text-center" onclick="window.close()"><p class="btn">{{Lang::get('mowork.close')}}</p></div>
                  
</div>
 
@stop 

@section('footer.append')
 
<link media="all" type="text/css" rel="stylesheet" href="/asset/dropzone4/dropzone.css">
<script type="text/javascript" src="/asset/dropzone4/dropzone.js"></script>
<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
      	   
      $('#me8').addClass('active');   
 
    });
     
   	
</script>


@stop