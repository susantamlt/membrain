<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\clearPhonehistory;
use DB;
class Phonehistory extends Command
{
    protected $signature = 'phonehistory:clear';
    protected $description = 'Clear Phone History';
    protected $clearPhonehistory;
    
    public function __construct(clearPhonehistory $clearPhonehistory) {
        parent::__construct();
        $this->clearPhonehistory = $clearPhonehistory;
        set_time_limit(0);
    }

    /* when we run signature like 'phonehistory:clear' then always hit handle function we fetch data and cler data from database as per requirement document */
    public function handle() {
        $date = date('Y-m-d');
        $validationdate = date('Y-m-d', strtotime($date .' -180 day'));
        $phonehistorys = DB::table('phone_history as phonehistory')->select('phonehistory.id','phonehistory.is_valid')
            ->where('phonehistory.validation_date','<',$validationdate)
            ->get(); //fatch all data from database

        if(!empty($phonehistorys)){
            foreach ($phonehistorys as $key => $_phonehistory) {  
                DB::table('phone_history')
                    ->where('id','=', $_phonehistory->id)
                    ->delete();
            }
        }
    }
}