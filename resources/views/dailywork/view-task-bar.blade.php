<html>
<head>
    <title></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- demo stylesheet -->
    <link type="text/css" rel="stylesheet" href="/asset/layout.css" />    

    <!-- helper libraries -->
    <script src="/asset/js/jquery-1.11.3.min.js" type="text/javascript"></script>

    <!-- daypilot libraries -->
    <script src="/asset/js/daypilot.js" type="text/javascript"></script>
	
</head>
<body style="margin:20px">
    
<form action="/dashboard/plan-task-update/{{$token}}/{{$id}}" id="f" method="post">

<div class="space">
    <div>{{Lang::get('mowork.node_name')}}</div>
    <div>
        <input id="name" name="name" value="<?php echo htmlspecialchars($row->name) ?>"/>
    </div>
</div>
<div class="section-milestone">
    <div class="space" class="">
        <div>{{Lang::get('mowork.milestone')}}:</div>
        <div>
            <input id="milestone" name="milestone" type="checkbox" <?php if ($row["milestone"]) { echo 'checked'; } ?> />
            <label for="milestone"></label>
        </div>    
    </div>
</div>

<div class="space">
    <div>{{Lang::get('mowork.start_date')}}:</div>
    <div>
        <input id="start" name="start"/> <a href="#" onclick="startPicker.show(); return false;"></a>
    </div>
</div>
    
<div class="section-taskonly">

    <div class="space">
        <div>{{Lang::get('mowork.end_date')}}:</div>
        <div>
            <input id="end" name="end"/> <a href="#" onclick="endPicker.show(); return false;"></a>
        </div>
    </div>
    
    <div class="space">
        @if(is_null($dayoffs))
        <div style="color:#fa0000">{{substr($row->end_date,0,4)}}{{Lang::get('mowork.no_next_calendar')}}</div>
        @else
        <div>{{Lang::get('mowork.dayoffs')}}: {{$dayoffs}}</div>
        @endif
        <div>
             
        </div>
    </div>
    
    <div class="space">
        <?php $duration = date_diff(date_create($row->start_date), date_create($row->end_date));
 			$duration = $duration->format("%d") + 1 - ($dayoffs? $dayoffs : 0);?>
        <div>{{Lang::get('mowork.workdays')}}: {{$duration}}</div>
        <div>
             
        </div>
    </div>

    <div class="space">
        <div>{{Lang::get('mowork.complete')}}:</div>
        <div>
            <select id="complete" name="complete">
                <?php 
                for($i = 0; $i <= 100; $i+=10) {
                    $selected = "";
                    if ($row["complete"] == $i) {
                        $selected = " selected";
                    }
                    echo "<option value='".$i."'".$selected.">".$i."%</option>";
                }
            ?>
            </select>
        </div>
    </div>
</div>
<input name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="space">
</div>
    
</form>
<div style="margin-left:40%; cursor:pointer" onclick="parent.DayPilot.ModalStatic.close()">{{Lang::get('mowork.close')}}</div>    
<script>
    $(document).ready(function() {        
        $("#cancel").click(function() {
            parent.DayPilot.ModalStatic.close();
            return false;
        });
        
        $("#milestone").change(function() {
            var checked = $(this).is(":checked");
            if (checked) {
                $(".section-taskonly").hide();
            }
            else {
                $(".section-taskonly").show();
                parent.DayPilot.ModalStatic.stretch();
            }
        });
        
        $("#f").submit(function(ev) {
            var f = $("#f");
            var action = this.getAttribute("action");
            $("#start").val(startPicker.date.toString("yyyy-MM-dd"));
            $("#end").val(endPicker.date.toString("yyyy-MM-dd"));
            $.post(action, f.serialize(),
            function(result) {
                parent.DayPilot.ModalStatic.close(eval(result));
            });
            return false;
        });
        
        $("#name").focus();
        $("#milestone").change();
        
        var isparent = <?php echo $isparent ?>;
        if (isparent) { 
            $(".section-milestone").hide();
        }
        
    });
    
    var startPicker =  new DayPilot.DatePicker({
        target: 'start', 
        pattern: 'yyyy-MM-dd',
        date: "<?php echo $row['start_date'] ?>",
        onShow: function() {
            parent.DayPilot.ModalStatic.stretch();
        }
    });
    
    var endPicker =  new DayPilot.DatePicker({
        target: 'end', 
        pattern: 'yyyy-MM-dd',
        date: "<?php echo $row['end_date'] ?>",
        onShow: function() {
            parent.DayPilot.ModalStatic.stretch();
        }
});
</script>
</body>
</html>