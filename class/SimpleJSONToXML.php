<?php
/**
 * Class SimpleJSONToXML
 * @author Łukasz Szymczak <szymy87@gmail.com>
 */
class SimpleJSONToXML
{
    private $jsonData;
    private $xmlElement;
    private $jsonString = null; 
    private $jsonArray = null;   
    
    public function __construct($jsonData = null, SimpleXMLElement $xmlElement = null)
    {
        $this->jsonData = $jsonData;
        $this->xmlElement = $xmlElement;
    }
    
    public function getJsonData()
    {
        return $this->jsonData;
    }
    
    public function getJsonString()
    {
        return $this->jsonString;
    }
    
    public function getJsonArray()
    {
        return $this->jsonArray;
    }
    
    public function getXML()
    {
        return $this->xmlElement->asXML();
    }
    
    public function objToStr()
    {
        $this->jsonString = json_encode($this->jsonData);
    }
    
    public function strToArray($toArray = true)
    {
        $this->jsonArray = json_decode($this->jsonString, $toArray);
    }
    
    public function arrayToXML($jsonArray = null, $xmlElement = null)
    {
        if ($jsonArray === null) {
            $jsonArray = $this->jsonArray;
        }
        if ($xmlElement === null) {
            $xmlElement = $this->xmlElement;
        }
        foreach ($jsonArray as $key => $value) {
            if (preg_match("/[0-9]+/", $key)) {
                    $key = "data-$key";
            }
            if (is_array($value)) {
                $xmlElement = $this->xmlElement->addChild($key);
                $this->arrayToXML($value, $xmlElement);
                $xmlElement = $this->xmlElement;
            } else {
                $xmlElement->addChild($key, htmlspecialchars($value));
            }
        }
    }
}
