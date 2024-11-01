<?php
/*
	Plugin Name: WP Development Utilities
    Plugin URI: http://www.fabiocicerchia.it/projects/wp-development-utilities/
   	Description: Extends the template tags with more functions that are very useful.
   	Author: Fabio Cicerchia
   	Version: 1.6
   	License: GPL
   	Author URI: http://www.fabiocicerchia.it
*/

/**
 * Extends the template tags with more functions that are very useful.
 * @author Fabio Cicerchia
 * @package wp_development_utilities
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.6
 */
if (!class_exists('WP_Dev_Utilities')) {
    /**
     * Extends the template tags with more functions that are very useful.
     * @package wp_development_utilities
     * @subpackage classes
     */
	class WP_Dev_Utilities {
	
    	/**
     	 * Is really execute in  wordpress
     	 * @var bool
     	 * @access public
     	 */
    	var $_is_wordpress;
	
    	/**
     	 * Contains the informations of logged user
     	 * @var object
     	 * @access private
     	 */
    	var $_userdata;
	
    	/**
     	 * What is the current version of this script
     	 * @var string
     	 * @access public
     	 */
    	var $version;
	
    	/**
     	 * PHP4 Constructor
     	 * @access public
     	 */
    	function WP_Dev_Utilities() {
        	$this->__construct();
    	}
	
    	/**
     	 * PHP5 Constructor
     	 * @access public
     	 */
    	function __construct() {
        	$this->_is_wordpress = (defined('WPINC') && defined('ABSPATH')) ? true : false;
	
        	if ($this->_is_wordpress) {
            	require_once(ABSPATH . WPINC . '/pluggable.php');
	
            	$this->version = $this->_get_version();
            	$this->_retrieve_user_data();
        	} else {
            	die("ERROR: You don't use this class out of wordpress!");
        	}
    	}
	
    	/**
     	 * A function to print variable only if the user logged as administrator
     	 * @access public
     	 */
    	function _debug() {
        	if ($this->is_administrator()) {
            	$args = func_get_args();
            	$numargs = count($args);
	
            	$exit = isset($num_args[$numargs - 2]) ? $num_args[$numargs - 2] : true;
            	$user_printf = isset($num_args[$numargs - 1]) ? $num_args[$numargs - 1] : false;
	
            	for ($i = 0; $i < ($numargs - 2); $i++) {
                	if ($use_printf) print_f($args[$i]);
                	else var_dump($args[$i]);
            	}
	
            	if ($exit) exit;
        	}
    	}
	
    	/**
     	 * Get the current plugin version
     	 * @access private
     	 * @return string
     	 */
    	function _get_version() {
        	$content = file_get_contents(__FILE__);
        	$regex = '/ersion: (.*)\n/';
        	preg_match($regex, $content, $matches);
	
        	return $matches[1];
    	}
	
    	/**
         * Retrive user data informations only if user is logged in
         * @access private
     	 * @global object $userdata
     	 */
    	function _retrieve_user_data() {
        	global $userdata;
	
           	$this->_userdata = (is_user_logged_in()) ? $userdata : null;
    	}
	
    	/**
     	 * Returns the number of pages of a post
     	 * @access public
     	 * @param int $id
     	 * @return int
     	 */
    	function count_num_pages_post($id) {
        	if (is_int($id)) {
            	$content = $this->get_post_content_from_id($id);
	
            	return count(split('<!--' . 'nextpage-->', $content));
        	} else {
            	return 0;
        	}
    	}
	
    	/**
     	 * Get the current execution time
     	 * @access public
     	 * @param int $precision
     	 * @global float $timestart
     	 * @return float
     	 */
    	function get_current_execution_time($precision = 3) {
        	global $timestart;
	
        	$mtime = explode(' ', microtime());
        	$timeend = $mtime[1] + $mtime[0];
        	$timetotal = $timeend-$timestart;
        	if (function_exists('number_format_i18n')) {
            	$r = number_format_i18n($timetotal, intval($precision));
        	} else {
            	$r = number_format($timetotal, intval($precision));
        	}
	
        	return $r;
    	}
	
    	/**
     	 * Returns the name of the user logged on
     	 * @access public
     	 * @return string
     	 */
    	function get_logged_username() {
        	return (is_user_logged_in()) ? $this->_userdata->user_login : false;
        }

        /**
         * Returns the content of the post from his id
         * @access public
         * @param int $id
         * @global object $wpdb
         * @return string
         */
    	function get_post_content_from_id($id) {
        	global $wpdb;
	
        	if (is_int($id)) {
            	$query = "SELECT `post_content` FROM $wpdb->posts WHERE `ID`=$id LIMIT 1;";
            	$res = $wpdb->get_results($query, ARRAY_A);
                
                return isset($res[0]['post_content']) ? $res[0]['post_content'] : '';
        	} else {
            	return '';
        	}
    	}
	
    	/**
     	 * Return the contents of the post permalink
     	 * @access public
     	 * @param string $permalink
     	 * @return string
     	 */
    	function get_post_content_from_permalink($permalink) {
        	$id = $this->get_post_id_from_permalink($permalink);
	
        	return $this->get_post_content_from_id($id);
    	}
	
    	/**
     	 * Return the id of the post permalink
     	 * @access public
     	 * @param string $permalink
     	 * @global object $wpdb
     	 * @return int
     	 */
    	function get_post_id_from_permalink($permalink) {
        	global $wpdb;
	
        	$permalink_structure = get_option('permalink_structure');
        	$regex = $permalink_structure;
	
        	$rewritecode = array(
                	'%year%'        => 'YEAR(`post_date`)=\'%s\'',
                	'%monthnum%'    => 'MONTH(`post_date`)=\'%s\'',
                	'%day%'         => 'DAYOFMONTH(`post_date`)=\'%s\'',
                	'%hour%'        => 'HOUR(`post_date`)=\'%s\'',
                	'%minute%'      => 'MINUTE(`post_date`)=\'%s\'',
                	'%second%'      => 'SECOND(`post_date`)=\'%s\'',
                	'%postname%'    => '`post_name`=\'%s\'',
                	'%post_id%'     => '`ID`=\'%s\'',
                	'%category%'    => '`ID` IN (%s)',
                	'%author%'      => '`post_author`=\'%s\'',
                	'%pagename%'    => 'post_name`=\'%s\'');
        	$codes = array_keys($rewritecode);
	
        	$token = array();
        	$ordered_codes = explode('%', $permalink_structure);
        	foreach($ordered_codes as $value) {
            	if (in_array("%$value%", $codes)) {
                	$token[] = "%$value%";
            	}
        	}
	
        	foreach($codes as $value) {
            	$regex = str_replace($value, '(.+)', $regex);
        	}
	
        	$regex = '/.*' . $_SERVER['HTTP_HOST'] . str_replace('/', '\/', $regex) . '$/';
        	preg_match_all($regex, $permalink, $matches);
	
        	for ($i = 1; $i < count($matches); $i++) {
            	$values[$token[$i - 1]] = $matches[$i][0];
        	}
	
        	$where = array('');
        	foreach($codes as $value) {
            	if (!isset($values[$value])) continue;
	
            	if ($value == '%category%') {
                	$parent = 0;
                	$posts = array();
                	$values[$value] = explode('/', $values[$value]);
                	foreach($values[$value] as $v) { //USE DISTINCT
                    	$q2 = "SELECT p.ID as id, t.term_id as parent_ti FROM $wpdb->posts p, $wpdb->terms t, $wpdb->term_relationships tr, $wpdb->term_taxonomy tt WHERE tr.object_id=p.ID AND tt.term_taxonomy_id=tr.term_taxonomy_id AND t.term_id=tt.term_id AND t.slug='$v' AND p.post_status='publish' AND tt.parent=$parent;";
                    	$res = $wpdb->get_results($q2, ARRAY_A);
                    	$posts[] = $res;
	
                    	$q2 = "SELECT t.term_id as parent_ti FROM $wpdb->terms t, $wpdb->term_taxonomy tt WHERE t.slug='$v' AND tt.term_id=t.term_id AND tt.parent=$parent LIMIT 1;";
                    	$res = $wpdb->get_results($q2, ARRAY_A);
                    	$parent = $res[0]['parent_ti'];
                	}
	
                	$ids = array();
                	foreach($posts as $v) {
                    	if (is_array($v)) {
                        	foreach ($v as $post) {
                            	$ids[] = $post['id'];
                        	}
                    	}
                	}
                	$values[$value] = implode(', ', $ids);
            	} elseif ($value == '%author%') {
                	$q2 = "SELECT `ID` FROM $wpdb->users WHERE `display_name`='" . $values['%author%'] . "'";
                	$res = $wpdb->get_results($q2, ARRAY_A);
                	$values[$value] = $res[0]['ID'];
            	}
            	$where[] = sprintf($rewritecode[$value], $values[$value]);
        	}
	
        	$query = "SELECT `ID` FROM $wpdb->posts WHERE `post_status`='publish'" . implode(' AND ', $where) . " LIMIT 1;";
        	$res = $wpdb->get_results($query, ARRAY_A);
        	return $res[0]['ID'];
    	}
	
    	/**
     	 * Check if logged user has the administrator privileges
     	 * @access public
     	 * @return bool
     	 */
    	function is_administrator() {
        	return (!is_null($this->_userdata)) ? $this->_userdata->wp_capabilities['administrator'] : false;
    	}
	
    	/**
     	 * Returns the user's browser name
     	 * @access public
     	 * @param bool $shortname
     	 * @param bool $uppercase
     	 * @global bool $is_safari
     	 * @global bool $is_NS4
     	 * @global bool $is_opera
     	 * @global bool $is_macIE
     	 * @global bool $is_winIE
     	 * @global bool $is_gecko
     	 * @global bool $is_lynx
     	 * @global bool $is_IE
     	 * @return string
     	 */
    	function get_web_browser($shortname = false, $uppercase = false) {
        	global $is_safari, $is_NS4, $is_opera, $is_macIE, $is_winIE, $is_gecko, $is_lynx, $is_IE;
	
        	if ($is_macIE || $is_winIE || $is_IE) {
            	$wb = ($shortname) ? 'ie' : 'internet explorer';
        	} elseif ($is_safari) {
            	$wb = 'safari';
        	} elseif ($is_NS4) {
            	$wb = ($shortname) ? 'ns' : 'netscape';
        	} elseif ($is_gecko) {
            	$wb = 'gecko';
        	} elseif ($is_lynx) {
            	$wb = 'lynx';
        	} else {
            	$wb = 'unknown';
        	}
	
        	return ($uppercase) ? strtoupper($wb) : $wb;
    	}
	
    	/**
     	 * Returns the web server is use
     	 * @access public
     	 * @param bool $uppercase
     	 * @global bool $is_apache
     	 * @global bool $is_IIS
     	 * @return string
     	 */
    	function get_web_server($uppercase = false) {
        	global $is_apache, $is_IIS;
	
        	if ($is_apache) {
            	$ws = 'apache';
        	} elseif ($is_IIS) {
            	$ws = 'iis';
        	} else {
            	$wb = 'unknown';
        	}
	
        	return ($uppercase) ? strtoupper($ws) : $ws;
    	}
	
    	/**
     	 * Get the current wordpress version
     	 * @access public
     	 * @global string $wp_version
     	 * @return string
     	 */
    	function get_wp_version() {
        	global $wp_version;
	
        	return $wp_version;
    	}
	
    	/**
     	 * Get the tag slug from tag title
     	 * @access public
         * @params string title
     	 * @global object $wpdb
     	 * @return string
     	 */
    	function get_tag_slug($title) {
        	global $wpdb;
	
            $query = "SELECT slug FROM $wpdb->terms WHERE name='$title'";
        	$res = $wpdb->get_results($query, ARRAY_A);
        	return $res[0]['slug'];
    	}

        /**
     	 * Get the tag slug from the current tag title
     	 * @access public
     	 * @return string
     	 */
    	function get_current_tag_slug() {
            $slug = $this->get_tag_slug(single_tag_title('', false));
            
            return $slug;
    	}
	}
	
	/**
 	 * Init the class plugin
 	 * @access public
 	 * @global object $wp_du
 	 * @global object $wp_dev_utilities
 	 */
	function wp_dev_utilities_init() {
    	global $wpdu;
    	/**
     	 * @deprecated
     	 */
    	global $wp_dev_utilities;
	
    	$wpdu = new WP_Dev_Utilities();
    	$wp_dev_utilities = $wpdu;
	}
	wp_dev_utilities_init();
}
?>
