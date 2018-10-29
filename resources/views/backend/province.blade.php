@extends('backend-base') 

@section('css.append')

@stop

@section('content')

<div class="col-xs-12 col-sm-10 col-sm-offset-1">
 
<ul class="nav nav-justified margin-b30">
  <li><a href="/dashboard/admin-region">{{Lang::get('mowork.country')}}</a></li>
  <li  class='active'><a href="/dashboard/admin-region/province">{{Lang::get('mowork.province')}}{{Lang::get('mowork.state')}}</a></li>
  <li><a href="/dashboard/admin-region/city">{{Lang::get('mowork.city')}}</a></li>
  
</ul>
 
@if(Session::has('result'))
	 <div class="alert alert-danger">
          {{Session::get('result')}}
     </div>
@endif

 <div class="col-sm-6">
    <form action="/dashboard/admin-region/province" method="post" id="form1" name='form1'>
       
    <select id='country' name='country' class="form-control" style="width:120px">
     @foreach ($countryList as $val)
       <option value="{{$val->country_id}}"  @if($val->country_id == $selectedCountry) selected @endif>
       {{$val->name}}
       </option>
     @endforeach
    </select>
  
     <input name="_token" type="hidden" value="{{ csrf_token() }}">
      
     
    </form> 
    
   </div>
 
  @if(count($rows))
  
  <table class="table data-table display sort table-responsive">

          <thead>
            <tr>
              <th>{{Lang::get('mowork.province')}}{{Lang::get('mowork.state')}}</th>
              <th>{{Lang::get('mowork.in_english')}}</th>
              <th>{{Lang::get('mowork.digit_code')}}</th>
  			  <th>{{Lang::get('mowork.alphabet_code')}}</th>
      		  <th>{{Lang::get('mowork.country_name')}}</th>
            </tr>

          </thead>

          <tbody>

  @endif

  @foreach($rows as $row) 
  

    <tr> 
    <td>{{ $row->name }}</td>
    <td>{{ $row->name_en }}</td>
    <td>{{ $row->digit_code }}</td>
    <td>{{ $row->english_code }}</td>
    <td>{{ $row->country }}</td>
    </td>
    </tr>

  @endforeach



  @if(count($rows))

     </tbody>

     </table>
 
     <div class='text-center'><?php echo $rows->links(); ?></div>

     <div class="clearfix"></div>
   @endif
</div>

@stop

 
@section('footer.append')
 
<script type="text/javascript" src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

    $(function(){
       $('#me8').addClass('active');     
       
       $('#country').change(function() {
    	  country = $('#country').val();
    	 
       	  input = $("<input>").attr("type", "hidden").attr("name", "country").val(country);
   	 	  $('#form1').append($(input));
   	 	  this.form.submit();

       });

       
    });

    
 </script>

@stop