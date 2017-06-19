<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Alert;
use DB;
class AlertsController extends Controller
{
    /* START: Authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* END: Authentication check */
    /* START: Shows all the list of alerts */
    public function index() {
        $alerts = Alert::all();
        return view('alerts.list',compact('alerts'));
    }
    /* END: Shows all the list of alerts */
    public function create() {
        //
    }

    public function store(Request $request) {
        //
    } 
    public function show($id) {
        $alerts = Alert::all();
        return view('alerts.list',compact('alerts'));
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }
    /*START: To delete a single (or) multiple alerts from database  */
    public function destroy(Request $request) {
        if(is_array($request->id))  {
            Alert::destroy($request->id);
            return 1;
        } else {
            Alert::findOrFail($request->id)->delete();
            return 1;
        }
    }
    /*END: To delete a single (or) multiple alerts from teh database */
    /*START: To Acknowledge an alert through detailed popup in the alerts page */
    public function acknowledgeAlert(Request $request) {
        $uid = \Auth::user()->id;
        $usersName = $email = DB::table('protal_user')->where('id', $uid)->value('name');
        $date = date('Y-m-d h:i:s');

        $id = $request->input('id');
        $alert = Alert::find($id);
        $alert->acknowledged = 0;
        $alert->login_usernme = $usersName;
        $alert->acknowledged_date = $date;
        if(!$alert->save()){
            return ['status'=>0];
        } else {
            $count = DB::table('alerts')->where('acknowledged', 1)->count();
            
            $request->session()->put('count_alert', $count);
            return ['count'=>$count, 'status'=>1,'name'=>$usersName,'date'=>date('dS M Y h:i:s a', strtotime($date))];
        }
    }
    /*END: To Acknowledge an alert through detailed popup in the alerts page */
    /* START: This is to download a sample csv file but not using right now */
    public function getDownload($id) {
        $pathToFile = public_path('frauddetections/'.$id.'/frauddetections_'.$id.'.txt');
        $name = 'frauddetections_'.$id.'.txt';
        $headers = ['Content-type'=>'text/plain'];
        return response()->download($pathToFile, $name, $headers);
    }
    /* END: This is to download a sample csv file but not using right now */
    /*START: Updates the alerts count on each page load & shows that count number in the menu  */
    public function getAlertsCount(Request $request) {
        $count = DB::table('alerts')
            ->where('acknowledged', 1)
            ->count();
        $request->session()->put('count_alert', $count);
        return $count;
    }
    /*END: Updates the alerts count on each page load & shows that count number in the menu */
}