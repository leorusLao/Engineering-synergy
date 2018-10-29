<html>
<head>
    <title></title>
    
    <!-- demo stylesheet -->
    <link type="text/css" rel="stylesheet" href="/asset/layout.css" />    

    <!-- helper libraries -->
    <script src="/asset/js/jquery-1.11.3.min.js" type="text/javascript"></script>

    <!-- daypilot libraries -->
    <script src="/asset/js/daypilot.js" type="text/javascript"></script>
	
</head>
<body style="margin:20px">
 
   <div style="color:#fa0000; display: none" id='result' >
          {{Lang::get('mowork.operation_success')}}
   </div>
 
<form action="/dashboard/task-update/{{$token}}/{{$id}}" id="f" method="post" onsubmit="return validateForm();">

<div style="margin-bottom:10px">
    <div>
    {{Lang::get('mowork.node_type')}}:
    
        <select name="nodetype" id="nodetype">
         <option value=""></option>
		 @foreach ($nodetypes as $type)
		 <option value="{{$type->type_id}}" @if($type->type_id == $row->node_type) selected @endif>{{$type->type_name}}</option>
		 @endforeach
        </select>
         
    </div>
</div>
<div style="margin-bottom:10px">
    <div>{{Lang::get('mowork.node_name')}}:
     
        <select class="form-control" name="node_id" id="node_id">
           <option value=''></option>
           @foreach($nodes as $node)
            <option value="{{$node->node_id}}" @if($node->node_id == $row->node_id) selected @endif>
            {{$node->node_no}}-{{$node->name}}</option>
           @endforeach       
	    </select>
         
    </div>
</div>

<div style="margin-bottom:10px">
    <div>{{Lang::get('mowork.department')}}:
     
        <select class="form-control" name="department_id" id="department_id">
           <!-- 只显示对节点类型可操控部门 -->
            @foreach ($allowedDeps as $dep)
			      <option value="{{$dep->dep_id}}" @if($dep->dep_id == $row->department_id) selected @endif>{{$dep->name}}</option>
			@endforeach  
	    </select>
         
    </div>
</div>

<div style="margin-bottom:10px">
    <div class="space" class="">
        <div>
            <input id="milestone" name="milestone" type="checkbox" <?php if ($row["milestone"]) { echo 'checked'; } ?> />
            <label for="milestone">{{Lang::get('mowork.milestone')}}</label>
        </div>    
    </div>
</div>



<div class="space">
    <div>
           <input id="start" name="start" type="hidden">
    </div>
</div>
    
<div class="section-taskonly">

    <div class="space">
        <div>
             <input id="end" name="end" type="hidden"> 
        </div>
    </div>
  
    <div class="space" style="margin:10px 0 10px">
        <div>{{Lang::get('mowork.duration')}}: 
             <input name="duration" type="text" value="{{$row->duration}}">
        </div>
    </div>
</div>
<input name="_token" type="hidden" value="{{ csrf_token() }}">
<div class="space">
    <input type="hidden" name="id" id="id" value="{{$row->id}}"/>
    <input type="submit" value="{{Lang::get('mowork.submit')}}" />
    <a href="#" id="cancel">{{Lang::get('mowork.cancel')}}</a>
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


        $('#nodetype').change(function() {
       	    nodetype = $('#nodetype').val();
       	    input = $("<input>").attr("type", "hidden").attr("name", "nodetype").val(nodetype);
      	    $('#f').append($(input));
      	    
      	    //this.form.submit();
      	   //1. 根据节点类型得到该类型的所有节点
      	   //2. 根据节点类型得到可操作该节点类型的部门
            $.ajax({
              type:"POST",
              url : '{{url("/dashboard/get-node-by-type")}}',
              data : { nodetype: nodetype, _token: "{{csrf_token()}}" },
              dataType: 'json',
              success : function(result) {
                var node_name = result.node_name;
                var department = result.department;
                var options = '';
                for(var ii in node_name){
                      options += "<option value='" + ii + "'>" + node_name[ii] + "</option>";
                }
                 
                $('#node_id').html(options);
                var options = '';
                for(var ii in department){
                      
                      options += "<option value='" + ii + "'>" + department[ii] + "</option>";
                }
                 
                $('#department_id').html(options);
            },

            error: function() {
                //do logic for error
            }
          });        
        });
     
        
    });
    
    var startPicker =  new DayPilot.DatePicker({
        target: 'start', 
        pattern: 'yyyy-MM-dd',
        date: "<?php echo $row['start'] ?>",
        onShow: function() {
            parent.DayPilot.ModalStatic.stretch();
        }
    });
    
    var endPicker =  new DayPilot.DatePicker({
        target: 'end', 
        pattern: 'yyyy-MM-dd',
        date: "<?php echo $row['end'] ?>",
        onShow: function() {
            parent.DayPilot.ModalStatic.stretch();
        }
    });


    function validateForm() {
		node_id = $('#node_id').val();
        node_type = $('#nodetype').val();
        department_id = $('#departmet_id').val();
		err = '';
        
		if(node_type  < 1) {
			err += "{{Lang::get('mowork.nodetype_required')}}\n";
		}

		if(node_id < 1) {
			err += "{{Lang::get('mowork.nodename_required')}}\n";
		}
		
		if(department_id < 1) {
			err += "{{Lang::get('mowork.department_required')}}\n";
		}
		
	    if(err.length > 0 ) {				
			alert(err);
			return false;
	    }
        $('#result').css('display','block');
	    return true;
    }
</script>
</body>
</html>