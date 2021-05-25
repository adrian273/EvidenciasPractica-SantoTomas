<?php

class Xml extends DOMDocument {

	public $root_name = "xml";

	public function __construct($version = '1.0', $encoding = 'ISO-8859-1')
	{
		parent::__construct($version, $encoding);
	}

	public function Document() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Content-type: application/xml");
		return $this->createElement($this->root_name , "");
	}

	function Element( $type, $params=NULL, $content=NULL)
    {
        $tempEl = $this->createElement($type, $content);
        $tempEl = $this->createAtribute($tempEl, $params);
		return $tempEl;
	}

	function create($document) {
		$this->appendChild($document);
        echo $this->saveXml();
	}

	function config(){
		$settings = $this->Element("settings");
		$settings->appendChild($this->Element("colwidth", null, "%"));
		return $settings;
	}

	function Option($value, $description) {
		$options = $this->createElement("option", $description);
		$options->setAttribute("value", $value);
		return $options;
	}

	/**
	 * @param el_name element's name <required>
	 * @param params optional
	 * @params $cdata <optional>
	 */
	function ElementCData($el_name, $params, $cdata=NULL) {
		$el_name = $this->createElement($el_name);
		$__cdata = $el_name->ownerDocument->createCDATASection($cdata);
		$el_name->appendChild($__cdata);

		$el_name = $this->createAtribute($el_name, $params);

		return $el_name;
	}

	function createAtribute($el_name, $params) {
		if(gettype($params) == "string" && strlen($params) > 0)
        {
            $attributesCollection = explode(";", $params);
            foreach($attributesCollection  as $attribute)
            {
                $keyvalue = explode("=", $attribute);
				// ajax grid request javascript error fixing for xml javascript multiple actions
				// which can be added like "script=function1():::function2():::function3():::"
				$_v=str_replace(":::",";", $keyvalue[1]);
				$_v=str_replace(":!:","=", $_v);
				$_v=str_replace(":1:","__", $_v);
				$_v=str_replace(":2:","__", $_v);
                $el_name->setAttribute($keyvalue[0], $_v);
            }
        }
        if(gettype($params) == "array")
        {
            if(gettype($params[0]) == "array")
            {
                foreach($params as $attribute)
                {
                    $el_name->setAttribute($attribute[0], $attribute[1]);
                }
            } else {
                $el_name->setAttribute($params[0], $params[1]);
            }
		}
		return $el_name;
	}

}
