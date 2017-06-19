<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\fraudAlert;
use DB;
class FraudAlerting extends Command
{
    protected $signature = 'fraudalerting:clear';
    protected $description = 'Fraud Alerting';    
    protected $fraudAlert;

    public function __construct(fraudAlert $fraudAlert) {
        parent::__construct();
        $this->fraudAlert = $fraudAlert;
        set_time_limit(0);
    }

    public function handle() {        
    }
}