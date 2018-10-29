@extends('backend-base') 

@section('css.append')

@stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
   
@if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
@endif 
@if($result)
	 <h4 class="text-center text-danger">
          {{$result}}
     </h4>
@endif
 
  
  @if(count($rows))
    
    {{ Form::open(array('url' => '/dashboard/supplier/select', 'method' => 'post', 'class' => 'form-inline', 'onsubmit' => 'return validateForm()')) }}
       
  		   {{ Form::text('qtext','',array('class' => 'form-control', 'id' => 'qtext' )) }}
  	       {{ Form::submit(Lang::get('mowork.search'),array('name' => 'submit','class' => 'btn btn-info','id' =>'sbmtbtn')) }}
        
    {{ Form::close()}}		
  <div class="margin-t20 margin-b20">{{Lang::get('mowork.search_company_help')}}</div>
  
  {{ Form::open(array('url' => '/dashboard/supplier/select', 'method' => 'post', 'class' => '', 'onsubmit' => 'return validateCheckbox()')) }}
  <div class="table-responsive table-scrollable"> 
  <table class="table dataTable table-striped display table-bordered table-condensed">

          <thead>
            <tr>
              <th>{{Lang::get('mowork.company_name')}}</th>
              <th>{{Lang::get('mowork.biz_number')}}</th>
              <th>{{Lang::get('mowork.ceo')}}</th>
              <th>{{Lang::get('mowork.phone')}}</th>
              <th>{{Lang::get('mowork.email')}}</th>
              <th>{{Lang::get('mowork.learn_more')}}</th>
              <th>{{Lang::get('mowork.please_select')}}</th>
            </tr>

          </thead>

          <tbody>

  @endif

  @foreach($rows as $row) 
   
    <tr> 
    <td>{{ $row->company_name }}</td>
    <td>{{ $row->reg_no }}</td>
    <td>{{ $row->ceo }}</td>
    <td>{{ $row->phone }}</td>
    <td>{{ $row->email }}</td>
    <td><div id='txt-{{$row->company_id}}' class="text-center" onclick="getMore({{$row->company_id}})"><i class="glyphicon glyphicon-th"  style="cursor:pointer"></i></div> </td>
    <td class="text-center"><input type="checkbox" id='cbx-{{$row->company_id}}' name='cbx[]' value="{{$row->company_id}}"></td>
    </tr>

  @endforeach



  @if(count($rows))

     </tbody>

     </table>
     </div>
     <input type="submit" name="batchSelect" class="btn btn-info" value="{{Lang::get('mowork.batch_supplier')}}">
   {{ Form::close() }}
      
     <div class='text-center'><?php echo $rows->links(); ?></div>

     <div class="clearfix"></div>
   @endif
</div>

<div class="modal fade" id="holder" style="z-index: 9999">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.more')}}</h4>
			</div>
			<div class="modal-body" id="more">
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
 
<script type="text/javascript" src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

    $(function(){
       $('#me8').addClass('active');   

       $('#loadRequest').click(function(event){

       }); 
       
       $('table.data-table.sort').dataTable( {

            "bPaginate": false,

            "bLengthChange": false,

            "bFilter": false,

            "bSort": true,

            "bInfo": false,

            "aaSorting" : [[0, 'desc']],

            "bAutoWidth": false 

        });

       $('table.data-table.full').dataTable( {

            "bPaginate": true,

            "bLengthChange": true,

            "bFilter": false,

            "bSort": true,

            "bInfo": true,

            "aaSorting" : [[0, 'desc']],

            "bAutoWidth": true,

            "sPaginationType": "full_numbers",

            "sDom": '<""f>t<"F"lp>',

            "sPaginationType": "bootstrap"

        });

    });

    function validateForm(){
  	  var errors = '';
  	 
  	  qtext = $.trim($('#qtext').val()); 
  	  if(qtext.length < 1) {
  	  	alert("{{Lang::get('mowork.search_required')}}");
  	  	return false;
  	  }
  	  return true;
  	  
    }

    function validateCheckbox(){
        
    	$cbx_group = $("input:checkbox[name='cbx[]']"); 
        if(! $cbx_group.is(":checked") ){
        	  alert("{{Lang::get('mowork.tick_required')}}");
        	  return false;
        }  
      	  return true;
    }

    function getMore(id) {
        
		 $.ajax({
  	        type:"POST",
  	        url : '{{url("/dashboard/get-company-info")}}',
  	        data : {company: id, _token: "{{csrf_token()}}" },
  	        dataType: "json",
  	        success : function(result) {
  	        	var txt = "";
  	        	for(var ii in result){
  	        		txt = txt + result[ii] + "<br>";
  	        	}
  	        	$('#holder').modal('toggle');  
  	            $('#more').html(txt);
  	        },
  	       error: function(xhr, status, error) {
  	    	  alert(error);
            },
  	    });
    }
    
 </script>

@stop