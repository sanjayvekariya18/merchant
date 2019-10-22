<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Security_question;
use Amranidev\Ajaxis\Ajaxis;
use URL;
use View;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;

/**
 * Class Hase_security_questionController.
 *
 * @author  The scaffold-interface created at 2017-03-01 12:27:50pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_security_questionController extends PermissionsController
{
    use PermissionTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_security_question');
        if (strcmp($connectionStatus['type'],"error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $haseSecurityQuestionAceess = $this->permissionDetails('Hase_security_question','access');
        if($haseSecurityQuestionAceess) {
            $title = 'Index - hase_security_question';
            $hase_security_questions = Security_question::orderBy('priority', 'asc')->get();
            $question_count = count($hase_security_questions);
            return view('hase_security_question.index',compact('hase_security_questions','question_count','title'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->questions)
        {
            $QuestionValueExist = array();
            foreach ($request->questions as $questionValueData) {
                if (array_key_exists('question_id', $questionValueData)) {
                    $QuestionValueExist[] = $questionValueData['question_id'];
                }
            }
            Security_question::whereNotIn('question_id', $QuestionValueExist)->delete();
            $questionIndex =1;
            foreach ($request->questions as $questionValue) {
                if ($questionValue['question_id']) {
                    $hase_security_question = Security_question::firstOrCreate(['question_id' => $questionValue['question_id']]);
                } else {
                    $hase_security_question = new Security_question;
                }
                $hase_security_question->text = $questionValue['text'];
                $hase_security_question->priority = $questionIndex;
                $hase_security_question->save();
                $questionIndex++;

            }
        } else {
            Security_question::where('question_id',$lastInsertedOptionId);
        }

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Security question Successfully Updated');
        return redirect('hase_security_question');
                
    }

    public function destroy($id)
    {
        $haseOptionAccess = $this->permissionDetails('Hase_security_question','delete');
        if($haseOptionAccess) {
            $hase_security_question = Security_question::findOrfail($id);
            $hase_security_question->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Security Question Successfully Deleted');
            return redirect('hase_security_question');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
