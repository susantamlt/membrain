<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\clearFraudtable;
use DB;
class Fraudtable extends Command
{
    protected $signature = 'fraudtable:clear';
    protected $description = 'Clear Fraud Detection';
    protected $clearFraudtable;

    public function __construct(clearFraudtable $clearFraudtable) {
        parent::__construct();
        $this->clearFraudtable = $clearFraudtable;
        set_time_limit(0);
    }
    
    /* when we run signature like 'fraudtable:clear' then always hit handle function we fetch data and cler data from database as per requirement document */
    public function handle() {
        $validationdate = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -90 minutes'));
        $frauddetections = DB::table('fraud_detection as frauddetection')->select('frauddetection.id','frauddetection.received')
            ->where('frauddetection.received','<',$validationdate)
            ->get(); //fatch all data from database

        if(!empty($frauddetections)){
            foreach ($frauddetections as $key => $_frauddetection) {  
                DB::table('fraud_detection')
                    ->where('id','=', $_frauddetection->id)
                    ->delete();
            }
        }
    }
}