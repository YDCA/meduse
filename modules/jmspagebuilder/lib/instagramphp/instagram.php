<?php
/**
* 2007-2017 PrestaShop
*
* Jms Page Builder
*
*  @author    Joommasters <joommasters@gmail.com>
*  @copyright 2007-2017 Joommasters
*  @license   license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*  @Website: http://www.joommasters.com
*/

class InstaWCD
{
    function userID(){
        $username = strtolower($this->username); // sanitization
        $token = $this->access_token;
        $url = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".$token;
        $get = file_get_contents($url);
        $json = json_decode($get);

        foreach ($json->data as $user) {
            if ($user->username == $username) {
                return $user->id;
            }
        }

        return '00000000'; // return this if nothing is found
    }

    function userMedia(){
        $url = 'https://api.instagram.com/v1/users/self/media/recent/?access_token='.$this->access_token;
        $content = file_get_contents($url);
            return $json = json_decode($content, true);
    }
}
