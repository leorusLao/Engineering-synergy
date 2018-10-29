<?php

namespace App\Http\Controllers;
use App;

use Session;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use App\Models\Task;
use App\Models\TaskLink;
use App\Models\Template;
use App\Models\UserCompany;
use App\Models\PlanType;
use App\Models\NodeType;
use App\Models\Department;

class TaskController extends Controller {
	protected $locale;

	public function __construct()
	{
		//TODO if company has not been setup; return to create company
		session_start();
		if(Session::has('locale')){
			$this->locale = Session::get('locale');
		}
		else if(isset($_COOKIE['locale'])){
			$this->locale = $_COOKIE['locale'];
		}
		else{
			$this->locale = config('app.locale');
		}
		session_cache_limiter(false); //let page no expiration after post data
	}

	public function templateList(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		if($request->has('submit')) {
			$template_code = $request->get('template_code');
			$template_name = $request->get('template_name');
			$tmpl_type_id = $request->get('template_type');
			$node_type_id = $request->get('node_type');
			$plan_type = PlanType::where('type_id',$tmpl_type_id)->pluck('type_name')[0];
			$node_type = NodeType::where('type_id',$node_type_id)->pluck('type_name')[0];
			if(Template::isExistedTemplate($template_code, $company_id)){
				return Redirect::back()->with('result', Lang::get('exited_template'));
			} else {
				$level_id = 2;//公司模板
				try {
					Template::addTemplate($template_code, $template_name, $plan_type, $tmpl_type_id, $node_type, $node_type_id, $level_id, $company_id);
					return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
				} catch (\Exception $e) {
					return Redirect::back()->with('result', Lang::get('mowork.db_err'));
				}
			}
				
		}

		$cookieTrail = Lang::get('mowork.plan_template');
		$planTypeList = PlanType::whereRaw('company_id = 0  OR company_id= ' .$company_id)->get();
		$nodeTypeList = NodeType::whereRaw('company_id = 0  OR company_id= ' .$company_id)->get();
		$rows = Template::getCompanyTemplate($company_id);
		$salt = $company_id.$this->salt.$uid;
		return view('dailywork.plan-template-list',array('cookieTrail' => $cookieTrail,'salt' => $salt, 'rows' => $rows, 'planTypeList' => $planTypeList,
				'nodeTypeList' => $nodeTypeList, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

	}

	public function templateEdit(Request $request, $token, $template_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$template_id);

		if($compToken != $token) {
			return Redirect::to('/dashboard/template-list')->with('result', Lang::get('mowork.operation_disallowed'));
		}

		if($request->has('submit')) {
			$template_code = $request->get('template_code');
			$template_name = $request->get('template_name');
			$tmpl_type_id = $request->get('template_type');
			$node_type_id = $request->get('node_type');
			$plan_type = PlanType::where('type_id',$tmpl_type_id)->pluck('type_name')[0];
			$node_type = NodeType::where('type_id',$node_type_id)->pluck('type_name')[0];

			if(Template::updateTemplate ($template_id, $template_code, $template_name, $plan_type, $tmpl_type_id, $node_type, $node_type_id, $company_id )) {
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			}
			return Redirect::back()->with('result', Lang::get('mowork.db_err'));
		}

		$row = Template::where(array('id' => $template_id, 'company_id' => $company_id) )->first();
		$planTypeList = PlanType::whereRaw('company_id = 0  OR company_id= ' .$company_id)->get();
		$nodeTypeList = NodeType::whereRaw('company_id = 0  OR company_id= ' .$company_id)->get();

		$cookieTrail = Lang::get('mowork.plan_template') .' &raquo; '.Lang::get('mowork.edit');
		return view('dailywork.template-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'planTypeList' => $planTypeList,
				'nodeTypeList' => $nodeTypeList, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	}

	public function templateDelete(Request $request, $token, $template_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$compToken = hash('sha256',$company_id.$this->salt.$uid.$template_id);

		if($compToken != $token) {
			return Redirect::to('/dashboard/template-list')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		//further check if it has made tasks associted with this template, if did ask to delete tasks first.
			
		DB::beginTransaction();
		try {
			Template::deleteTemplate($template_id, $company_id);
			//further check if it has made tasks or task_links associted with this template, then delete it if existed
			Task::where(array('template_id' => $template_id, 'company_id' => $company_id))->delete();
			TaskLink::where(array('template_id' => $template_id, 'company_id' => $company_id))->delete();
		} catch (\Exception $e) {
			DB::rollback();
			return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
		}
		DB::commit();
		return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
	}

	public function templateMake(Request $request, $token, $template_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
			
		$compToken = hash('sha256',$company_id.$this->salt.$uid.$template_id);
		if($compToken != $token) {
			return Redirect::to('/dashboard/template-list')->with('result', Lang::get('mowork.operation_disallowed'));
		}

		if($request->has('submit')) {
			if($request->has('cbx')) {
				$reference = $request->get('cbx');
			} else {
				$reference = $request->get('cbx')[0];
			}
				
			//get nodes of this reference
			$nodes = Task::where(array('template_id' => $reference, 'company_id' => $company_id) )->orderBy('id','asc')->get();
			$links = TaskLink::where('template_id', $reference)->get();

			DB::beginTransaction();
			$map = array();
			foreach ($nodes as $node) {
				$parent_id = $node->parent_id;
				if($parent_id != 0) $parent_id = $map[$node->parent_id];
					
				$newId = Task::create(array('name' => $node->name, 'start' => $node->start, 'end' => $node->end, 'parent_id' => $parent_id, 'milestone' => $node->milestone,
						'ordinal' => $node->ordinal, 'ordinal_priority' => $node->ordinal_priority, 'complete' => $node->complete, 'template_id' => $template_id,
						'company_id' => $company_id))->id;
				$map[$node->id] = $newId;
			}
			foreach ($links as $link) {
				$from = $link->from_id;
				$to = $link->to_id;
				$new_from = $map[$from];
				$new_to = $map[$to];
				TaskLink::create(array('from_id' => $new_from, 'to_id' => $new_to, 'type' => $link->type, 'template_id' => $template_id, 'company_id' => $company_id ));
			}
			//update parents children relationship
			DB::commit();
		}

		$row = Template::where('id', $template_id)->first();
		$tmplts = Template::whereRaw('company_id =' . $company_id . ' OR company_id = 0')->get();
		$refs = count($tmplts);
		$cookieTrail =  Lang::get('mowork.plan_template').' &raquo; '.Lang::get('mowork.template_maker');
		$task = Task::where(array('template_id' => $template_id, 'parent_id' => 0))->orderBy('ordinal','asc')->first();
		return view('dailywork.plan-template-make',array('cookieTrail' => $cookieTrail,'row' => $row, 'tmplts' => $tmplts, 'refs' => $refs, 'task'  => $task, 'token' => $token, 'template_id' => $template_id, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

	}

	public function task(Request $request, $token, $template_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');

		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$rows = Task::where(array('template_id' => $template_id, 'parent_id' => 0))->orderBy('ordinal','asc')->orderBy('ordinal_priority', 'desc')->get();

		$result = $this->taskList($rows,$template_id);
		header('Content-Type: application/json');
		//file_put_contents('qqqq', 'res==='.print_r($result,true));
			
		return response()->json($result);//echo json_encode($result);
	}

	public function taskList($items,$template_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');

		$result = array();

		foreach($items as $item) {
			$t = array();
			$r = (object) $t;

			// rows
			$r->id = "$item->id";
			//forcefully add qutation mark in order to taskLink can find this matched task id for its from_id and to_id
			$r->text = htmlspecialchars($item['name']);
			$r->start = $item['start'];
			$r->end = $item['end'];
			$r->complete = $item['complete'];
			if ($item['milestone']) {
				$r->type = 'Milestone';
			}

			$parent = $r->id;

			$children =  Task::where(array('template_id' => $template_id, 'parent_id' => $parent))->orderBy('ordinal','asc')->orderBy('ordinal_priority', 'desc')->get();

			if (!empty($children)) {
				$r->children = $this->taskList($children, $template_id);
			}

			$result[] = $r;
		}
		return $result;
	}

	public function taskCreate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$template_id = $request->get('template_id');
		$ordinal = Task::where(array('template_id' => $template_id, 'parent_id' => 0))->max('ordinal') + 1;
		$now = date('Y-m-d H:i:s');
		//file_put_contents('qqqq', 'create task===ordinal:'.$ordinal);
		$task_id = Task::create(array('name' => $request->get('name'), 'start' => $request->get('start'), 'end' => $request->get('end'),
				'ordinal' => $ordinal, 'ordinal_priority' => $now, 'template_id' => $template_id, 'company_id' => $company_id
		));
		//$db->prepare("INSERT INTO task (name, start, end, ordinal, ordinal_priority) VALUES (:name, :start, :end, :ordinal, :priority)");
		$request = array();
		$response = (object) $request;
		$response->result = 'OK';
		//$response->message = 'Created with id: '.$db->lastInsertId();
		$response->id = $task_id;

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function taskEdit(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$row = Task::where('id', $id)->first();
			
		if (!$row) {
			die("Not found");
		}
			
		$isparent = Task::where('parent_id',$id)->count();
		$employees = UserCompany::join('user','user.uid','=','user_company.uid')->where(array('company_id' => $company_id, 'user_company.status' => 1))->get();
		$departments = Department::where('company_id',$company_id)->get();
		$nodetypes = NodeType::whereRaw('company_id = 0 OR company_id='.$company_id)->get();

		return view('dailywork.task-edit',array( 'row' => $row, 'token' => $token, 'id' => $id, 'isparent' => $isparent,
				'employees'=> $employees, 'departments' => $departments, 'nodetypes' => $nodetypes,
				'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));

	}

	public function taskUpdate(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		//file_put_contents('qqqq', print_r($request->all(),true));

		$milestone = $request->has("milestone");
		Task::where('id', $id)->update(array('name' => $request->get('name'), 'start' => $request->get('start'), 'end' => $request->get('end'), 'milestone' => $milestone));

		$result = array();
		$response = (object) $result;
		$response->result = 'OK';

		header('Content-Type: application/json');
		echo json_encode($response);


	}

	public function taskDelete(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	    
		//删除task_id之前，记录被删该节点的父节点号；如果task_id有子节点，则将task_id所有子节点的父节点设为task_id的父节点
		$row = Task::where(array('id' => $request->get('id'), 'template_id' => $request->get('template_id')))->first();
		Task::where(array('id' => $request->get('id'), 'template_id' => $request->get('template_id')))->delete();
		
		//更改被删除节点的子节点的父节点号，以免悬空
		/*
		Task::where(array('parent_id' => $row->task_id, 'template_id' => $request->get('template_id')))
		->update(array('parent_id' => $row->parent_id));
		*/
		 
		//删除该节点的link指针 如果有的话
		TaskLink::whereRaw('from_id = '.$row->id .' OR to_id = '.$row->id)->delete();
		//删除节点的所有子节点
		$rows = Task::where(array('parent_id' => $row->task_id, 'template_id' => $request->get('template_id')))->get();
		Task::where(array('parent_id' => $row->task_id, 'template_id' => $request->get('template_id')))->delete();
		
		//删除子节点的link指针 如果有的话
		foreach($rows as $row) {
		   TaskLink::whereRaw('from_id = '.$row->id .' OR to_id = '.$row->id)->delete();
		}
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';

		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function taskMove(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		if($request->has('id')) {
			Task::where('id', $request->get('id'))->update(array('start' => $request->get('start'), 'end' => $request->get('end')));
			//check if this task id has parent task, yes: update parent id respectively
			$parent = Task::where('id', $request->get('id'))->pluck('parent_id')[0];
			if($parent > 0) {//update parent task info
				$min_date = Task::where('parent_id',$parent)->min('start');
				$max_date = Task::where('parent_id',$parent)->max('end');
				Task::where('id', $parent)->update(array('start' => $min_date , 'end' => $max_date));
			}
		}
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';

		header('Content-Type: application/json');
		echo json_encode($response);

	  
	}

	public function taskRowMove(Request $request)
	{    //Task name button on the left side
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
		//file_put_contents('qqqq','task name move ==='. print_r($request->all(),true));
		$source = $request->get('source');
		$target = $request->get('target');
		$template_id = $request->get('template_id');

		$task_source = Task::where('id',$source)->first();
		$task_target = Task::where('id',$target)->first();

		$source_parent_id = $task_source? $task_source->parent_id : 0;
		$target_parent_id = $task_target? $task_target->parent_id : 0;
		$target_ordinal = $task_target->ordinal;
		$now = date('Y-m-d H:i:s');
		switch ($request->get('position')) {
			case "before":
				Task::where('id', $task_source->id)->update(array('parent_id' => $target_parent_id, 'ordinal' => $target_ordinal, 'ordinal_priority' => $now));
				break;
			case "after":
				Task::where('id', $task_source->id)->update(array('parent_id' => $target_parent_id, 'ordinal' => $target_ordinal + 1, 'ordinal_priority' => $now));
				break;
			case "child":
				echo "child:source/".$task_source->id. "/target/" . $task_target->id;
				//db_update_task_parent($source["id"], $target["id"], $max);
				Task::where('id', $task_source->id)->update(array('parent_id' => $task_target->id, 'ordinal' => 10000, 'ordinal_priority' => $now));//max 10000 nodes or tasks for a certain level in a template
				$target_parent_id = $task_target->id;
				break;
			case "forbidden":
				break;
		}

		self::compactOrdinals($source_parent_id, $template_id);
			
		if ($source_parent_id != $target_parent_id) {
			self::compactOrdinals($target_parent_id, $template_id);
		}
		 
		$result = array();
		$response = (object) $result;
		$response->result = 'OK';

		header('Content-Type: application/json');
		echo json_encode($response);

			
	}

	public function taskLink(Request $request, $token, $id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	  
		$result = array();
		$rows = TaskLink::where('template_id',$id)->get();

		foreach($rows as $item) {
			$t = array();
			$r = (object) $t;
			$r->id = $item->id;
			$r->from =  $item->from_id ;
			$r->to =   $item->to_id;
			$r->type = $item->type;

			$result[] = $r;
			//$result[] = array('id' => $item->id, 'from' => $item->from_id, 'type' => $item->type );
		}

		//file_put_contents('qqqq', 'task-link=='.print_r($result,true));
		header('Content-Type: application/json');
		return response()->json($result); //echo json_encode($result);return response()->json($result);

	}

	public function taskLinkCreate(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$token = $request->get('token');
		$template_id = $request->get('template_id');
			

		$link_id = TaskLink::create(array('from_id' => $request->get('from'), 'to_id' => $request->get('to'), 'type' => $request->get('type'),
				'template_id' => $template_id, 'company_id' => $company_id))->id;

		$result = array();

		$response = (object) $result;
		$response->result = 'OK';
		$response->message = 'Created with id: ' . $link_id;
		$response->id = $link_id;

		header('Content-Type: application/json');
		echo json_encode($response);

	}

	public function taskLinkDelete(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;

		$id = $request->get('id');
		$token = $request->get('token');
		$template_id = $request->get('template_id');

		TaskLink::where(array('id' => $id, 'template_id' => $template_id))->delete();

		$result = array();

		$response = (object) $result;
		$response->result = 'OK';

		header('Content-Type: application/json');
		echo json_encode($response);
	}
		
	static public function compactOrdinals($parent, $template_id)
	{

		$children = Task::where(array('template_id' => $template_id, 'parent_id' => $parent))->orderBy('ordinal','asc')->orderBy('ordinal_priority','desc')->get();
		$size = count($children);

		for ($i = 0; $i < $size; $i++) {
			$row = $children[$i];
			self::updateTaskOrdinal($row["id"], $i, $size);
		}
	}

	static public function updateTaskOrdinal($task_id, $ordinal, $size)
	{
			
		$now = date('Y-m-d H:i:s');
		Task::where('id', $task_id)->update(array('ordinal' => $ordinal, 'ordinal_priority' => $now));
			
	}
}
