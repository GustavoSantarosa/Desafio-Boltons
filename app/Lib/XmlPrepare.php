<?php

namespace App\Lib;

class XmlPrepare
{
    public $xml;
    public $chnfe;
    public $vnf;

    public function __construct($xml, $accessKey = null)
    {
        $xml = json_decode(json_encode(simplexml_load_string(base64_decode($xml))));
        if (!isset($xml->NFe)) {
            $xml->NFe = $xml;
        }

        $this->xml = $xml;
        $this->chnfe = $accessKey;

        $this->infNfe($xml->NFe->infNFe);
        $this->protNfe();
    }

    private function infNfe($infNFe = null)
    {
        $this->vnf = isset($infNFe->total) ? $infNFe->total->ICMSTot->vNF : 0;
    }

    private function protNfe()
    {
        $this->chnfe = isset($this->xml->protNFe) ? $this->xml->protNFe->infProt->chNFe : $this->chnfe;
    }
}
