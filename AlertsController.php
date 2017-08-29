<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Alert;
use DB;
use Carbon\Carbon;
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
        //$_date = date('Y-m-d h:i:s a');
        $date = Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a'));
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
        $alertsId = DB::table('alerts')
            ->where('alerts.id','=',$id)
            ->LeftJoin('suppliers', 'alerts.supplier_id', '=', 'suppliers.id')
            ->select('alerts.*', 'suppliers.id as sid','suppliers.public_id','suppliers.name')
            ->get();
        $campain_id = explode('_',$alertsId[0]->filename);
        $campainId = $campain_id[0];
        $ext = end($campain_id);
        if($alertsId[0]->sid==''){
             $newfilename = $campainId.'_Return-CSV_out.csv';
            $campaignsName = DB::table('campaigns')->where('public_id','=',$campainId)->value('name');
            $pathToFile1 = storage_path('process_dir/portal_csv_upload/temp/'.$alertsId[0]->filename);
            $pathToFile2 = storage_path('supplier_files/portal_csv_upload/download/'.$newfilename);
            if(file_exists($pathToFile1)){
                $pathToFile = storage_path('supplier_files/'.$alertsId[0]->public_id.'/download/'.$alertsId[0]->filename);
            } else {
                $pathToFile = storage_path('supplier_files/portal_csv_upload/download/'.$newfilename);
            }
            $name = $campaignsName.'_'.$ext;
        } else {            
            if($ext=='Return-CSV.csv'){
                $newfilename = $campainId.'_Return-CSV_out.csv';
                $campaignsName = DB::table('campaigns')->where('public_id','=',$campainId)->value('name');
                $pathToFile1 = storage_path('supplier_files/'.$alertsId[0]->public_id.'/download/'.$alertsId[0]->filename);
                $pathToFile2 = storage_path('supplier_files/portal_csv_upload/download/'.$newfilename);
                $pathToFile3 = storage_path('supplier_files/'.$alertsId[0]->public_id.'/download/'.$newfilename);
                $pathToFile4 = storage_path('supplier_files/portal_csv_upload/download/'.$alertsId[0]->filename);
                $name = $alertsId[0]->name.'_'.$campaignsName.'_'.$ext;
                if(file_exists($pathToFile1)){
                    $pathToFile = storage_path('supplier_files/'.$alertsId[0]->public_id.'/download/'.$alertsId[0]->filename);
                } else if(file_exists($pathToFile2)){
                    $pathToFile = storage_path('supplier_files/portal_csv_upload/download/'.$newfilename);
                } else if(file_exists($pathToFile3)){
                    $pathToFile = storage_path('supplier_files/'.$alertsId[0]->public_id.'/download/'.$newfilename);
                } else if(file_exists($pathToFile4)){
                    $pathToFile = storage_path('supplier_files/'.$alertsId[0]->public_id.'/download/'.$newfilename);
                } else {
                    $pathToFile = storage_path('supplier_files/portal_csv_upload/download/'.$alertsId[0]->filename);
                }
            } else {
                $pathToFile = storage_path('frauddetections/'.$alertsId[0]->sid.'/'.$alertsId[0]->filename);
                $name = 'Fraud_Detections_'.$alertsId[0]->name.'.txt';
            }
        }   
        $headers = ['Content-type'=>'text/csv'];
        if (file_exists($pathToFile)) {
            return response()->download($pathToFile, $name, $headers);
        } else {
            $newExt = explode('.',$ext);
            $_ext = end($newExt);       
            $pathToFilenew = storage_path('frauddetections/frauddetections_empty.'.$_ext);
            if($alertsId[0]->sid==''){
                $namenew = 'Fraud_Detections_empty.'.$_ext;
            } else {
                $namenew = 'Fraud_Detections_'.$alertsId[0]->name.'_empty.'.$_ext;
            }
            return response()->download($pathToFilenew, $namenew, $headers);
        }         
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