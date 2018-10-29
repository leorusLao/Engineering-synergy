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
  <h4 class="text-center">{{$row->plan_name}} ({{$row->plan_type}})</h4>
  
 
  <div id="header"></div>
      <div class="shadow"></div>
      <div class="hideSkipLink"></div>
  <div class="main">

      <div class="space"></div>

      <div id="dp"></div>
      <?php if($task) {
      	       $start_date = substr($task->start_date,0,10);
            }
            else {
               $start_date = date('Y-m') . '-' .'01';  
            }
      ?>
      <script type="text/javascript">
                var dp = new DayPilot.Gantt("dp");
                dp.startDate = new DayPilot.Date("{{$start_date}}");
                dp.days = 730;

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
  
                dp.onTaskClick = function(args) {
                    var modal = new DayPilot.Modal();
                    modal.closed = function() {
                        loadTasks();
                    };
                    //edit.php?id=
                    modal.showUrl("/dashboard/view-plan-chart/viwe-task-bar/{{$token}}/" + args.task.id());
                };
                 
                dp.init();
               
             	loadTasks();
                loadLinks();
                
				 
                function loadTasks() {//backend_tasks.php
                    $.get("/dashboard/plan-task/{{$token}}/{{$plan_id}}", function(data) {
                        dp.tasks.list = data;
                        dp.update();
                    })
                }
                
                function loadLinks() {
                   //backend_links.php
                     $.get("/dashboard/plan-task-link/{{$token}}/{{$plan_id}}", function(data) {
					 	dp.links.list = data;
                      	//alert(JSON.stringify(data));
                        dp.update();
                    }) 
                }
                
                
                var taskMenu = new DayPilot.Menu({
                    items: [
                    	 
                    ]
                });

          </script>  
  
  
  </div>
  
  <div class="margin-t10 text-center"><a  href="javascript:window.top.close();">{{Lang::get('mowork.close')}}</a></div>
</div>

  
 
@stop

@section('footer.append')
 <script type="text/javascript">
    
    $(function(){
    	 
    	// the rest of your code ...
    	 
    	$('.checkbox').click(function(){
    	    $('.checkbox').each(function(){
    	        $(this).prop('checked', false); 
    	    }); 
    	    $(this).prop('checked', true);
    	});
    
     });

    
 </script>

@stop