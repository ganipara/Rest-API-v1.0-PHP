<?php

/**
 * Ganipara Rest API PHP Library
 * version 1.0
 *
 *
 * @package        	Ganipara
 * @author			Berkay ÜNAL
 * @author			Bora ÜNAL
 * @author			Ganipara Team
 * @license         http://ganipara.com
 * @link			http://ganipara.com
 */

class Ganipara {

	public $rest;
	private $_endpoint = "https://api.ganipara.com/1.0/";
	private $_verify_peer = FALSE;
	protected $_private_key;
	protected $_public_key;
	protected $_access_token;
	protected $_config;

	private static $instance;

	public $page;
	public $app;
	public $webhook;
	public $cargo;
	public $shop;
	public $collection;
	public $product;
	public $order;

	function __construct($config = array()) {

		self::$instance = $this;

		$this -> _config['server'] = $this -> endpoint;
		$this -> _config['ssl_verify_peer'] = $this -> verify_peer;

		$this -> rest = new Ganipara_rest();
		$this -> app = new Ganipara_app();
		$this -> page = new Ganipara_page();
		$this -> webhook = new Ganipara_webhook();
		$this -> cargo = new Ganipara_cargo();
		$this -> shop = new Ganipara_shop();
		$this -> collection = new Ganipara_collection();
		$this -> product = new Ganipara_product();
		$this -> order = new Ganipara_order();
	}

	public static function getInstance() {
		if (!isset(self::$instance)) {
			$class = __CLASS__;
			self::$instance = new $class();
			self::$instance -> initialize($this -> private_key, $this -> public_key);
		}
		return self::$instance;
	}

	public function endpoint($end_point = FALSE) {
		if (!empty($end_point)) {
			$this -> _endpoint = $end_point;
		}
		return TRUE;
	}

	public function verify_peer($verify_peer = FALSE) {
		if (!empty($verify_peer) && is_bool($verify_peerF)) {
			$this -> _verify_peer = (bool)$verify_peer;
		}
		return FALSE;
	}

	public function access_token($access_token = "") {
		if (empty($access_token)) {
			throw new Exception("Ganipara API access token missing");
		}
		$this -> _access_token = $access_token;
		return TRUE;
	}

	public function private_key($private_key) {
		if (empty($private_key)) {
			throw new Exception("Ganipara API private key missing");
		}
		$this -> _private_key = $private_key;
		return TRUE;
	}

	public function public_key($public_key) {
		if (empty($public_key)) {
			throw new Exception("Ganipara API public key missing");
		}
		$this -> _public_key = $public_key;
		return TRUE;
	}

	public function initialize($private_key = FALSE, $public_key = FALSE, $access_token = FALSE) {

		if (!empty($private_key)) {
			$this -> private_key($private_key);
		}
		if (!empty($public_key)) {
			$this -> public_key($public_key);
		}
		if (!empty($access_token)) {
			$this -> access_token($access_token);
		}

		$this -> _config['api_key'] = $this -> _private_key;
		$this -> _config['api_public_key'] = $this -> _public_key;
		$this -> _config['api_access_token'] = $this -> _access_token;
		$this -> _config['server'] = $this -> _endpoint;
		$this -> _config['ssl_verify_peer'] = $this -> _verify_peer;

		$this -> rest -> initialize($this -> _config);

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

	function now() {
		return date('Y-m-d H:i:s');
	}

	function is_date($date) {
		$ddmmyyy = '(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](19|20)[0-9]{2}';
		if (preg_match("/$ddmmyyy$/", $date)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function date_convert_to_ISO8601($date = "") {
		if (empty($date)) {
			$date = $this -> now();
		}
		return date(DATE_ISO8601, strtotime($date));
	}

	function date_convert_from_ISO8601($date = "") {
		if (empty($date)) {
			return date('Y-m-d H:i:s', strtotime($date));
		}
		return FALSE;
	}

	function date_add_hours($date = "", $hour = "0") {
		if ($date == "") {
			$date = date("Y-m-d H:i:s");
		}

		if ($hour >= 0) {
			$hour = "+" . $hour;
		} else {
			$hour = "-" . abs($hour);
		}
		$newdate = strtotime("$hour hours", strtotime($date));
		$newdate = date("Y-m-d H:i:s", $newdate);
		return $newdate;
	}

	function encode_file($filepath = FALSE) {
		if (file_exists($filepath) && is_file($filepath)) {
			$data = file_get_contents($filepath);
			return base64_encode($data);
		}
		return FALSE;
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
		$request['title'] = $this -> title;

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

class Ganipara_order {

	protected $gpInstance;
	protected $cClass = "Ganipara_order";
	private $_id;

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
	 * Detail order
	 *
	 */

	function detail() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> get('order/detail/', $request);
		$this -> __reset();

		return $data;

	}

}

class Ganipara_product {

	protected $gpInstance;
	protected $cClass = "Ganipara_product";
	private $_id;
	private $_title;
	private $_content;
	private $_slug;
	private $_published_status;
	private $_meta_title;
	private $_meta_description;
	private $_limit;
	private $_page;
	private $_excerpt;
	private $_quantity_type;
	private $_price;
	private $_discount_price;
	private $_cargo;
	private $_photo_id;
	private $_type;
	private $_stock;
	private $_collection_id;
	private $_variant_id;
	private $_variant_key_data = array();
	private $_sku = array();
	private $_collection = array();
	private $_file = array();
	private $_tags = array();
	private $_images = array();

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

			case "collection_id" :
				if (!is_numeric($value)) {
					throw new Exception("Property($key) should be numeric");
				}
				break;

			case "limit" :
				if (!is_numeric($value)) {
					throw new Exception("Property($key) should be numeric");
				}
				break;

			case "photo_id" :
				if (!is_numeric($value)) {
					throw new Exception("Property($key) should be numeric");
				}
				break;
			case "discount_price" :
				if (!empty($value)) {
					if (!is_numeric($value)) {
						throw new Exception("Property($key) should be numeric");
					}
				}
				break;

			case "price" :
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

	public function & __get($key) {
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

	function variant($options = array()) {

		$variant_data = array();

		$default = $options['default'];
		$key = $options['key'];
		$name = $options['name'];

		if (empty($key) && empty($name)) {
			throw new Exception("Property(name) or Property(key) is missing parameter");
		}

		if (!empty($name)) {
			$variant_data['name'] = $name;
		}
		if (!empty($key)) {
			$variant_data['key'] = $key;
		}
		if (!empty($default)) {
			$variant_data['default'] = $default;
		}

		$this -> _variant_key_data[] = $variant_data;
		return TRUE;
	}

	function image($options = array()) {

		$image_data = array();
		$src = $options['src'];
		$attachment = $options['attachment'];
		$alt = $options['alt'];

		if (empty($src) && empty($attachment)) {
			throw new Exception("Property(src) or Property(attachment) is missing parameter");
		}
		if (!empty($src)) {
			$image_data['src'] = $src;
		}
		if (!empty($attachment)) {
			$image_data['attachment'] = $attachment;
		}
		if (!empty($alt)) {
			$image_data['alt'] = $alt;
		}
		$this -> _images[] = $image_data;
		return TRUE;
	}

	function file($options = array()) {

		$image_data = array();
		$src = $options['src'];
		$attachment = $options['attachment'];
		$filename = $options['filename'];

		if (empty($src) && empty($attachment)) {
			throw new Exception("Property(src) or Property(attachment) is missing parameter");
		}
		if (!empty($src)) {
			$file_data['src'] = $src;
		}
		if (!empty($attachment)) {
			$file_data['attachment'] = $attachment;
		}
		if (!empty($filename)) {
			$file_data['filename'] = $filename;
		}
		$this -> _file[] = $file_data;
		return TRUE;
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

		$data = $this -> gpInstance -> rest -> get('product/detail/', $request);
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

		$data = $this -> gpInstance -> rest -> post('product/delete/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Variant add for the product
	 *
	 */

	function variant_add($data = array()) {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		if (!empty($this -> _price)) {
			$request['price'] = $this -> price;
		}
		if (!empty($this -> _sku)) {
			$request['sku'] = $this -> sku;
		}
		if (!empty($this -> _stock)) {
			$request['stock'] = $this -> stock;
		}
		if (!empty($this -> _cargo)) {
			$request['cargo'] = $this -> cargo;
		}
		if (is_array($data) && count($data) > 0) {
			$request['variant_data'] = $data;
		}

		$data = $this -> gpInstance -> rest -> post('product/variant_add/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Variant delete from the product
	 *
	 */

	function variant_delete() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		if (empty($this -> _variant_id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;
		$request['variant_id'] = $this -> variant_id;

		$data = $this -> gpInstance -> rest -> post('product/variant_delete/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Variant keys for the product
	 *
	 */

	function variant_keys() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> post('product/variant_keys/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Update variant keys for the product
	 *
	 */

	function variant_keys_update() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$variant_data = $this -> _variant_key_data;
		if (!is_array($variant_data)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;
		foreach ($variant_data as $k => $v) {
			$request['variant_' . $k] = $v;
		}

		$data = $this -> gpInstance -> rest -> post('product/variant_keys_update/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Create page
	 *
	 */

	function create() {

		$request = array();

		if (!empty($this -> _type)) {
			$request['type'] = $this -> type;
		}

		if (!empty($this -> _title)) {
			$request['title'] = $this -> title;
		}
		if (!empty($this -> _content)) {
			$request['content'] = $this -> content;
		}

		if (!empty($this -> _slug)) {
			$request['slug'] = $this -> slug;
		}

		if (!empty($this -> _excerpt)) {
			$request['excerpt'] = $this -> excerpt;
		}
		if (!empty($this -> _quantity_type)) {
			$request['quantity_type'] = $this -> quantity_type;
		}
		if (!empty($this -> _price)) {
			$request['price'] = $this -> price;
		}
		if (!empty($this -> _discount_price)) {
			$request['discount_price'] = $this -> discount_price;
		}
		if (!empty($this -> _stock)) {
			$request['stock'] = $this -> stock;
		}

		if (!empty($this -> _cargo)) {
			$request['cargo'] = $this -> cargo;
		}
		if (!empty($this -> _collection)) {
			$request['collection'] = $this -> collection;
		}
		if (!empty($this -> _images)) {
			$request['images'] = $this -> images;
		}
		if (!empty($this -> _file)) {
			$request['file'] = $this -> _file[0];
		}
		if (!empty($this -> _tags)) {
			$request['tags'] = $this -> tags;
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

		$data = $this -> gpInstance -> rest -> post('product/create/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Update page
	 *
	 */

	function update() {

		if (empty($this -> _id)) {
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
		if (!empty($this -> _excerpt)) {
			$request['excerpt'] = $this -> excerpt;
		}
		if (!empty($this -> _quantity_type)) {
			$request['quantity_type'] = $this -> quantity_type;
		}
		if (!empty($this -> _price)) {
			$request['price'] = $this -> price;
		}
		if (!empty($this -> _cargo)) {
			$request['cargo'] = $this -> cargo;
		}
		if (!empty($this -> _collection)) {
			$request['collection'] = $this -> collection;
		}
		if (!empty($this -> _file)) {
			$request['file'] = $this -> _file[0];
		}
		if (!empty($this -> _tags)) {
			$request['tags'] = $this -> tags;
		}
		if (!empty($this -> _discount_price)) {
			$request['discount_price'] = $this -> discount_price;
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

		$data = $this -> gpInstance -> rest -> post('product/update/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Images add to product
	 *
	 */

	function image_add() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$images = $this -> images;

		if (is_array($images) && count($images) > 0) {
			if (isset($images['attachment']) || isset($images['src'])) {
				$_image = array("attachment" => $images['attachment'], "src" => $images['src'], "alt" => $images['v']);
			} else {
				$_image = $images[0];
			}
		} else {
			throw new Exception("Missing parameter");
		}

		if (!empty($_image['attachment'])) {
			$request['attachment'] = $_image['attachment'];
		}
		if (!empty($_image['src'])) {
			$request['src'] = $_image['src'];
		}
		if (!empty($_image['alt'])) {
			$request['alt'] = $_image['alt'];
		}

		$data = $this -> gpInstance -> rest -> post('product/image_add/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Images delete from product
	 *
	 */

	function image_delete() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}
		if (empty($this -> _photo_id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;
		$request['photo_id'] = $this -> _photo_id;

		$data = $this -> gpInstance -> rest -> post('product/image_delete/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Images update from product
	 *
	 */

	function image_update($options = array()) {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}
		if (empty($this -> _photo_id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;
		$request['photo_id'] = $this -> _photo_id;

		if (!empty($options['alt'])) {
			$request['alt'] = $options['alt'];
		}
		if (!empty($options['sort_order']) && is_numeric($options['sort_order'])) {
			$request['sort_order'] = $options['sort_order'];
		}

		$data = $this -> gpInstance -> rest -> post('product/image_update/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Images of the product
	 *
	 */

	function image_get() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> get('product/images/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * List page
	 *
	 */

	function get() {

		$request = array();

		if (!empty($this -> _type)) {
			$request['type'] = $this -> type;
		}

		if (!empty($this -> _collection_id)) {
			$request['collection_id'] = $this -> collection_id;
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

		$data = $this -> gpInstance -> rest -> get('product/list/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Count page
	 *
	 */

	function record_count() {

		$request = array();

		if (!empty($this -> _type)) {
			$request['type'] = $this -> type;
		}

		if (!empty($this -> _collection_id)) {
			$request['collection_id'] = $this -> collection_id;
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

		$data = $this -> gpInstance -> rest -> get('product/count/', $request);
		$this -> __reset();

		return $data;

	}

}

class Ganipara_collection {

	protected $gpInstance;
	protected $cClass = "Ganipara_collection";
	private $_id;
	private $_title;
	private $_slug;
	private $_description;
	private $_meta_title;
	private $_product_id;
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

			case "product_id" :
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

			case "description" :
				$value = trim(strip_tags($value));
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
	 * Update collection
	 *
	 */

	function update() {

		if (empty($this -> _id)) {
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
		if (!empty($this -> _description)) {
			$request['description'] = $this -> description;
		}

		if (!empty($this -> _meta_title)) {
			$request['meta_title'] = $this -> meta_title;
		}
		if (!empty($this -> _meta_description)) {
			$request['meta_description'] = $this -> meta_description;
		}

		$data = $this -> gpInstance -> rest -> post('collection/update/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Create collection
	 *
	 */

	function create() {

		if (empty($this -> _title)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['title'] = $this -> _title;

		if (!empty($this -> _slug)) {
			$request['slug'] = $this -> slug;
		}
		if (!empty($this -> _description)) {
			$request['description'] = $this -> description;
		}

		if (!empty($this -> _meta_title)) {
			$request['meta_title'] = $this -> meta_title;
		}
		if (!empty($this -> _meta_description)) {
			$request['meta_description'] = $this -> meta_description;
		}

		$data = $this -> gpInstance -> rest -> post('collection/create/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Delete collection
	 *
	 */

	function delete() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> post('collection/delete/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Detail collection
	 *
	 */

	function detail() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> get('collection/detail/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * List collection
	 *
	 */

	function get() {

		$request = array();

		if (!empty($this -> _product_id)) {
			$request['product_id'] = $this -> product_id;
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

		$data = $this -> gpInstance -> rest -> get('collection/list/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Add product to collection
	 *
	 */

	function product_add() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		if (empty($this -> _product_id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['product_id'] = $this -> product_id;
		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> post('collection/add_product/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Remove product from collection
	 *
	 */

	function product_remove() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		if (empty($this -> _product_id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['product_id'] = $this -> product_id;
		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> post('collection/remove_product/', $request);
		$this -> __reset();

		return $data;

	}

}

class Ganipara_webhook {

	protected $gpInstance;
	protected $cClass = "Ganipara_webhook";
	private $_id;
	private $_url;
	private $_name;
	private $_event;
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

			case "slug" :
				$value = trim(strip_tags($value));
				break;

			case "name" :
				$value = trim(strip_tags($value));
				break;

			case "url" :
				$value = trim(strip_tags($value));
				break;

			case "event" :
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
	 * Update webhook
	 *
	 */

	function update() {

		if (empty($this -> _id) || empty($this -> _content)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['id'] = $this -> id;

		if (!empty($this -> _event)) {
			$request['event'] = $this -> event;
		}

		if (!empty($this -> _url)) {
			$request['url'] = $this -> url;
		}
		if (!empty($this -> _name)) {
			$request['name'] = $this -> name;
		}

		$data = $this -> gpInstance -> rest -> post('webhook/update/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Create webhook
	 *
	 */

	function create() {

		if (empty($this -> _event) || empty($this -> _url)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['event'] = $this -> event;
		$request['url'] = $this -> url;

		if (!empty($this -> _name)) {
			$request['name'] = $this -> name;
		}

		$data = $this -> gpInstance -> rest -> post('webhook/create/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Delete webhook
	 *
	 */

	function delete() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> post('webhook/delete/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Detail webhook
	 *
	 */

	function detail() {

		if (empty($this -> _id)) {
			throw new Exception("Missing parameter");
		}

		$request = array();

		$request['id'] = $this -> id;

		$data = $this -> gpInstance -> rest -> get('webhook/detail/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * List webhooks
	 *
	 */

	function get() {

		$request = array();

		if (!empty($this -> _url)) {
			$request['url'] = $this -> url;
		}

		if (!empty($this -> _event)) {
			$request['event'] = $this -> event;
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

		$data = $this -> gpInstance -> rest -> get('webhook/list/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Count page
	 *
	 */

	function record_count() {

		$request = array();

		if (!empty($this -> _url)) {
			$request['url'] = $this -> url;
		}

		if (!empty($this -> _event)) {
			$request['event'] = $this -> event;
		}

		if (!empty($this -> _date_start)) {
			$request['date_start'] = $this -> date_start;
		}

		if (!empty($this -> _date_end)) {
			$request['date_end'] = $this -> date_end;
		}

		$data = $this -> gpInstance -> rest -> get('webhook/count/', $request);
		$this -> __reset();

		return $data;

	}

}

class Ganipara_app {
	protected $gpInstance;
	protected $cClass = "Ganipara_app";

	private $_endpoint = "https://ganipara.com/oauth";
	private $_client_id;
	private $_client_secret;
	private $_redirect_url;
	private $_access_code;

	function __construct() {
		$this -> gpInstance = Ganipara::getInstance();
	}

	public function __set($key, $value) {

		switch ($key) {

			case "endpoint" :
				if (empty($value)) {
					throw new Exception("Property($key) cannot be empty");
				}
				break;
			case "client_id" :
				if (empty($value)) {
					throw new Exception("Property($key) cannot be empty");
				}
				break;
			case "client_secret" :
				if (empty($value)) {
					throw new Exception("Property($key) cannot be empty");
				}
				break;
			case "redirect_url" :
				if (empty($value)) {
					throw new Exception("Property($key) cannot be empty");
				}
				break;
		}

		$property = "_$key";
		if (!property_exists($this, $property))
			throw new Exception("Property($key) not found");

		$this -> {$property} = $value;
	}

	public function & __get($key) {
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

	// Get the URL required to request authorization
	public function getAuthorizeUrl($scope = FALSE, $state = FALSE) {

		if (empty($scope)) {
			throw new Exception("Scope cannot be empty");
		}

		if (is_array($scope) && count($scope) > 0) {
			$scope = implode(",", $scope);
		}

		$url = $this -> _endpoint . "/request?response_type=code&client_id={$this->_client_id}&scope=" . urlencode($scope);
		if ($this -> _redirect_url != '') {
			$url .= "&redirect_uri=" . urlencode($this -> _redirect_url);
		}
		if (!empty($state)) {
			$url .= "&state=" . urlencode($state);
		}
		return $url;
	}

	public function getScopes($access_code = "") {

		if (!empty($access_code)) {
			$this -> access_code = $access_code;
		}

		$request['client_id'] = $this -> client_id;
		$request['client_secret'] = $this -> client_secret;
		$request['access_code'] = $this -> access_code;

		$curl = new Ganipara_curl();

		$result = $curl -> simple_post($this -> endpoint . '/scopes/', $request);
		$response = json_decode($result, TRUE);
		if (isset($response['scopes'])) {
			return $response['scopes'];
		}
		return FALSE;
	}

	// Once the User has authorized the app, call this with the code to get the access token
	function getAccessToken($code) {

		$request['client_id'] = $this -> client_id;
		$request['client_secret'] = $this -> client_secret;
		$request['redirect_uri'] = $this -> redirect_url;
		$request['code'] = trim($code);
		$request['grant_type'] = "authorization_code";

		$curl = new Ganipara_curl();
		$result = $curl -> simple_post($this -> _endpoint . '/access_token/', $request);
		$response = json_decode($result, TRUE);
		if (isset($response['access_token'])) {
			return $response['access_token'];
		}
		return FALSE;
	}

	function __deconstruct() {

	}

}

class Ganipara_cargo {

	protected $gpInstance;
	protected $cClass = "Ganipara_cargo";

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
	 * Calculate cargo dimension from width, height, depth
	 *
	 */

	function calculate_unit($height = FALSE, $width = FALSE, $depth = FALSE) {

		if (!is_numeric($height) || !is_numeric($width) || !is_numeric($depth)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['height'] = $height;
		$request['width'] = $width;
		$request['depth'] = $depth;

		$data = $this -> gpInstance -> rest -> get('cargo/unit/', $request);
		$this -> __reset();

		return $data;

	}

	/**
	 * Calculate cargo dimension from width, height, depth
	 *
	 */

	function calculate($unit = FALSE) {

		if (!is_numeric($unit)) {
			throw new Exception("Missing parameter");
		}

		$request = array();
		$request['unit'] = $unit;

		$data = $this -> gpInstance -> rest -> get('cargo/calculate/', $request);
		$this -> __reset();

		return $data;

	}

}

class Ganipara_shop {

	protected $gpInstance;
	protected $cClass = "Ganipara_shop";

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
	 * Return detail about the shop
	 *
	 */

	function detail() {

		$data = $this -> gpInstance -> rest -> get('shop/detail/');
		$this -> __reset();

		return $data;

	}

	/**
	 * Return resource detail about the shop
	 *
	 */

	function resource() {

		$data = $this -> gpInstance -> rest -> get('shop/resource_available/');
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
	protected $api_access_token = null;

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

		isset($config['api_access_token']) && $this -> api_access_token = $config['api_access_token'];
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

		// If we have an API Private Key, then use it
		if ($this -> api_key != '') {
			$this -> curl -> http_header($this -> api_name, $this -> api_key);
		}

		// If we have an API Public Key, then use it
		if ($this -> api_public_key != '') {
			$this -> curl -> http_header($this -> api_name . "_PUBLIC", $this -> api_public_key);
		}

		// If we have an API Access Token, then use it
		if ($this -> api_access_token != '') {
			$this -> curl -> http_header($this -> api_name . "_ACCESS_TOKEN", $this -> api_access_token);
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
