<?php // Controller name Emailblacklists
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Nameblacklist; //Model name and Where model have
use Excel; //csv file reader
use DB;
class NameblacklistsController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
     /* end authentication check */
    /* Start show all the list of emailblacklists */
    public function index() {
        $nameblacklists = Nameblacklist::all()->count();
        return view('nameblacklists.list')
            ->with(compact('nameblacklists'));
    }
    /* Start show all the list of emailblacklists */
    /* Start show created page not needed */
    public function create() {
        echo date('Y-m-d h:i:s a');
        //
    }
    /* End show created page not needed */
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        set_time_limit(0);
        $error = array();
        $succe = array();
        $vname = array();
        $dup = array();
        $notformeted = 0;
        $empty = 1;
        if($request->hasFile('myfile')){
            $path = $request->file('myfile')->getRealPath();
            $CountData = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,'r');
            if(!empty($CountData) && $CountData->count() > 0){
                $NameblacklistTruncate = DB::table('name_blacklist')->truncate();;
                while (($lineN = fgetcsv($data)) !== FALSE) {                    
                    $value = array('name'=>$lineN[0],'comment'=>(isset($lineN[1]) ? $lineN[1] : ''));
                    if(isset($value['name'])){
                        if(!empty($value) && $value['name']!='' && preg_match("/^[a-zA-Z ]*$/", $value['name'])){
                            $validation = Validator::make($value,array(
                                'name' => 'required|string|max:255|unique:name_blacklist',
                            ));

                            if($validation->fails()) {
                                $dup[]=0;
                            } else {
                                $nameblacklist = new Nameblacklist;
                                $nameblacklist->name = $value['name'];
                                $nameblacklist->comment = $value['comment'];
                                if(!$nameblacklist->save()){
                                    $error[]=0;
                                } else {
                                    $succe[] = 1;
                                }  
                            }
                        } else {
                            $vname[]=0;
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
        return array('status'=>$status,'error'=>(count($error) + count($dup) + count($vname)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'vname'=>count($vname),'notformeted'=>$notformeted);
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($nameblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($nameblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function update(Request $request, $nameblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($nameblacklists) {
        //
    }
    /* End show created page not needed */
    /* Start particular nameblacklists csv file */
    public function downloadExcelFile($type){
        $nameblacklists = Nameblacklist::get(['name','comment'])->toArray();
        return Excel::create('nameblacklists', function($excel) use ($nameblacklists) {
            $excel->sheet('nameblacklists', function($sheet) use ($nameblacklists) {
                $sheet->fromArray($nameblacklists);
            });
        })->download($type);
    }
    /* Start particular nameblacklists csv file */
}