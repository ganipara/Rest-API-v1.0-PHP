<?php

/**
 * Ganipara Rest API PHP Library
 * version 1.0
 *
 *
 * @package        	Ganipara
 * @license         http://ganipara.com
 * @link			http://ganipara.com
 */

class Ganipara {

	public $rest;
	protected $endpoint = "http://api.ganipara.home/1.0/";
	protected $private_key;
	protected $public_key;

	private static $instance;

	public $page;

	function __construct($config = array()) {

		self::$instance = $this;

		$this -> rest = new Ganipara_rest();
		$this -> page = new Ganipara_page();
	}

	public static function getInstance() {
		if (!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class();
			self::$instance -> initialize($this -> private_key, $this -> public_key);
		}
		return self::$instance;
	}

	public function initialize($private_key = "", $public_key = "") {

		if (empty($private_key)) {
			throw new Exception("Ganipara API private key missing");
		}
		if (empty($public_key)) {
			throw new Exception("Ganipara API public key missing");
		}

		$config['api_key'] = $private_key;
		$config['api_public_key'] = $public_key;
		$config['server'] = $this -> endpoint;
		$this -> rest -> initialize($config);

	}

	public function dump() {
		list($callee) = debug_backtrace();
		$arguments = func_get_args();
		$total_arguments = count($arguments);

		echo '<fieldset style="background: #fefefe !important; border:2px #5AA70A solid; padding:5px">';
		echo '<legend style="background:lightgrey; padding:5px;">' . $callee['file'] . ' @ line: ' . $callee['line'] . '</legend><pre>';
		$i = 0;
		foreach ($arguments as $argument) {
			echo '<br/><strong>Debug #' . (++$i) . ' of ' . $total_arguments . '</strong>: ';
			var_dump($argument);
		}

		echo "</pre>";
		echo "</fieldset>";
	}

	/*
	 |--------------------------------------------------------------------------
	 | Shop Methods
	 |--------------------------------------------------------------------------
	 |
	 | shop_detail: Return detail about the shop
	 |
	 */

	public function shop_detail() {

		$data = $this -> rest -> get('shop/detail/');
		return $data;
	}

	/*
	 |--------------------------------------------------------------------------
	 | Cargo Methods
	 |--------------------------------------------------------------------------
	 |
	 |
	 */

	/**
	 * Calculate cargo fee from desi dimension
	 *
	 * @param int    $unit  Dimension in desi
	 *
	 */
	public function cargo_calculate($unit = FALSE) {

		$request = array();
		$request['unit'] = $unit;
		$data = $this -> rest -> get('cargo/calculate/', $request);

		return $data;
	}

	/**
	 * Calculate cargo dimension from width, height, depth
	 *
	 * @param int    $height  Dimension in cm
	 * @param int    $width  Dimension in cm
	 * @param int    $depth  Dimension in cm
	 *
	 */
	public function cargo_calculate_unit($height = FALSE, $width = FALSE, $depth = FALSE) {

		if (!is_numeric($height) || !is_numeric($width) || !is_numeric($depth)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['height'] = $height;
		$request['width'] = $width;
		$request['depth'] = $depth;
		$data = $this -> rest -> get('cargo/unit/', $request);

		return $data;
	}

	/*
	 |--------------------------------------------------------------------------
	 | Webhook Methods
	 |--------------------------------------------------------------------------
	 |
	 |
	 */

	/**
	 * Create webhook
	 *
	 * @param string    $event  Webhook event
	 * @param string    $url  URL that will recieve the data
	 * @param string    $name  Name of the webhook
	 *
	 *
	 */
	public function webhook_create($event = FALSE, $url = FALSE, $name = FALSE) {

		if (empty($event) || empty($url)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['event'] = $event;
		$request['url'] = $url;
		if (!empty($name)) {
			$request['name'] = $name;
		}
		$data = $this -> rest -> post('webhook/create/', $request);

		return $data;
	}

	/**
	 * Delete webhook
	 *
	 * @param int    $id  Webhook ID
	 *
	 */

	public function webhook_delete($id = FALSE) {

		if (empty($id) || !is_numeric($id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $id;

		$data = $this -> rest -> post('webhook/delete/', $request);

		return $data;
	}

	/**
	 * Detail webhook
	 *
	 * @param int    $id  Webhook ID
	 *
	 */

	public function webhook_detail($id = FALSE) {

		if (empty($id) || !is_numeric($id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $id;

		$data = $this -> rest -> get('webhook/detail/', $request);

		return $data;
	}

	/**
	 * List webhooks
	 *
	 * @param string    $url  Webhook URL
	 * @param string    $event  Webhook event
	 * @param string    $date_start  Create date in ISO 8601 format
	 * @param string    $date_end  Create date in ISO 8601 format
	 * @param int    $limit  Items per page
	 * @param int    $page  List page
	 *
	 */

	public function webhook_list($url = FALSE, $event = FALSE, $date_start = FALSE, $date_end = FALSE, $limit = FALSE, $page = FALSE) {

		$request = array();

		if (!empty($url)) {
			$request['url'] = $url;
		}
		if (!empty($event)) {
			$request['event'] = $event;
		}
		if (!empty($date_start)) {
			$request['date_start'] = $date_start;
		}
		if (!empty($date_end)) {
			$request['date_end'] = $date_end;
		}
		if (!empty($limit)) {
			$request['limit'] = $limit;
		}
		if (!empty($page)) {
			$request['page'] = $page;
		}

		$data = $this -> rest -> get('webhook/list/', $request);

		return $data;
	}

	/**
	 * Count webhooks
	 *
	 * @param string    $url  Webhook URL
	 * @param string    $event  Webhook event
	 * @param string    $date_start  Create date in ISO 8601 format
	 * @param string    $date_end  Create date in ISO 8601 format
	 * @param int    $limit  Items per page
	 * @param int    $page  List page
	 *
	 */

	public function webhook_count($url = FALSE, $event = FALSE, $date_start = FALSE, $date_end = FALSE) {

		$request = array();

		if (!empty($url)) {
			$request['url'] = $url;
		}
		if (!empty($event)) {
			$request['event'] = $event;
		}
		if (!empty($date_start)) {
			$request['date_start'] = $date_start;
		}
		if (!empty($date_end)) {
			$request['date_end'] = $date_end;
		}
		if (!empty($limit)) {
			$request['limit'] = $limit;
		}
		if (!empty($page)) {
			$request['page'] = $page;
		}

		$data = $this -> rest -> get('webhook/count/', $request);

		return $data;
	}

	/**
	 * Update webhook
	 *
	 * @param int    	$id  Webhook ID
	 * @param string    $event  Webhook event
	 * @param string    $url  URL that will recieve the data
	 * @param string    $name  Name of the webhook
	 *
	 */

	public function webhook_update($id = FALSE, $event = FALSE, $url = FALSE, $name = FALSE) {

		if (empty($id) || !is_numeric($id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $id;

		if (!empty($event)) {
			$request['event'] = $event;
		}

		if (!empty($url)) {
			$request['url'] = $url;
		}
		if (!empty($name)) {
			$request['name'] = $name;
		}

		$data = $this -> rest -> post('webhook/update/', $request);

		return $data;
	}

	/*
	 |--------------------------------------------------------------------------
	 | Page Methods
	 |--------------------------------------------------------------------------
	 |
	 |
	 */

	/**
	 * Count pages
	 *
	 * @param string    $title  Page title
	 * @param string    $slug  Page slug
	 * @param string    $published_status  Page status (published|unpublished)
	 * @param string    $date_start  Create date in ISO 8601 format
	 * @param string    $date_end  Create date in ISO 8601 format

	 *
	 */

	public function page_count($title = FALSE, $slug = FALSE, $published_status = FALSE, $date_start = FALSE, $date_end = FALSE) {

		$request = array();

		if (!empty($title)) {
			$request['title'] = $title;
		}
		if (!empty($slug)) {
			$request['slug'] = $slug;
		}
		if (!empty($published_status)) {
			$request['published_status'] = $published_status;
		}
		if (!empty($date_start)) {
			$request['date_start'] = $date_start;
		}
		if (!empty($date_end)) {
			$request['date_end'] = $date_end;
		}

		$data = $this -> rest -> get('page/count/', $request);
		return $data;
	}

	/*
	 |--------------------------------------------------------------------------
	 | Collection Methods
	 |--------------------------------------------------------------------------
	 |
	 |
	 */

	/**
	 * Create collection
	 *
	 * @param string    $title  Collection title
	 * @param string    $slug  Collection slug. Leave blank for auto generation
	 * @param string    $description  Collection description
	 * @param string    $meta_title  Meta Title for the page
	 * @param string    $meta_description Meta Description for the page
	 *
	 */

	public function collection_create($title = FALSE, $slug = FALSE, $description = FALSE, $meta_title = FALSE, $meta_description = FALSE) {

		if (empty($title)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['title'] = $title;

		if (!empty($slug)) {
			$request['slug'] = $slug;
		}
		if (!empty($description)) {
			$request['description'] = $description;
		}
		if (!empty($meta_title)) {
			$request['meta_title'] = $meta_title;
		}
		if (!empty($meta_description)) {
			$request['meta_description'] = $meta_description;
		}

		$data = $this -> rest -> post('page/create/', $request);

		return $data;
	}

	/**
	 * Update page
	 *
	 * @param int    	$id  Page ID
	 * @param string    $title  Page title
	 * @param string    $content  Page content
	 * @param string    $slug  Page slug. Leave blank for auto generation
	 * @param string    $published_status  Page status (published|unpublished)
	 * @param string    $meta_title  Meta Title for the page
	 * @param string    $meta_description Meta Description for the page
	 *
	 */

	public function collection_update($id = FALSE, $title = FALSE, $content = FALSE, $slug = FALSE, $published_status = FALSE, $meta_title = FALSE, $meta_description = FALSE) {

		if (empty($id) || !is_numeric($id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $id;

		if (!empty($title)) {
			$request['title'] = $title;
		}

		if (!empty($slug)) {
			$request['slug'] = $slug;
		}
		if (!empty($content)) {
			$request['content'] = $content;
		}
		if (!empty($published_status)) {
			$request['published_status'] = $published_status;
		}
		if (!empty($meta_title)) {
			$request['meta_title'] = $meta_title;
		}
		if (!empty($meta_description)) {
			$request['meta_description'] = $meta_description;
		}

		$data = $this -> rest -> post('page/update/', $request);

		return $data;
	}

	/**
	 * Delete page
	 *
	 * @param int    $id  Page ID
	 *
	 */

	public function collection_delete($id = FALSE) {

		if (empty($id) || !is_numeric($id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $id;

		$data = $this -> rest -> post('page/delete/', $request);

		return $data;
	}

	/**
	 * Detail page
	 *
	 * @param int    $id  Webhook ID
	 *
	 */

	public function collection_detail($id = FALSE) {

		if (empty($id) || !is_numeric($id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $id;

		$data = $this -> rest -> get('page/detail/', $request);

		return $data;
	}

	/**
	 * List pages
	 *
	 * @param string    $title  Page title
	 * @param string    $slug  Page slug
	 * @param string    $published_status  Page status (published|unpublished)
	 * @param string    $date_start  Create date in ISO 8601 format
	 * @param string    $date_end  Create date in ISO 8601 format
	 * @param int    $limit  Items per page
	 * @param int    $page  List page
	 *
	 */

	public function collection_list($title = FALSE, $slug = FALSE, $published_status = FALSE, $date_start = FALSE, $date_end = FALSE, $limit = FALSE, $page = FALSE) {

		$request = array();

		if (!empty($title)) {
			$request['title'] = $title;
		}
		if (!empty($slug)) {
			$request['slug'] = $slug;
		}
		if (!empty($published_status)) {
			$request['published_status'] = $published_status;
		}
		if (!empty($date_start)) {
			$request['date_start'] = $date_start;
		}
		if (!empty($date_end)) {
			$request['date_end'] = $date_end;
		}
		if (!empty($limit)) {
			$request['limit'] = $limit;
		}
		if (!empty($page)) {
			$request['page'] = $page;
		}

		$data = $this -> rest -> get('page/list/', $request);

		return $data;
	}

}

class Ganipara_page {

	protected $gpInstance;
	protected $cClass = "Ganipara_page";
	private $_id;
	private $_title;
	private $_content;
	private $_slug;
	private $_published_status;
	private $_meta_title;
	private $_meta_description;
	private $_limit;
	private $_page;

	function __construct() {
		$this -> gpInstance = Ganipara::getInstance();
	}

	function __deconstruct() {

	}

	public function __set($key, $value) {

		switch ($key) {

			case "id" :
				if (!is_numeric($value)) {
					throw new Exception("Property($key) should be numeric");
				}
				break;

			case "limit" :
				if (!is_numeric($value)) {
					throw new Exception("Property($key) should be numeric");
				}
				break;

			case "page" :
				if (!is_numeric($value)) {
					throw new Exception("Property($key) should be numeric");
				}
				break;

			case "meta_description" :
				$value = trim(strip_tags($value));
				break;

			case "meta_title" :
				$value = trim(strip_tags($value));
				break;

			case "slug" :
				$value = trim(strip_tags($value));
				break;

			case "title" :
				$value = trim(strip_tags($value));
				break;
		}

		$property = "_$key";
		if (!property_exists($this, $property))
			throw new Exception("Property($key) not found");

		$this -> {$property} = $value;
	}

	public function __get($key) {
		switch ($key) {

			default :
				$property = "_$key";
				if (!property_exists($this, $property)) {
					throw new Exception("Property($key) not found");
				}
				return $this -> {$property};
				break;
		}
	}

	function __reset() {
		$this -> gpInstance -> page = new $this->cClass();
	}

	/**
	 * Update page
	 *
	 */

	function update() {

		if (empty($this -> _id) || empty($this -> _content)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $this -> id;

		if (!empty($this -> _title)) {
			$request['title'] = $this -> title;
		}

		if (!empty($this -> _slug)) {
			$request['slug'] = $this -> slug;
		}
		if (!empty($this -> _content)) {
			$request['content'] = $this -> content;
		}
		if (!empty($this -> _published_status)) {
			$request['published_status'] = $this -> published_status;
		}
		if (!empty($this -> _meta_title)) {
			$request['meta_title'] = $this -> meta_title;
		}
		if (!empty($this -> _meta_description)) {
			$request['meta_description'] = $this -> meta_description;
		}

		$data = $this -> gpInstance -> rest -> post('page/update/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Create page
	 *
	 */

	function create() {

		if (empty($this -> _title) || empty($this -> _content)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['title'] = $this -> _title;

		if (!empty($this -> _slug)) {
			$request['slug'] = $this -> _slug;
		}
		if (!empty($this -> _content)) {
			$request['content'] = $this -> _content;
		}
		if (!empty($this -> _published_status)) {
			$request['published_status'] = $this -> _published_status;
		}
		if (!empty($this -> _meta_title)) {
			$request['meta_title'] = $this -> _meta_title;
		}
		if (!empty($this -> _meta_description)) {
			$request['meta_description'] = $this -> _meta_description;
		}

		$data = $this -> gpInstance -> rest -> post('page/create/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Delete page
	 *
	 */

	function delete() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> post('page/delete/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Detail page
	 *
	 */

	function detail() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> get('page/detail/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * List page
	 *
	 */

	function get() {

		$request = array();

		if (!empty($this -> _title)) {
			$request['title'] = $this -> title;
		}
		if (!empty($this -> _slug)) {
			$request['slug'] = $this -> slug;
		}
		if (!empty($this -> _published_status)) {
			$request['published_status'] = $this -> published_status;
		}
		if (!empty($this -> _date_start)) {
			$request['date_start'] = $this -> date_start;
		}
		if (!empty($this -> _date_end)) {
			$request['date_end'] = $this -> date_end;
		}
		if (!empty($this -> _limit)) {
			$request['limit'] = $this -> limit;
		}
		if (!empty($this -> _page)) {
			$request['page'] = $this -> page;
		}

		$data = $this -> gpInstance -> rest -> get('page/list/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Count page
	 *
	 */

	function record_count() {

		$request = array();

		if (!empty($this -> _title)) {
			$request['title'] = $this -> title;
		}
		if (!empty($this -> _slug)) {
			$request['slug'] = $this -> slug;
		}
		if (!empty($this -> _published_status)) {
			$request['published_status'] = $this -> published_status;
		}
		if (!empty($this -> _date_start)) {
			$request['date_start'] = $this -> date_start;
		}
		if (!empty($this -> _date_end)) {
			$request['date_end'] = $this -> date_end;
		}

		$data = $this -> gpInstance -> rest -> get('page/count/', $request);
		$this -> __reset();

		return $data;

	}

}

/**
 * Ganipara Rest Client Library
 * version 1.0
 *
 *
 * @package        	Ganipara
 * @license         http://ganipara.com
 * @link			http://ganipara.com
 */

class Ganipara_rest {

	protected $supported_formats = array('json' => 'application/json');

	protected $auto_detect_formats = array('application/json' => 'json', 'text/json' => 'json');

	protected $rest_server;
	protected $format;
	protected $mime_type;
	protected $curl;

	protected $http_auth = null;
	protected $http_user = null;
	protected $http_pass = null;

	protected $api_name = 'GP-API-KEY';
	protected $api_key = null;
	protected $api_public_key = null;

	protected $ssl_verify_peer = null;
	protected $ssl_cainfo = null;

	protected $response_string;

	function __construct($config = array()) {

		$this -> curl = new Ganipara_curl();
		empty($config) OR $this -> initialize($config);
	}

	function __destruct() {
		$this -> curl -> set_defaults();
	}

	/**
	 * initialize
	 */

	public function initialize($config) {
		$this -> rest_server = @$config['server'];

		if (substr($this -> rest_server, -1, 1) != '/') {
			$this -> rest_server .= '/';
		}
		$this -> api_name = "GP";

		isset($config['api_key']) && $this -> api_key = $config['api_key'];
		isset($config['api_public_key']) && $this -> api_public_key = $config['api_public_key'];
		isset($config['ssl_verify_peer']) && $this -> ssl_verify_peer = $config['ssl_verify_peer'];
	}

	/**
	 * get
	 *
	 */
	public function get($uri, $params = array(), $format = NULL) {
		if ($params) {
			$uri .= '?' . (is_array($params) ? http_build_query($params) : $params);
		}

		return $this -> _call('get', $uri, NULL, $format);
	}

	/**
	 * post
	 *
	 */
	public function post($uri, $params = array(), $format = NULL) {
		return $this -> _call('post', $uri, $params, $format);
	}

	/**
	 * put
	 *
	 */
	public function put($uri, $params = array(), $format = NULL) {
		return $this -> _call('put', $uri, $params, $format);
	}

	/**
	 * patch
	 *
	 */
	public function patch($uri, $params = array(), $format = NULL) {
		return $this -> _call('patch', $uri, $params, $format);
	}

	/**
	 * delete
	 *
	 */
	public function delete($uri, $params = array(), $format = NULL) {
		return $this -> _call('delete', $uri, $params, $format);
	}

	/**
	 * api_key
	 *
	 */
	public function api_key($key, $name = FALSE) {
		$this -> api_key = $key;

		if ($name !== FALSE) {
			$this -> api_name = $name;
		}

	}

	public function api_public_key($key, $name = FALSE) {
		$this -> api_public_key = $key;

		if ($name !== FALSE) {
			$this -> api_name = $name;
		}

	}

	/**
	 * language
	 *
	 */
	public function language($lang) {
		if (is_array($lang)) {
			$lang = implode(', ', $lang);
		}

		$this -> curl -> http_header('Accept-Language', $lang);
	}

	/**
	 * header
	 *
	 */
	public function header($header) {
		$this -> curl -> http_header($header);
	}

	/**
	 * _call
	 *
	 */
	protected function _call($method, $uri, $params = array(), $format = NULL) {
		if ($format !== NULL) {
			$this -> format($format);
		}

		$this -> http_header('Accept', $this -> mime_type);

		// Initialize cURL session
		$this -> curl -> create($this -> rest_server . $uri);

		// If using ssl set the ssl verification value and cainfo
		// contributed by: https://github.com/paulyasi
		if ($this -> ssl_verify_peer === FALSE) {
			$this -> curl -> ssl(FALSE);
		} elseif ($this -> ssl_verify_peer === TRUE) {
			$this -> ssl_cainfo = getcwd() . $this -> ssl_cainfo;
			$this -> curl -> ssl(TRUE, 2, $this -> ssl_cainfo);
		}

		// If authentication is enabled use it
		if ($this -> http_auth != '' && $this -> http_user != '') {
			$this -> curl -> http_login($this -> http_user, $this -> http_pass, $this -> http_auth);
		}

		// If we have an API Key, then use it
		if ($this -> api_key != '') {
			$this -> curl -> http_header($this -> api_name, $this -> api_key);
		}

		// If we have an API Key, then use it
		if ($this -> api_public_key != '') {
			$this -> curl -> http_header($this -> api_name . "_PUBLIC", $this -> api_public_key);
		}

		// Set the Content-Type (contributed by https://github.com/eriklharper)
		$this -> http_header('Content-type', $this -> mime_type);

		// We still want the response even if there is an error code over 400
		$this -> curl -> option('failonerror', FALSE);

		// Call the correct method with parameters
		$this -> curl -> {$method}($params);

		// Execute and return the response from the REST server
		$response = $this -> curl -> execute();

		// Format and return
		return $this -> _format_response($response);
	}

	/**
	 * initialize
	 *
	 * If a type is passed in that is not supported, use it as a mime type
	 *
	 */
	public function format($format) {
		if (array_key_exists($format, $this -> supported_formats)) {
			$this -> format = $format;
			$this -> mime_type = $this -> supported_formats[$format];
		} else {
			$this -> mime_type = $format;
		}

		return $this;
	}

	/**
	 * debug
	 *
	 */
	public function debug() {
		$request = $this -> curl -> debug_request();

		echo "=============================================<br/>\n";
		echo "<h2>Ganipara REST Test</h2>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Request</h3>\n";
		echo $request['url'] . "<br/>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Response</h3>\n";

		if ($this -> response_string) {
			echo "<code>" . nl2br(htmlentities($this -> response_string)) . "</code><br/>\n\n";
		} else {
			echo "No response<br/>\n\n";
		}

		echo "=============================================<br/>\n";

		if ($this -> curl -> error_string) {
			echo "<h3>Errors</h3>";
			echo "<strong>Code:</strong> " . $this -> curl -> error_code . "<br/>\n";
			echo "<strong>Message:</strong> " . $this -> curl -> error_string . "<br/>\n";
			echo "=============================================<br/>\n";
		}

		echo "<h3>Call details</h3>";
		echo "<pre>";
		print_r($this -> curl -> info);
		echo "</pre>";

	}

	/**
	 * status
	 *
	 */
	// Return HTTP status code
	public function status() {
		return $this -> info('http_code');
	}

	/**
	 * info
	 *
	 * Return curl info by specified key, or whole array
	 *
	 */
	public function info($key = null) {
		return $key === null ? $this -> curl -> info : @$this -> curl -> info[$key];
	}

	/**
	 * option
	 *
	 * Set custom CURL options
	 *
	 */
	//
	public function option($code, $value) {
		$this -> curl -> option($code, $value);
	}

	/**
	 * http_header
	 *
	 */
	public function http_header($header, $content = NULL) {
		// Did they use a single argument or two?
		$params = $content ? array($header, $content) : array($header);

		// Pass these attributes on to the curl library
		call_user_func_array(array($this -> curl, 'http_header'), $params);
	}

	/**
	 * _format_response
	 *
	 */
	protected function _format_response($response) {
		$this -> response_string = &$response;

		// It is a supported format, so just run its formatting method
		if (array_key_exists($this -> format, $this -> supported_formats)) {
			return $this -> {"_".$this->format}($response);
		}

		// Find out what format the data was returned in
		$returned_mime = @$this -> curl -> info['content_type'];

		// If they sent through more than just mime, strip it off
		if (strpos($returned_mime, ';')) {
			list($returned_mime) = explode(';', $returned_mime);
		}

		$returned_mime = trim($returned_mime);

		if (array_key_exists($returned_mime, $this -> auto_detect_formats)) {
			return $this -> {'_'.$this->auto_detect_formats[$returned_mime]}($response);
		}

		return $response;
	}

	/**
	 * _xml
	 *
	 * Format XML for output
	 *
	 */
	protected function _xml($string) {
		return $string ? (array) simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
	}

	/**
	 * _csv
	 *
	 *
	 */
	protected function _csv($string) {
		$data = array();

		// Splits
		$rows = explode("\n", trim($string));
		$headings = explode(',', array_shift($rows));
		foreach ($rows as $row) {
			// The substr removes " from start and end
			$data_fields = explode('","', trim(substr($row, 1, -1)));

			if (count($data_fields) === count($headings)) {
				$data[] = array_combine($headings, $data_fields);
			}

		}

		return $data;
	}

	/**
	 * _json
	 *
	 * Encode as JSON
	 *
	 */
	protected function _json($string) {
		return json_decode(trim($string));
	}

	/**
	 * _serialize
	 *
	 * Encode as Serialized array
	 *
	 */
	protected function _serialize($string) {
		return unserialize(trim($string));
	}

	/**
	 * _php
	 *
	 * Encode raw PHP
	 *
	 */
	protected function _php($string) {
		$string = trim($string);
		$populated = array();
		eval("\$populated = \"$string\";");
		return $populated;
	}

}

/**
 * Ganipara Curl Class
 *
 * Special thanks to Philip Sturgeon for the curl class
 *
 * @package        	Ganipara
 * @license         http://ganipara.com
 * @link			http://ganipara.com
 */
class Ganipara_curl {

	protected $response = '';
	// Contains the cURL response for debug
	protected $session;
	// Contains the cURL handler for a session
	protected $url;
	// URL of the session
	protected $options = array();
	// Populates curl_setopt_array
	protected $headers = array();
	// Populates extra HTTP headers
	public $error_code;
	// Error code returned as an int
	public $error_string;
	// Error message returned as a string
	public $info;
	// Returned after request (elapsed time, etc)

	function __construct($url = '') {
		if (!$this -> is_enabled()) {
			throw new Exception("PHP was not built with cURL enabled");
		}

		$url AND $this -> create($url);
	}

	public function __call($method, $arguments) {
		if (in_array($method, array('simple_get', 'simple_post', 'simple_put', 'simple_delete', 'simple_patch'))) {
			// Take off the "simple_" and past get/post/put/delete/patch to _simple_call
			$verb = str_replace('simple_', '', $method);
			array_unshift($arguments, $verb);
			return call_user_func_array(array($this, '_simple_call'), $arguments);
		}
	}

	/* =================================================================================
	 * SIMPLE METHODS
	 * Using these methods you can make a quick and easy cURL call with one line.
	 * ================================================================================= */

	public function _simple_call($method, $url, $params = array(), $options = array()) {
		// Get acts differently, as it doesnt accept parameters in the same way
		if ($method === 'get') {
			// If a URL is provided, create new session
			$this -> create($url . ($params ? '?' . http_build_query($params, NULL, '&') : ''));
		} else {
			// If a URL is provided, create new session
			$this -> create($url);

			$this -> {$method}($params);
		}

		// Add in the specific options provided
		$this -> options($options);

		return $this -> execute();
	}

	public function simple_ftp_get($url, $file_path, $username = '', $password = '') {
		// If there is no ftp:// or any protocol entered, add ftp://
		if (!preg_match('!^(ftp|sftp)://! i', $url)) {
			$url = 'ftp://' . $url;
		}

		// Use an FTP login
		if ($username != '') {
			$auth_string = $username;

			if ($password != '') {
				$auth_string .= ':' . $password;
			}

			// Add the user auth string after the protocol
			$url = str_replace('://', '://' . $auth_string . '@', $url);
		}

		// Add the filepath
		$url .= $file_path;

		$this -> option(CURLOPT_BINARYTRANSFER, TRUE);
		$this -> option(CURLOPT_VERBOSE, TRUE);

		return $this -> execute();
	}

	/* =================================================================================
	 * ADVANCED METHODS
	 * Use these methods to build up more complex queries
	 * ================================================================================= */

	public function post($params = array(), $options = array()) {
		// If its an array (instead of a query string) then format it correctly
		if (is_array($params)) {
			$params = http_build_query($params, NULL, '&');
		}

		// Add in the specific options provided
		$this -> options($options);

		$this -> http_method('post');

		$this -> option(CURLOPT_POST, TRUE);
		$this -> option(CURLOPT_POSTFIELDS, $params);
	}

	public function put($params = array(), $options = array()) {
		// If its an array (instead of a query string) then format it correctly
		if (is_array($params)) {
			$params = http_build_query($params, NULL, '&');
		}

		// Add in the specific options provided
		$this -> options($options);

		$this -> http_method('put');
		$this -> option(CURLOPT_POSTFIELDS, $params);

		// Override method, I think this overrides $_POST with PUT data but... we'll see eh?
		$this -> option(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
	}

	public function patch($params = array(), $options = array()) {
		// If its an array (instead of a query string) then format it correctly
		if (is_array($params)) {
			$params = http_build_query($params, NULL, '&');
		}

		// Add in the specific options provided
		$this -> options($options);

		$this -> http_method('patch');
		$this -> option(CURLOPT_POSTFIELDS, $params);

		// Override method, I think this overrides $_POST with PATCH data but... we'll see eh?
		$this -> option(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PATCH'));
	}

	public function delete($params, $options = array()) {
		// If its an array (instead of a query string) then format it correctly
		if (is_array($params)) {
			$params = http_build_query($params, NULL, '&');
		}

		// Add in the specific options provided
		$this -> options($options);

		$this -> http_method('delete');

		$this -> option(CURLOPT_POSTFIELDS, $params);
	}

	public function set_cookies($params = array()) {
		if (is_array($params)) {
			$params = http_build_query($params, NULL, '&');
		}

		$this -> option(CURLOPT_COOKIE, $params);
		return $this;
	}

	public function http_header($header, $content = NULL) {
		$this -> headers[] = $content ? $header . ': ' . $content : $header;
		return $this;
	}

	public function http_method($method) {
		$this -> options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
		return $this;
	}

	public function http_login($username = '', $password = '', $type = 'any') {
		$this -> option(CURLOPT_HTTPAUTH, constant('CURLAUTH_' . strtoupper($type)));
		$this -> option(CURLOPT_USERPWD, $username . ':' . $password);
		return $this;
	}

	public function proxy($url = '', $port = 80) {
		$this -> option(CURLOPT_HTTPPROXYTUNNEL, TRUE);
		$this -> option(CURLOPT_PROXY, $url . ':' . $port);
		return $this;
	}

	public function proxy_login($username = '', $password = '') {
		$this -> option(CURLOPT_PROXYUSERPWD, $username . ':' . $password);
		return $this;
	}

	public function ssl($verify_peer = TRUE, $verify_host = 2, $path_to_cert = NULL) {
		if ($verify_peer) {
			$this -> option(CURLOPT_SSL_VERIFYPEER, TRUE);
			$this -> option(CURLOPT_SSL_VERIFYHOST, $verify_host);
			if (isset($path_to_cert)) {
				$path_to_cert = realpath($path_to_cert);
				$this -> option(CURLOPT_CAINFO, $path_to_cert);
			}
		} else {
			$this -> option(CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		return $this;
	}

	public function options($options = array()) {
		// Merge options in with the rest - done as array_merge() does not overwrite numeric keys
		foreach ($options as $option_code => $option_value) {
			$this -> option($option_code, $option_value);
		}

		// Set all options provided
		curl_setopt_array($this -> session, $this -> options);

		return $this;
	}

	public function option($code, $value, $prefix = 'opt') {
		if (is_string($code) && !is_numeric($code)) {
			$code = constant('CURL' . strtoupper($prefix) . '_' . strtoupper($code));
		}

		$this -> options[$code] = $value;
		return $this;
	}

	public function site_url() {

	}

	// Start a session from a URL
	public function create($url) {

		// If no a protocol in URL, assume its a CI link
		// BERKAY
		/*
		 if (!preg_match('!^\w+://! i', $url)) {
		 $this -> _ci -> load -> helper('url');
		 $url = site_url($url);
		 }
		 */
		$this -> url = $url;
		$this -> session = curl_init($this -> url);

		return $this;
	}

	// End a session and return the results
	public function execute() {
		// Set two default options, and merge any extra ones in
		if (!isset($this -> options[CURLOPT_TIMEOUT])) {
			$this -> options[CURLOPT_TIMEOUT] = 30;
		}
		if (!isset($this -> options[CURLOPT_RETURNTRANSFER])) {
			$this -> options[CURLOPT_RETURNTRANSFER] = TRUE;
		}
		if (!isset($this -> options[CURLOPT_FAILONERROR])) {
			$this -> options[CURLOPT_FAILONERROR] = TRUE;
		}

		// Only set follow location if not running securely
		if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
			// Ok, follow location is not set already so lets set it to true
			if (!isset($this -> options[CURLOPT_FOLLOWLOCATION])) {
				$this -> options[CURLOPT_FOLLOWLOCATION] = TRUE;
			}
		}

		if (!empty($this -> headers)) {
			$this -> option(CURLOPT_HTTPHEADER, $this -> headers);
		}

		$this -> options();

		// Execute the request & and hide all output
		$this -> response = curl_exec($this -> session);
		$this -> info = curl_getinfo($this -> session);

		// Request failed
		if ($this -> response === FALSE) {
			$errno = curl_errno($this -> session);
			$error = curl_error($this -> session);

			curl_close($this -> session);
			$this -> set_defaults();

			$this -> error_code = $errno;
			$this -> error_string = $error;

			return FALSE;
		}

		// Request successful
		else {
			curl_close($this -> session);
			$this -> last_response = $this -> response;
			$this -> set_defaults();
			return $this -> last_response;
		}
	}

	public function is_enabled() {
		return function_exists('curl_init');
	}

	public function debug() {
		echo "=============================================<br/>\n";
		echo "<h2>CURL Test</h2>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Response</h3>\n";
		echo "<code>" . nl2br(htmlentities($this -> last_response)) . "</code><br/>\n\n";

		if ($this -> error_string) {
			echo "=============================================<br/>\n";
			echo "<h3>Errors</h3>";
			echo "<strong>Code:</strong> " . $this -> error_code . "<br/>\n";
			echo "<strong>Message:</strong> " . $this -> error_string . "<br/>\n";
		}

		echo "=============================================<br/>\n";
		echo "<h3>Info</h3>";
		echo "<pre>";
		print_r($this -> info);
		echo "</pre>";
	}

	public function debug_request() {
		return array('url' => $this -> url);
	}

	public function set_defaults() {
		$this -> response = '';
		$this -> headers = array();
		$this -> options = array();
		$this -> error_code = NULL;
		$this -> error_string = '';
		$this -> session = NULL;
	}

}
