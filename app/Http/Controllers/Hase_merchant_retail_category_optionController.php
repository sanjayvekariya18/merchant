<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\ProductApprovalController;
use App\Merchant;
use App\Location_list;
use App\Identity_merchant_retail_category_option;
use App\Merchant_retail_category_option;
use App\Merchant_retail_category_option_list;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
use Session;
use Redirect;
use Auth;

/**
 * Class Hase_merchant_retail_category_optionController.
 *
 */
class Hase_merchant_retail_category_optionController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('hase_cuisine_types');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->productApproval = new ProductApprovalController();
    }

    public function store(Request $request)
    {
        $hase_merchant_retail_category_option = new Merchant_retail_category_option();
        $hase_identity = new Identity_merchant_retail_category_option();
        
        $hase_merchant_retail_category_option->merchant_type_id = $request->merchant_type;
        
        $hase_merchant_retail_category_option->category_option_enable = 
                                        isset($request->category_option_enable) ? 1 : 0 ;   

        // Identity field
        $hase_identity->identity_name = $request->option_name;

       
        $option_name = preg_replace('/\s*/', '', $request->option_name);

        $option_name = strtolower($option_name);

        $hase_identity->identity_code = $option_name;

        
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

            if($request->file('option_image')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('option_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_option->option_name.$imageName).".".$imageArray[1];
                $request->file('option_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }


        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {

            if($request->file('option_image_compact')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('option_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_option->option_name.$imageName).".".$imageArray[1];
                $request->file('option_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
            }
        }

        // Insert record in identity schema 
        $identity_merchant_retail_category_option=Identity_merchant_retail_category_option::
        join("merchant_retail_category_option",'merchant_retail_category_option.identity_id','identity_merchant_retail_category_option.identity_id')
        ->where('identity_name',$hase_identity->identity_name)
        ->where('merchant_type_id',$request->merchant_type)
        ->first();

        if(!isset($identity_merchant_retail_category_option->identity_name)){
        $hase_identity->save();
        $identityID = $hase_identity->identity_id;

        $hase_merchant_retail_category_option->identity_id = $identityID;
        $hase_merchant_retail_category_option->save();

        $optionID = $hase_merchant_retail_category_option->category_option_type_id;
        
        $categoryOptionImage = parse_url($hase_identity->identity_logo);
        if(isset($categoryOptionImage['scheme']))
        {
            if($categoryOptionImage['scheme'] === 'https' || $categoryOptionImage['scheme'] === 'http')
            {
                $hase_merchant_retail_category_option->option_image = $hase_identity->identity_logo;
            }
        } else {
            if($hase_identity->identity_logo)
            {
                $hase_merchant_retail_category_option->option_image = asset(env('image_dir_path').$hase_identity->identity_logo);
            } else {
                $hase_merchant_retail_category_option->option_image=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $categoryOptionImageCompact = parse_url($hase_identity->identity_logo_compact);
        if(isset($categoryOptionImageCompact['scheme']))
        {
            if($categoryOptionImageCompact['scheme'] === 'https' || $categoryOptionImageCompact['scheme'] === 'http')
            {
                $hase_merchant_retail_category_option->option_image_compact = $hase_identity->identity_logo_compact;
            }
        } else {
            if($hase_identity->identity_logo_compact)
            {
                $hase_merchant_retail_category_option->option_image_compact = asset(env('image_dir_path').$hase_identity->identity_logo_compact);
            } else {
                $hase_merchant_retail_category_option->option_image_compact=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $optionRowObject = $hase_merchant_retail_category_option->toArray();
        $optionRowObject['editUrl'] = url("hase_retail_option_type").'/'.$hase_merchant_retail_category_option->category_option_type_id.'/update';
        $optionRowObject['deleteUrl'] = url("hase_retail_option_type").'/'.$hase_merchant_retail_category_option->category_option_type_id.'/delete';

        $optionRowObject['category_option_type_id'] = $optionID;
        $optionRowObject['option_name'] = $request->option_name;
        $optionRowObject['success'] = 1;
        return json_encode($optionRowObject);

        }else{
            $optionRowObject['nosuccess'] = 1;
            return json_encode($optionRowObject);
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
        $hase_merchant_retail_category_option = Merchant_retail_category_option::findOrfail($id);
        $hase_identity = Identity_merchant_retail_category_option::findOrfail($hase_merchant_retail_category_option->identity_id);
        
        $hase_merchant_retail_category_option->merchant_type_id = $request->merchant_type;
        
        $hase_merchant_retail_category_option->category_option_enable = 
                                        isset($request->category_option_enable) ? 1 : 0 ;
        
        // Identity field
        $hase_identity->identity_name = $request->option_name;

        $option_name = preg_replace('/\s*/', '', $request->option_name);

        $option_name = strtolower($option_name);

        $hase_identity->identity_code = $option_name;
        
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

            if($request->file('option_image')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imagePath = $publicDirPath.$hase_merchant_retail_category_option->option_image;
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }

                $imageName = $request->file('option_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_option->option_name.$imageName).".".$imageArray[1];
                $request->file('option_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }


        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {

            if($request->file('option_image_compact')){
                
                $publicDirPath = public_path(env('image_dir_path'));

                $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imagePath = $publicDirPath.$hase_merchant_retail_category_option->option_image_compact;
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }

                $imageName = $request->file('option_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_merchant_retail_category_option->option_name.$imageName).".".$imageArray[1];
                $request->file('option_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
            }
        }
        $identity_merchant_retail_category_option=DB::table('identity_merchant_retail_category_option')->where('identity_name','=',$hase_identity->identity_name)->first();
        if(!isset($identity_merchant_retail_category_option->identity_name)){
        $hase_identity->save();
        $hase_merchant_retail_category_option->save();

        $categoryOptionImage = parse_url($hase_identity->identity_logo);
        if(isset($categoryOptionImage['scheme']))
        {
            if($categoryOptionImage['scheme'] === 'https' || $categoryOptionImage['scheme'] === 'http')
            {
                $hase_merchant_retail_category_option->option_image = $hase_identity->identity_logo;
            }
        } else {
            if($hase_identity->identity_logo)
            {
                $hase_merchant_retail_category_option->option_image = asset(env('image_dir_path').$hase_identity->identity_logo);
            } else {
                $hase_merchant_retail_category_option->option_image=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $categoryOptionImageCompact = parse_url($hase_identity->identity_logo_compact);
        if(isset($categoryOptionImageCompact['scheme']))
        {
            if($categoryOptionImageCompact['scheme'] === 'https' || $categoryOptionImageCompact['scheme'] === 'http')
            {
                $hase_merchant_retail_category_option->option_image_compact = $hase_identity->identity_logo_compact;
            }
        } else {
            if($hase_identity->identity_logo_compact)
            {
                $hase_merchant_retail_category_option->option_image_compact = asset(env('image_dir_path').$hase_identity->identity_logo_compact);
            } else {
                $hase_merchant_retail_category_option->option_image_compact=asset(env('image_dir_path').'no_photo.png');
            }
        }

        $optionRowObject = $hase_merchant_retail_category_option->toArray();
        
        $optionRowObject['action'] = Requests::segment(1).'/'.$hase_merchant_retail_category_option->option_type_id.'/edit';

        $optionRowObject['option_name'] = $request->option_name;
        $optionRowObject['success'] = 1;        
        $optionRowObject['editUrl'] = url("hase_retail_category_option").'/'.$hase_merchant_retail_category_option->option_type_id.'/update';
        
        return json_encode($optionRowObject);
    }else{
         $optionRowObject['nosuccess'] = 1;       
        return json_encode($optionRowObject);
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
        if($this->permissionDetails('Hase_retail_category_option','delete')){
            $hase_merchant_retail_category_option = Merchant_retail_category_option::findOrfail($id);
            $hase_merchant_retail_category_option->delete();
            
            $hase_merchant_retail_category_option_list = Merchant_retail_category_option_list::where('category_option_type_id',$id);
            $hase_merchant_retail_category_option_list->delete();

            $optionRowObject['success'] = 1;
            
            $optionRowObject['category_option_type_id'] = $hase_merchant_retail_category_option->category_option_type_id;

            return json_encode($optionRowObject);

        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getRowOption(request $request)
    {
        $optionId = $request->option_type_id;

        $hase_merchant_retail_category_option = Merchant_retail_category_option::findOrfail($optionId);

        $hase_merchant_retail_category_option = DB::table('merchant_retail_category_option')
                ->join('identity_merchant_retail_category_option','identity_merchant_retail_category_option.identity_id','=','merchant_retail_category_option.identity_id')
                ->select('merchant_retail_category_option.*','identity_merchant_retail_category_option.identity_name as option_name','identity_merchant_retail_category_option.identity_logo as option_image','identity_merchant_retail_category_option.identity_logo_compact as option_image_compact')
                ->where('merchant_retail_category_option.category_option_type_id', $optionId)
                ->get()->first();


        $retailCategoryOptionImageUrl = parse_url($hase_merchant_retail_category_option->option_image);
        if(isset($retailCategoryOptionImageUrl['scheme']))
        {
            if($retailCategoryOptionImageUrl['scheme'] === 'https' || $retailCategoryOptionImageUrl['scheme'] === 'http')
            {
                $hase_merchant_retail_category_option->option_image_url=$hase_merchant_retail_category_option->option_image;
            }
        } else {
            $hase_merchant_retail_category_option->option_image_url ='';
            if($hase_merchant_retail_category_option->option_image)
            {
                $hase_merchant_retail_category_option->option_image = asset(env('image_dir_path').$hase_merchant_retail_category_option->option_image);
            } else {
                $hase_merchant_retail_category_option->option_image='';
            }
        }

        $retailCategoryOptionImageCompactUrl = parse_url($hase_merchant_retail_category_option->option_image_compact);
        if(isset($retailCategoryOptionImageCompactUrl['scheme']))
        {
            if($retailCategoryOptionImageCompactUrl['scheme'] === 'https' || $retailCategoryOptionImageCompactUrl['scheme'] === 'http')
            {
                $hase_merchant_retail_category_option->option_image_compact_url=$hase_merchant_retail_category_option->option_image_compact;
            }
        } else {
            $hase_merchant_retail_category_option->option_image_compact_url ='';
            if($hase_merchant_retail_category_option->option_image_compact)
            {
                $hase_merchant_retail_category_option->option_image_compact = asset(env('image_dir_path').$hase_merchant_retail_category_option->option_image_compact);
            } else {
                $hase_merchant_retail_category_option->option_image_compact='';
            }
        }
        
        return json_encode($hase_merchant_retail_category_option);
    }
}
