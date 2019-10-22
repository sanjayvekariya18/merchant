<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Input;
use Collective\Html\FormFacade as Form;

use App\Module;
use App\Helpers\LAHelper;
use Zizaco\Entrust\EntrustFacade as Entrust;

use Auth;
use DB;
use File;
use Validator;
use Datatables;
use Session;

use App\Upload;

class UploadsController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'path', 'extension', 'caption', 'user_id'];
	
	public function __construct() {
		// for authentication (optional)
		$this->middleware('auth', ['except' => 'get_file']);
		
		$module = Module::get('Uploads');

		$listing_cols_temp = array();
		foreach ($this->listing_cols as $col) {
			if($col == 'id') {
				$listing_cols_temp[] = $col;
			} else  {
				$listing_cols_temp[] = $col;
			}
		}
		$this->listing_cols = $listing_cols_temp;
	}
	
	/**
	 * Display a listing of the Uploads.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Uploads');

		return View('uploads.index', [
			'show_actions' => $this->show_action,
			'listing_cols' => $this->listing_cols,
			'module' => $module
		]);
		
	}
	
	/**
     * Get file
     *
     * @return \Illuminate\Http\Response
     */
    public function get_file($hash, $name)
    {
        $upload = Upload::where("hash", $hash)->first();
        $user_id = Session::get("userId");

        // Validate Upload Hash & Filename
        if(!isset($upload->id) || $upload->name != $name) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 1"
            ]);
        }

        if($upload->public == 1) {
            $upload->public = true;
        } else {
            $upload->public = false;
        }

        // Validate if Image is Public
        if(!$upload->public && !isset($user_id)) {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 2",
            ]);
        }

        if($upload->public || $user_id == $upload->user_id) {
            
            $path = $upload->path;

            if(!File::exists($path))
                abort(404);
            
            // Check if thumbnail
            $size = Input::get('s');
            if(isset($size)) {
                if(!is_numeric($size)) {
                    $size = 150;
                }
                $thumbpath = storage_path("thumbnails/".basename($upload->path)."-".$size."x".$size);
                
                if(File::exists($thumbpath)) {
                    $path = $thumbpath;
                } else {
                    // Create Thumbnail
                    LAHelper::createThumbnail($upload->path, $thumbpath, $size, $size, "transparent");
                    $path = $thumbpath;
                }
            }

            $file = File::get($path);
            $type = File::mimeType($path);

            $download = Input::get('download');
            if(isset($download)) {
                return response()->download($path, $upload->name);
            } else {
                $response = FacadeResponse::make($file, 200);
                $response->header("Content-Type", $type);
            }
            
            return $response;
        } else {
            return response()->json([
                'status' => "failure",
                'message' => "Unauthorized Access 3"
            ]);
        }
    }

    /**
     * Upload fiels via DropZone.js
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_files() {
        
		$input = Input::all();
		$user_id = Session::get("userId");
		
		if(Input::hasFile('file')) {
			
			$file = Input::file('file');
			
			// print_r($file);
			
			$folder = public_path('uploads');
			$filename = $file->getClientOriginalName();

			$date_append = date("Y-m-d-His-");
			$upload_success = Input::file('file')->move($folder, $date_append.$filename);
			
			if( $upload_success ) {

				// Get public preferences
				// config("laraadmin.uploads.default_public")
				$public = Input::get('public');
				if(isset($public)) {
					$public = true;
				} else {
					$public = false;
				}

				$upload = Upload::create([
					"name" => $filename,
					"path" => $folder.DIRECTORY_SEPARATOR.$date_append.$filename,
					"extension" => pathinfo($filename, PATHINFO_EXTENSION),
					"caption" => "",
					"hash" => "",
					"public" => $public,
					"user_id" => $user_id
				]);
				// apply unique random hash to file
				while(true) { 
					$hash = strtolower(str_random(20));
					if(!Upload::where("hash", $hash)->count()) {
						$upload->hash = $hash;
						break;
					}
				}
				$upload->save();

				return response()->json([
					"status" => "success",
					"upload" => $upload
				], 200);
			} else {
				return response()->json([
					"status" => "error"
				], 400);
			}
		} else {
			return response()->json('error: upload file not found.', 400);
		}
		
    }

    /**
     * Get all files from uploads folder
     *
     * @return \Illuminate\Http\Response
     */
    public function uploaded_files()
    {
    	$user_id = Session::get("userId");

		if(isset($user_id)) {
			$user_uploads = Upload::where('user_id',$user_id)->get()->toArray();
		} 

		$public_uploads = Upload::where('public','1')->where('user_id','!=',$user_id)->get()->toArray();

		$uploads = array_merge($user_uploads,$public_uploads);
	
		$uploads2 = array();
		foreach ($uploads as $upload) {

			$upload_file_path = explode("public\\", $upload['path']);

			$u = (object) array();
			$u->id = $upload['id'];
			$u->name = $upload['name'];
			$u->extension = $upload['extension'];
			$u->hash = $upload['hash'];
			$u->public = $upload['public'];
			$u->caption = $upload['caption'];
			$u->path = url($upload_file_path[1]);
			$u->user = Session::get("staffName");
			
			$uploads2[] = $u;
		}
		
		return response()->json(['uploads' => $uploads2]);
		
    }

    /**
     * Update Uploads Caption
     *
     * @return \Illuminate\Http\Response
     */
    public function update_caption()
    {
		$file_id = Input::get('file_id');
		$caption = Input::get('caption');
		$user_id = Session::get("userId");
		
		$upload = Upload::find($file_id);
		if(isset($upload->id)) {
			if($upload->user_id == $user_id) {

				// Update Caption
				$upload->caption = $caption;
				$upload->save();

				return response()->json([
					'status' => "success"
				]);

			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Upload not found"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}		
    }

    /**
     * Update Uploads Filename
     *
     * @return \Illuminate\Http\Response
     */
    public function update_filename()
    {
		$file_id = Input::get('file_id');
		$filename = Input::get('filename');
		$user_id = Session::get("userId");
		
		$upload = Upload::find($file_id);
		if(isset($upload->id)) {
			if($upload->user_id == $user_id) {

				// Update Caption
				$upload->name = $filename;
				$upload->save();

				return response()->json([
					'status' => "success"
				]);

			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Unauthorized Access 1"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}		
    }

    /**
     * Update Uploads Public Visibility
     *
     * @return \Illuminate\Http\Response
     */
    public function update_public()
    {
		$file_id = Input::get('file_id');
		$public = Input::get('public');
		$user_id = Session::get("userId");

		if(isset($public)) {
			$public = true;
		} else {
			$public = false;
		}
		
		$upload = Upload::find($file_id);
		if(isset($upload->id)) {
			if($upload->user_id == $user_id) {

				// Update Caption
				$upload->public = $public;
				$upload->save();

				return response()->json([
					'status' => "success"
				]);

			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Unauthorized Access 1"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}		
    }

    /**
     * Remove the specified upload from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete_file()
    {
		$file_id = Input::get('file_id');
		$user_id = Session::get("userId");

		$upload = Upload::find($file_id);
		if(isset($upload->id)) {
			if($upload->user_id == $user_id) {

				// Update Caption
				$upload->delete();

				return response()->json([
					'status' => "success"
				]);

			} else {
				return response()->json([
					'status' => "failure",
					'message' => "Unauthorized Access 1"
				]);
			}
		} else {
			return response()->json([
				'status' => "failure",
				'message' => "Upload not found"
			]);
		}
		
    }
}
