@extends('backend-base') 

@section('css.append')

<link href="/asset/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script src="/asset/js/daypilot.js" type="text/javascript"></script>
@stop
 
@section('content')
<div class="col-xs-12">

@if(Session::has('result'))
   <div class="text-center text-warning">
          {{Session::get('result')}}
   </div>
@endif
  <h4 class="text-center">{{$row->plan_name}} - {{$basinfo->node_no ." ". $basinfo->name}}</h4>
  <h5 class="text-center">{{$basinfo->dep_name}}  [{{Lang::get('mowork.scheduled_duration')}}: {{substr($basinfo->start_date,0,10)}} &#8212; {{substr($basinfo->end_date,0,10)}} ]</h5>
  <h5 class="text-center">{{Lang::get('mowork.task_note')}}</h5>
  <div class="margin-b10">{{Lang::get('mowork.apply')}}
 	<a href='#replan' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="Lang::get('mowork.reference_plan')}}">
		<span>{{Lang::get('mowork.reference_plan')}}</span>
	</a>
	{{Lang::get('mowork.plan_easy')}}
 </div>
  
 
  <div id="header"></div>
      <div class="shadow"></div>
      <div class="hideSkipLink"></div>
  <div class="main">

      <div class="space"></div>

      <div id="dp"></div>
      <?php if($task) {
      	       $start_date = substr($basinfo->start_date,0,10);
            }
            else {
               $start_date = date('Y-m') . '-' .'01';  
            }
            
      ?>
      <script type="text/javascript">
                var dp = new DayPilot.Gantt("dp");
                dp.startDate = new DayPilot.Date("{{$start_date}}");
                dp.days = 365;

                dp.linkBottomMargin = 5;

                dp.rowCreateHandling = 'Enabled';
                dp.columns = [
                    { title: "{{Lang::get('mowork.node_name')}}", property: "text", width: 150},
                    { title: "{{Lang::get('mowork.duration')}}", width: 50},
                ];

                dp.onBeforeRowHeaderRender = function(args) {
                    //alert('args=='+ JSON.stringify(args));
                	args.row.columns[1].html = new DayPilot.Duration(args.task.end().getTime() - args.task.start().getTime() + 86400).toString("d") + " {{Lang::get('mowork.days')}}";
                    args.row.areas = [
                        {
                            right: 3,
                            top: 3,
                            width: 16,
                            height: 16,
                            style: "cursor: pointer; box-sizing: border-box; background: white; border: 1px solid #ccc; background-repeat: no-repeat; background-position: center center; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAABASURBVChTYxg4wAjE0kC8AoiFQAJYwFcgjocwGRiMgPgdEP9HwyBFDkCMAtAVY1UEAzDFeBXBAEgxQUWUAgYGAEurD5Y3/iOAAAAAAElFTkSuQmCC);",
                            action: "ContextMenu",
                            menu: taskMenu,
                            v: "Hover"
                        }
                    ];
                };

                dp.contextMenuLink = new DayPilot.Menu([
                    {  
                        text: "{{Lang::get('mowork.delete')}}",
                        onclick: function() {
                            var link = this.source;
                            //backend_link_delete.php
                            //$.get("/dashboard/plan-task-link-delete", {
                            //	token: "{{$token}}",
                            //  plan_id: "{{$plan_id}}",
                            $.get("/dashboard/department-task-link-delete", {
                                token: "{{$token}}",
                                task_id: "{{$task_id}}",
                                id: link.id()
                            },
                            function(data) {
                                loadLinks();
                            });
                        }
                    }
                ]);

                dp.onRowCreate = function(args) {
                    //backend_create.php
                    //$.get("/dashboard/plan-task-create", {
                    //    name: args.text,
                    //    token: "{{$token}}",
                    //    plan_id: "{{$plan_id}}",
                    $.get("/dashboard/department-task-create", {
                        name: args.text,
                        token: "{{$token}}",
                        plan_id: "{{$plan_id}}",
                        task_id: "{{$task_id}}",
                        start: dp.startDate.toString(),
                        end: dp.startDate.addDays(1).toString()
                    },
                    function(data) {
                        loadTasks();
                    });
                };

                dp.onTaskMove = function(args) {
                	 
                    $.get("/dashboard/department-task-move", {
                        id: args.task.id(),
                        token: "{{$token}}",
                        start: args.newStart.toString(),
                        end: args.newEnd.toString()
                    },
                    function(data) {
                    	loadTasks();
                        //dp.message("Updated");
                    });
                };

                dp.onTaskResize = function(args) {
                 
                    $.get("/dashboard/department-task-move", {
                        id: args.task.id(),
                        token: "{{$token}}",
                        start: args.newStart.toString(),
                        end: args.newEnd.toString()
                    },
                    function(data) {
                    	loadTasks();
                        //dp.message("Updated");
                    });
                };


                dp.onRowMove = function(args) {
                    //move task name button: backend_row_move.php
                    //$.get("/dashboard/plan-task-row-move", {
                    //	token: "{{$token}}",
                    //  plan_id: "{{$plan_id}}",
                    $.get("/dashboard/department-task-row-move", {
                    	token: "{{$token}}",
                        task_id: "{{$task_id}}",
                        source: args.source.id,
                        target: args.target.id,
                        position: args.position
                    },
                    function(data) {
                        dp.message("Updated");
                        //loadTasks();
                    });
                };

                dp.onLinkCreate = function(args) {
                    //backend_link_create.php
                    //$.get("/dashboard/plan-task-link-create", {
                    //    token: "{{$token}}",
                    //    plan_id: "{{$plan_id}}",
                    $.get("/dashboard/department-task-link-create", {
                        token: "{{$token}}",
                        task_id: "{{$task_id}}",
                        from: args.from,
                        to: args.to,
                        type: args.type
                    },
                    function(data) {
                        loadLinks();
                    });
                };

                dp.onTaskClick = function(args) {
                    var modal = new DayPilot.Modal();
                    modal.closed = function() {
                        loadTasks();
                    };
                    //edit.php?id=
                    //modal.showUrl("/dashboard/plan-task-edit/{{$token}}/" + args.task.id());
                    $('#task_id').val(args.task.id());
                    $('#taskedit').modal('show');
                  
                };
                 
                dp.init();
               
             	loadTasks();
                loadLinks();
                
				 
                function loadTasks() {//backend_tasks.php
                    //$.get("/dashboard/plan-task/{{$token}}/{{$plan_id}}", function(data) {
                    $.get("/dashboard/department-task/{{$token}}/{{$task_id}}", function(data) {
                        dp.tasks.list = data;
                        dp.update();
                    })
                }
                
                function loadLinks() {
                    //backend_links.php
                    //$.get("/dashboard/plan-task-link/{{$token}}/{{$plan_id}}", function(data) {
                    $.get("/dashboard/department-task-link/{{$token}}/{{$task_id}}", function(data) {
					 	dp.links.list = data;
                      	//alert(JSON.stringify(data));
                        dp.update();
                    }) 
                }
                
                var taskMenu = new DayPilot.Menu({
                    items: [
                    	 {   
                         	
                         	text: "{{Lang::get('mowork.edit')}}",
                             onclick: function() {
                            	 var task = this.source; 
                                 var modal = new DayPilot.Modal();
                                 modal.closed = function() {
                                     loadTasks();
                                 };
                                 //edit.php?id=
                                 // modal.showUrl("/dashboard/plan-task-edit/{{$token}}/" + task.id());
                                 $('#task_id').val(task.id());
                                 $('#taskedit').modal('show');
                                 
                             }
                         }, 
                         {   
                        	
                        	text: "{{Lang::get('mowork.delete')}}",
                            onclick: function() {
                                var task = this.source;
                                //backend_task_delete.php
                                //$.get("/dashboard/plan-task-delete", {
                                //    plan_id: "{{$plan_id}}",
                                $.get("/dashboard/department-task-delete", {
                                    task_id: "{{$task_id}}",    
                                    token: "{{$token}}",
                                    id: task.id()
                                },
                                function(data) {
                                    loadTasks();
                                });
                            }
                        }
                    ]
                });

          </script>  
  
  
  </div>
  
  <div class="margin-t10 text-center"><a href='/dashboard/department-plan'>{{Lang::get('mowork.back')}}</a></div>
</div>
  
<div class="modal fade" id="replan" style="z-index: 9999">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.reference_plan')}}</h4>
			</div>
			<div class="modal-body">
			<form action='/department-plan/make/{{$token}}/{{$task_id}}' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
			<div class="table-responsive table-scrollable">
			<table class="table data-table table-bordered">	 
			@if($refps > 0)
			<thead>
			<tr>
				<th>{{Lang::get('mowork.plan_code')}}</th>
				<th>{{Lang::get('mowork.plan_name')}}</th>
				<th>{{Lang::get('mowork.plan_type')}}</th>
				<th>{{Lang::get('mowork.description')}}</th>
			    <th>{{Lang::get('mowork.select')}}</th>
			</tr>
		    </thead>
		    @else 
		     <div class="text-center">{{Lang::get('mowork.no_refplan')}}</div>
		    @endif
		    <tbody>
 	        
			@foreach($refplans as $line)
                 @if($line->id == $row->id)
                   <?php continue; ?>
                 @endif
			<tr>
				<td>{{ $line->plan_code }}</td>
				<td>{{ $line->plan_name }}</td>
				<td>{{ $line->plan_type }}</td>
				<td>{{ $line->description }}</td>
				@if($refps > 2)
				<td><input class="checkbox" name="cbx[]" type="checkbox" value="{{$line->id}}"></td>
				@else
				<td><input class="checkbox" name="cbx" type="checkbox" value="{{$line->id}}"></td>
				@endif
		 	</tr>

			@endforeach

		  </tbody>
          </table>
	 				 
			</div>
			<div class="modal-footer"></div>
			<div class="text-center">
			    @if($refps > 0)
				<input type="submit" name="submit" class="btn-info" value="{{Lang::get('mowork.confirm')}}" >&nbsp;&nbsp;&nbsp;
				@endif
				<input type="button" class="btn-info" data-dismiss="modal" value="{{Lang::get('mowork.cancel')}}" >
			</div>
			<input name="reference" type="hidden" value="2">
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			<div class="text-center text-warning  margin-t10">{{Lang::get('mowork.plan_refplan')}}</div>
		</form>
		</div>
		<div class="text-center">{{$refplans->links()}}</div>
	</div>
</div>
</div>
@if($tmplPage)
<script type="text/javascript">
$('#formholder').modal('show');
</script>
@endif

@if($planPage)
<script type="text/javascript">
$('#replan').modal('show');
</script>
@endif


<div class="modal fade" id="taskedit" style="z-index: 9999">
	<div class="modal-dialog modal-lg" style="top:3%">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.edit_task_detail')}}</h4>
			</div>
		<div class="modal-body">
		<form action="/dashboard/department-task-update" id="form1" method="post" onsubmit="return validateTask()">
              <input type="hidden" name="task_id" value="" id="task_id">
		 <div class="table-responsive table-scrollable">
	     <table class="table data-table table-bordered">
	<tbody>		 
  
    <tr><td class="form-inline" colspan=2>{{Lang::get('mowork.node_name')}}: 
        
        <input  type="text" class="form-control" name="nodename" id="js8">
       
        </td>
    </tr>
    <tr> 
        <td>{{Lang::get('mowork.leader')}}
           <select class="margin-t20" id='pickman' multiple="multiple" name="people[]">
			     @foreach ($employees as $man)
			       <option value="{{$man->uid}}" id="man{{$man->uid}}">{{$man->fullname}}</option>
			     @endforeach
		   </select>
        </td>
    </tr>    
     
    <tr><td class="form-inline">{{Lang::get('mowork.start_date')}} 
            <div class='input-group date' id='datepicker1'>
                 <input type="text" name="start_date" id="js13" class="form-control">
                 <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                 </span>
            </div>
            
			<script type="text/javascript">
           		 $(function () {
               		   $('#datepicker1').datetimepicker({
               	         minView: 2,
               			 format: "yyyy-mm-dd",
               		    });
           		 });
            </script>       
        </td>
        <td class="form-inline">{{Lang::get('mowork.end_date')}} 
            <div class='input-group date' id='datepicker2'>
                 <input type="text" name="end_date" id="js14" class="form-control">
                 <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                 </span>
            </div>
			<script type="text/javascript">
           		 $(function () {
               		   $('#datepicker2').datetimepicker({
               	         minView: 2,
               			 format: "yyyy-mm-dd",
               		    });
           		 });
            </script>    
        </td>
    </tr>
    <tr><td>{{Lang::get('mowork.duration')}}: <span id='duration'></span></td>
        <td>{{Lang::get('mowork.dayoffs')}}: <span id='dayoffs'></span></td></tr>
    <tr><td>{{Lang::get('mowork.workdays')}}: <span id="workdays"></span></td><td>{{Lang::get('mowork.milestone')}}: <input id="milestone" name="milestone" type="checkbox" value="1"></td></tr>  
    <tr><td class="form-inline">{{Lang::get('mowork.key_node')}}: <input type="checkbox" name="keynode" value="1" id="keynode"></td>
        <td class="form-inline">{{Lang::get('mowork.control_condition')}}: <input type="text" name="condition" value="" class="form-control" id="condition"></td>
    </tr>
 </tbody>
</table>
</div>
<input name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="space">
    <input type="hidden" name="plan_id" value="{{$row->plan_id}}"/>
    <input type="hidden" name="dep_id" value="{{$basinfo->department}}"/>
    <input type="submit" class="btn btn-info" value="{{Lang::get('mowork.confirm')}}" />
    <button id="cancel" class="btn" data-dismiss="modal">{{Lang::get('mowork.cancel')}}</button>
</div>
</form>
		</div>
		<div class="text-center">{{$refplans->links()}}</div>
	</div>
</div>
</div>


@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="/asset/css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript" src="/asset/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
    
    $(function(){
    	$(".modal-dialog").draggable({
    	    handle: ".modal-header"
    	});
    	
    	$("#datepicker1").on("show", function(e){
   	     e.preventDefault();
   	     e.stopPropagation();
   	    }).on("hide", function(e){
   	     e.preventDefault();
   	     e.stopPropagation();
   	    });

   	    $("#datepicker2").on("show", function(e){
  	     e.preventDefault();
  	     e.stopPropagation();
  	    }).on("hide", function(e){
  	     e.preventDefault();
  	     e.stopPropagation();
  	    });

  	    //above prevent datepicker from fireing 'show.bs.modal';avoid conflict with Datepicker show.bs.modal
  	     $('#pickman').multiselect({
        	numberDisplayed: 20,
        	allSelectedText: '{{Lang::get("mowork.selected_all")}}',
        	nonSelectedText: '{{Lang::get("mowork.please_select")}}',
            nSelectedText: '{{Lang::get("mowork.click_see")}}'
        });
  	 
  	 
    	$('.checkbox').click(function(){
    	    $('.checkbox').each(function(){
    	        $(this).prop('checked', false); 
    	    }); 
    	    $(this).prop('checked', true);
    	});

        $('#taskedit').on('show.bs.modal', function(e) {
            
            task_id = $('#task_id').val();
           
            $.ajax({
    	        type:"POST", //get department task's detailed info when open modal
    	        url : '{{url("/dashboard/get-department-task-info2edit")}}',
    	        data : { task_id: task_id, _token: "{{csrf_token()}}" },
    	        dataType: 'json',
    	        success : function(result) {
    	        	 
        	        
    	         	for(var ii in result){
    	         		 
    	         	   
    	         	   if (ii == 4) {
    	         		  $('#duration').text(result[ii]);
        	           }
    	         	   else if (ii == 5) {
    	         		  $('#dayoffs').text((result[ii] == null || result[ii] == 0) ? '':result[ii]);
        	           }
    	         	   else if (ii == 6) {
    	         		  $('#workdays').text((result[ii] == null || result[ii] == 0) ? '':result[ii]);
        	           }
    	         	   else if (ii == 8) {
    	         		  $('#js8').val(result[ii]);
        	           }
    	         	   else if(ii == 12) {
    						var array = result[12].split(',');
    							$('#pickman').multiselect('select', array);
    	    	       }
    	         	   else if(ii == 13){
    	        	      $('#js13').val(result[ii]);    
        	           }
    	         	   else if(ii == 14){
    	        	      $('#js14').val(result[ii]);    
        	           }
        	           else if(ii == 16){
							$('#condition').val( (result[ii] == null || result[ii] == 0) ? '':result[ii] );
            	       }
        	           else if(ii == 20)
                	   { 
            	         	if(result[ii] == 1) {
        	         		   $('#milestone').prop('checked', true);
            	         	}
            	        }
        	         	else if(ii == 21)
                	    { 
            	         	if(result[ii] == 1) {
        	         		   $('#outsource').prop('checked', true);
            	         	}
            	        }
        	         	else if(ii == 22)
                	    { 
            	         	if(result[ii] == 1) {
        	         		   $('#keynode').prop('checked', true);
            	         	}
            	        }		
    	        	}

    	        },
    	        error: function() {
    	            //do logic for error
    	        }
    	    });
            
        });
   	   
    
    });

    function validateForm(){
        errors = '';
        @if($refs > 2)
        	cbx_group = $("input:checkbox[name='cbx[]']");  
        @else
    	   cbx_group = $("input:checkbox[name='cbx']"); 
        @endif
      
        if(! cbx_group.is(":checked") ){
        	  errors = "{{Lang::get('mowork.reference_required')}}";
        }  
       
    	if(errors.length > 0) {
    		alert(errors);
    		return false;
    	}
    	return true;
        
    }

    function validateTask() {
         
		nodename = $('#js8').val();
		 
		start = $('#js13').val();
		end = $('#js14').val();
        err = '';
        
		if(nodename.length < 1) {
			err += "{{Lang::get('mowork.nodename_required')}}\n";
		}
		 
		 
		if(start.length < 1) {
			err += "{{Lang::get('mowork.start_required')}}\n";
		}
		if(end.length < 1) {
			err += "{{Lang::get('mowork.end_required')}}\n";
		}
		  
	    if(err.length > 0 ) { 
	    	alert(err);
	 	    return false;
	    }
	    
	    return true;    
    }
 
 </script>

@stop
