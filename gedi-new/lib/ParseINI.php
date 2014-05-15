<?php
/**
 * ParseINI class
 *
 * This class represents an INI configuation file.
 * @author Patrick Wu
 * @package gedi
 * @version 1.0
 */
Class ParseINI{
	/**
     * The array of the GEDI INI.
     *
     * @since 1.0
     * @var array
     * @access protected
     */
	protected  $ini_array = array();
	/**
     * The mapping array of the GEDI INI.
     *
     * @since 1.0
     * @var array
     * @access protected
     */
	protected  $mapping_ini = array();
    /**
     * The $key of the GEDI INI array.
     *
     * @since 1.0
     * @var string
     * @access protected
     */
	protected  $key = "";
	 /**
     * Class constructor
     *
     * This function is used to instantiate the class. The instatiation is done
     * <br/>Example:
     * <code>
     * $ini = new ParseINI();              
     * </code>
     * @since 1.0
     * @access public
     */
    function __construct(){
	    $this->ini_array = parse_ini_file(dirname(dirname(__FILE__))."/gedi.ini");
	    if($this->ini_array){
			$this->mapping_ini['-gediip'] = $this->ini_array['GEDI_IP'];
			$this->mapping_ini['-cn'] = $this->ini_array['CN'];
			$this->mapping_ini['-pass'] = $this->ini_array['PASSWD'];
			$ftp_info_str = "";
			if($this->ini_array['FTP_IP']){
				$ftp_info_str.= $this->ini_array['FTP_IP'];
				if($this->ini_array['FTP_USER'] && $this->ini_array['FTP_PASSWD']){
					$ftp_info_str.=",".$this->ini_array['FTP_USER'].",".$this->ini_array['FTP_PASSWD'];
					if($this->ini_array['FTP_SUBDIR']){
						$ftp_info_str.=",".$this->ini_array['FTP_SUBDIR'];
					}
				}
			}
			$this->mapping_ini['-ftp'] = $ftp_info_str;
			$this->mapping_ini['PROXY_IP'] = $this->ini_array['PROXY_IP'];
			$this->mapping_ini['PROXY_PORT'] = $this->ini_array['PROXY_PORT'];
			$this->mapping_ini['CONFIG_FILE'] = $this->ini_array['CONFIG_FILE'];
			$this->mapping_ini['GEDINIPATH'] = $this->ini_array['GEDINIPATH'];
			
	    }
	}
	 /**
     * Function get()
     *
     * This function is used to get ini array or key value
     * <br/>Example:
     * <code>
     * $ini = new ParseINI();    
     * $ret = $ini->get();//return all array 
	 * $ret = $ini->get('-gediip');//return "-gediip" value
     * </code>
     *
     * @return string or Array
     * @since 1.0
     * @access public
     */
	public function get($key=''){
	    if($key){
			return $this->mapping_ini[$key];
	    } else {
			return $this->mapping_ini;
	    }
	}
}
/*
$ini = new ParseINI();
echo "<pre>";
var_dump($ini->get());
var_dump($ini->get('-gediip'));
echo "</pre>";
*/
?>