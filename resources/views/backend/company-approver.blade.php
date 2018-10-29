@extends('backend-base')
  
@section('content')

<div class="col-xs-12 col-sm-6 col-sm-offset-3">
  
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
   <div class="text-center text-danger margin-b20">{{Lang::get('mowork.approver_note')}}</div>
   <form action='/dashboard/project-config/company/approver' method='post'
					autocomplete='off' role='form' onsubmit='return validateForm()'>
    <div class="table-responsive table-scrollable">
	<table class="table data-table table-bordered">
 
		<tbody>
            <tr>
				<td>{{ Lang::get('mowork.project_approver') }}</td>
				<td><select name="project_uid" disabled class='form-control' id="project_uid">
				    <option value=''></option>
				    @foreach ($employees as $man)
				    <option value="{{$man->uid}}" @if(isset($row->project_uid) && ($row->project_uid == $man->uid) ) selected @endif>{{$man->fullname}}</option>
				    @endforeach
				    </select>
				</td>
		 	</tr>
            <tr>
				<td>{{ Lang::get('mowork.plan_approver') }}</td>
				<td><select name="plan_uid" disabled class='form-control'>
				    <option></option>
				    @foreach ($employees as $man)
				    <option value="{{$man->uid}}" @if(isset($row->plan_uid) && ($row->plan_uid == $man->uid) ) selected @endif>{{$man->fullname}}</option>
				    @endforeach
				    </select>
				</td>
		 	</tr>
		 	<tr>
				<td>{{ Lang::get('mowork.issue_approver') }}</td>
				<td><select name="issue_uid" disabled class='form-control'>
				    <option></option>
				    @foreach ($employees as $man)
				    <option value="{{$man->uid}}" @if(isset($row->issue_uid) && ($row->issue_uid == $man->uid) ) selected @endif>{{$man->fullname}}</option>
				    @endforeach
				    </select>
				 </td>
		 	</tr>
		 	 
		</tbody>

	</table>
	<input name="_token" type="hidden" value="{{ csrf_token() }}">
	<div class="btn btn-info text-center" onclick="activeEdit()" id='edit' type="submit">{{Lang::get('mowork.edit')}}</div>
	<input class="btn btn-info text-center" name="submit" type="submit"  value="{{Lang::get('mowork.save')}}"  id="submit" style="display:none">
    </div>
    </form>
    
	<div class="clearfix"></div>

</div>

@stop 

@section('footer.append')

<script type="text/javascript"	src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" 	src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
$("[rel=tooltip]").tooltip({animation:false});

    $(function(){
    	 
        $('#me8').addClass('active');   
        /*
         offset =  $('#region').offset().top - ($(window).height() -  $('#region').outerHeight(true)) / 2
	      
          $('html,body').animate({
        	   scrollTop: offset > 0 ? offset:1000
          }, 200);
       */
             

     });
 

    function activeEdit() {
    	 $('.form-control').prop("disabled", false);
    	 $('#edit').css('display','none');
    	 $('#submit').css('display','block');
    	 
    }

    function validateForm() {
       project_uid = $('#project_uid').val();
       
       if(project_uid == '') {
			alert('uid not been set');
			return false; 
       }
       return true; 
    	
    }
</script>


@stop