<?php
/**
 * 2007-2015 PrestaShop
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2014 PrestaShop SA
 * @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
 * International Registered Trademark & Property of PrestaShop SA
 */

define('CMCIC_CTLHMAC', 'V1.04.sha1.php--[CtlHmac%s%s]-%s');
define('CMCIC_CTLHMACSTR', 'CtlHmac%s%s');
define('CMCIC_CGI2_RECEIPT', "version=2\ncdr=%s");
define('CMCIC_CGI2_MACOK', '0');
define('CMCIC_CGI2_MACNOTOK', "1\n");
define('CMCIC_CGI2_FIELDS', '%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*');
define('CMCIC_CGI1_FIELDS', '%s*%s*%s%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s*%s');
define('CMCIC_URLPAIEMENT', 'paiement.cgi');


/*****************************************************************************
*
* Classe / Class : CmCicTpe
*
*****************************************************************************/

class CmCicTpe
{
	public $s_version;
	public $s_numero;
	public $s_code_societe;
	public $s_langue;
	public $s_url_ok;
	public $s_url_ko;
	public $s_url_paiement;

	private $s_cle;

	/**
	*
	* Constructeur / Constructor
	*
	**/

	public function __construct($s_langue = 'FR')
	{
		$a_required_constants = array('CMCIC_KEY', 'CMCIC_VERSION', 'CMCIC_TPE', 'CMCIC_COMPANY_CODE');
		$this->checkTpeParams($a_required_constants);

		$this->s_version = CMCIC_VERSION;
		$this->s_cle = CMCIC_KEY;
		$this->s_numero = CMCIC_TPE;
		$this->s_url_paiement = CMCIC_SERVER.CMCIC_URLPAIEMENT;

		$this->s_code_societe = CMCIC_COMPANY_CODE;
		$this->s_langue = $s_langue;

		$this->s_url_ok = CMCIC_URLOK;
		$this->s_url_ko = CMCIC_URLKO;
	}

	/**
	 *
	 * Fonction / Function : getCle
	 * Renvoie la cl� du TPE / return the TPE Key
	 *
	 **/

	public function getCle()
	{
		return $this->s_cle;
	}

	/**
	 *
	 * Fonction / Function : checkTpeParams
	 *
	 * Contr�le l'existence des constantes d'initialisation du TPE
	 * Check for the initialising constants of the TPE
	 *
	 **/

	private function checkTpeParams($a_constants)
	{
		$count = count($a_constants);
		for ($i = 0; $i < $count; $i++)
			if (!defined($a_constants[$i]))
				Tools::displayError('Error, parameter '.$a_constants[$i].' is undefined');
	}
}

/*****************************************************************************
*
* Classe / Class : CmCicHmac
*
*****************************************************************************/

class CmCicHmac
{
	private $s_usable_key;

	/**
	 *
	 * Constructeur / Constructor
	 *
	 **/

	public function __construct($o_tpe)
	{
		$this->s_usable_key = $this->getUsableKey($o_tpe);
	}

	/**
	 *
	 * Fonction / Function : getUsableKey
	 * Renvoie la cl� dans un format utilisable par la certification hmac
	 * Return the key to be used in the hmac function
	 *
	 **/

	private function getUsableKey($o_tpe)
	{
		$hex_str_key = Tools::substr($o_tpe->getCle(), 0, 38);
		$hex_final = ''.Tools::substr($o_tpe->getCle(), 38, 2).'00';

		$cca0 = ord($hex_final);

		if ($cca0 > 70 && $cca0 < 97)
			$hex_str_key .= chr($cca0 - 23).Tools::substr($hex_final, 1, 1);
		else
		{
			if (Tools::substr($hex_final, 1, 1) == 'M')
				$hex_str_key .= Tools::substr($hex_final, 0, 1).'0';
			else
				$hex_str_key .= Tools::substr($hex_final, 0, 2);
		}
		return pack('H*', $hex_str_key);
	}

	/**
	 *
	 * Fonction / Function : computeHmac
	 * Renvoie le sceau HMAC d'une chaine de donn�es
	 * Return the HMAC for a data string
	 *
	 **/

	public function computeHmac($s_data)
	{
		$hash = 'hash_hmac';
		return Tools::strtolower($hash('sha1', $s_data, $this->s_usable_key));
	}

	/**
	*
	* Fonction / Function : hmacSha1
	*
	* RFC 2104 HMAC implementation for PHP >= 4.3.0 - Creates a SHA1 HMAC.
	* Eliminates the need to install mhash to compute a HMAC
	* Adjusted from the md5 version by Lance Rushing .
	*
	* Impl�mentation RFC 2104 HMAC pour PHP >= 4.3.0 - Cr�ation d'un SHA1 HMAC.
	* Elimine l'installation de mhash pour le calcul d'un HMAC
	* Adapt�e de la version MD5 de Lance Rushing.
	*
	 **/
	public function hmacSha1($key, $data)
	{
		$length = 64; // block length for SHA1
		if (Tools::strlen($key) > $length)
			$key = pack('H*', sha1($key));
		$key  = str_pad($key, $length, chr(0x00));
		$ipad = str_pad('', $length, chr(0x36));
		$opad = str_pad('', $length, chr(0x5c));
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;

		return sha1($k_opad.pack('H*', sha1($k_ipad.$data)));
	}
}

/**
* function getMethode
*
* IN:
* OUT: Donn�es soumises par GET ou POST / Data sent by GET or POST
* description: Renvoie le tableau des donn�es / Send back the data array
*
**/
function getMethode()
{
	if ($_SERVER['REQUEST_METHOD'] == 'GET')
		return $_GET;

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
		return $_POST;

	Tools::displayError('Invalid REQUEST_METHOD (not GET, not POST).');
}

/**
* function htmlEncode
*
* IN:  chaine a encoder / String to encode
* OUT: Chaine encod�e / Encoded string
*
* Description: Encode special characters under HTML format
*	Encodage des caract�res sp�ciaux au format HTML
*
**/
function htmlEncode($data)
{
	$safe_out_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890._-';
	$result = '';
	$len = Tools::strlen($data);
	for ($i = 0; $i < $len; $i++)
	{
		if (strchr($safe_out_chars, $data{$i}))
			$result .= $data{$i};
		elseif (($var = bin2hex(Tools::substr($data, $i, 1))) <= '7F')
			$result .= '&#x'.$var.';';
		else
			$result .= $data{$i};
	}
	return $result;
}
?>