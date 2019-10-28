<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_MODULE_DIR_.'rbthemeslider/rbthemeslider.php';

if (!defined('ABSPATH')) {
    die();
}

class RbSliderFacebook
{
    private $stream;
    private $transient_sec;

    public function __construct($transient_sec = 1200)
    {
        $this->transient_sec = $transient_sec;
    }

    public function getUserFromUrl($user_url)
    {
        $theid = str_replace("https", "", $user_url);
        $theid = str_replace("http", "", $theid);
        $theid = str_replace("://", "", $theid);
        $theid = str_replace("www.", "", $theid);
        $theid = str_replace("facebook", "", $theid);
        $theid = str_replace(".com", "", $theid);
        $theid = str_replace("/", "", $theid);
        $theid = explode("?", $theid);

        return trim($theid[0]);
    }

    public function getPhotoSets($user_id, $item_count = 10, $app_id, $app_secret)
    {
        $oauth = PSRemoteFopen(
        	"https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=" .
        	$app_id . "&client_secret=" . $app_secret
        );

        $url = "https://graph.facebook.com/$user_id/albums?" . $oauth;
        $photo_sets_list = Tools::jsonDecode(PSRemoteFopen($url));

        if (@Rbthemeslider::getIsset($photo_sets_list->data)) {
            return $photo_sets_list->data;
        } else {
            return '';
        }
    }

    public function getPhotoSetPhotos($photo_set_id, $item_count = 10, $app_id, $app_secret)
    {
        $oauth = PSRemoteFopen(
        	"https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=" .
        	$app_id . "&client_secret=" . $app_secret
        );

        $url = "https://graph.facebook.com/$photo_set_id/photos?fields=photos&" . $oauth .
        "&fields=id,from,message,picture,link,name,icon,privacy,type,status_type,object_id,application,
        created_time,updated_time,is_hidden,is_expired,comments.limit(1).summary(true),
        likes.limit(1).summary(true)";

        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $photo_set_photos = Tools::jsonDecode(PSRemoteFopen($url));

        if (@Rbthemeslider::getIsset($photo_set_photos->data)) {
            setTransient($transient_name, $photo_set_photos->data, $this->transient_sec);

            return $photo_set_photos->data;
        } else {
            return '';
        }
    }

    public function getPhotoSetPhotosOptions(
    	$user_url,
    	$current_album,
    	$app_id,
    	$app_secret,
    	$item_count = 99
    ) {
        $user_id = $this->getUserFromUrl($user_url);
        $photo_sets = $this->getPhotoSets($user_id, 999, $app_id, $app_secret);

        if (empty($current_album)) {
            $current_album = "";
        }

        $return = array();

        if (is_array($photo_sets)) {
            foreach ($photo_sets as $photo_set) {
                $return[] = '<option title="' . $photo_set->name . '" ' .
                selected($photo_set->id, $current_album, false) . ' value="' .
                $photo_set->id . '">' . $photo_set->name . '</option>"';
            }
        }

        return $return;
    }

    public function getPhotoFeed($user, $app_id, $app_secret, $item_count = 10)
    {
        $oauth = PSRemoteFopen(
        	"https://graph.facebook.com/oauth/access_token?type=client_cred&client_id=" .
        	$app_id . "&client_secret=" . $app_secret
        );

        $url = "https://graph.facebook.com/$user/feed?" . $oauth .
        "&fields=id,from,message,picture,link,name,icon,privacy,type,status_type,object_id,
        application,created_time,updated_time,is_hidden,is_expired,comments.limit(1).summary(true),
        likes.limit(1).summary(true)";

        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $feed = Tools::jsonDecode(PSRemoteFopen($url));

        if (@Rbthemeslider::getIsset($feed->data)) {
            setTransient($transient_name, $feed->data, $this->transient_sec);

            return $feed->data;
        } else {
            return '';
        }
    }

    private function decodeFacebookUrl($url)
    {
        $url = str_replace('u00253A', ':', $url);
        $url = str_replace('\u00255C\u00252F', '/', $url);
        $url = str_replace('u00252F', '/', $url);
        $url = str_replace('u00253F', '?', $url);
        $url = str_replace('u00253D', '=', $url);
        $url = str_replace('u002526', '&', $url);

        return $url;
    }
}


class RbSliderTwitter
{
    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
    private $twitter_account;
    private $transient_sec;
    private $stream;

    public function __construct(
    	$consumer_key,
    	$consumer_secret,
    	$access_token,
    	$access_token_secret,
    	$transient_sec = 1200
    ) {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->access_token = $access_token;
        $this->access_token_secret = $access_token_secret;
        $this->transient_sec = $transient_sec;
    }


    public function getPublicPhotos(
    	$twitter_account,
    	$include_rts,
    	$exclude_replies,
    	$count,
    	$imageonly
    ) {
        $credentials = array(
            'consumer_key' => $this->consumer_key,
            'consumer_secret' => $this->consumer_secret
        );

        $twitter_api = new RbSliderTwitterApi($credentials, $this->transient_sec);

        $include_rts = $include_rts == "on" ? "true" : "false";
        $exclude_replies = $include_rts == "on" ? "false" : "true";

        $query = 'count=500&include_entities=true&include_rts=' . $include_rts .
        '&exclude_replies=' . $exclude_replies . '&screen_name=' . $twitter_account;

        $tweets = $twitter_api->query($query);

        if (!empty($tweets)) {
            return $tweets;
        } else {
            return '';
        }
    }

    /**
     * Find Key in array and return value (multidim array possible)
     *
     * @since    1.0.0
     * @param    string    $key   Needle
     * @param    array     $form  Haystack
     */
    public function arrayFindElementByKey($key, $form)
    {
        if (is_array($form) && array_key_exists($key, $form)) {
            $ret = $form[$key];
            return $ret;
        }
        
        if (is_array($form)) {
            foreach ($form as $k => $v) {
                if (is_array($v)) {
                    $ret = $this->arrayFindElementByKey($key, $form[$k]);

                    if ($ret) {
                        return $ret;
                    }
                }
            }
        }
        return false;
    }
}

class RbSliderTwitterApi
{
    public $bearer_token,

    $args = array(
        'consumer_key' => 'default_consumer_key',
        'consumer_secret' => 'default_consumer_secret'
    ),

    $query_args = array(
        'type' => 'statuses/user_timeline',
        'cache' => 1800
    ),

    $has_error = false;

    /**
     * WordPress Twitter API Constructor
     *
     * @param array $args
     */
    public function __construct($args = array(), $transient_sec = 1200)
    {
        if (is_array($args) && !empty($args)) {
            $this->args = array_merge($this->args, $args);
        }

        $this->bearer_token = getOption('twitter_bearer_token');

        if (empty($this->bearer_token)) {
            $this->bearer_token = $this->getBearerToken();
        }

        $this->query_args['cache'] = $transient_sec;
        $this->modules = new Rbthemeslider();
    }

    /**
     * Get the token from oauth Twitter API
     *
     * @return string Oauth Token
     */
    private function getBearerToken()
    {
        $bearer_token_credentials = $this->args['consumer_key'] . ':' . $this->args['consumer_secret'];
        $bearer_token_credentials_64 = base64_encode($bearer_token_credentials);

        $args = array(
            'method' => 'POST',
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.1',
            'blocking' => true,
            'headers' => array(
                'Authorization' => 'Basic ' . $bearer_token_credentials_64,
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'Accept-Encoding' => 'gzip'
            ),
            'body' => array('grant_type' => 'client_credentials'),
            'cookies' => array()
        );

        $response = PSRemotePost('https://api.twitter.com/oauth2/token', $args);

        if (@Rbthemeslider::getIsset($response['info']['http_code']) && 200 != $response['info']['http_code']) {
            return $this->modules->l('Can\'t get the bearer token, check your credentials');
        }

        $resp = Tools::jsonDecode($response['body']);

        if (@Rbthemeslider::getIsset($resp->access_token)) {
            updateOption('twitter_bearer_token', $resp->access_token);

            return $resp->access_token;
        }
        return false;
    }

    /**
     * Query twitter's API
     *
     * @uses $this->get_bearer_token() to retrieve token if not working
     *
     * @param string $query Insert the query in the format "count=1&include_entities=true&include_rts=true&screen_name=micc1983!
     * @param array $query_args Array of arguments: Resource type (string) and cache duration (int)
     * @param bool $stop Stop the query to avoid infinite loop
     *
     * @return bool|object Return an object containing the result
     */
    public function query($query, $query_args = array(), $stop = false)
    {
        if ($this->has_error) {
            return false;
        }

        if (is_array($query_args) && !empty($query_args)) {
            $this->query_args = array_merge($this->query_args, $query_args);
        }

        $transient_name = 'wta_' . md5($query);

        if ($this->query_args['cache'] > 0 && false !== ($data = getTransient($transient_name))) {
            return Tools::jsonDecode($data);
        }

        $args = array(
            'method' => 'GET',
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->bearer_token,
                'Accept-Encoding' => 'gzip'
            ),
            'body' => null,
            'cookies' => array()
        );

        $response = PSRemoteGet(
        	'https://api.twitter.com/1.1/' .
        	$this->query_args['type'] . '.json?' . $query, $args
        );

        if (@Rbthemeslider::getIsset($response['info']['http_code'])) {
            if ((int)$response['info']['http_code'] !== 200) {
                if (!$stop) {
                    $this->bearer_token = $this->getBearerToken();

                    return $this->query($query, $this->query_args, true);
                } else {
                    return $this->modules->l('Bearer Token is good, check your query');
                }
            }

            setTransient($transient_name, $response['body'], $this->query_args['cache']);

            return Tools::jsonDecode($response['body']);
        }

        return false;
    }

    /**
     * Let's manage errors
     *
     *
     * @param string $error_text Error message
     * @param string $error_object Server response
     */
    private function bail($error_text, $error_object = '')
    {
        $this->has_error = true;

        if (200 != $error_object['errno']) {
            $error_text .= ' ( Response: ' . $error_object['error'] . ' )';
        }

        triggerError($error_text, E_USER_NOTICE);
    }
}

/**
 * Instagram
 *
 * with help of the API this class delivers all kind of Images from instagram
 *
 * @package    socialstreams
 * @subpackage socialstreams/instagram
 * @author     ThemePunch <info@themepunch.com>
 */
class RbSliderInstagram
{
    private $api_key;
    private $stream;
    private $transient_sec;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $api_key	Instagram API key.
     */
    public function __construct($api_key, $transient_sec = 1200)
    {
        $this->api_key = $api_key;
        $this->transient_sec = $transient_sec;
    }

    public function getPublicPhotos($search_user_id, $count)
    {
        $url = "https://api.instagram.com/v1/users/" . $search_user_id . "/media/recent?count=" .
        $count . "&access_token=" . $this->api_key . "&client_id=" . $search_user_id;

        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $rsp = Tools::jsonDecode(PSRemoteFopen($url));

        if (@Rbthemeslider::getIsset($rsp->data)) {
            setTransient($transient_name, $rsp->data, $this->transient_sec);
            return $rsp->data;
        } else {
            return '';
        }
    }
}

class RbSliderFlickr
{
    private $api_key;
    private $api_param_defaults;
    private $stream;
    private $flickr_url;
    private $transient_sec;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $api_key	flickr API key.
     */
    public function __construct($api_key, $transient_sec = 1200)
    {
        $this->api_key = $api_key;

        $this->api_param_defaults = array(
            'api_key' => $this->api_key,
            'format' => 'json',
            'nojsoncallback' => 1,
        );

        $this->transient_sec = $transient_sec;
    }

    private function callFlickrApi($params)
    {
        $encoded_params = array();

        foreach ($params as $k => $v) {
            $encoded_params[] = urlencode($k) . '=' . urlencode($v);
        }

        //call the API and decode the response
        $url = "https://api.flickr.com/services/rest/?" . implode('&', $encoded_params);
        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $rsp = Tools::jsonDecode(PSRemoteFopen($url));

        if (@Rbthemeslider::getIsset($rsp)) {
            setTransient($transient_name, $rsp, $this->transient_sec);

            return $rsp;
        } else {
            return '';
        }
    }

    /**
     * Get User ID from its URL
     *
     * @since    1.0.0
     * @param    string    $user_url URL of the Gallery
     */
    public function getUserFromUrl($user_url)
    {
        //gallery params
        $user_params = $this->api_param_defaults + array(
            'method' => 'flickr.urls.lookupUser',
            'url' => $user_url,
        );

        //set User Url
        $this->flickr_url = $user_url;

        //get gallery info
        $user_info = $this->callFlickrApi($user_params);

        if (@Rbthemeslider::getIsset($user_info->user->id)) {
            return $user_info->user->id;
        } else {
            return '';
        }
    }

    /**
     * Get Group ID from its URL
     *
     * @since    1.0.0
     * @param    string    $group_url URL of the Gallery
     */
    public function getGroupFromUrl($group_url)
    {
        //gallery params
        $group_params = $this->api_param_defaults + array(
            'method' => 'flickr.urls.lookupGroup',
            'url' => $group_url,
        );

        //set User Url
        $this->flickr_url = $group_url;

        //get gallery info
        $group_info = $this->callFlickrApi($group_params);
        if (@Rbthemeslider::getIsset($group_info->group->id)) {
            return $group_info->group->id;
        } else {
            return '';
        }
    }

    /**
     * Get Public Photos
     *
     * @since    1.0.0
     * @param    string    $user_id 	flicker User id (not name)
     * @param    int       $item_count 	number of photos to pull
     */
    public function getPublicPhotos($user_id, $item_count = 10)
    {
        //public photos params
        $public_photo_params = $this->api_param_defaults + array(
            'method' => 'flickr.people.getPublicPhotos',
            'user_id' => $user_id,
            'extras' => 'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o',
            'per_page' => $item_count,
            'page' => 1
        );

        //get photo list
        $public_photos_list = $this->callFlickrApi($public_photo_params);

        if (@Rbthemeslider::getIsset($public_photos_list->photos->photo)) {
            return $public_photos_list->photos->photo;
        } else {
            return '';
        }
    }

    /**
     * Get Photosets List from User
     *
     * @since    1.0.0
     * @param    string    $user_id 	flicker User id (not name)
     * @param    int       $item_count 	number of photos to pull
     */
    public function getPhotoSets($user_id, $item_count = 10, $current_photoset)
    {
        //photoset params
        $photo_set_params = $this->api_param_defaults + array(
            'method' => 'flickr.photosets.getList',
            'user_id' => $user_id,
            'per_page' => $item_count,
            'page' => 1
        );

        //get photoset list
        $photo_sets_list = $this->callFlickrApi($photo_set_params);
        $return = array();

        foreach ($photo_sets_list->photosets->photoset as $photo_set) {
            if (empty($photo_set->title->_content)) {
                $photo_set->title->_content = "";
            }

            if (empty($photo_set->photos)) {
                $photo_set->photos = 0;
            }

            $return[] = '<option title="' . $photo_set->description->_content . '" ' . selected($photo_set->id, $current_photoset, false) . ' value="' . $photo_set->id . '">' . $photo_set->title->_content . ' (' . $photo_set->photos . ' photos)</option>"';
        }
        return $return;
    }

    /**
     * Get Photoset Photos
     *
     * @since    1.0.0
     * @param    string    $photo_set_id 	Photoset ID
     * @param    int       $item_count 	number of photos to pull
     */
    public function getPhotoSetPhotos($photo_set_id, $item_count = 10)
    {
        $this->stream = array();

        $photo_set_params = $this->api_param_defaults + array(
            'method' => 'flickr.photosets.getPhotos',
            'photoset_id' => $photo_set_id,
            'per_page' => $item_count,
            'page' => 1,
            'extras' => 'license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
        );

        //get photo list
        $photo_set_photos = $this->callFlickrApi($photo_set_params);

        if (@Rbthemeslider::getIsset($photo_set_photos->photoset->photo)) {
            return $photo_set_photos->photoset->photo;
        } else {
            return '';
        }
    }

    /**
     * Get Groop Pool Photos
     *
     * @since    1.0.0
     * @param    string    $group_id 	Photoset ID
     * @param    int       $item_count 	number of photos to pull
     */
    public function getGroupPhotos($group_id, $item_count = 10)
    {
        //photoset photos params
        $group_pool_params = $this->api_param_defaults + array(
            'method' => 'flickr.groups.pools.getPhotos',
            'group_id' => $group_id,
            'per_page' => $item_count,
            'page' => 1,
            'extras' => 'license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o'
        );

        //get photo list
        $group_pool_photos = $this->callFlickrApi($group_pool_params);

        if (@Rbthemeslider::getIsset($group_pool_photos->photos->photo)) {
            return $group_pool_photos->photos->photo;
        } else {
            return '';
        }
    }

    /**
     * Get Gallery ID from its URL
     *
     * @since    1.0.0
     * @param    string    $gallery_url URL of the Gallery
     * @param    int       $item_count 	number of photos to pull
     */
    public function getGalleryFromUrl($gallery_url)
    {
        //gallery params
        $gallery_params = $this->api_param_defaults + array(
            'method' => 'flickr.urls.lookupGallery',
            'url' => $gallery_url,
        );

        //get gallery info
        $gallery_info = $this->callFlickrApi($gallery_params);

        if (@Rbthemeslider::getIsset($gallery_info->gallery->id)) {
            return $gallery_info->gallery->id;
        } else {
            return '';
        }
    }

    /**
     * Get Gallery Photos
     *
     * @since    1.0.0
     * @param    string    $gallery_id 	flicker Gallery id (not name)
     * @param    int       $item_count 	number of photos to pull
     */
    public function getGalleryPhotos($gallery_id, $item_count = 10)
    {
        //gallery photos params
        $gallery_photo_params = $this->api_param_defaults + array(
            'method' => 'flickr.galleries.getPhotos',
            'gallery_id' => $gallery_id,
            'extras' => 'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o',
            'per_page' => $item_count,
            'page' => 1
        );

        //get photo list
        $gallery_photos_list = $this->callFlickrApi($gallery_photo_params);

        if (@Rbthemeslider::getIsset($gallery_photos_list->photos->photo)) {
            return $gallery_photos_list->photos->photo;
        } else {
            return '';
        }
    }

    /**
     * Encode the flickr ID for URL (base58)
     *
     * @since    1.0.0
     * @param    string    $num 	flickr photo id
     */
    private function baseEncode($num, $alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ')
    {
        $base_count = Tools::strlen($alphabet);
        $encoded = '';

        while ($num >= $base_count) {
            $div = $num / $base_count;
            $mod = ($num - ($base_count * (int) ($div)));
            $encoded = $alphabet[$mod] . $encoded;
            $num = (int) ($div);
        }

        if ($num) {
            $encoded = $alphabet[$num] . $encoded;
        }

        return $encoded;
    }
}

// End Class

/**
 * Youtube
 *
 * with help of the API this class delivers all kind of Images/Videos from youtube
 *
 * @package    socialstreams
 * @subpackage socialstreams/youtube
 * @author     ThemePunch <info@themepunch.com>
 */
class RbSliderYoutube
{
    private $api_key;
    private $channel_id;
    private $stream;
    private $transient_sec;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $api_key	Youtube API key.
     */
    public function __construct($api_key, $channel_id, $transient_sec = 1200)
    {
        $this->api_key = $api_key;
        $this->channel_id = $channel_id;
        $this->transient_sec = $transient_sec;
    }

    /**
     * Get Youtube Playlists
     *
     * @since    1.0.0
     */
    public function getlaylists()
    {
        //call the API and decode the response
        $url = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&maxResults=50&channelId=" . $this->channel_id . "&key=" . $this->api_key;
        $rsp = Tools::jsonDecode(psRemoteFopen($url));

        if (@Rbthemeslider::getIsset($rsp->items)) {
            return $rsp->items;
        } else {
            return false;
        }
    }

    /**
     * Get Youtube Playlist Items
     *
     * @since    1.0.0
     * @param    string    $playlist_id 	Youtube Playlist ID
     * @param    integer    $count 	Max videos count
     */
    public function showPlaylistVideos($playlist_id, $count = 50)
    {
        //call the API and decode the response
        if (empty($count)) {
            $count = 50;
        }

        $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=" .
        $playlist_id . "&maxResults=" . $count . "&fields=items%2Fsnippet&key=" . $this->api_key;
        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $rsp = Tools::jsonDecode(PSRemoteFopen($url));
        setTransient($transient_name, $rsp->items, $this->transient_sec);

        return $rsp->items;
    }

    /**
     * Get Youtube Channel Items
     *
     * @since    1.0.0
     * @param    integer    $count 	Max videos count
     */
    public function showChannelVideos($count = 50)
    {
        if (empty($count)) {
            $count = 50;
        }
        //call the API and decode the response
        $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=" .
        $this->channel_id . "&maxResults=" . $count . "&key=" . $this->api_key . "&order=date";

        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $rsp = Tools::jsonDecode(PSRemoteFopen($url));
        setTransient($transient_name, $rsp->items, $this->transient_sec);

        return $rsp->items;
    }

    /**
     * Get Playlists from Channel as Options for Selectbox
     *
     * @since    1.0.0
     */
    public function get_playlist_options($current_playlist)
    {
        $return = array();
        $playlists = $this->getPlaylists();

        if (!empty($playlists)) {
            foreach ($playlists as $playlist) {
                $return[] = '<option title="' . $playlist->snippet->description . '" ' .
                selected($playlist->id, $current_playlist, false) . ' value="' .
                $playlist->id . '">' . $playlist->snippet->title . '</option>"';
            }
        }

        return $return;
    }
}

/**
 * Vimeo
 *
 * with help of the API this class delivers all kind of Images/Videos from Vimeo
 *
 * @package    socialstreams
 * @subpackage socialstreams/vimeo
 * @author     ThemePunch <info@themepunch.com>
 */
class RbSliderVimeo
{
    private $stream;
    private $transient_sec;

    public function __construct($transient_sec = 1200)
    {
        $this->transient_sec = $transient_sec;
    }

    /**
     * Get Vimeo User Videos
     *
     * @since    1.0.0
     */
    public function getVimeoVideos($type, $value)
    {
        //call the API and decode the response
        if ($type == "user") {
            $url = "https://vimeo.com/api/v2/" . $value . "/videos.json";
        } else {
            $url = "https://vimeo.com/api/v2/" . $type . "/" . $value . "/videos.json";
        }

        $transient_name = 'rbslider_' . md5($url);

        if ($this->transient_sec > 0 && false !== ($data = getTransient($transient_name))) {
            return ($data);
        }

        $rsp = Tools::jsonDecode(PSRemoteFopen($url));
        setTransient($transient_name, $rsp, $this->transient_sec);

        return $rsp;
    }
}
