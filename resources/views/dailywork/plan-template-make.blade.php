@extends('backend-base') 

@section('css.append')
<link href="/asset/css/layout.css" type="text/css" rel="stylesheet" />
<script src="/asset/js/daypilot.js" type="text/javascript"></script>
@stop
 
@section('content')
<div class="col-xs-12">

@if(Session::has('result'))
   <div class="text-center text-warning">
          {{Session::get('result')}}
   </div>
@endif
  <h4 class="text-center">{{$row->template_name}} ({{Lang::get('mowork.template_type')}}: {{$row->template_type}})</h4>
 <div class="margin-b10">{{Lang::get('mowork.apply')}}
 	<a href='#formholder' rel="tooltip"
		data-placement="right" data-toggle="modal"
		data-placement="right" 
		data-original-title="{{Lang::get('mowork.add').Lang::get('mowork.plan_type')}}">
		<span>{{Lang::get('mowork.reference_template')}}</span></a>, {{Lang::get('mowork.template_easy')}}
 </div>
 <div class="hidden-xs margin-b10 margin-t-20 text-center">{{Lang::get('mowork.template_note')}}</div>
 
  <div id="header"></div>
      <div class="shadow"></div>
      <div class="hideSkipLink"></div>
  <div class="main">

      <div class="space"></div>

      <div id="dp"></div>
      <?php if($task) {
      	       $start_date = substr($task->start,0,10);
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
                    { title: "{{Lang::get('mowork.node_name')}}", property: "text", width: 100},
                    { title: "{{Lang::get('mowork.duration')}}", width: 100},
                ];

                dp.onBeforeRowHeaderRender = function(args) {
                    args.row.columns[1].html = new DayPilot.Duration(args.task.end().getTime() - args.task.start().getTime() + 86400 ).toString("d") + " {{Lang::get('mowork.days')}}";
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
                            $.get("/dashboard/task-link-delete", {
                            	token: "{{$token}}",
                                template_id: "{{$template_id}}",
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
                    $.get("/dashboard/task-create", {
                        name: args.text,
                        token: "{{$token}}",
                        template_id: "{{$template_id}}",
                        start: dp.startDate.toString(),
                        end: dp.startDate.addDays(1).toString()
                    },
                    function(data) {
                        loadTasks();
                    });
                };

                dp.onTaskMove = function(args) {
                    //move taskbar: backend_move.php
                    $.get("/dashboard/task-move", {
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
                    //resize taskbar: backend_move.php
                     
                    $.get("/dashboard/task-move", {
                        id: args.task.id(),
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
                     
                    $.get("/dashboard/task-row-move", {
                    	token: "{{$token}}",
                        template_id: "{{$template_id}}",
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
                    $.get("/dashboard/task-link-create", {
                        token: "{{$token}}",
                        template_id: "{{$template_id}}",
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
                     
                    modal.showUrl("/dashboard/task-edit/{{$token}}/" + args.task.id());
                };
                 
                dp.init();
               
             	loadTasks();
                loadLinks();
                
				/*
                function loadTasks() {//backend_tasks.php
                    $.get("/dashboard/task/{{$token}}/{{$template_id}}").done( function(data) {
                        dp.tasks.list = data;
                        dp.update();
                    })
                    .fail(function(xhr, status, error) {
         				alert( error );//xhr.responseText
    				 })
                }*/

                function loadTasks() {//backend_tasks.php
                    $.get("/dashboard/task/{{$token}}/{{$template_id}}", function(data) {
                        dp.tasks.list = data;
                        dp.update();
                    })
                }
               
                function loadLinks() {
                   //backend_links.php
                     $.get("/dashboard/task-link/{{$token}}/{{$template_id}}", function(data) {
					 	dp.links.list = data;
                      	//alert(JSON.stringify(data));
                        dp.update();
                    }) 
                }
                
                
                var taskMenu = new DayPilot.Menu({
                    items: [
                    	 {   
                         	
                         	text: "{{Lang::get('mowork.change')}}",
                             onclick: function() {
                            	 var task = this.source; 
                                 var modal = new DayPilot.Modal();
                                 modal.closed = function() {
                                     loadTasks();
                                 };
                                 //edit.php?id=
                                 
                                 modal.showUrl("/dashboard/task-edit/{{$token}}/" + task.id());
                             }
                         }, 
                         {   
                        	
                        	text: "{{Lang::get('mowork.delete')}}",
                            onclick: function() {
                                var task = this.source;
                                //backend_task_delete.php
                                $.get("/dashboard/task-delete", {
                                    template_id: "{{$template_id}}",
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
  
  <div class="margin-t10 text-center"><a href='/dashboard/template-list'>{{Lang::get('mowork.back')}}</a></div>
</div>

 
<div class="modal fade" id="formholder" style="z-index: 9999">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">X</button>
				<h4 class="modal-title text-center">{{Lang::get('mowork.reference_template')}}</h4>
			</div>
			<div class="modal-body">
			<form action='/dashboard/template/make/{{$token}}/{{$template_id}}' method='post'
					autocomplete='off' role=form onsubmit='return validateForm();'>
			<div class="table-responsive table-scrollable">
			<table class="table data-table table-bordered">	 
			<thead>
			<tr>
				<th>{{Lang::get('mowork.template_code')}}</th>
				<th>{{Lang::get('mowork.template_name')}}</th>
				<th>{{Lang::get('mowork.template_type')}}</th>
				<th>{{Lang::get('mowork.node_type')}}</th>
			    <th>{{Lang::get('mowork.select')}}</th>
			</tr>
		    </thead>
		    <tbody>
 	
			@foreach($tmplts as $line)
                 @if($line->id == $row->id)
                   <?php continue; ?>
                 @endif
			<tr>
				<td>{{ $line->template_code }}</td>
				<td>{{ $line->template_name }}</td>
				<td>{{ $line->template_type }}</td>
				<td>{{ $line->node_type }}</td>
				@if($refs > 2)
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
				<input type="submit" name="submit" class="btn-info" value="{{Lang::get('mowork.confirm')}}" >&nbsp;&nbsp;&nbsp;
				<input type="button" class="btn-info" data-dismiss="modal" value="{{Lang::get('mowork.cancel')}}" >
			</div>
			<input name="_token" type="hidden" value="{{ csrf_token() }}">
			<div class="text-center text-warning  margin-t10">{{Lang::get('mowork.reference_note')}}</div>
		</form>
		</div>
	</div>
</div>
</div>
@stop

@section('footer.append')
 <script type="text/javascript">
    
    $(function(){
    	$('.checkbox').click(function(){
    	    $('.checkbox').each(function(){
    	        $(this).prop('checked', false); 
    	    }); 
    	    $(this).prop('checked', true);
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
 </script>

@stop
 