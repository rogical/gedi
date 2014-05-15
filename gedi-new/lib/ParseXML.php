<?php 
/**
 * XML class
 *
 * This class represents an XML configuation file.
 * @author Patrick WU
 * @package gedi
 * @version 1.0
 */
Class ParseXML{
	/**
     * The array of the GEDI configuation xml file.
     *
     * @since 1.0
     * @var array
     * @access protected
     */
	protected $xml_array = array();
	 /**
     * Class constructor
     *
     * This function is used to instantiate the class. The instatiation is done
     * <br/>Example:
     * <code>
     * $xml = new ParseXML();              
     * </code>
     * @since 1.0
     * @access public
     */
	function __construct(){
		$xml = simplexml_load_file(dirname(dirname(__FILE__))."/configparam.xml");
		$res = $xml->xpath("param");

		$this->xml_array = array();

		if($res){
			foreach($res as $key=>$val){
				$xml_array_key  =  str_replace("</name>","",str_replace("<name>","",$val->name->asXML()));
				if($xml_array_key) {
					$this->xml_array["$xml_array_key"]["def"] = str_replace("</def>","",str_replace("<def>","",$val->def->asXML()));
					$this->xml_array["$xml_array_key"]["balise"] = str_replace("</balise>","",str_replace("<balise>","",$val->balise->asXML()));
				}
			}
		}
	}
	/**
     * Function get()
     *
     * This function is used to get ini array or key value
     * <br/>Example:
     * <code>
     * $xml = new ParseXML();    
     * $ret = $xml->get('reference');//return the array of  key 'reference' value 'def' and 'balise'
	 * $ret = $xml->get('reference','def');//return the key 'reference' value of "def"
     * </code>
     *
     * @return mixed string or Array
     * @since 1.0
     * @access public
     */
	public function get($key='',$name=''){
	   if($key){
			if($name){
				return $this->xml_array[$key][$name];
			}else{
				return $this->xml_array[$key];
			}
	   }else{
			return "";
	   }
	}

}
/*
$xml = new ParseXML();
echo "<pre>";
var_dump($xml->get('reference'));
var_dump($xml->get('reference','def'));
echo "</pre>";
*/
?>