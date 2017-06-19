<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\fraudDetectionPatternAnaly;
use DB;
class FrauddetectionPatternanalysis extends Command
{
    protected $signature = 'frauddetectionpatternanalysis:clear';
    protected $description = 'Fraud Detection Pattern Analysis';    
    protected $fraudDetectionPatternAnaly;

    public function __construct(fraudDetectionPatternAnaly $fraudDetectionPatternAnaly) {
        parent::__construct();
        $this->fraudDetectionPatternAnaly = $fraudDetectionPatternAnaly;
        set_time_limit(0);
    }

    public function handle() {
        
    }
}