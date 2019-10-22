<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Merchant;
use App\Location_list;
use App\Identity_merchant_retail_category_type;
use App\Merchant_retail_category_type;
use App\Merchant_retail_category_list;
use App\Merchant_retail_category_option_list;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
use Session;
use Redirect;
use Auth;

/**
 * Class Hase_merchant_retail_category_typeController.
 *
 */
class Hase_merchant_retail_category_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_cuisine_types');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function store(Request $request)
    {
        $hase_merchant_retail_category_type = new Merchant_retail_category_type();
        $hase_identity = new Identity_merchant_retail_category_type();
        
        if(!$request->category_parent_id)
        {
            $request->category_parent_id = 0;
        }
        $hase_merchant_retail_category_type->category_parent_id = $request->category_parent_id;
        
        $hase_merchant_retail_category_type->merchant_type_id = $request->merchant_type;        
        
        
        if(!$request->category_priority)
        {
            $request->category_priority = 0;
        }

        $hase_merchant_retail_category_type->category_priority = $request->category_priority;

        // Identity field

        $hase_identity->identity_name = $request->category_name;


        $category_name = preg_replace('/\s*/', '', $request->category_name);

        $category_name = strtolower($category_name);

        $hase_identity->identity_code = $category_name;

        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id',$this->merchantId)
                            ->get()->first();

        $hase_location = Location_list::
                            select('location_list.list_id as location_id','postal.postal_premise as location_name')
                            ->join('postal','postal.postal_id','=','location_list.postal_id')
                            ->where('location_list.list_id','=',$this->locationId)
                            ->get()->first();

        $merchantDirName = md5($hase_merchant->merchant_name);
        $locationDirName = md5($hase_merchant->merchant_name.$hase_location->location_name);

        if($request->live_image_url)
        {
            $hase_identity->identity_logo = $request->live_image_url;
        } else {
            if($request->file('category_image')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('category_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_type->category_name.$imageName).".".$imageArray[1];
                $request->file('category_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
            if($request->file('category_image_compact')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('category_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_type->category_name.$imageName).".".$imageArray[1];
                $request->file('category_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
            }
        }

        // Insert record in identity schema 
         $identity_merchant_retail_category_type=DB::table('identity_merchant_retail_category_type')->where('identity_name','=',$hase_identity->identity_name)->first();
            if(!isset($identity_merchant_retail_category_type->identity_name)){
        $hase_identity->save();
        $identityID = $hase_identity->identity_id;

        $hase_merchant_retail_category_type->identity_id = $identityID;
        $hase_merchant_retail_category_type->save();

        $typeID = $hase_merchant_retail_category_type->category_type_id;

        $categoryImage = parse_url($hase_identity->identity_logo);
        if(isset($categoryImage['scheme']))
        {
            if($categoryImage['scheme'] === 'https' || $categoryImage['scheme'] === 'http')
            {
                $hase_merchant_retail_category_type->category_image = $hase_identity->identity_logo;
            }
        } else {
            if($hase_identity->identity_logo)
            {
                $hase_merchant_retail_category_type->category_image = asset(env('image_dir_path').$hase_identity->identity_logo);
            } else {
                $hase_merchant_retail_category_type->category_image=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $categoryImageCompact = parse_url($hase_identity->identity_logo_compact);
        if(isset($categoryImageCompact['scheme']))
        {
            if($categoryImageCompact['scheme'] === 'https' || $categoryImageCompact['scheme'] === 'http')
            {
                $hase_merchant_retail_category_type->category_image_compact = $hase_identity->identity_logo_compact;
            }
        } else {
            if($hase_identity->identity_logo_compact)
            {
                $hase_merchant_retail_category_type->category_image_compact = asset(env('image_dir_path').$hase_identity->identity_logo_compact);
            } else {
                $hase_merchant_retail_category_type->category_image_compact=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $categoryRowObject = $hase_merchant_retail_category_type->toArray();
        $categoryRowObject['editUrl'] = url("hase_retail_category_type").'/'.$hase_merchant_retail_category_type->category_type_id.'/update';
        $categoryRowObject['deleteUrl'] = url("hase_retail_category_type").'/'.$hase_merchant_retail_category_type->category_type_id.'/delete';

        $style_parent_name_object = Merchant_retail_category_type::
                select('identity_merchant_retail_category_type.identity_name as category_name')
                ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                ->where('merchant_retail_category_type.category_type_id','=',$hase_merchant_retail_category_type->category_parent_id)
                ->get()->first();

        if(isset($style_parent_name_object))
        {
            $categoryRowObject['parent_name'] = $style_parent_name_object->category_name;
        }else{
            $categoryRowObject['parent_name'] = "";
        }

        $categoryRowObject['category_type_id'] = $typeID;
        $categoryRowObject['category_name'] = $request->category_name;
        $categoryRowObject['success'] = 1;
        

        return json_encode($categoryRowObject);
        }else{
            $categoryRowObject['nosuccess'] = 1;
                 return json_encode($categoryRowObject);
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $hase_merchant_retail_category_type = Merchant_retail_category_type::findOrfail($id);
        $hase_identity = Identity_merchant_retail_category_type::findOrfail($hase_merchant_retail_category_type->identity_id);
        
        if(!$request->category_parent_id)
        {
            $request->category_parent_id = 0;
        }

        $hase_identity->identity_name = $request->category_name;

        $category_name = preg_replace('/\s*/', '', $request->category_name);

        $category_name = strtolower($category_name);

        $hase_identity->identity_code = $category_name;

        
        $hase_merchant_retail_category_type->category_parent_id = $request->category_parent_id;
        
        $hase_merchant_retail_category_type->merchant_type_id = $request->merchant_type_id;
        

        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id',$this->merchantId)
                            ->get()->first();

        $hase_location = Location_list::
                            select('location_list.list_id as location_id','postal.postal_premise as location_name')
                            ->join('postal','postal.postal_id','=','location_list.postal_id')
                            ->where('location_list.list_id','=',$this->locationId)
                            ->get()->first();

        $merchantDirName = md5($hase_merchant->merchant_name);
        $locationDirName = md5($hase_merchant->merchant_name.$hase_location->location_name);
        
        if(!$request->category_priority)
        {
            $request->category_priority = 0;
        }
        $hase_merchant_retail_category_type->category_priority = $request->category_priority;

        if($request->live_image_url)
        {
            $hase_identity->identity_logo = $request->live_image_url;
        } else {
            if($request->file('category_image')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imagePath = $publicDirPath.$hase_merchant_retail_category_type->category_image;
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }

                $imageName = $request->file('category_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_type->category_name.$imageName).".".$imageArray[1];
                $request->file('category_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
            if($request->file('category_image_compact')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imagePath = $publicDirPath.$hase_merchant_retail_category_type->category_image_compact;
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }

                $imageName = $request->file('category_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_type->category_name.$imageName).".".$imageArray[1];
                $request->file('category_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
            }
        }

         $identity_merchant_retail_category_type = Identity_merchant_retail_category_type::
                                    join('merchant_retail_category_type','merchant_retail_category_type.identity_id','identity_merchant_retail_category_type.identity_id')
                                    ->where('identity_name',$hase_identity->identity_name)
                                    ->where('merchant_retail_category_type.category_type_id','!=',$id)
                                    ->first(); 

        if(!isset($identity_merchant_retail_category_type->identity_name)){

        $hase_identity->save();
        $hase_merchant_retail_category_type->save();

        $categoryImage = parse_url($hase_identity->identity_logo);
        if(isset($categoryImage['scheme']))
        {
            if($categoryImage['scheme'] === 'https' || $categoryImage['scheme'] === 'http')
            {
                $hase_merchant_retail_category_type->category_image = $hase_identity->identity_logo;
            }
        } else {
            if($hase_identity->identity_logo)
            {
                $hase_merchant_retail_category_type->category_image = asset(env('image_dir_path').$hase_identity->identity_logo);
            } else {
                $hase_merchant_retail_category_type->category_image=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $categoryImageCompact = parse_url($hase_identity->identity_logo_compact);
        if(isset($categoryImageCompact['scheme']))
        {
            if($categoryImageCompact['scheme'] === 'https' || $categoryImageCompact['scheme'] === 'http')
            {
                $hase_merchant_retail_category_type->category_image_compact = $hase_identity->identity_logo_compact;
            }
        } else {
            if($hase_identity->identity_logo_compact)
            {
                $hase_merchant_retail_category_type->category_image_compact = asset(env('image_dir_path').$hase_identity->identity_logo_compact);
            } else {
                $hase_merchant_retail_category_type->category_image_compact=asset(env('image_dir_path').'no_photo.png');
            }
        }
        $categoryRowObject = $hase_merchant_retail_category_type->toArray();
        $categoryRowObject['action'] = Requests::segment(1).'/'.$hase_merchant_retail_category_type->category_type_id.'/edit';

        $style_parent_name_object = Merchant_retail_category_type::
                select('identity_merchant_retail_category_type.identity_name as category_name')
                ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                ->where('merchant_retail_category_type.category_type_id','=',$hase_merchant_retail_category_type->category_parent_id)
                ->get()->first();

        if(isset($style_parent_name_object))
        {
            $categoryRowObject['parent_name'] = $style_parent_name_object->category_name;
        }else{
            $categoryRowObject['parent_name'] = "";
        }

        $categoryRowObject['category_name'] = $request->category_name;
        $categoryRowObject['success'] = 1;
        $categoryRowObject['editUrl'] = url("hase_retail_category_type").'/'.$hase_merchant_retail_category_type->category_type_id.'/update';
        return json_encode($categoryRowObject);
        }else{
            $categoryRowObject['nosuccess'] = 1;
                 return json_encode($categoryRowObject);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->permissionDetails('Hase_retail_category_type','delete')){
            $hase_merchant_retail_category_type = Merchant_retail_category_type::findOrfail($id);
            $hase_merchant_retail_category_type->delete();

            $hase_merchant_retail_category_list = Merchant_retail_category_list::where('category_type_id',$id);
            $hase_merchant_retail_category_list->delete();

            $hase_merchant_retail_category_option_list = Merchant_retail_category_option_list::where('category_type_id',$id);
            $hase_merchant_retail_category_option_list->delete();

            $categoryRowObject['success'] = 1;
            $categoryRowObject['category_type_id'] = $hase_merchant_retail_category_type->category_type_id;
            return json_encode($categoryRowObject);
            /*Session::flash('type', 'success'); 
            Session::flash('msg', 'style Successfully Deleted');
            return redirect(Requests::segment(1));*/
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getRowCategory(request $request)
    {
        $typeId = $request->type_id;

        $hase_merchant_retail_category_type = DB::table('merchant_retail_category_type')
                ->leftjoin('merchant_retail_category_type as merchant_retail_category_type_parent','merchant_retail_category_type.category_parent_id','=','merchant_retail_category_type_parent.category_type_id')
                ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                ->leftjoin('identity_merchant_retail_category_type as identity_merchant_retail_category_type_parent','identity_merchant_retail_category_type_parent.identity_id','=','merchant_retail_category_type_parent.identity_id')
                ->select('merchant_retail_category_type.*','identity_merchant_retail_category_type.identity_name as category_name','identity_merchant_retail_category_type_parent.identity_name as parent_category_name','identity_merchant_retail_category_type.identity_logo as category_image','identity_merchant_retail_category_type.identity_logo_compact as category_image_compact')
                ->where('merchant_retail_category_type.category_type_id', $typeId)
                ->get()->first();

        $retailCategoryImageUrl = parse_url($hase_merchant_retail_category_type->category_image);
        if(isset($retailCategoryImageUrl['scheme']))
        {
            if($retailCategoryImageUrl['scheme'] === 'https' || $retailCategoryImageUrl['scheme'] === 'http')
            {
                $hase_merchant_retail_category_type->category_image_url=$hase_merchant_retail_category_type->category_image;
            }
        } else {
            $hase_merchant_retail_category_type->category_image_url ='';
            if($hase_merchant_retail_category_type->category_image)
            {
                $hase_merchant_retail_category_type->category_image = asset(env('image_dir_path').$hase_merchant_retail_category_type->category_image);
            } else {
                $hase_merchant_retail_category_type->category_image='';
            }
        }

        $retailCategoryImageCompactUrl = parse_url($hase_merchant_retail_category_type->category_image_compact);
        if(isset($retailCategoryImageCompactUrl['scheme']))
        {
            if($retailCategoryImageCompactUrl['scheme'] === 'https' || $retailCategoryImageCompactUrl['scheme'] === 'http')
            {
                $hase_merchant_retail_category_type->category_image_compact_url=$hase_merchant_retail_category_type->category_image_compact;
            }
        } else {
            $hase_merchant_retail_category_type->category_image_compact_url ='';
            if($hase_merchant_retail_category_type->category_image_compact)
            {
                $hase_merchant_retail_category_type->category_image_compact = asset(env('image_dir_path').$hase_merchant_retail_category_type->category_image_compact);
            } else {
                $hase_merchant_retail_category_type->category_image_compact='';
            }
        }
        return json_encode($hase_merchant_retail_category_type);
    
    }

    public function getParentCategory(request $request){

        $hase_merchant_retail_category_types = Merchant_retail_category_type::
                select('merchant_retail_category_type.*','identity_merchant_retail_category_type.identity_name as category_name','identity_merchant_retail_category_type.identity_logo as category_image')
                ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                ->where('merchant_retail_category_type.merchant_type_id','=',$request->merchant_type)
                ->where('merchant_retail_category_type.category_type_id','!=',$request->category_type_id)
                ->orderBy('category_name','asc')
                ->get();

        return json_encode($hase_merchant_retail_category_types);       

    }
}
