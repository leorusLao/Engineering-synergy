<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link href="/asset/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/asset/css/app.css" rel="stylesheet" type="text/css" />
    <!-- end of global css -->
     
    <script src="/asset/js/app.js" type="text/javascript"></script>
    <script src="/asset/js/bootstrap.min.js"></script>
    <script src="/asset/js/daypilot.js" type="text/javascript"></script>
 
    <!--end of page level css-->
    
</head>

 
 
 
<body class="skin-josh"> 
<div class="col-xs-12">
 
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
                    //modal.showUrl("/dashboard/view-plan-chart/viwe-task-bar/{{$token}}/" + args.task.id());disabled this on mobile
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
   
</div>
</body>
  
 
 

 
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

 