@extends('backend-base')
@section('css.append')
 
<link href="/asset/css/treeview.css" rel="stylesheet">
@stop
@section('content')

<div class="col-xs-12">

    @if(Session::has('result'))
	<div class="text-center text-danger marging-b20">{{Session::get('result')}}</div>
	@endif
	@if(isset($errors))
		<h3>{{ Html::ul($errors->all(), array('class'=>'text-danger col-sm-3 error-text'))}}</h3>
	@endif
	 
		<div class="panel panel-primary">
			<div class="panel-heading">{{Lang::get('mowork.file_management')}}</div>
	  		<div class="panel-body">
	  			<div class="row">
	  				<div class="col-md-6">
	  					<h4>{{Lang::get('mowork.folder')}}</h4>
				        <ul id="tree1" class="tree">
				           
				            @foreach($pfolders as $category)
				                <li>
				                    @if(count($category->childs))
				                    <a class="fa fa-fw fa-plus-square-o">{{ $category->title }}</a>
				                    @else
				                     {{ $category->title }}
				                    @endif
				                    @if(count($category->childs))
				                        @include('dailywork.folder-child',['childs' => $category->childs])
				                    @endif
				                </li>
				            @endforeach
				        </ul>
	  				</div>
	  				<div class="col-md-6">
	  					<h3>Add New Folder (File)</h3>

				  			{!! Form::open(['url'=> '/dashboard/folder-tree']) !!}

				  				@if ($message = Session::get('success'))
									<div class="alert alert-success alert-block">
										<button type="button" class="close" data-dismiss="alert">×</button>	
									        <strong>{{ $message }}</strong>
									</div>
								@endif

				  				<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
									{!! Form::label('Title:') !!}
									{!! Form::text('title', old('title'), ['class'=>'form-control', 'placeholder'=>'Enter Title']) !!}
									<span class="text-danger">{{ $errors->first('title') }}</span>
								</div>

								<div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
									{!! Form::label('Folder:') !!}
									{!! Form::select('parent_id',$allfolders, old('parent_id'), ['class'=>'form-control', 'placeholder'=>'Select Folder']) !!}
									<span class="text-danger">{{ $errors->first('parent_id') }}</span>
								</div>

								<div class="form-group">
									<button class="btn btn-success">Add New</button>
								</div>

				  			{!! Form::close() !!}

	  				</div>
	  			</div>

	  			
	  		</div>
        </div>
 
</div> 
 
@stop

@section('footer.append')
<script type="text/javascript" src="/asset/js/treeview.js"></script>
<script type="text/javascript">

    $(function(){
    	 
        
     });

    $("[rel=tooltip]").tooltip({animation:false});
 
</script>
 
@stop