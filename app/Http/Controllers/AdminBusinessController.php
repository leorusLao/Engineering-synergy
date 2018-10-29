<?php

namespace App\Http\Controllers;
use App;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Company;
use App\Models\UserCompany;
use App\Models\UserResourceRole;
use App\Models\UserResource;
use App\Models\Buhost;
use App\Models\BuAdmin;
use App\Models\Node;
use App\Models\NodeType;
use App\Models\ProjectType;
use phpDocumentor\Reflection\Location;

class AdminBusinessController extends  Controller
{
	/*
	 * Only use this controller at gateway site www.mowork.cn
	 */
	 
	public function __construct()
	{
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

	}

	public function projectType(Request $request)
	{
		 
		if(!Session::has('roleId') || Session::get('roleId') >=20 ) return Redirect::to('/');
		
		$uid = Session::get('USERINFO')->userId;
		$buId = Session::get('USERINFO')->buId;
		
		if($request->has('submit')) {
			 
			if(ProjectType::isExistedProjectType($request->get('name'), $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
			}
	
			try {
				ProjectType::addProjectType ( $request->get ( 'name' ), $request->get ( 'include_plantype' ), $company_id );
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
		}
	
		$rows = ProjectType::where('buid',$buId)->orderBy('type_id','ASC')->paginate(PAGEROWS);
		 
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.project_type');
		return view('backend.project-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function projectTypeEdit(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
		 
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')) {
			 
			if(ProjectType::updateProjectType ( $type_id, $request->get ( 'name' ), $request->get ( 'include_plantype' ), $company_id )) {
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			}
	
			return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			 
		}
	
		$row = ProjectType::where(array('company_id' => $company_id, 'type_id' => $type_id) )->first();
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.project_type').' &raquo; '.Lang::get('mowork.edit');
		return view('backend.project-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function projectTypeDelete(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		 
		if(ProjectType::deleteProjectType($type_id, $company_id)) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	
		return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
	}
	
	public function partType(Request $request)
	{
	
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		if($request->has('submit')) {
	
			if(PartType::isExistedPartType($request->get('type_code'), $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
			}
	
			try {
				PartType::addPartType ( $request->get ( 'type_code' ), $request->get ( 'name' ), $company_id );
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
		}
	
		$rows = PartType::whereRaw('company_id = 0 OR company_id =' . $company_id )->orderBy('type_id','ASC')->paginate(PAGEROWS);
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.part_type');
		return view('backend.part-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function partTypeEdit(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
		 
		if($request->has('submit')) {
	
			if(PartType::updatePartType ( $type_id, $request->get ( 'type_code' ), $request->get ( 'name' ), $company_id )) {
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			}
	
			return Redirect::back()->with('result', Lang::get('mowork.db_err'));
	
		}
	
		$row = PartType::where(array('company_id' => $company_id, 'type_id' => $type_id) )->first();
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.part_type').' &raquo; '.Lang::get('mowork.edit');
		return view('backend.part-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function partTypeDelete(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/project-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if(PartType::deletePartType($type_id, $company_id)) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	
		return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
	}
	
	public function planType(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		if($request->has('submit')) {
	
			if(PlanType::isExistedPlanType($request->get('type_code'), $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
			}
	
			try {
				PlanType::addPlanType ( $request->get ( 'type_code' ), $request->get ( 'type_name' ), $request->get ( 'type_name_en' ), $company_id );
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
		}
	
		$rows = PlanType::whereRaw('company_id = 0 OR company_id =' . $company_id )->orderBy('type_id','ASC')->paginate(PAGEROWS);
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.plan_type');
		return view('backend.plan-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function planTypeDelete(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/plan-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if( planType::deletePlanType($type_id, $company_id)) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	
		return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
	}
	
	public function planTypeEdit(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/plan-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')) {
	
			if(PlanType::updateplanType ( $type_id, $request->get ( 'type_code' ), $request->get ( 'type_name' ),  $request->get ( 'type_name_en' ), $company_id )) {
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			}
	
			return Redirect::back()->with('result', Lang::get('mowork.db_err'));
	
		}
	
		$row = PlanType::where(array('company_id' => $company_id, 'type_id' => $type_id) )->first();
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.part_type').' &raquo; '.Lang::get('mowork.edit');
		return view('backend.plan-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function nodeType(Request $request)
	{
		if(!Session::has('userId')) return Redirect::to('/');
	  
		$buid = Session::get('USERINFO')->buId;
	
		if($request->has('submit')) {
	
			if(NodeType::isExistedNodeType($request->get('type_name'), $buid)) {
				return Redirect::back()->with('result', Lang::get('mowork.projtype_existed'));
			}
	
			try {
				 
				NodeType::addNodeType ( $request->get ( 'type_name' ), implode(',', $request->get ( 'deps' )), $request->get ( 'forecolor' ), $request->get ( 'backcolor' ), $company_id );
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
		}
	
		$rows = NodeType::whereRaw('company_id = 0 AND bu_id =' . $buid )->orderBy('type_id','ASC')->paginate(PAGEROWS);
		 
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_type');
		return view('platform-bu.node-type',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt, 'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function nodeTypeEdit(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/node-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if($request->has('submit')) {
			 
			$deps = $request->get ( 'deps' );
			if($deps) {
				$deps = implode(',', $deps);
			} else {
				$deps = '';
			}
	
			NodeType::updateNodeType ( $type_id, $request->get ( 'type_name' ), $deps, $request->get ( 'forecolor' ), $request->get ( 'backcolor' ), $company_id );
			return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			 
		}
	
		$row =NodeType::where(array('type_id' => $type_id) )->first();
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_type');
		$departments = Department::where('company_id',$company_id)->get();
	
		return view('backend.node-type-edit',array('cookieTrail' => $cookieTrail, 'token' => $token, 'row' => $row, 'departments' => $departments, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function nodeTypeDelete(Request $request, $token, $type_id)
	{
		if(!Session::has('userId')) return Redirect::to('/');
		$company_id = Session::get('USERINFO')->companyId;
		$uid = Session::get('USERINFO')->userId;
	
		$compToken = hash('sha256',$this->salt.$type_id.$company_id);
	
		if($compToken != $token) {
			return Redirect::to('/dashboard/project-config/node-type')->with('result', Lang::get('mowork.operation_disallowed'));
		}
	
		if(NodeType::deleteNodeType($type_id, $company_id)) {
			return Redirect::back()->with('result', Lang::get('mowork.operation_success'));
		}
	
		return Redirect::back()->with('result', Lang::get('mowork.operation_failure'));
	}
	
	public function nodeList(Request $request)
	{
		//公司节点查看添加
		if(!Session::has('buAdmin')) return Redirect::to('/');
		$buid = Session::get('USERINFO')->buId;
		$company_id = 0;
		if($request->has('submit')) {
			//添加节点基本信息：（一个节点可用由基本信息+定制信息组成）
			 
			if(Node::isExistedNode($request->get('node_code'), $company_id)) {
				return Redirect::back()->with('result', Lang::get('mowork.nodecode_existed'));
			}
	
			try {
				$category = '30';//节点属性:公司型;创建公司节点主信息
				Node::addNode( $request->get ( 'node_code' ), $request->get ( 'type_id' ), $request->get ( 'node_name' ), $request->get ( 'node_en' ), $category, $company_id );
				return Redirect::back ()->with ( 'result', Lang::get ( 'mowork.operation_success' ) );
			} catch (\Exception $e) {
				return Redirect::back()->with('result', Lang::get('mowork.db_err'));
			}
		}
	
		$rows = Node::where(array('company_id' => 0, 'bu_id' => $buid ))->orderBy('node_id','ASC')->
		select('node.*')->paginate(PAGEROWS);
		 
		$nodeTypeList = NodeType::getNodeTypeList($company_id);
	
		$cookieTrail = Lang::get('mowork.system_configuration').' &raquo; '.Lang::get('mowork.project_configuration').' &raquo; '.Lang::get('mowork.node_list');
		return view('platform-bu.node-list',array('cookieTrail' => $cookieTrail, 'salt' => $this->salt . $company_id, 'rows' => $rows,
				'nodeTypeList' => $nodeTypeList, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
	
	}
	
	public function companyList(Request $request) 
	{
		if(!Session::has('buAdmin')) return Redirect::to('/');
		$buid = Session::get('USERINFO')->buId;
		 
		$rows = Company::getCompaniesByBuid($buid, PAGEROWS);
		 
		$cookieTrail = Lang::get('mowork.subsidiary').' &raquo; '.Lang::get('mowork.company_list');
		return view('platform-bu.company-list',array('salt' => $this->salt, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
	
	public function newArrivalApproval(Request $request)
	{
		if(!Session::has('buAdmin')) return Redirect::to('/');
		$buid = Session::get('USERINFO')->buId;
		if($request->has('flag')) {
			try {
				Company::where(array('company_id' => $request->get('company'), 'domain_id' => $request->get('domain')))
				->update(array('is_approved' => $request->get('flag')));
				$res = array('0' => Lang::get('mowork.operation_success'));
				$json = json_encode($res);
				return $json;
			} catch (\Exception $e) {
				 
					$res = array('0' => Lang::get('mowork.operation_failure'));
					$json = json_encode($res);
					return $json;
				 
			}
		}
		
		$rows = Company::getNewArrivalCompaniesByBuid($buid, PAGEROWS);
			
		$cookieTrail = Lang::get('mowork.subsidiary').' &raquo; '.Lang::get('mowork.company_bu_approval');
		return view('platform-bu.new-arrival-list',array('salt' => $this->salt, 'cookieTrail' => $cookieTrail,'rows' => $rows, 'pageTitle' => Lang::get('mowork.dashboard'),'locale' => $this->locale));
		
	}
}
