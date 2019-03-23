<?php
/**
 * WordPress Integration module
 *
 * @author Jonathan Gaudé
 * @copyright 2018
 * @license Commercial
 */
 
namespace WordpressIntegration;

if (!defined('_PS_VERSION_'))
	exit;

class Helper
{
	public static function curlGetContents($url, $ip = false)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);

		if ($ip)
		{
			$parse_url = parse_url($url);
			curl_setopt($ch, CURLOPT_RESOLVE, array($parse_url['host'] . ':' . ($parse_url['scheme'] == 'https' ? 443 : 80) . ':' . $ip));
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: ' . $parse_url['host']));
		}
		
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
	
	public static function displayXmlError($error)
	{
		$html  = '';

		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$html .= "Warning";
				break;
			 case LIBXML_ERR_ERROR:
				$html .= "Error";
				break;
			case LIBXML_ERR_FATAL:
				$html .= "Fatal Error";
				break;
		}

		$html .= " " . $error->code . " : " . trim($error->message);

		return $html;
	}
}