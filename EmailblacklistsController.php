<?php // Controller name Emailblacklists
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Emailblacklist; //Model name and Where model have
use Excel; //csv file reader
use DB;
class EmailblacklistsController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of emailblacklists */
    public function index() {
        $emailblacklists = Emailblacklist::all()->count();
        return view('emailblacklists.list')
            ->with(compact('emailblacklists'));
    }
    /* Start show all the list of emailblacklists */
    /* Start show created page not needed */
    public function create() {
        //
    }
    /* End show created page not needed */
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        set_time_limit(0);
        $error = array();
        $succe = array();
        $vdom = array();
        $dup = array();
        $notformeted = 0;
        $empty = 1;
        if($request->hasFile('myfile')){
            $path = $request->file('myfile')->getRealPath();
            $CountData = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,'r');            
            if(!empty($CountData) && $CountData->count() > 0){
                $EmailblacklistTruncate = DB::table('email_blacklist')->truncate();
                while (($lineE = fgetcsv($data)) !== FALSE) {
                    $value = array('email_address'=>$lineE[0],'comment'=>$lineE[1]);
                    if(isset($value['email_address'])){
                        if(!empty($value) && filter_var($value['email_address'], FILTER_VALIDATE_EMAIL)){
                            $validation = Validator::make($value, array(
                                'email_address' => 'required|string|email|max:255|unique:email_blacklist',
                            ));
                            
                            if($validation->fails()) {
                                $dup[]=0;
                            } else {
                                $emailblacklist = new Emailblacklist;
                                $emailblacklist->email_address = $value['email_address'];
                                $emailblacklist->comment = $value['comment'];
                                if(!$emailblacklist->save()){
                                    $error[]=0;
                                } else {
                                    $succe[] = 1;
                                } 
                            }
                        } else {
                            $vdom[]=0;
                        }
                    } else {
                        $notformeted = 1;
                    }
                }
            } else {
                $empty = 0;
            }
            fclose($data);
        }
        if(count($succe)>0){
            $status=1;
        } else {
            $status=0;
        }
        return array('status'=>$status,'error'=>(count($error) + count($dup) + count($vdom)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'vdom'=>count($vdom),'notformeted'=>$notformeted);
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($id) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($id) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function update(Request $request, $id) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($id) {
        //
    }
    /* End show created page not needed */
    /* Start particular emailblacklists csv file */
    public function downloadExcelFile($type){
        $emailblacklists = Emailblacklist::get(['email_address','comment'])->toArray();
        return Excel::create('emailblacklists', function($excel) use ($emailblacklists) {
            $excel->sheet('emailblacklists', function($sheet) use ($emailblacklists) {
                $sheet->fromArray($emailblacklists);
            });
        })->download($type);
    }
    /* Start particular emailblacklists csv file */
}