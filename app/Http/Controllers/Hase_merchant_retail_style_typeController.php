<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Merchant;
use App\Location_list;
use App\Identity_merchant_retail_style_type;
use App\Merchant_retail_style_type;
use App\Merchant_retail_style_list;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use DB; 
use Session;
use Redirect;
use Auth;

/**
 * Class Hase_merchant_retail_style_typeController.
 *
 */
class Hase_merchant_retail_style_typeController extends PermissionsController
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

        $hase_merchant_retail_style_type = new Merchant_retail_style_type();
        $hase_identity = new Identity_merchant_retail_style_type();
        
        if(!$request->style_parent_id)
        {
            $request->style_parent_id = 0;
        }
        $hase_merchant_retail_style_type->style_parent_id = $request->style_parent_id;
        
        $hase_merchant_retail_style_type->merchant_type_id = $request->merchant_type;


        if(!$request->style_priority)
        {
            $request->style_priority = 999999;
        }

        $hase_merchant_retail_style_type->style_priority = $request->style_priority;

        // Identity field

        $hase_identity->identity_name = $request->style_name;

        $style_name = preg_replace('/\s*/', '', $request->style_name);

        $style_name = strtolower($style_name);

        $hase_identity->identity_code = $style_name;
        
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
            if($request->file('style_image')){
                
                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('style_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$request->style_name.$imageName).".".$imageArray[1];
                $request->file('style_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
            if($request->file('style_image_compact')){
                
                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('style_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$request->style_name.$imageName).".".$imageArray[1];
                $request->file('style_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
            }
        }

            $identity_merchant_retail_style_type_check=DB::table('identity_merchant_retail_style_type')->where('identity_name','=',$hase_identity->identity_name)->first();
            if(!isset($identity_merchant_retail_style_type_check->identity_name)){
               
            
        // Insert record in identity schema 

        $hase_identity->save();
        $identityID = $hase_identity->identity_id;

        $hase_merchant_retail_style_type->identity_id = $identityID;
        
        $hase_merchant_retail_style_type->save();

        $typeID = $hase_merchant_retail_style_type->style_type_id;

        $styleImage = parse_url($hase_identity->identity_logo);
        if(isset($styleImage['scheme']))
        {
            if($styleImage['scheme'] === 'https' || $styleImage['scheme'] === 'http')
            {
                $hase_merchant_retail_style_type->style_image = $hase_identity->identity_logo;
            }
        } else {
            if($hase_identity->identity_logo)
            {
                $hase_merchant_retail_style_type->style_image = asset(env('image_dir_path').$hase_identity->identity_logo);
            } else {
                $hase_merchant_retail_style_type->style_image=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $styleImageCompact = parse_url($hase_identity->identity_logo_compact);
        if(isset($styleImageCompact['scheme']))
        {
            if($styleImageCompact['scheme'] === 'https' || $styleImageCompact['scheme'] === 'http')
            {
                $hase_merchant_retail_style_type->style_image_compact = $hase_identity->identity_logo_compact;
            }
        } else {
            if($hase_identity->identity_logo_compact)
            {
                $hase_merchant_retail_style_type->style_image_compact = asset(env('image_dir_path').$hase_identity->identity_logo_compact);
            } else {
                $hase_merchant_retail_style_type->style_image_compact=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $styleRowObject = $hase_merchant_retail_style_type->toArray();
        $styleRowObject['editUrl'] = url("hase_retail_style_type").'/'.$hase_merchant_retail_style_type->style_type_id.'/update';
        $styleRowObject['deleteUrl'] = url("hase_retail_style_type").'/'.$hase_merchant_retail_style_type->style_type_id.'/delete';
        $style_parent_name_object = Merchant_retail_style_type::
                select('identity_merchant_retail_style_type.identity_name as style_name')
                ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                ->where('merchant_retail_style_type.style_type_id','=',$hase_merchant_retail_style_type->style_parent_id)
                ->get()->first();

        if(isset($style_parent_name_object)) 
        {
            $styleRowObject['parent_name'] = $style_parent_name_object->style_name;
        }

        $styleRowObject['style_type_id'] = $typeID;
        $styleRowObject['style_name'] = $request->style_name;
        $styleRowObject['success'] = 1;
        return json_encode($styleRowObject);
        }else{
            $styleRowObject['nosuccess'] = 1;
                 return json_encode($styleRowObject);
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

        $hase_merchant_retail_style_type = Merchant_retail_style_type::findOrfail($id);
        $hase_identity = Identity_merchant_retail_style_type::findOrfail($hase_merchant_retail_style_type->identity_id);

        if(!$request->style_parent_id)
        {
            $request->style_parent_id = 0;
        }

        $hase_merchant_retail_style_type->style_parent_id = $request->style_parent_id;
        
        $hase_merchant_retail_style_type->merchant_type_id = $request->merchant_type_id;
        
        $hase_identity->identity_name = $request->style_name;

        $style_name = preg_replace('/\s*/', '', $request->style_name);

        $style_name = strtolower($style_name);

        $hase_identity->identity_code = $style_name;
        
        if(!$request->style_priority)
        {
            $request->style_priority = 0;
        }
        $hase_merchant_retail_style_type->style_priority = $request->style_priority;
        
        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
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
            if($request->file('style_image')){
                
                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                if($this->roleId === 1){
                    $imagePath = $publicDirPath.$hase_merchant_retail_style_type->style_image;
                    if (is_file($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $imageName = $request->file('style_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_style_type->style_name.$imageName).".".$imageArray[1];
                $request->file('style_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
            if($request->file('style_image_compact')){
                
                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                if($this->roleId === 1){
                    $imagePath = $publicDirPath.$hase_merchant_retail_style_type->style_image_compact;
                    if (is_file($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $imageName = $request->file('style_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_style_type->style_name.$imageName).".".$imageArray[1];
                $request->file('style_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
            }
        }

        $identity_merchant_retail_style_type_check=Identity_merchant_retail_style_type::
                                    join('merchant_retail_style_type','merchant_retail_style_type.identity_id','identity_merchant_retail_style_type.identity_id')
                                    ->where('identity_name',$hase_identity->identity_name)
                                    ->where('merchant_retail_style_type.style_type_id','!=',$id)
                                    ->first();

            if(!isset($identity_merchant_retail_style_type_check->identity_name)){
    
            $hase_identity->save();
            $hase_merchant_retail_style_type->save();

            $styleImage = parse_url($hase_identity->identity_logo);
            if(isset($styleImage['scheme']))
            {
                if($styleImage['scheme'] === 'https' || $styleImage['scheme'] === 'http')
                {
                    $hase_merchant_retail_style_type->style_image = $hase_identity->identity_logo;
                }
            } else {
                if($hase_identity->identity_logo)
                {
                    $hase_merchant_retail_style_type->style_image = asset(env('image_dir_path').$hase_identity->identity_logo);
                } else {
                    $hase_merchant_retail_style_type->style_image=asset(env('image_dir_path').'no_photo.png');
                }
            }

            $styleImageCompact = parse_url($hase_identity->identity_logo_compact);
            if(isset($styleImageCompact['scheme']))
            {
                if($styleImageCompact['scheme'] === 'https' || $styleImageCompact['scheme'] === 'http')
                {
                    $hase_merchant_retail_style_type->style_image_compact = $hase_identity->identity_logo_compact;
                }
            } else {
                if($hase_identity->identity_logo_compact)
                {
                    $hase_merchant_retail_style_type->style_image_compact = asset(env('image_dir_path').$hase_identity->identity_logo_compact);
                } else {
                    $hase_merchant_retail_style_type->style_image_compact=asset(env('image_dir_path').'no_photo.png');
                }
            }
        
            $styleRowObject = $hase_merchant_retail_style_type->toArray();
            $styleRowObject['action'] = Requests::segment(1).'/'.$hase_merchant_retail_style_type->style_type_id.'/edit';
            $style_parent_name_object = Merchant_retail_style_type::
                    select('identity_merchant_retail_style_type.identity_name as style_name')
                    ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                    ->where('merchant_retail_style_type.style_type_id','=',$hase_merchant_retail_style_type->style_parent_id)
                    ->get()->first();

            if(isset($style_parent_name_object))
            {
                $styleRowObject['parent_name'] = $style_parent_name_object->style_name;
            }else{
                $styleRowObject['parent_name'] = "";
            }

            $styleRowObject['style_name'] = $request->style_name;
            $styleRowObject['success'] = 1;
            $styleRowObject['editUrl'] = url("hase_retail_style_type").'/'.$hase_merchant_retail_style_type->style_type_id.'/update';
            return json_encode($styleRowObject);
        }else{
            $styleRowObject['nosuccess'] = 1;
                 return json_encode($styleRowObject);
            }

        //return redirect('hase_merchant_retail_style_type');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->permissionDetails('Hase_retail_style_type','delete')){
            $hase_merchant_retail_style_type = Merchant_retail_style_type::findOrfail($id);
            $hase_merchant_retail_style_type->delete();

            $hase_merchant_retail_style_list = Merchant_retail_style_list::where('style_type_id',$id);
            $hase_merchant_retail_style_list->delete();

            $styleRowObject['success'] = 1;
            $styleRowObject['style_type_id'] = $hase_merchant_retail_style_type->style_type_id;
            return json_encode($styleRowObject);
            /*Session::flash('type', 'success'); 
            Session::flash('msg', 'style Successfully Deleted');
            return redirect(Requests::segment(1));*/
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getRowStyle(request $request)
    {

        $typeId = $request->style_type_id;
        $hase_merchant_retail_style_type = DB::table('merchant_retail_style_type')
                ->leftjoin('merchant_retail_style_type as merchant_retail_style_type_parent','merchant_retail_style_type.style_parent_id','=','merchant_retail_style_type_parent.style_type_id')
                ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                ->leftjoin('identity_merchant_retail_style_type as identity_merchant_retail_style_type_parent','identity_merchant_retail_style_type_parent.identity_id','=','merchant_retail_style_type_parent.identity_id')
                ->select('merchant_retail_style_type.*','identity_merchant_retail_style_type.identity_name as style_name','identity_merchant_retail_style_type.identity_code as style_code','identity_merchant_retail_style_type_parent.identity_name as parent_style_name','identity_merchant_retail_style_type.identity_logo as style_image','identity_merchant_retail_style_type.identity_logo_compact as style_image_compact')
                ->where('merchant_retail_style_type.style_type_id', $typeId)
                ->get()->first();
                
        $retailsStyleImageUrl = parse_url($hase_merchant_retail_style_type->style_image);
        if(isset($retailsStyleImageUrl['scheme']))
        {
            if($retailsStyleImageUrl['scheme'] === 'https' || $retailsStyleImageUrl['scheme'] === 'http')
            {
                $hase_merchant_retail_style_type->style_image_url=$hase_merchant_retail_style_type->style_image;
            }
        } else {
            $hase_merchant_retail_style_type->style_image_url ='';
            if($hase_merchant_retail_style_type->style_image)
            {
                $hase_merchant_retail_style_type->style_image = asset(env('image_dir_path').$hase_merchant_retail_style_type->style_image);
            } else {
                $hase_merchant_retail_style_type->style_image='';
            }
        }

        $retailsStyleImageCompactUrl = parse_url($hase_merchant_retail_style_type->style_image_compact);
        if(isset($retailsStyleImageCompactUrl['scheme']))
        {
            if($retailsStyleImageCompactUrl['scheme'] === 'https' || $retailsStyleImageCompactUrl['scheme'] === 'http')
            {
                $hase_merchant_retail_style_type->style_image_compact_url=$hase_merchant_retail_style_type->style_image_compact;
            }
        } else {
            $hase_merchant_retail_style_type->style_image_compact_url ='';
            if($hase_merchant_retail_style_type->style_image_compact)
            {
                $hase_merchant_retail_style_type->style_image_compact = asset(env('image_dir_path').$hase_merchant_retail_style_type->style_image_compact);
            } else {
                $hase_merchant_retail_style_type->style_image_compact='';
            }
        }


        return json_encode($hase_merchant_retail_style_type);
    }

    public function getParentStyle(request $request){

        $hase_merchant_retail_style_types = Merchant_retail_style_type::
                select('merchant_retail_style_type.*','identity_merchant_retail_style_type.identity_name as style_name','identity_merchant_retail_style_type.identity_logo as style_image')
                ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                ->where('merchant_type_id','=',$request->merchant_type)
                ->where('style_type_id','!=',$request->style_type_id)
                ->orderBy('style_name','asc')
                ->get();
        return json_encode($hase_merchant_retail_style_types);     
    }
}
