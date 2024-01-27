<?php

namespace App\Message;

class ProcessMepsXmlMessage
{
    private \SimpleXMLElement $xml;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }

    public function getXml(): \SimpleXMLElement
    {
        return $this->xml;
    }
}