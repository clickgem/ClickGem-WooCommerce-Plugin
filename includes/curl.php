<?phpif ( ! defined( 'ABSPATH' ) ) {	exit;}/** * Plugin Name: ClickGem Paygate for Woocommerce * Plugin URI: https://www.clickgem.com/ * Description: Add-on paygate clickgem.com for Woocommerce * Version: 1.0.0 * Author: HaiNN * Author URI: http://newwayit.vn/ * License: GPL2 *//** * Plugin này xây dựng cơ bản theo URL http://newwayit.vn/ */
class curl_paygate
{
    private $username;
    private $password;
    private $url;
    /**
     * @param string $username
     * @param string $password
     * @param string $host
     * @param int $port
     * @param string $proto
     * @param string $url
     */
    public function __construct($username, $password, $url = 'https://api.clickgem.com/paygate/')
    {
        $this->username			= $username;
        $this->password     	= $password;
        $this->url           	= $url;
		
    }
	
    public function show_paygate($params = array()){
		
		if(!empty($params)){
			return $this->__request('show', $params);
		}
		return false;
	}
	
    public function create_paygate($params = array()){
		
		if(!empty($params)){
			return $this->__request('create', $params);
		}
		return false;
	}
	
    public function __request($method, $params = array())
    {
        $username      		= $this->username;
        $password	        = $this->password;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		
		curl_setopt($ch, CURLOPT_URL, $this->url . $method . '/');
		
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);
		$ret = curl_exec($ch);
		
		if($ret !== FALSE)
		{
			$formatted = @json_decode($ret);
			return $formatted;
		}
		else
		{
			return false;
		}
    }
}