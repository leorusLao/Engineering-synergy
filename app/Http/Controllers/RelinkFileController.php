<?php

namespace App\Http\Controllers;

use App;
use Session;
use Cookie;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelinkFileController extends Controller {
	public function removeTempFile(Request $request) {
		session_start ();
	 
		// for dropzone
		$filename = $_REQUEST ['fname'];
	 	
		$filename =  session_id () . str_replace ( ' ', '', $filename );
		$fullpath = storage_path().'/tmp/' . $filename;
			
		if(file_exists($fullpath)) {
			//file_put_contents('qqqq', 'to file exists');
			unlink (storage_path().'/tmp/' . $filename );
		}
		
		if(isset($_SESSION['FileNames'])){//for uploaded multiple files
			
			$key = array_search($filename,$_SESSION['FileNames']);
		 
		    if($key !== false) {
		       
			   unset($_SESSION['FileNames'][$key]);
			   $_SESSION['NumOfFiles'] = $_SESSION['NumOfFiles'] - 1;
		
		    }
		}
	}

	public function removeFile(Request $request) {
	 
		// for dropzone
		$filedata = json_decode($request->get('fdata'),true);

		$basecata = $request->get('basecata');

		$fullpath = public_path()."/$basecata/".$filedata['path'];


		if(file_exists($fullpath)) {
			unlink ($fullpath);
		}
		
	}
		
	
}

?>