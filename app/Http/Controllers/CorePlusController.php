<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use View;
use URL;
use Auth;
use Carbon\Carbon;
use DB;
use App\Staff;

class CorePlusController extends Controller
{

    public function showView($name)
    {
        if(View::exists($name))
        {
           return view($name);

        }
        else
        {
            return view('404');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       URL::to('login');
       return view('login');
    }

    public function logout(Request $request){
        
        // Activity Log
        $user = Auth::user();        
        
        if(isset($user->staff_id)){
            $staffInfo = Staff::findOrfail($user->staff_id);
            $staffUrl = "/hase_staff/".$user->staff_id."/edit";
            $activityLog = array(
                "merchant_id" => $staffInfo->merchant_id,
                "domain" => "admin",
                "context" => "staffs",
                "user" => "staff",
                "user_id" => $user->user_id,
                "action" => "logged out",
                "message" => "<a href='".URL::to($staffUrl)."'>".$staffInfo->staff_name."</a> <strong>logged</strong> out.",
                "date_added" => $currentDate =Carbon::now()
                );
            DB::table('activities')->insert($activityLog);
        }
        
        Auth::logout();
        $request->session()->flush();

        $request->session()->regenerate();
        

        return redirect('login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        URL::to('login');
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new \App\User();

        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = \App\User::findOrfail($request->user_id);

        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = \App\User::findOrfail($id);

        $user->delete();

        return redirect('users');
    }
}
