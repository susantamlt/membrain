<?php // Controller name Domainblacklists
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Donotcall; //Model name and Where model have
use Excel; //csv file reader
use DB;
class DonotcallsController extends Controller
{   
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of Donotcalls */
    public function index() {
        $donotcalls = Donotcall::all()->count();
        return view('donotcalls.list')
            ->with(compact('donotcalls'));
    }
    /* End show all the list of Donotcalls */
    /* Start show created page not needed */
    public function create() {
        echo date('Y-m-d H:i:s');
        //
    }
    /* End show created page not needed */
   /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        set_time_limit(0);
        $error = array();
        $succe = array();
        $dup = array();
        $notformeted = array();
        $empty = 1;
        $regexP = '/^[0-9]*$/';
        if($request->hasFile('myfile')){
            $path = $request->file('myfile')->getRealPath();
            $CountData = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,'r');
            if(!empty($CountData) && $CountData->count() > 0){
                $Donotcall = DB::table('membrain_global_do_not_call')->truncate();
                while (($lineP = fgetcsv($data)) !== FALSE) {
                    $value = array('country_code'=>$lineP[0],'phone_number'=>$lineP[1],'reason_code'=>$lineP[2]);
                    if(!empty($value['phone_number'])){
                        if(!empty($value) && $value['phone_number']!=''){
                            $validation = Validator::make($value,array(
                                'phone_number' => 'required|numeric|unique:membrain_global_do_not_call',
                            ));

                            if($validation->fails()) {
                                $dup[]=0;
                            } else {
                                /* Start Check country wise phone number */
                                $countrycode = array('AU','NZ','UK','CA','US');
                                $phonelength = strlen((string)$value['phone_number']);
                                if($phonelength >= 9 && $phonelength <= 11 && $value['country_code'] == 'AU'){
                                    $valid = 1;
                                } elseif ($phonelength >= 9 && $phonelength <= 11 && $value['country_code'] == 'NZ') {
                                    $valid = 1;
                                } elseif ($phonelength >= 10 && $phonelength <= 11 && $value['country_code'] == 'UK') {
                                    $valid = 1;
                                } elseif ($phonelength == 11 && $value['country_code'] == 'CA') {
                                    $valid = 1;
                                } elseif ($phonelength == 11 && $value['country_code'] == 'US') {
                                    $valid = 1;
                                } elseif ($phonelength == 10 && !in_array($value['country_code'], $countrycode)) {
                                    $valid = 1;
                                } else {
                                    $valid = 0;
                                } 
                                /* End Check country wise phone number */
                                if($valid == 1){
                                    $donotcall = new Donotcall;
                                    $donotcall->country_code = $value['country_code'];
                                    $donotcall->phone_number = $value['phone_number'];
                                    $donotcall->reason_code = $value['reason_code'];
                                    if(!$donotcall->save()){
                                        $error[]=0;
                                    } else {
                                        $succe[] = 1;
                                    }
                                } else {
                                    $error[]=0;
                                }
                            }
                        }
                    } else {
                        $notformeted[] = 1;
                    }
                }
            } else {
                $empty=0;
            }
            fclose($data);
        }
        if(count($succe)>1){
            $status=1;
        } else {
            $status=0;
        }
        return array('status'=>$status,'error'=>(count($error) + count($dup)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'notformeted'=>count($notformeted));
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($donotcalls) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($donotcalls) {
        //
    }
    /* End show created page not needed */
    //* Start show created page not needed */
    public function update(Request $request, $donotcalls) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($donotcalls) {
        //
    }
    /* End show created page not needed */
    /* Start particular quarantines csv file */
    public function downloadExcelFile($type){
        $donotcalls = Donotcall::get(['country_code','phone_number','reason_code'])->toArray();
        return Excel::create('donotcall', function($excel) use ($donotcalls) {
            $excel->sheet('donotcall', function($sheet) use ($donotcalls) {
                $sheet->fromArray($donotcalls);
            });
        })->download($type);
    }
    /* End particular quarantines csv file */
}