<?php // Controller name Domainblacklists
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Domainblacklist; //Model name and Where model have
use Excel; //csv file reader
use DB;
class DomainblacklistsController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of domainblacklists */
    public function index() {
        $domainblacklists = Domainblacklist::all()->count();
        return view('domainblacklists.list')
            ->with(compact('domainblacklists'));
    }
    /* Start show all the list of domainblacklists */
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
        $dup = array();        
        $vdom = array();
        $notformeted = 0;
        $empty = 1;
        if($request->hasFile('myfile')){
            $path = $request->file('myfile')->getRealPath();
            $CountData = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,'r');
            if(!empty($CountData) && $CountData->count() > 0){
                $DomainblacklistTruncate = DB::table('domain_blacklist')->truncate();
                while (($lineD = fgetcsv($data)) !== FALSE) {
                    $value = array('domain'=>$lineD[0],'comment'=>$lineD[1]);
                    if(!empty($value['domain'])){
                        if(!empty($value) && filter_var($value['domain'], FILTER_VALIDATE_URL)){
                            $validation = Validator::make($value,array(
                                'domain' => 'required|string|max:255|unique:domain_blacklist',
                            ));
                            
                            if($validation->fails()) {
                                $dup[]=0;
                            } else {
                                $domainblacklist = new Domainblacklist;
                                $domainblacklist->domain = $value['domain'];
                                $domainblacklist->comment = $value['comment'];
                                if(!$domainblacklist->save()){
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
        if(count($succe)>1){
            $status=1;
        } else {
            $status=0;
        }
        return array('status'=>$status,'error'=> (count($error) + count($dup) + count($vdom)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'vdom'=>count($vdom),'notformeted'=>$notformeted);
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($domainblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($domainblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function update(Request $request, $id) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($domainblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start particular quarantines csv file */
    public function downloadExcelFile($type){
        $domainblacklists = Domainblacklist::get(['domain','comment'])->toArray();
        return Excel::create('domainblacklists', function($excel) use ($domainblacklists) {
            $excel->sheet('domainblacklists', function($sheet) use ($domainblacklists) {
                $sheet->fromArray($domainblacklists);
            });
        })->download($type);
    }
    /* End particular quarantines csv file */
}