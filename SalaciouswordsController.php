<?php // Controller name salaciouswords
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Salaciousword; //Model name and Where model have
use Excel; //csv file reader
use DB;
class SalaciouswordsController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of salaciouswords */
    public function index() {
        $salaciouswords = Salaciousword::all()->count();
        return view('salaciouswords.list')
            ->with(compact('salaciouswords'));
    }
    /* Start show all the list of salaciouswords */
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
        $notformeted = 0;
        $empty = 1;
        if($request->hasFile('myfile')){
            $path = $request->file('myfile')->getRealPath();
            $CountData = Excel::load($path, function($reader) {})->get();
            $data = fopen($path,'r');
            if(!empty($CountData) && $CountData->count() > 0){
                $SalaciouswordTruncate = DB::table('salacious_word')->truncate();
                while (($lineSW = fgetcsv($data)) !== FALSE) {
                    $value = array('pattern'=>$lineSW[0],'email_address'=>$lineSW[1],'first_name'=>$lineSW[2],'last_name'=>$lineSW[3],'address'=>$lineSW[4]);
                    if(isset($value['pattern'])){
                        if(!empty($value) && $value['pattern']!=''){
                            $validation = Validator::make($value,array(
                                'pattern' => 'required|string|max:255|unique:salacious_word',
                            ));

                            if($validation->fails()) {
                                $dup[]=0;
                            } else {
                                $salaciousword = new Salaciousword;
                                $salaciousword->pattern = $value['pattern'];
                                $salaciousword->email_address = (bool)$value['email_address'];
                                $salaciousword->first_name = (bool)$value['first_name'];
                                $salaciousword->last_name = (bool)$value['last_name'];
                                $salaciousword->address = (bool)$value['address'];
                                if(!$salaciousword->save()){
                                    $error[]=0;
                                } else {
                                    $succe[] = 1;
                                }
                            }
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

        return array('status'=>$status,'error'=> (count($error) + count($dup)),'success'=>count($succe),'empty'=>$empty,'total'=>$CountData->count(),'dup'=>count($dup),'notformeted'=>$notformeted);
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show created page not needed */
    public function show($salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function edit($salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function update(Request $request, $salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start show created page not needed */
    public function destroy($salaciouswords) {
        //
    }
    /* End show created page not needed */
    /* Start particular salaciouswords csv file */
    public function downloadExcelFile($type){
        $salaciouswords = Salaciousword::get(['pattern','email_address','first_name','last_name','address'])->toArray();
        return Excel::create('salaciouswords', function($excel) use ($salaciouswords) {
            $excel->sheet('salaciouswords', function($sheet) use ($salaciouswords) {
                $sheet->fromArray($salaciouswords);
            });
        })->download($type);
    }
    /* Start particular salaciouswords csv file */
}