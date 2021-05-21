<?php

namespace App\Jobs;

use App\Lib\XmlPrepare;
use Illuminate\Bus\Queueable;
use App\Models\Faturamento\Nfe;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class receiveNfes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $tries = 3;
    protected $nfe;
    private $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, Nfe $nfe)
    {
        $this->nfe  = $nfe->withoutRelations();
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $nfe) {
            $xmlPrepare = new XmlPrepare($nfe->xml, $nfe->access_key);
            if (!$this->nfe->findByChnfe($xmlPrepare->chnfe)) {
                $this->nfe->create([
                    "chnfe" => $xmlPrepare->chnfe,
                    "vnf"   => $xmlPrepare->vnf,
                ]);
            }
        }
    }
}
