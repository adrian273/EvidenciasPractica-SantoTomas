<?php
/**
 * @package tag class
 */

class clsTag {

	var $indent = 0; // Sets indenting for generated HTML code
	var $noprint = false; // Sets the printer of specific $xml

	var $outputCode = "";
	var $tag="";
	var $child = array();
	var $indenting;

	// Constructor method
	function clsTag($tag,$attr="",$indenting="1") {

		$this->tag=$tag;
		$this->indenting=$indenting;
		$theAttr="";
		$attrArr=array();
		if (is_array($attr)) {
			while (list($key,$value)=each($attr)){
                if (strlen($value) > 0) {
                    $attrArr[]="$key=\"$value\"";
                } else {
                    //$attrArr[]="$key";
                    $attrArr[]="$key=\"$value\"";
                }
			}
			$theAttr=implode(" ",$attrArr);
            //echo "<p>" . $theAttr . "</p>";
		} else {
			if (!empty($attr)) {
				$attr=explode(";",$attr);
				for ($k=0; $k<count($attr); $k++) {
					list($key,$value)=explode("=",$attr[$k]);
                    if (strlen($value) > 0) {
                        $attrArr[]="$key=\"$value\"";
                    } else {
                        //$attrArr[]="$key";
                        $attrArr[]="$key=\"$value\"";
                    }
				}
				$theAttr=" ".implode(" ",$attrArr);
			}
		}
		if (!empty($theAttr)) $theAttr=" $theAttr";
		$this->attr=$theAttr;
		if (!empty($this->tag))
			//$this->outputCode.="<$tag$theAttr>\n";
			$this->outputCode.="<$tag$theAttr>";
	}

	function clsTag2($tag,$attr="",$indenting="1") {

		$this->tag=$tag;
		$this->indenting=$indenting;
		$theAttr="";
		$attrArr=array();
		if (is_array($attr)) {
			while (list($key,$value)=each($attr)){
                if (strlen($value) > 0) {
                    $attrArr[]="$key=\"$value\"";
                } else {
                    $attrArr[]="$key";
                }
			}
			$theAttr=implode(" ",$attrArr);
            //echo "<p>" . $theAttr . "</p>";
		} else {
			if (!empty($attr)) {
				$attr=explode(";",$attr);
				for ($k=0; $k<count($attr); $k++) {
					list($key,$value)=explode("=",$attr[$k]);
                    if (strlen($value) > 0) {
                        $attrArr[]="$key=\"$value\"";
                    } else {
                        $attrArr[]="$key";
                    }
				}
				$theAttr=" ".implode(" ",$attrArr);
			}
		}
		if (!empty($theAttr)) $theAttr=" $theAttr";
		$this->attr=$theAttr;
		if (!empty($this->tag))
			$this->outputCode.="<$tag$theAttr><![CDATA[\n";
	}

	// This (private) method uses the strpos function to the first occurrence of the substring $where in $what 
	// Returns a boolean
	function InString($what,$where) {
		$pos = strpos ($where, $what);
		if (is_string($pos) && !$pos) {
		    return false;
		}
		return true;
	}
		
	
    // This (public) method returns an array containing all the attributes for a tag, one element per attribute in the form $arr["attribute"]=value. 
    // The "attribute" index is converted to lowercase to avoid the superpositioning of attributes.
	function GetAttributes($addAttr,$attr="") {
	
		/*
			This function returns an array containing all the attributes for a tag, one element per attribute
			in the form $arr["attribute"]=value. The "attribute" index is lowercased to avoid the superposition
			of attributes.
		*/
	
		$theRetVal=array();
		
		if (!empty($attr)) {
			if (is_array($attr)){ // the attributes were given as an array
				while (list($k,$v)=each($attr)){
					if (instring(substr($k,0,1),"0123456789")) { // If is a numeric key (e.g. $attr[0])
						list($k,$v)=explode("=",$v); // assume that the array element is in the form "key=value"
						$theKey=strtolower($k);
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);							
						$theRetVal[$theKey]=$_v;
					} else { // if is an alphanumeric key (e.g. $attr["name"])
						$theKey=strtolower($k);
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);							
						$theRetVal[$theKey]=$_v;
					}
				}
			} else { // the attributes were given as a string in the form "key1=value1;key2=value2;....;keyN=valueN"
				$attr2=explode(";",$attr);
				while (list($k,$v)=each($attr2)){
					if ($this->instring(substr($k,0,1),"0123456789")) { // If is a numeric key (e.g. $attr[0])
						list($k,$v)=explode("=",$v); // assume that the array element is in the form "key=value"
						$theKey=strtolower($k); 
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);							
						$theRetVal[$theKey]=$_v;
					} else { // if is an alphanumeric key (e.g. $attr["name"])
						$theKey=strtolower($k); 
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);	 						
						$theRetVal[$theKey]=$_v;
					}
				}
			}
		}
			
		if (!empty($addAttr)) {
			if (is_array($addAttr)){ // the new attributes were given as an array
				while (list($k,$v)=each($addAttr)){
					if ($this->instring(substr($k,0,1),"0123456789")) { // If is a numeric key (e.g. $attr[0])
						list($k,$v)=explode("=",$v); // assume that the array element is in the form "key=value"
						$theKey=strtolower($k); 
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);						
						$theRetVal[$theKey]=$_v;
					} else { // if is an alphanumeric key (e.g. $attr["name"])
						$theKey=strtolower($k); 
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);							
						$theRetVal[$theKey]=$_v;
					}
				}
			} else { // the new attributes were given as a string in the form "key1=value1;key2=value2;....;keyN=valueN"
				$attr2=explode(";",$addAttr);
				while (list($k,$v)=each($attr2)){
					if ($this->instring(substr($k,0,1),"0123456789")) { // If is a numeric key (e.g. $attr[0])
						list($k,$v)=explode("=",$v); // assume that the array element is in the form "key=value"
						// if (substr($k,0,2)!="on") 
							$theKey=strtolower($k); 
						// else $theKey=$k;
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);							
						$theRetVal[$theKey]=$_v;
					} else { // if is an alphanumeric key (e.g. $attr["name"])
						$theKey=strtolower($k); 
						$_v=str_replace(":::",";",$v);
						$_v=str_replace(":!:","=",$_v);
						$_v=str_replace(":1:","__",$_v);
						$_v=str_replace(":2:","__",$_v);							
						$theRetVal[$theKey]=$_v;
					}
				}
			}
		}
	
		return $theRetVal;
	}
	
	// This (private) method builds the HTML output string
	function getOutputCode() {
			
		$_bckOutputCode=$this->outputCode;
		$this->outputCode="";
		for ($in=0;$in<$this->indent; $in++) {
			$this->outputCode.="";
		}
		$this->indent++;
		$this->outputCode.=$_bckOutputCode;

		for ($childIdx=0; $childIdx<count($this->child); $childIdx++) {
			if (is_object($this->child[$childIdx])) {
				$theChild=$this->child[$childIdx];
				if (is_object($theChild)) {
					$theChild->indent=$this->indent;
					$this->outputCode.=$theChild->getOutputCode();
				} else {
					$this->outputCode.=$theChild;
				}
			}
		}
		$ind=$this->indent;
		$ind--;
		$this->indent=$ind;
		$this->CloseTag();
		return $this->outputCode;
	}
	
	// This (public) method emits the HTML string with an echo  
	function Generate($noprint=false) {
		
	    if ($noprint) {
	   	   return $this->getOutputCode();
	    } else {
	   	   echo $this->getOutputCode();
	    }
	   
	}
	
	// This (public) method adds a child node to an existing HTML object
	// Usage:
	
	/*	   $myTable = fnTable("width=100%");
	       $myTable->append(fnRow("",array(
                        		fnCell("","","width=100%",array(
                                    fnAnchor("top","&nbsp;"),
                                    fnHeading("1",$myTitle)
                        	 ))
                     ));
     */
	
	function append($node) {
		if (!is_array($node)) {
			$this->child[]=$node;
		} else if (is_array($node)) {
			for ($childIdx=0; $childIdx<count($node); $childIdx++)
				$this->child[]=$node[$childIdx];
		}
	}
	
	// This (public) method issues indenting instructions for the type of tag are we building
	// $tag is any valid HTML 3.2/4.0 or XHTML 1.x tag
	// $attr is either a semi-colon-separated key=value string or an associative array 
	// $indenting has two possible values: "1" - add spaces for indenting, "0" - do not indent
	
	function HookIt($tag,$attr="",$indenting="1"){
		$this->clsTag($tag,$attr,$indenting);
	}
	
	function HookIt2($tag,$attr="",$indenting="1"){
		$this->clsTag2($tag,$attr,$indenting);
	}
		
	// This (private) method builds the closing portion of a tag (e.g., </td>)
	function CloseTag() {
		if (!empty($this->tag)) {
			for ($in=0;$in<$this->indent; $in++) {
				$this->outputCode.="";
			}
			if (!empty($this->tag))
			$this->outputCode.="</".$this->tag.">";
		}
		//$this->outputCode.="\n";
		$this->indent--;
	}
	
	function __destruct () {
		
		//$this->Generate();
		
	}
	
}

# ------------------- BEGIN SUBCLASSES -------------------

class clsHTMLDocument extends clsTag {

	function clsHTMLDocument() {
		$this->HookIt("HTML","","1");
	}
	
}

class clsXMLDocument extends clsTag {
	
	function clsXMLDocument($root_name, $auto_destruct, $attr) {
		$this->auto_destruct = $auto_destruct;
		$this->HookIt($root_name, $attr);
	}
	
	function clsTag($tag,$attr="",$indenting="1") {

		$this->tag=$tag;
		$this->indenting=$indenting;
		$theAttr="";
		$attrArr=array();
		if (is_array($attr)) {
			while (list($key,$value)=each($attr)){
                if (strlen($value) > 0) {
                    $attrArr[]="$key=\"$value\"";
                } else {
                    $attrArr[]="$key";
                }
			}
			$theAttr=implode(" ",$attrArr);
            //echo "<p>" . $theAttr . "</p>";
		} else {
			if (!empty($attr)) {
				$attr=explode(";",$attr);
				for ($k=0; $k<count($attr); $k++) {
					list($key,$value)=explode("=",$attr[$k]);
                    if (strlen($value) > 0) {
                        $attrArr[]="$key=\"$value\"";
                    } else {
                        $attrArr[]="$key";
                    }
				}
				$theAttr=" ".implode(" ",$attrArr);
			}
		}
		if (!empty($theAttr)) $theAttr=" $theAttr";
		$this->attr=$theAttr;
		if (!empty($this->tag))
			$this->outputCode.="<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<$tag$theAttr>\n";
	}
	
	function __destruct () {
		
		if ($this->auto_destruct) {
			$this->Generate();
		}
	}
	
} 

class clsHeaderSection extends clsTag {

	function clsHeaderSection() {
		$this->HookIt("head","","1");
	}
	
}

class clsTitle extends clsTag {
	function clsTitle($theTitle) {
		$this->HookIt("title","","1");
		$this->append(fnShowText($theTitle));
	}

}

class clsMeta extends clsTag {
	
	function clsMeta($attr) {
		$newAttrs="http-equiv=Content-Type;content=text/html;charset=iso-8859-1";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("meta",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
	
}


class clsStyleSheet extends clsTag {

	function clsStyleSheet($stylesheet,$attr="") {
		$newAttrs="rel=stylesheet;type=text/css;href=$stylesheet";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("link",$attr);
	}

	function CloseTag() {
		$this->indent--;
	}

}


class clsJavaScript extends clsTag {

    function clsJavaScript($source,$attr="") {
        $newAttrs="language=Javascript;type=text/javascript;src=$source";
        $attr=$this->GetAttributes($attr,$newAttrs);
        $this->HookIt("script",$attr);
    }
}


class clsBody extends clsTag {

	function clsBody($attr="") {
		$this->HookIt("body",$attr,"1");
	}
	
}

class clsNewTag extends clsTag {

	function clsNewTag($name, $attr="") {
		$attr=$this->GetAttributes($attr);
		$this->HookIt2($name,$attr,"1");
	}
	
	function CloseTag() {
		if (!empty($this->tag)) {
			for ($in=0;$in<$this->indent; $in++) {
				$this->outputCode.="    ";
			}
			if (!empty($this->tag))
			$this->outputCode.="]]></".$this->tag.">";
		}
		$this->outputCode.="\n";
		$this->indent--;
	}	
	
}


# added 2004-02-16
# author:  Chris Phillipson -- phillipsonc@calpine.com
class clsDiv extends clsTag {
	
	function clsDiv($attr="") {
		$attr=$this->GetAttributes($attr);
		$this->HookIt("div",$attr,"1");
	}
}

class clsXMLNode extends clsTag {
	
	function clsXMLNode($attr="") {
		//$attr=$this->GetAttributes($attr);
		$this->HookIt($attr,"","1");
	}
}

class clsAction extends clsTag {
	
	function clsAction($name, $attr="") {
		$attr=$this->GetAttributes($attr);
		$this->HookIt($name,$attr,"1");
	}
}

class clsLabel extends clsTag {
	
	function clsLabel($attr="") {
		$attr=$this->GetAttributes($attr);
		$this->HookIt("label",$attr,"1");
	}
}

class clsJavaScriptinLine extends clsTag {
	
	function clsJavaScriptinLine($attr="") {
		$attr=$this->GetAttributes($attr);
		$this->HookIt("script",$attr,"1");
	}
}


class clsTable extends clsTag {

	function clsTable($attr="") {
        #$newAttrs="border=0;cellpadding=0;cellspacing=0";
        $newAttrs = "";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("table",$attr);
	}
}


class clsRow extends clsTag {
	function clsRow($attr="") {
        $newAttrs="";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("tr",$attr);
	}
}


class clsCell extends clsTag {
	function clsCell($text="",$textFont="",$attr="") {
		$newAttrs = "";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("td",$attr);
		if (!empty($text)) {
			$this->append(fnShowText($text,$textFont));
		}
	}

}


class clsFont extends clsTag {

	function clsFont($text="",$attr="") {
		$retVal="face=Verdana, Helvetica, Arial, Sans-Serif;size=2;color=#000000";
		$attr=$this->GetAttributes($attr,$retVal);
		$this->HookIt("font",$attr,"0");
		if (!empty($text)) {
			$this->append(fnShowText($text));
		}
	}

}


class clsShowText extends clsTag {

	function clsShowText($theText,$attr="") {
		$this->HookIt("",$attr,"0");
		$this->outputCode.=$theText;
	}

}


class clsImg extends clsTag {

	function clsImg($src,$id="",$attr="") {
		$retVal="";
		if (!empty($id)) $retVal.=";id=$id";
		$attr=$this->GetAttributes($attr,$retVal);
		$attr["src"]=$src;
		$this->HookIt("img",$attr,"0");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}

class clsEmbed extends clsTag {

	function clsEmbed($src,$id="",$attr="") {
		$retVal="type=image/svg+xml";
		if (!empty($id)) $retVal.=";id=$id";
		$attr=$this->GetAttributes($attr,$retVal);
		$attr["src"]=$src;
		$this->HookIt("embed",$attr,"0");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}


class clsLink extends clsTag {

	function clsLink($target,$text="",$textFont="",$attr="") {
		$newAttrs = "";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$attr["href"]=$target;
		$this->HookIt("a",$attr,"1");
        /*
        if (!empty($text)) {
			$this->append(fnFont($text,$textFont));
		}
        */
        if (!empty($text)) {
            $this->append(fnShowText($text,""));
        }
	}

}


# added 2004-02-08
# author:  Chris Phillipson -- phillipsonc@calpine.com
class clsAnchor extends clsTag {

    function clsAnchor($target,$text="",$textFont="",$attr="") {
        $newAttrs = "";
    	$attr=$this->GetAttributes($attr,$newAttrs);
        $attr["name"]=$target;
        $this->HookIt("a",$attr,"1");
        /*
        if (!empty($text)) {
            $this->append(fnFont($text,$textFont));
        }
        */
        if (!empty($text)) {
            $this->append(fnShowText($text,""));
        }
    }

}


class clsParagraph extends clsTag {

	function clsParagraph($text="",$textFont="",$id="",$attr="") {
		//$retVal="align=justify";
        $retVal="";
		if (!empty($id)) $retVal.=";id=$id";
		$attr=$this->GetAttributes($attr,$retVal);
		$this->HookIt("p",$attr,"0");
		//$this->append(fnFont("$text",$textFont));
        $this->append(fnShowText($text,""));
	}

}


# added 2004-02-08
# author:  Chris Phillipson -- phillipsonc@calpine.com
class clsHeading extends clsTag {

    function clsHeading($size="",$text="",$textFont="",$id="",$attr="") {
        //$retVal="align=justify";
        $retVal = "";
        if (!empty($id)) $retVal.=";id=$id";
        $attr=$this->GetAttributes($attr,$retVal);
        $this->HookIt("h" . $size,$attr,"0");
        //$this->append(fnFont("$text",$textFont));
        $this->append(fnShowText($text,""));
    }

}


class clsForm extends clsTag {

	function clsForm($action="",$name="",$hiddens="",$attr=""){
		$newAttrs="method=post";
        /*
        if (!empty($name)) $newAttrs.=";name=$name";
		if (!empty($action)) $newAttrs.=";action=$action";
        $attr=$this->GetAttributes($attr,$newAttrs);
		$attr["style"]="margin-top: 0px; margin-bottom: 0px";
        */
        $attr=$this->GetAttributes($attr,$newAttrs);

        if (!empty($name)) $attr["id"] = $name;
        if (!empty($action)) $attr["action"] = $action;
		$this->HookIt("form",$attr,"1");
		
		if (!empty($hiddens)) {
			$theHiddenValues=explode(";",$hiddens);
			for ($h=0; $h<count($theHiddenValues); $h++) {
				$parts=explode("=",$theHiddenValues[$h]);
				$this->append(new clsHidden($parts[0],$parts[1]));
			}
		}
	}
}


class clsOption extends clsTag {

	function clsOption($value,$text,$sel="",$attr="") {
		$newAttrs="value=$value";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("option $sel",$attr,"1");
		$this->outputCode.=$text;
	}
	
}


class clsSelect extends clsTag {

	function clsSelect($name,$values="",$selValue="",$attr="") {
	
		$attr=$this->GetAttributes($attr,"name=$name");
		$this->HookIt("select",$attr);
		if (!empty($values)){
			if (is_array($values)){
				reset($values);
				while (list($key,$option)=each($values)) {
					if (trim(strtolower($key))==trim(strtolower($selValue))) $sel="selected"; else $sel="";
					$this->append(new clsOption($key,$option,$sel));
				}
			}
		}
	}
}


class clsTextArea extends clsTag {

	function clsTextArea($name,$value="",$attr="") {
	
		$attr=$this->GetAttributes($attr,"name=$name");
		$this->HookIt("textarea",$attr);
	}
}


class clsInput extends clsTag {

	function clsInput($name,$value="",$size="",$maxlength="",$attr="") {
		$newAttrs="name=$name;type=text;value=$value";
		if (!empty($size)) $newAttrs.=";size=$size"; /* else $newAttrs.=";size=10"; */
		if (!empty($maxlength)) $newAttrs.=";maxlength=$maxlength";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input",$attr,"1");
	}

	function CloseTag() {
		$this->indent--;
	}
}

# added 2004-02-12
# author:  Chris Phillipson -- phillipsonc@calpine.com
class clsFileSelect extends clsTag {

    function clsFileSelect($name,$size="",$attr="") {
        $newAttrs="name=$name;type=file";
        if (!empty($size)) $newAttrs.=";size=$size";
        $attr=$this->GetAttributes($attr,$newAttrs);
        $this->HookIt("input",$attr,"1");
    }

    function CloseTag() {
        $this->indent--;
    }
}


class clsHidden extends clsTag {

	function clsHidden($name,$value="",$attr="") {
		$newAttrs="name=$name;type=hidden;value=$value";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}


class clsRadioButton extends clsTag {

	function clsRadioButton($name,$value="",$check="",$attr="") {
		$newAttrs="name=$name;type=radio;value=$value";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input $check",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}


class clsCheckBox extends clsTag {

	function clsCheckBox($name,$value="",$check="",$attr="") {
		$newAttrs="name=$name;type=checkbox;value=$value";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input $check",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}


class clsPassword extends clsTag {

	function clsPassword($name,$value="",$size="",$maxlength="",$attr="") {
		$newAttrs="name=$name;type=password;value=$value";
		if (!empty($size)) $newAttrs.=";size=$size"; else $newAttrs.=";size=10";
		if (!empty($maxlength)) $newAttrs.=";maxlength=$maxlength";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}


class clsButton extends clsTag {

	function clsButton($name,$value="",$attr="") {
		$newAttrs="name=$name;type=button;value=$value";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}

# added 2004-02-08
# author:  Chris Phillipson -- phillipsonc@calpine.com
class clsReset extends clsTag {

    function clsReset($name,$value="",$attr="") {
        $newAttrs="name=$name;type=reset;value=$value";
        $attr=$this->GetAttributes($attr,$newAttrs);
        $this->HookIt("input",$attr,"1");
    }

    function CloseTag() {
        $this->indent--;
    }
}


class clsSubmit extends clsTag {

	function clsSubmit($name,$value="",$attr="") {
		$newAttrs="name=$name;type=submit;value=$value";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input",$attr,"1");
	}

	function CloseTag() {
		$this->indent--;
	}
}


class clsImgSubmit extends clsTag {

	function clsImgSubmit($name,$src,$attr="") {
		$newAttrs="name=$name;type=image;src=$src";
		$attr=$this->GetAttributes($attr,$newAttrs);
		$this->HookIt("input",$attr,"1");
	}
	
	function CloseTag() {
		$this->indent--;
	}
}

# ------------------- END SUBCLASSES -------------------


/*

Functions to emulate HTML Tags, and build a web page w/o worrying about
tags, nor indenting...
-
Funciones específicas para el armado de una página web.
Emulan los tags de HTML, y permiten la anidación de tags.

*/

/**
 * fnHtml($child="")
 */
function fnHtml($child="") {
	
	$theObject=new clsHTMLDocument();
	if ($child) $theObject->append($child);
	return $theObject;
	
}

/**
 * fnXML($specific=true)
 */
function fnXML($root_name, $auto_destruct, $attr) {
	
	$theObject=new clsXMLDocument($root_name, $auto_destruct, $attr);
	return $theObject;
	
}

/**
 * fnHeader($child="")
 */
function fnHeader($child="") {

	$theObject=new clsHeaderSection();
	if ($child) $theObject->append($child);
	return $theObject;

}

/**
 * fnTitle($theTitle="")
 */
function fnTitle($theTitle="") {

	$theObject=new clsTitle($theTitle);
	return $theObject;
	
}

/**
 * fnMeta($attr)
 */
function fnMeta($attr) {
	$theObject=new clsMeta($attr);
	return $theObject;
}

/**
 * fnStyleSheet($stylesheet,$attr="")
 */
function fnStyleSheet($stylesheet,$attr="") {
	$theObject=new clsStyleSheet($stylesheet,$attr);
	return $theObject;
}

/**
 * fnJavaScript($source,$attr="")
 */
function fnJavaScript($source,$attr="") {
    $theObject=new clsJavaScript($source,$attr);
    return $theObject;
}

/**
 * fnJavaScriptinLine($source,$attr="")
 */
function fnJavaScriptinLine($source,$attr="") {
    $theObject=new clsJavaScriptinLine($source);
    return $theObject;
}

/**
 * fnBody($attr="",$child="")
 */
function fnBody($attr="",$child="") {
	$theObject=new clsBody($attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnNewTag($attr="",$child="")
 */
function fnNewTag($name, $attr="", $child=null) {
	$theObject=new clsNewTag($name, $attr);
	
	if (!is_object($child)) { $child = fnShowText($child); }
	
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnDiv($attr="",$child="")
 */
function fnDiv($attr="",$child="") {
	$theObject=new clsDiv($attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnDiv($attr="",$child="")
 */
function fnXMLNode($attr="",$child="") {
	settype($child, "string");
	$theObject=new clsXMLNode($attr);
	if ($child) $theObject->append(fnShowText($child));
	return $theObject;
}

/**
 * fnLabel($for="",$child="")
 */
function fnLabel($for="",$child="") {
	$for = "for=" . $for;
	$theObject=new clsLabel($for);
	if ($child) $theObject->append(fnShowText($child));
	return $theObject;
}

/**
 * fnTable($attr="",$child="")
 */
function fnTable($attr="",$child="") {
	if (!$attr) { $attr = "width=100%;border=0;cellspacing=0;cellpadding=1"; }
	$theObject=new clsTable($attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnRow($attr="",$child="")
 */
function fnRow($attr="",$child="") {
	$theObject=new clsRow($attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnCell($text="",$textFont="",$attr="",$child="")
 */
function fnCell($text="",$textFont="",$attr="",$child="") {
	$theObject=new clsCell($text,$textFont,$attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnFont($text="",$attr="",$child="")
 */
function fnFont($text="",$attr="",$child="") {
	$theObject=new clsFont($text,$attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnHeading($size="",$text="",$textFont="",$attr="",$child="")
 */
function fnHeading($size="",$text="",$textFont="",$attr="",$child="") {
    $theObject=new clsHeading($size,$text,$textFont,$attr);
    if ($child) $theObject->append($child);
    return $theObject;
}

/**
 * fnParagraph($text="",$textFont="",$id="",$attr="",$child="")
 */
function fnParagraph($text="",$textFont="",$id="",$attr="",$child="") {
	$theObject=new clsParagraph($text,$textFont,$id,$attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnShowText($theText,$attr="")
 */
function fnShowText($theText,$attr="") {
//	echo $theText . "<br>";
	$theObject=new clsShowText($theText,$attr);
	return $theObject;
}

/**
 * fnLineBreak()
 */
function fnLineBreak() {
	$theObject=new clsShowText("<br />","");
	return $theObject;
}	

/**
 * fnImg($src,$id="",$attr="")
 */
function fnImg($src,$id="",$attr="") {
	$theObject=new clsImg($src,$id,$attr);
	return $theObject;
}

/**
 * fnEmbed($src,$id="",$attr="")
 */
function fnEmbed($src,$id="",$attr="") {
	$theObject=new clsEmbed($src,$id,$attr);
	return $theObject;
}

/**
 * fnAnchor($target="",$text="",$textFont="",$attr="",$child="")
 */
function fnAnchor($target="",$text="",$textFont="",$attr="",$child="") {
    $theObject=new clsAnchor($target,$text,$textFont,$attr);
    if ($child) $theObject->append($child);
    return $theObject;
}

/**
 * fnLink($target="",$text="",$textFont="",$attr="",$child="")
 */
function fnLink($target="",$text="",$textFont="",$attr="",$child="") {
	$theObject=new clsLink($target,$text,$textFont,$attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnForm($action="",$name="",$hiddens="",$attr="",$child="")
 */
function fnForm($action="",$name="",$hiddens="",$attr="",$child=""){
	$theObject=new clsForm($action,$name,$hiddens,$attr);
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnOption($value,$text,$sel="",$attr="")
 */
function fnOption($value,$text,$sel="",$attr="") {
	$theObject=new clsOption($value,$text,$sel,$attr);
	return $theObject;
}

/**
 * fnSelect($name,$values="",$selValue="",$attr="")
 */
function fnSelect($name,$values="",$selValue="",$attr="") {
	$theObject=new clsSelect($name,$values,$selValue,$attr);
	return $theObject;
}

function fnTextArea($name,$value="",$attr="") {
	$theObject=new clsTextArea($name,$value,$attr);
	if ($value) $theObject->append(fnShowText($value));
	return $theObject;
}

/**
 * fnInput($name,$value="",$size="",$maxlength="",$attr="")
 */
function fnInput($name,$value="",$size="",$maxlength="",$attr="") {
	$theObject=new clsInput($name,$value,$size,$maxlength,$attr);
	return $theObject;
}

/**
 * fnFileSelect($name,$size="40",$attr="")
 */
function fnFileSelect($name,$size="40",$attr="") {
    $theObject=new clsFileSelect($name,$size,$attr);
    return $theObject;
}

/**
 * fnHidden($name,$value="",$attr="")
 */
function fnHidden($name,$value="",$attr="") {
	$theObject=new clsHidden($name,$value,$attr);
	return $theObject;
}
/**
 * fnRadioButton($name,$value="",$check="",$attr="")
 */
function fnRadioButton($name,$value="",$check="",$attr="") {
	$theObject=new clsRadioButton($name,$value,$check,$attr);
	return $theObject;
}

/**
 * fnCheckBox($name,$value="",$check="",$attr="")
 */
function fnCheckBox($name,$value="",$check="",$attr="") {
	$theObject=new clsCheckBox($name,$value,$check,$attr);
	return $theObject;
}

/**
 * fnPassword($name,$value="",$size="",$maxlength="",$attr="")
 */
function fnPassword($name,$value="",$size="",$maxlength="",$attr="") {
	$theObject=new clsPassword($name,$value,$size,$maxlength,$attr);
	return $theObject;
}

/**
 * fnButton($name,$value="",$attr="")
 */
function fnButton($name,$value="",$attr="") {
	$theObject=new clsButton($name,$value,$attr);
	return $theObject;
}

/**
 * fnReset($name,$value="",$attr="")
 */
function fnReset($name,$value="",$attr="") {
    $theObject=new clsReset($name,$value,$attr);
    return $theObject;
}

/**
 * fnSubmit($name,$value="",$attr="")
 */
function fnSubmit($name,$value="",$attr="") {
	$theObject=new clsSubmit($name,$value,$attr);
	return $theObject;
}

/**
 * fnImgSubmit($name,$src,$attr="")
 */
function fnImgSubmit($name,$src,$attr="") {
	$theObject=new clsImgSubmit($name,$src,$attr);
	return $theObject;
}

/**
 * fnCenter($child="")
 */
function fnCenter($child="") {
	$theObject=new clsTag("center","","1");
	if ($child) $theObject->append($child);
	return $theObject;
}

/**
 * fnAction($attr="",$child="")
 */
function fnAction($name, $attr="", $child=null) {
	$theObject=new clsAction($name, $attr);
	
	if (!is_object($child)) { $child = fnShowText($child); }
	
	if ($child) $theObject->append($child);
	return $theObject;
}

	 class CI_Xml { 
	 	
	 	var $root_name    = "xml";
	 	var $sendXMLHeader = true;
	 	
		function Div($attr="",$child="") {
			return fnDiv($attr,$child);
		}
		
		function Table($attr="",$child="") {
			return fnTable($attr,$child);
		}
		
		function Row($attr="",$child="") {
			return fnRow($attr,$child);
		}
		
		function Cell($text="",$textFont="",$attr="",$child="") {
			return fnCell($text,$textFont,$attr,$child);
		}
		
		function Option($value,$text,$sel="",$attr="") {
			return fnOption($value, $text, $sel, $attr);
		}		
		
		function sendHeader($set = true) {
			$this->sendXMLHeader = $set;
		}
		
		function Document ($auto_destruct=true, $attr="") {
			
			if($this->sendXMLHeader) {
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				header("Content-type: application/xml");
			}
			
			return fnXML($this->root_name, $auto_destruct, $attr);
			
		}		
		
		function ElementCData ($name, $attr="",$child="") {
			return fnNewTag($name, $attr, $child);
		}
		
		/**
		 * $name, $attr="", $child=""
		 */
		function Element ($name, $attr="", $child="") {
			return fnAction($name, $attr, $child);
		}
		
		function Text($theText,$attr="") {
			return fnShowText($theText, $attr);
		}
		
		function Img ($src, $id="", $attr="") {
			return fnImg($src, $id, $attr);
		}
		
		function CheckBox( $name,$value="",$check="",$attr="") {
			return fnCheckBox($name, $value, $check, $attr);
		}
		
		function Form ($name="",$hiddens="",$attr="",$child="", $action="") {
			return fnForm($action, $name, $hiddens, $attr, $child);
		}
		
		function Input ($name, $value="", $size="", $maxlength="", $attr="") {
			return fnInput($name, $value, $size, $maxlength, $attr);
		}
		
		function Select($name, $values="", $selValue="", $attr="") {
			return fnSelect($name, $values, $selValue, $attr);
		}
		
		function TextArea($name,$value="",$attr="") {
			return fnTextArea($name, $value, $attr);
		}
		
	 }

?>