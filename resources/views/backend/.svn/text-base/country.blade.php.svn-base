@extends('backend-base') 

@section('css.append')

@stop

@section('sidebar-menu')
@include('sidebar-menu') 
@stop

@section('content')
 
  @if(count($rows))
  
  <table class="table data-table display sort table-responsive">

          <thead>

            <tr>
 
              <th>{{Lang::get('mowork.country_name')}}</th>

              <th>{{Lang::get('mowork.in_english')}}</th>

              <th>{{Lang::get('mowork.iso_code')}}</th>

      
            </tr>

          </thead>

          <tbody>

  @endif

  @foreach($rows as $row) 
  

    <tr> 
 
    <td>{{{ $row->name }}}</td>

    <td>{{{ $row->name_en }}}</td>
 
    <td>{{{ $row->iso_code2 }}}</td>
    
    </tr>

  @endforeach



  @if(count($rows))

     </tbody>

     </table>
 
     <div class='text-center'><?php echo $rows->links(); ?></div>

     <div class="clearfix"></div>
   @endif

@stop

 
@section('footer.append')
 
<script type="text/javascript" src="/js/DataTables-1.10.15/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/DataTables-1.10.15/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">

    $(function(){

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

 </script>

@stop