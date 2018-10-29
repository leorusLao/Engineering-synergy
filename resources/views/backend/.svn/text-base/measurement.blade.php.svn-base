@extends('backend-base') 

@section('css.append')

@stop
 
@section('content')
<div class="col-xs-12">

@if(Session::has('result'))
   <div class="alert alert-warning">
          {{Session::get('result')}}
     </div>
@endif
 
<form action="/dashboard/personal-profile"  method='post' autocomplete='off' role='form' onsubmit='return validateForm();'> 
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
             
            <th>
              {{Lang::get('mowork.measurement_type')}}
            </th>
            <th>
              {{Lang::get('mowork.measurement_name')}}              
            </th>
            <th>
              {{Lang::get('mowork.measurement_unit')}}  
            </th>
            <th>
              {{Lang::get('mowork.measurement_symbol')}} 
            </th>
            <th>
              {{Lang::get('mowork.measurement_ratio')}} 
            </th>
            
          </tr>
        </thead>
          <tbody>
        @foreach($result as $key=>$value)
          <tr>
            <td>
              {{$value->type}}
            </td>
            <td>
              {{$value->name}}
            </td>
            <td>
              {{$value->unit}}
            </td>
            <td>
              {{$value->symbol}}
            </td>
            <td>
              {{$value->ratio}}
            </td>
               
          </tr>
            
          @endforeach
          </tbody>
      </table>
     </div>
  </div>
</div>
</form>
</div>
@stop 