@extends('backend-base')
 
 
@section('content')

<div class="col-xs-12">

   <div class="col-xs-6 text-right"><input type="checkbox" checked disabled><a href="/dashboard/distribute-project"><b>{{Lang::get('mowork.assign_project')}}</b></a></div>
   <div class="col-xs-6 text-left"><a href="/dashboard/accept-project"><input type="checkbox">{{Lang::get('mowork.accept_project')}}</a></div>
   <div class="clearfix margin-b20"></div> 
    @if(count($rows) > 0)
   	     
	@if(Session::has('result'))
	<div class="text-center text-danger margin-b20">{{Session::get('result')}}</div>
	@endif
	 
	 
			<div class="table-responsive table-scrollable">
			<table class="table dataTable table-striped display table-bordered table-condensed">

			<thead>
			<tr>
			<th nowrap="nowrap">{{Lang::get('mowork.project_number')}}</th>
		    <th nowrap="nowrap">{{Lang::get('mowork.project_name')}}</th>
		 
			<th nowrap="nowrap">{{Lang::get('mowork.project_manager')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.part_name')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.quantity')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.fixture')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.gauge')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.mould')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.supplier')}}</th>
            <th nowrap="nowrap">{{Lang::get('mowork.assign_supplier')}}</th>
            </tr>
			</thead>

			<tbody>
 			<?php $last_proj_id = 0;?>
			@foreach($rows as $row)

				<tr>
				@if($row->proj_id == $last_proj_id)
				<td></td>
				<td></td>
				<td></td>
				@else
				<td><a href="/dashboard/project-view/{{hash('sha256',$salt.$row->proj_id)}}/{{$row->proj_id}}" target="_blank">{{ $row->proj_code }}</a></td>
				<td>{{ $row->proj_name }}</td>
				<td>{{ $row->proj_manager}}</td>
				@endif
				<td>{{ $row->part_name }}</td>
				<td>{{ $row->quantity }}</td>
			 	<td>{{$row->jig }}</td>
			 	<td>{{$row->gauge }}</td>
			 	<td>{{$row->mold }}</td>
			 	<td>{{$row->supplier_name }}</td>
			    <td class="text-center"> 
	            @if($row->supplier_accepted)
	              {{Lang::get('mowork.assigned')}}
	            @else 	
	            <a href='#formholder' data-toggle="modal" data-book-id={{$row->id}}><span class="glyphicon glyphicon-hand-right"></span></a>
			 	@endif
			 	</td>
 	 		</tr>
             <?php $last_proj_id = $row->proj_id; ?>
	 		@endforeach

	 		</tbody>

	 		</table>

	 		<div class='text-center'><?php echo $rows->links(); ?></div>

	<div class="clearfix"></div>
    
</div>
@else
 <h5 class="text-danger"><b>{{Lang::get('mowork.no_public_project')}}</b></h5>
@endif 
</div>
  
<div class="modal fade" id="formholder" style="z-index: 9999;">
	<div class="modal-dialog modal-lg" style="top: 1%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.select_supplier')}}</h4>
			</div>
			
		<div class="modal-body">
		 
    <form action='/dashboard/distribute-project' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
    
	<div class="table-responsive table-scrollable"> 
    <table class="table dataTable table-striped display table-condensed">

		<thead>
			<tr>
			    <th>{{Lang::get('mowork.select')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.supp_name')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.biz_number')}}</th>
			 	<th nowrap='nowrap'>{{Lang::get('mowork.contact')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.phone')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.email')}}</th>
 				<th nowrap='nowrap'>{{Lang::get('mowork.city')}}</th>
				<th nowrap='nowrap'>{{Lang::get('mowork.address')}}</th>
			</tr>
		</thead>

		<tbody>
    		@foreach($suppliers as $row)
 
			<tr>
			    <td><input type="checkbox" class="check" name="cbx[]" value="{{$row->sup_company_id}}"/></td>
				<td>{{ $row->company_name }}</td>
		 		<td>{{ $row->reg_no}}</td>
				<td>{{ $row->contact_person}}</td>
				<td>{{ $row->phone}}</td>
				<td>{{ $row->email}}</td>
				<td>{{ $row->city_name}}</td>
				<td>{{ $row->address}}</td>
			</tr>

			@endforeach 
	 

		</tbody>

	</table>
 	 <input name="_token" type="hidden" value="{{ csrf_token() }}">
	 <input name="proj_detail_id" type="hidden" id="proj_detail_id" value="">
	 <div class="form-group">
			<input type="submit" class="btn-info" name="submit"	value="{{Lang::get('mowork.assign_supplier')}}">
	 </div>
    </div>
    </form>
        
	<div class='text-center'><?php echo $suppliers->links(); ?></div>

	<div class="clearfix"></div>
	 
 </div>
</div>  
@stop

@section('footer.append')
 
<script type="text/javascript">

    $(function(){
    	$(".check").change(function() {
    	    $(".check").prop('checked', false);
    	    $(this).prop('checked', true);
    	}); 

   	    //above prevent datepicker from fireing 'show.bs.modal';avoid conflict with Datepicker show.bs.modal
        $('#me3').addClass('active');  
          
        $('#formholder').on('show.bs.modal', function(e) {
            var bookId = $(e.relatedTarget).data('book-id');
            
            $(e.currentTarget).find('input[name="proj_detail_id"]').val(bookId);
              
        });
        
     });

    $("[rel=tooltip]").tooltip({animation:false});

    function validateForm() {
        var errors = '';
       
        $cbx_via = $("input:checkbox[name='cbx[]']"); 
        if(! $cbx_via.is(":checked") ){
        	  errors += "{{Lang::get('mowork.supplier_required')}}\n";
        }  

        
        if(errors.length > 0) {
      	alert(errors);
    	return false;
        }
        return true;
        
      }


    function isValidDate(dateString)
    {
    	 
      // First check for the pattern
      var regex_date = /^\d{4}\-\d{1,2}\-\d{1,2}$/;

      if(!regex_date.test(dateString))
      {
          return false;
      }

      // Parse the date parts to integers
      var parts   = dateString.split("-");
      var day     = parseInt(parts[2], 10);
      var month   = parseInt(parts[1], 10);
      var year    = parseInt(parts[0], 10);

      // Check the ranges of month and year
      if(year < 1000 || year > 3000 || month == 0 || month > 12)
      {
          return false;
      }
   	  return true;
     }
</script>
 
@stop