<?php

namespace App\Jobs;

use stdClass;
use Illuminate\Bus\Queueable;
use App\Models\Faturamento\Nfe;
use App\Repository\Arquivei\NfeApi;
use Illuminate\Support\Facades\Log;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

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
        $this->nfe = $nfe->withoutRelations();
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
            if (!$this->nfe->findByChnfe($nfe->access_key)) {
                $xml = simplexml_load_string(base64_decode($nfe->xml));

                $this->nfe->create([
                    "chnfe" => $nfe->access_key,
                    "vnf"   => isset($xml->NFe->infNFe->total) ? $xml->NFe->infNFe->total->ICMSTot->vNF : 0,
                ]);
            }
        }
    }
}
