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

class RbSlide extends UniteElementsBaseRb
{
    private $id;
    private $sliderID;
    private $slideOrder;
    private $imageUrl;
    private $imageID;
    private $imageThumb;
    private $imageFilepath;
    private $imageFilename;
    private $params;
    private $arrLayers;
    private $settings;
    private $arrChildren = null;
    private $slider;
    private $static_slide = false;
    private $postData;
    public $templateID;
    private $arrLayers_export;

    public function __construct()
    {
        parent::__construct();
    }

    public function initByData($record)
    {
        if (@Rbthemeslider::getIsset($record["id"])) {
            $this->id = $record["id"];
        }

        if (@Rbthemeslider::getIsset($record["slider_id"])) {
            $this->sliderID = $record["slider_id"];
        }

        if (@Rbthemeslider::getIsset($record["slide_order"])) {
            $this->slideOrder = $record["slide_order"];
        }

        $params = $record["params"];

        if (get_magic_quotes_gpc()) {
            $params = Tools::stripslashes($params);
        }

        $params = (array) Tools::jsonDecode($params);
        $layers = $record["layers"];

        if (get_magic_quotes_gpc()) {
            $layers = Tools::stripslashes($layers);
        }

        $layers = str_replace('\\"', "'", $layers);
        $layers = (array) Tools::jsonDecode($layers);
        $layers = UniteFunctionsRb::convertStdClassToArray($layers);
        $settings = (@Rbthemeslider::getIsset($record["settings"])) ? $record["settings"] : '[]';
        $settings = (array) Tools::jsonDecode($settings);
        $imageID = UniteFunctionsRb::getVal($params, "image_id");

        //get image url and thumb url
        if (!empty($imageID)) {
            $this->imageID = $imageID;
            $imageUrl = UniteFunctionsPSRb::getUrlAttachmentImage($imageID);

            if (empty($imageUrl)) {
                $imageUrl = UniteFunctionsRb::getVal($params, "image");
            }

            $this->imageThumb = UniteFunctionsPSRb::getUrlAttachmentImage(
                $imageID,
                UniteFunctionsPSRb::THUMB_MEDIUM
            );
        } else {
            $imageUrl = UniteFunctionsRb::getVal($params, "image");
        }

        if (is_ssl()) {
            $imageUrl = str_replace("http://", "https://", $imageUrl);
        }

        $this->imageUrl = $imageUrl;
        $this->imageFilepath = UniteFunctionsPSRb::getImagePathFromURL($this->imageUrl);
        $realPath = UniteFunctionsPSRb::getPathContent() . $this->imageFilepath;

        if (file_exists($realPath) == false || is_file($realPath) == false) {
            $this->imageFilepath = "";
        }

        $this->imageFilename = basename($this->imageUrl);
        $this->params = $params;
        $this->arrLayers_export = $layers;
        $ijk = 0;

        foreach ($layers as $layer) {
            if (@Rbthemeslider::getIsset($layer['image_url']) && !empty($layer['image_url'])) {
                $layers[$ijk]['image_url'] = modifyImageUrl($layers[$ijk]['image_url']);
            }

            $ijk++;
        }

        $this->arrLayers = $layers;
        $this->settings = $settings;
    }

    private function initBySlide(RbSlide $slide)
    {
        $this->id = "template";
        $this->templateID = $slide->getID();
        $this->sliderID = $slide->getSliderID();
        $this->slideOrder = $slide->getOrder();
        $this->imageUrl = $slide->getImageUrl();
        $this->imageID = $slide->getImageID();
        $this->imageThumb = $slide->getThumbUrl();
        $this->imageFilepath = $slide->getImageFilepath();
        $this->imageFilename = $slide->getImageFilename();
        $this->params = $slide->getParams();
        $this->arrLayers = $slide->getLayers();
        $this->arrChildren = $slide->getArrChildrenPure();
    }

    /**
     * 
     * init slide by post data
     */
    public function initByStreamData($postData, $slideTemplate, $sliderID, $sourceType, $additions)
    {
        $this->postData = array();
        $this->postData = (array) $postData;

        //init by global template
        $this->initBySlide($slideTemplate);

        switch ($sourceType) {
            case 'facebook':
                $this->initByFacebook($sliderID, $additions);
                break;
            case 'twitter':
                $this->initByTwitter($sliderID, $additions);
                break;
            case 'instagram':
                $this->initByInstagram($sliderID);
                break;
            case 'flickr':
                $this->initByFlickr($sliderID);
                break;
            case 'youtube':
                $this->initByYoutube($sliderID, $additions);
                break;
            case 'vimeo':
                $this->initByVimeo($sliderID, $additions);
                break;
            default:
                RbSliderFunctions::throwError("Source must be from Stream");
                break;
        }
    }

    /**
     * init the data for facebook
     * @since: 5.0
     * @change: 5.1.1 Facebook Album
     */
    private function initByFacebook($sliderID, $additions)
    {
        //set some slide params
        $this->id = RbSliderFunctions::getVal($this->postData, 'id');
        $this->params["title"] = RbSliderFunctions::getVal($this->postData, 'name');

        if (@Rbthemeslider::getIsset($this->params['enable_link']) &&
            $this->params['enable_link'] == "true" &&
            @Rbthemeslider::getIsset($this->params['link_type']) &&
            $this->params['link_type'] == "regular"
        ) {
            $link = RbSliderFunctions::getVal($this->postData, 'link');

            $this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
        }

        $this->params["state"] = "published";

        if ($this->params["background_type"] == 'image') {
            if ($additions['fb_type'] == 'album') {
                $this->imageUrl = 'https://graph.facebook.com/' . RbSliderFunctions::getVal($this->postData, 'id') . '/picture';
                $this->imageThumb = RbSliderFunctions::getVal($this->postData, 'picture');
            } else {
                $img = $this->getFacebookTimelineImage();
                $this->imageUrl = $img;
                $this->imageThumb = $img;
            }

            if (empty($this->imageUrl)) {
                $this->imageUrl = _MODULE_DIR_ . 'rbthemeslider/views/img/images/sources/fb.png';
            }

            if (is_ssl()) {
                $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
            }

            $this->imageFilename = basename($this->imageUrl);
        }

        $this->setLayersByStreamData($sliderID, 'facebook', $additions);
    }

    /**
     * init the data for twitter
     * @since: 5.0
     */
    private function initByTwitter($sliderID, $additions)
    {
        $this->id = RbSliderFunctions::getVal($this->postData, 'id');
        $this->params["title"] = RbSliderFunctions::getVal($this->postData, 'title');

        if (@Rbthemeslider::getIsset($this->params['enable_link']) &&
            $this->params['enable_link'] == "true" &&
            @Rbthemeslider::getIsset($this->params['link_type']) &&
            $this->params['link_type'] == "regular"
        ) {
            $link = 'https://twitter.com/' . $additions['twitter_user'] . '/status/' .
            RbSliderFunctions::getVal($this->postData, 'id_str');
            $this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
        }

        $this->params["state"] = "published";

        if ($this->params["background_type"] == 'trans' ||
            $this->params["background_type"] == 'image' ||
            $this->params["background_type"] == 'streamtwitter' ||
            $this->params["background_type"] == 'streamtwitterboth'
        ) {
            $img_sizes = RbSliderBase::getAllImageSizes('twitter');
            $imgResolution = RbSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));
            $this->imageID = RbSliderFunctions::getVal($this->postData, 'id');

            if (!@Rbthemeslider::getIsset($img_sizes[$imgResolution])) {
                $imgResolution = key($img_sizes);
            }

            $image_url_array = RbSliderFunctions::getVal($this->postData, 'media');
            $image_url_large = RbSliderFunctions::getVal($image_url_array, 'large');

            $img = RbSliderFunctions::getVal($image_url_large, 'media_url', '');
            $entities = RbSliderFunctions::getVal($this->postData, 'entities');

            if ($img == '') {
                $image_url_array = RbSliderFunctions::getVal($entities, 'media');

                if (is_array($image_url_array) && @Rbthemeslider::getIsset($image_url_array[0])) {
                    if (is_ssl()) {
                        $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url_https');
                    } else {
                        $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url');
                    }
                }
            }

            $urls = RbSliderFunctions::getVal($entities, 'urls');

            if (is_array($urls) && @Rbthemeslider::getIsset($urls[0])) {
                $display_url = RbSliderFunctions::getVal($urls[0], 'display_url');
                //check if youtube or vimeo is inside
                if (strpos($display_url, 'youtu.be') !== false) {
                    $raw = explode('/', $display_url);
                    $yturl = $raw[1];
                    $this->params["slide_bg_youtube"] = $yturl; //set video for background video
                } elseif (strpos($display_url, 'vimeo.com') !== false) {
                    $raw = explode('/', $display_url);
                    $vmurl = $raw[1];
                    $this->params["slide_bg_vimeo"] = $vmurl; //set video for background video
                }
            }

            $image_url_array = RbSliderFunctions::getVal($entities, 'media');

            if (is_array($image_url_array) && @Rbthemeslider::getIsset($image_url_array[0])) {
                $video_info = RbSliderFunctions::getVal($image_url_array[0], 'video_info');
                $variants = RbSliderFunctions::getVal($video_info, 'variants');

                if (is_array($variants) && @Rbthemeslider::getIsset($variants[0])) {
                    $mp4 = RbSliderFunctions::getVal($variants[0], 'url');
                    $this->params["slide_bg_html_mpeg"] = $mp4; //set video for background video
                }
            }

            $entities = RbSliderFunctions::getVal($this->postData, 'extended_entities');

            if ($img == '') {
                $image_url_array = RbSliderFunctions::getVal($entities, 'media');

                if (is_array($image_url_array) && @Rbthemeslider::getIsset($image_url_array[0])) {
                    if (is_ssl()) {
                        $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url_https');
                    } else {
                        $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url');
                    }
                }
            }

            $urls = RbSliderFunctions::getVal($entities, 'urls');

            if (is_array($urls) && @Rbthemeslider::getIsset($urls[0])) {
                $display_url = RbSliderFunctions::getVal($urls[0], 'display_url');

                //check if youtube or vimeo is inside
                if (strpos($display_url, 'youtu.be') !== false) {
                    $raw = explode('/', $display_url);
                    $yturl = $raw[1];
                    $this->params["slide_bg_youtube"] = $yturl; //set video for background video
                } elseif (strpos($display_url, 'vimeo.com') !== false) {
                    $raw = explode('/', $display_url);
                    $vmurl = $raw[1];
                    $this->params["slide_bg_vimeo"] = $vmurl; //set video for background video
                }
            }

            $image_url_array = RbSliderFunctions::getVal($entities, 'media');

            if (is_array($image_url_array) && @Rbthemeslider::getIsset($image_url_array[0])) {
                $video_info = RbSliderFunctions::getVal($image_url_array[0], 'video_info');
                $variants = RbSliderFunctions::getVal($video_info, 'variants');

                if (is_array($variants) && @Rbthemeslider::getIsset($variants[0])) {
                    $mp4 = RbSliderFunctions::getVal($variants[0], 'url');
                    $this->params["slide_bg_html_mpeg"] = $mp4; //set video for background video
                }
            }

            if ($img !== '') {
                $this->imageUrl = $img;
                $this->imageThumb = $img;
            }

            if (empty($this->imageUrl)) {
                $this->imageUrl = _MODULE_DIR_ . 'rbthemeslider/views/img/images/sources/tw.png';
            }

            if (is_ssl()) {
                $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
            }

            $this->imageFilename = basename($this->imageUrl);
        }

        $this->setLayersByStreamData($sliderID, 'twitter', $additions);
    }

    /**
     * init the data for instagram
     * @since: 5.0
     */
    private function initByInstagram($sliderID)
    {
        $this->id = RbSliderFunctions::getVal($this->postData, 'id');
        $caption = RbSliderFunctions::getVal($this->postData, 'caption');
        $this->params["title"] = RbSliderFunctions::getVal($caption, 'text');
        $link = RbSliderFunctions::getVal($this->postData, 'link');

        if (@Rbthemeslider::getIsset($this->params['enable_link']) &&
            $this->params['enable_link'] == "true" &&
            @Rbthemeslider::getIsset($this->params['link_type']) &&
            $this->params['link_type'] == "regular"
        ) {
            $this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
        }

        $this->params["state"] = "published";

        if ($this->params["background_type"] == 'trans' ||
            $this->params["background_type"] == 'image' ||
            $this->params["background_type"] == 'streaminstagram' ||
            $this->params["background_type"] == 'streaminstagramboth'
        ) {
            $img_sizes = RbSliderBase::getAllImageSizes('instagram');
            $imgResolution = RbSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));

            if (!@Rbthemeslider::getIsset($img_sizes[$imgResolution])) {
                $imgResolution = key($img_sizes);
            }

            $this->imageID = RbSliderFunctions::getVal($this->postData, 'id');
            $imgs = RbSliderFunctions::getVal($this->postData, 'images', array());
            $is = array();

            foreach ($imgs as $k => $im) {
                $is[$k] = $im->url;
            }

            $this->imageUrl = $is[$imgResolution];
            $this->imageThumb = $is['thumbnail'];

            if (empty($this->imageUrl)) {
                $this->imageUrl = _MODULE_DIR_ . 'rbthemeslider/views/img/images/sources/ig.png';
            }

            if (is_ssl()) {
                $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
            }

            $this->imageFilename = basename($this->imageUrl);
        }

        $videos = RbSliderFunctions::getVal($this->postData, 'videos');

        if (!empty($videos) &&
            @Rbthemeslider::getIsset($videos->standard_resolution) &&
            @Rbthemeslider::getIsset($videos->standard_resolution->url)
        ) {
            $this->params["slide_bg_instagram"] = $videos->standard_resolution->url; //set video for background video
            $this->params["slide_bg_html_mpeg"] = $videos->standard_resolution->url; //set video for background video
        }

        //replace placeholders in layers:
        $this->setLayersByStreamData($sliderID, 'instagram');
    }

    /**
     * init the data for flickr
     * @since: 5.0
     */
    private function initByFlickr($sliderID)
    {
        $this->id = RbSliderFunctions::getVal($this->postData, 'id');
        $this->params["title"] = RbSliderFunctions::getVal($this->postData, 'title');

        if (@Rbthemeslider::getIsset($this->params['enable_link']) &&
            $this->params['enable_link'] == "true" &&
            @Rbthemeslider::getIsset($this->params['link_type']) &&
            $this->params['link_type'] == "regular"
        ) {
            $link = 'http://flic.kr/p/' . $this->baseEncode(RbSliderFunctions::getVal($this->postData, 'id'));
            $this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
        }

        $this->params["state"] = "published";

        if ($this->params["background_type"] == 'image') {
            $img_sizes = RbSliderBase::getAllImageSizes('flickr');
            $imgResolution = RbSliderFunctions::getVal($this->params, 'image_source_type', reset($img_sizes));
            $this->imageID = RbSliderFunctions::getVal($this->postData, 'id');

            if (!@Rbthemeslider::getIsset($img_sizes[$imgResolution])) {
                $imgResolution = key($img_sizes);
            }

            $is = @array(
                'square' => RbSliderFunctions::getVal($this->postData, 'url_sq'),
                'large-square' => RbSliderFunctions::getVal($this->postData, 'url_q'),
                'thumbnail' => RbSliderFunctions::getVal($this->postData, 'url_t'),
                'small' => RbSliderFunctions::getVal($this->postData, 'url_s'),
                'small-320' => RbSliderFunctions::getVal($this->postData, 'url_n'),
                'medium' => RbSliderFunctions::getVal($this->postData, 'url_m'),
                'medium-640' => RbSliderFunctions::getVal($this->postData, 'url_z'),
                'medium-800' => RbSliderFunctions::getVal($this->postData, 'url_c'),
                'large' => RbSliderFunctions::getVal($this->postData, 'url_l'),
                'original' => RbSliderFunctions::getVal($this->postData, 'url_o')
            );

            $this->imageUrl = (@Rbthemeslider::getIsset($is[$imgResolution])) ? $is[$imgResolution] : '';
            $this->imageThumb = (@Rbthemeslider::getIsset($is['thumbnail'])) ? $is['thumbnail'] : '';

            if (empty($this->imageUrl)) {
                $this->imageUrl = _MODULE_DIR_ . 'rbthemeslider/views/img/images/sources/fr.png';
            }

            if (is_ssl()) {
                $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
            }

            $this->imageFilename = basename($this->imageUrl);
        }

        $this->setLayersByStreamData($sliderID, 'flickr');
    }

    /**
     * init the data for youtube
     * @since: 5.0
     */
    private function initByYoutube($sliderID, $additions)
    {
        $snippet = RbSliderFunctions::getVal($this->postData, 'snippet');
        $resource = RbSliderFunctions::getVal($snippet, 'resourceId');

        if ($additions['yt_type'] == 'channel') {
            $link_raw = RbSliderFunctions::getVal($this->postData, 'id');
            $link = RbSliderFunctions::getVal($link_raw, 'videoId');
        } else {
            $link_raw = RbSliderFunctions::getVal($snippet, 'resourceId');
            $link = RbSliderFunctions::getVal($link_raw, 'videoId');
        }

        if (@Rbthemeslider::getIsset($this->params['enable_link']) &&
            $this->params['enable_link'] == "true" &&
            @Rbthemeslider::getIsset($this->params['link_type']) &&
            $this->params['link_type'] == "regular"
        ) {
            if ($link !== '') {
                $link = '//youtube.com/watch?v=' . $link;
            }

            $this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
        }

        $this->params["slide_bg_youtube"] = $link;

        switch ($additions['yt_type']) {
            case 'channel':
                $id = RbSliderFunctions::getVal($this->postData, 'id');
                $this->id = RbSliderFunctions::getVal($id, 'videoId');
                break;
            case 'playlist':
                $this->id = RbSliderFunctions::getVal($resource, 'videoId');
                break;
        }

        if ($this->id == '') {
            $this->id = 'not-found';
        }

        $this->params["title"] = RbSliderFunctions::getVal($snippet, 'title');

        $this->params["state"] = "published";

        if ($this->params["background_type"] == 'trans' ||
            $this->params["background_type"] == 'image' ||
            $this->params["background_type"] == 'streamyoutube' ||
            $this->params["background_type"] == 'streamyoutubeboth'
        ) {
            $img_sizes = RbSliderBase::getAllImageSizes('youtube');

            $imgResolution = RbSliderFunctions::getVal(
                $this->params,
                'image_source_type',
                reset($img_sizes)
            );

            $this->imageID = RbSliderFunctions::getVal($resource, 'videoId');

            if (!@Rbthemeslider::getIsset($img_sizes[$imgResolution])) {
                $imgResolution = key($img_sizes);
            }

            $thumbs = RbSliderFunctions::getVal($snippet, 'thumbnails');
            $is = array();

            if (!empty($thumbs)) {
                foreach ($thumbs as $name => $vals) {
                    $is[$name] = RbSliderFunctions::getVal($vals, 'url');
                }
            }

            $this->imageUrl = (@Rbthemeslider::getIsset($is[$imgResolution])) ? $is[$imgResolution] : '';
            $this->imageThumb = (@Rbthemeslider::getIsset($is['medium'])) ? $is['medium'] : '';

            if (empty($this->imageUrl)) {
                $this->imageUrl = _MODULE_DIR_ . 'rbthemeslider/views/img/images/sources/yt.png';
            }

            if (is_ssl()) {
                $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
            }

            $this->imageFilename = basename($this->imageUrl);
        }

        $this->setLayersByStreamData($sliderID, 'youtube', $additions);
    }

    /**
     * init the data for vimeo
     * @since: 5.0
     */
    private function initByVimeo($sliderID, $additions)
    {
        $this->id = RbSliderFunctions::getVal($this->postData, 'id');
        $this->params["title"] = RbSliderFunctions::getVal($this->postData, 'title');

        if (@Rbthemeslider::getIsset($this->params['enable_link']) &&
            $this->params['enable_link'] == "true" &&
            @Rbthemeslider::getIsset($this->params['link_type']) &&
            $this->params['link_type'] == "regular"
        ) {
            $link = RbSliderFunctions::getVal($this->postData, 'url');
            $this->params["link"] = str_replace(array("%link%", '{{link}}'), $link, $this->params["link"]);
        }

        $this->params["slide_bg_vimeo"] = RbSliderFunctions::getVal($this->postData, 'url');
        $this->params["state"] = "published";

        if ($this->params["background_type"] == 'trans' ||
            $this->params["background_type"] == 'image' ||
            $this->params["background_type"] == 'streamvimeo' ||
            $this->params["background_type"] == 'streamvimeoboth'
        ) {
            $img_sizes = RbSliderBase::getAllImageSizes('vimeo');

            $imgResolution = RbSliderFunctions::getVal(
                $this->params,
                'image_source_type',
                reset($img_sizes)
            );

            $this->imageID = RbSliderFunctions::getVal($this->postData, 'id');

            if (!@Rbthemeslider::getIsset($img_sizes[$imgResolution])) {
                $imgResolution = key($img_sizes);
            }

            $is = array();

            foreach ($img_sizes as $handle => $name) {
                $is[$handle] = RbSliderFunctions::getVal($this->postData, $handle);
            }

            $this->imageUrl = (@Rbthemeslider::getIsset($is[$imgResolution])) ? $is[$imgResolution] : '';
            $this->imageThumb = (@Rbthemeslider::getIsset($is['thumbnail'])) ? $is['thumbnail'] : '';

            if (empty($this->imageUrl)) {
                $this->imageUrl = _MODULE_DIR_ . 'rbthemeslider/views/img/images/sources/vm.png';
            }

            if (is_ssl()) {
                $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
            }

            $this->imageFilename = basename($this->imageUrl);
        }

        $this->setLayersByStreamData($sliderID, 'vimeo', $additions);
    }

    /**
     * replace layer placeholders by stream data
     * @since: 5.0
     */
    private function setLayersByStreamData($sliderID, $stream_type, $additions = array())
    {
        $attr = $this->returnStreamData($stream_type, $additions);

        foreach ($this->arrLayers as $key => $layer) {
            $text = RbSliderFunctions::getVal($layer, "text");
            $text = $this->setStreamData($text, $attr, $stream_type, $additions);
            $layer["text"] = $text;

            //set link actions to the stream data
            $layer['layer_action'] = (array) $layer['layer_action'];

            if (@Rbthemeslider::getIsset($layer['layer_action'])) {
                if (@Rbthemeslider::getIsset($layer['layer_action']['image_link']) &&
                    !empty($layer['layer_action']['image_link'])
                ) {
                    foreach ($layer['layer_action']['image_link'] as $jtsk => $jtsval) {
                        $layer['layer_action']['image_link'][$jtsk] = $this->setStreamData(
                            $layer['layer_action']['image_link'][$jtsk],
                            $attr,
                            $stream_type,
                            $additions,
                            true
                        );
                    }
                }
            }

            $this->arrLayers[$key] = $layer;
        }

        //set params to the stream data
        for ($mi = 1; $mi <= 10; $mi++) {
            $pa = $this->getParam('params_' . $mi, '');
            $pa = $this->setStreamData($pa, $attr, $stream_type, $additions);
            $this->setParam('params_' . $mi, $pa);
        }
    }

    public function setStreamData(
        $text,
        $attr,
        $stream_type,
        $additions = array(),
        $is_action = false
    ) {
        $img_sizes = RbSliderBase::getAllImageSizes($stream_type);
        $text = str_replace(array('%title%', '{{title}}'), $attr['title'], $text);
        $text = str_replace(array('%excerpt%', '{{excerpt}}'), $attr['excerpt'], $text);
        $text = str_replace(array('%alias%', '{{alias}}'), $attr['alias'], $text);
        $text = str_replace(array('%content%', '{{content}}'), $attr['content'], $text);
        $text = str_replace(array('%link%', '{{link}}'), $attr['link'], $text);
        $text = str_replace(array('%date_published%', '{{date_published}}', '%date%', '{{date}}'), $attr['date'], $text);
        $text = str_replace(array('%date_modified%', '{{date_modified}}'), $attr['date_modified'], $text);
        $text = str_replace(array('%author_name%', '{{author_name}}'), $attr['author_name'], $text);
        $text = str_replace(array('%num_comments%', '{{num_comments}}'), $attr['num_comments'], $text);
        $text = str_replace(array('%catlist%', '{{catlist}}'), $attr['catlist'], $text);
        $text = str_replace(array('%taglist%', '{{taglist}}'), $attr['taglist'], $text);
        $text = str_replace(array('%likes%', '{{likes}}'), $attr['likes'], $text);
        $text = str_replace(array('%retweet_count%', '{{retweet_count}}'), $attr['retweet_count'], $text);
        $text = str_replace(array('%favorite_count%', '{{favorite_count}}'), $attr['favorite_count'], $text);
        $text = str_replace(array('%views%', '{{views}}'), $attr['views'], $text);

        if ($stream_type == 'twitter' && $is_action === false) {
            $text = RbSliderBase::addWrapAroundUrl($text);
        }

        switch ($stream_type) {
            case 'facebook':
                foreach ($img_sizes as $img_handle => $img_name) {
                    if (!@Rbthemeslider::getIsset($attr['img_urls'])) {
                        $attr['img_urls'] = array();
                    }

                    if (!@Rbthemeslider::getIsset($attr['img_urls'][$img_handle])) {
                        $attr['img_urls'][$img_handle] = array();
                    }

                    if (!@Rbthemeslider::getIsset($attr['img_urls'][$img_handle]['url'])) {
                        $attr['img_urls'][$img_handle]['url'] = '';
                    }

                    if (!@Rbthemeslider::getIsset($attr['img_urls'][$img_handle]['tag'])) {
                        $attr['img_urls'][$img_handle]['tag'] = '';
                    }

                    if ($additions['fb_type'] == 'album') {
                        $text = str_replace(array('%image_url_' . $img_handle . '%', '{{image_url_' . $img_handle . '}}'), $attr['img_urls'][$img_handle]['url'], $text);
                        $text = str_replace(array('%image_' . $img_handle . '%', '{{image_' . $img_handle . '}}'), $attr['img_urls'][$img_handle]['tag'], $text);
                    } else {
                        $text = str_replace(array('%image_url_' . $img_handle . '%', '{{image_url_' . $img_handle . '}}'), $attr['img_urls']['url'], $text);
                        $text = str_replace(array('%image_' . $img_handle . '%', '{{image_' . $img_handle . '}}'), $attr['img_urls']['tag'], $text);
                    }
                }

                break;
            case 'youtube':
            case 'vimeo':
            case 'twitter':
            case 'instagram':
            case 'flickr':
                foreach ($img_sizes as $img_handle => $img_name) {
                    if (!@Rbthemeslider::getIsset($attr['img_urls'])) {
                        $attr['img_urls'] = array();
                    }

                    if (!@Rbthemeslider::getIsset($attr['img_urls'][$img_handle])) {
                        $attr['img_urls'][$img_handle] = array();
                    }

                    if (!@Rbthemeslider::getIsset($attr['img_urls'][$img_handle]['url'])) {
                        $attr['img_urls'][$img_handle]['url'] = '';
                    }

                    if (!@Rbthemeslider::getIsset($attr['img_urls'][$img_handle]['tag'])) {
                        $attr['img_urls'][$img_handle]['tag'] = '';
                    }

                    $text = str_replace(array('%image_url_' . $img_handle . '%', '{{image_url_' . $img_handle . '}}'), $attr['img_urls'][$img_handle]['url'], $text);
                    $text = str_replace(array('%image_' . $img_handle . '%', '{{image_' . $img_handle . '}}'), $attr['img_urls'][$img_handle]['tag'], $text);
                }

                break;
        }

        return $text;
    }

    public function returnStreamData($stream_type, $additions = array())
    {
        $attr = array();
        $attr['title'] = '';
        $attr['excerpt'] = '';
        $attr['alias'] = '';
        $attr['content'] = '';
        $attr['link'] = '';
        $attr['date'] = '';
        $attr['date_modified'] = '';
        $attr['author_name'] = '';
        $attr['num_comments'] = '';
        $attr['catlist'] = '';
        $attr['taglist'] = '';
        $attr['likes'] = '';
        $attr['retweet_count'] = '';
        $attr['favorite_count'] = '';
        $attr['views'] = '';
        $attr['img_urls'] = array();

        $img_sizes = RbSliderBase::getAllImageSizes($stream_type);

        switch ($stream_type) {
            case 'facebook':
                if ($additions['fb_type'] == 'album') {
                    $attr['title'] = RbSliderFunctions::getVal($this->postData, 'name');
                    $attr['content'] = RbSliderFunctions::getVal($this->postData, 'name');
                    $attr['link'] = RbSliderFunctions::getVal($this->postData, 'link');

                    $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                        RbSliderFunctions::getVal($this->postData, 'created_time'),
                        true
                    );

                    $attr['date_modified'] = RbSliderFunctionsPS::convertPostDate(
                        RbSliderFunctions::getVal($this->postData, 'updated_time'),
                        true
                    );

                    $author_name_raw = RbSliderFunctions::getVal($this->postData, 'from');
                    $attr['author_name'] = $author_name_raw->name;
                    $likes_data = RbSliderFunctions::getVal($this->postData, 'likes');
                    $attr['likes'] = count(RbSliderFunctions::getVal($likes_data, 'data'));
                    $fb_img_thumbnail = RbSliderFunctions::getVal($this->postData, 'picture');
                    $fb_img = 'https://graph.facebook.com/' . RbSliderFunctions::getVal($this->postData, 'id') . '/picture';

                    $attr['img_urls']['full'] = array(
                        'url' => $fb_img,
                        'tag' => '<img src="' . $fb_img . '" data-no-retina />'
                    );

                    $attr['img_urls']['thumbnail'] = array(
                        'url' => $fb_img_thumbnail,
                        'tag' => '<img src="' . $fb_img_thumbnail . '" data-no-retina />'
                    );
                } else {
                    $attr['title'] = RbSliderFunctions::getVal($this->postData, 'message');
                    $attr['content'] = RbSliderFunctions::getVal($this->postData, 'message');
                    $post_url = explode('_', RbSliderFunctions::getVal($this->postData, 'id'));
                    $attr['link'] = 'https://www.facebook.com/' . $additions['fb_user_id'] . '/posts/' . $post_url[1];

                    $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                        RbSliderFunctions::getVal($this->postData, 'created_time'),
                        true
                    );

                    $attr['date_modified'] = RbSliderFunctionsPS::convertPostDate(
                        RbSliderFunctions::getVal($this->postData, 'updated_time'),
                        true
                    );

                    $author_name_raw = RbSliderFunctions::getVal($this->postData, 'from');
                    $attr['author_name'] = $author_name_raw->name;
                    $likes_data = RbSliderFunctions::getVal($this->postData, 'likes');
                    $likes_data = RbSliderFunctions::getVal($likes_data, 'summary');
                    $likes_data = RbSliderFunctions::getVal($likes_data, 'total_count');
                    $attr['likes'] = (int) ($likes_data);
                    $img = $this->getFacebookTimelineImage();

                    $attr['img_urls'] = array(
                        'url' => $img,
                        'tag' => '<img src="' . $img . '" data-no-retina />'
                    );
                }
                break;
            case 'twitter':
                $user = RbSliderFunctions::getVal($this->postData, 'user');
                $attr['title'] = RbSliderFunctions::getVal($this->postData, 'text');
                $attr['content'] = RbSliderFunctions::getVal($this->postData, 'text');
                $attr['link'] = 'https://twitter.com/' . $additions['twitter_user'] . '/status/' .
                RbSliderFunctions::getVal($this->postData, 'id_str');

                $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                    RbSliderFunctions::getVal($this->postData, 'created_at'),
                    true
                );

                $attr['author_name'] = RbSliderFunctions::getVal($user, 'screen_name');
                $attr['retweet_count'] = RbSliderFunctions::getVal($this->postData, 'retweet_count', '0');
                $attr['favorite_count'] = RbSliderFunctions::getVal($this->postData, 'favorite_count', '0');
                $image_url_array = RbSliderFunctions::getVal($this->postData, 'media');
                $image_url_large = RbSliderFunctions::getVal($image_url_array, 'large');
                $img = RbSliderFunctions::getVal($image_url_large, 'media_url', '');

                if ($img == '') {
                    $entities = RbSliderFunctions::getVal($this->postData, 'entities');
                    $image_url_array = RbSliderFunctions::getVal($entities, 'media');

                    if (is_array($image_url_array) && @Rbthemeslider::getIsset($image_url_array[0])) {
                        if (is_ssl()) {
                            $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url_https');
                        } else {
                            $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url');
                        }

                        $image_url_large = $image_url_array[0];
                    }
                }

                if ($img == '') {
                    $entities = RbSliderFunctions::getVal($this->postData, 'extended_entities');
                    $image_url_array = RbSliderFunctions::getVal($entities, 'media');

                    if (is_array($image_url_array) && @Rbthemeslider::getIsset($image_url_array[0])) {
                        if (is_ssl()) {
                            $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url_https');
                        } else {
                            $img = RbSliderFunctions::getVal($image_url_array[0], 'media_url');
                        }

                        $image_url_large = $image_url_array[0];
                    }
                }

                if ($img !== '') {
                    $w = RbSliderFunctions::getVal($image_url_large, 'w', '');
                    $h = RbSliderFunctions::getVal($image_url_large, 'h', '');
                    $attr['img_urls']['large'] = array(
                        'url' => $img,
                        'tag' => '<img src="' . $img . '" width="' . $w . '" height="' . $h . '" data-no-retina />'
                    );
                }

                break;
            case 'instagram':
                $caption = RbSliderFunctions::getVal($this->postData, 'caption');
                $user = RbSliderFunctions::getVal($this->postData, 'user');
                $attr['title'] = RbSliderFunctions::getVal($caption, 'text');
                $attr['content'] = RbSliderFunctions::getVal($caption, 'text');
                $attr['link'] = RbSliderFunctions::getVal($this->postData, 'link');

                $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                    RbSliderFunctions::getVal($this->postData, 'created_time'),
                    true
                );

                $attr['author_name'] = RbSliderFunctions::getVal($user, 'username');
                $likes_raw = RbSliderFunctions::getVal($this->postData, 'likes');
                $attr['likes'] = RbSliderFunctions::getVal($likes_raw, 'count');
                $comments_raw = RbSliderFunctions::getVal($this->postData, 'comments');
                $attr['num_comments'] = RbSliderFunctions::getVal($comments_raw, 'count');
                $inst_img = RbSliderFunctions::getVal($this->postData, 'images', array());

                foreach ($inst_img as $key => $img) {
                    $attr['img_urls'][$key] = array(
                        'url' => $img->url,
                        'tag' => '<img src="' . $img->url . '" width="' . $img->width .
                        '" height="' . $img->height . '" data-no-retina />'
                    );
                }
                break;
            case 'flickr':
                $attr['title'] = RbSliderFunctions::getVal($this->postData, 'title');
                $tc = RbSliderFunctions::getVal($this->postData, 'description');
                $attr['content'] = RbSliderFunctions::getVal($tc, '_content');

                $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                    RbSliderFunctions::getVal($this->postData,'datetaken')
                );

                $attr['author_name'] = RbSliderFunctions::getVal($this->postData, 'ownername');

                $attr['link'] = 'http://flic.kr/p/' . $this->baseEncode(
                    RbSliderFunctions::getVal($this->postData, 'id')
                );

                $attr['views'] = RbSliderFunctions::getVal($this->postData, 'views');

                $attr['img_urls'] = @array(
                    'square' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_sq'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_sq') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_sq') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_sq') . '" data-no-retina />'),
                    'large-square' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_q'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_q') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_q') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_q') . '"  data-no-retina />'),
                    'thumbnail' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_t'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_t') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_t') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_t') . '"  data-no-retina />'),
                    'small' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_s'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_s') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_s') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_s') . '"  data-no-retina />'),
                    'small-320' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_n'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_n') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_n') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_n') . '"  data-no-retina />'),
                    'medium' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_m'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_m') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_m') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_m') . '"  data-no-retina />'),
                    'medium-640' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_z'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_z') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_z') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_z') . '"  data-no-retina />'),
                    'medium-800' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_c'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_c') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_c') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_c') . '"  data-no-retina />'),
                    'large' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_l'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_l') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_l') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_l') . '"  data-no-retina />'),
                    'original' => array('url' => RbSliderFunctions::getVal($this->postData, 'url_o'), 'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, 'url_o') . '" width="' . RbSliderFunctions::getVal($this->postData, 'width_o') . '" height="' . RbSliderFunctions::getVal($this->postData, 'height_o') . '"  data-no-retina />')
                );
                break;
            case 'youtube':
                $snippet = RbSliderFunctions::getVal($this->postData, 'snippet');
                $attr['title'] = RbSliderFunctions::getVal($snippet, 'title');
                $attr['excerpt'] = RbSliderFunctions::getVal($snippet, 'description');
                $attr['content'] = RbSliderFunctions::getVal($snippet, 'description');

                $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                    RbSliderFunctions::getVal($snippet, 'publishedAt')
                );

                if ($additions['yt_type'] == 'channel') {
                    $link_raw = RbSliderFunctions::getVal($this->postData, 'id');
                    $attr['link'] = RbSliderFunctions::getVal($link_raw, 'videoId');
                    if ($attr['link'] !== '') {
                        $attr['link'] = '//youtube.com/watch?v=' . $attr['link'];
                    }
                } else {
                    $link_raw = RbSliderFunctions::getVal($this->postData, 'resourceId');
                    $attr['link'] = RbSliderFunctions::getVal($link_raw, 'videoId');
                    if ($attr['link'] !== '') {
                        $attr['link'] = '//youtube.com/watch?v=' . $attr['link'];
                    }
                }

                $thumbs = RbSliderFunctions::getVal($snippet, 'thumbnails');
                $attr['img_urls'] = array();

                if (!empty($thumbs)) {
                    foreach ($thumbs as $name => $vals) {
                        $attr['img_urls'][$name] = array(
                            'url' => RbSliderFunctions::getVal($vals, 'url'),
                        );
                        switch ($additions['yt_type']) {
                            case 'channel':
                                $attr['img_urls'][$name]['tag'] = '<img src="' . RbSliderFunctions::getVal($vals, 'url') . '" data-no-retina />';
                                break;
                            case 'playlist':
                                $attr['img_urls'][$name]['tag'] = '<img src="' . RbSliderFunctions::getVal($vals, 'url') . '" width="' . RbSliderFunctions::getVal($vals, 'width') . '" height="' . RbSliderFunctions::getVal($vals, 'height') . '" data-no-retina />';
                                break;
                        }
                    }
                }
                break;
            case 'vimeo':
                $attr['title'] = RbSliderFunctions::getVal($this->postData, 'title');
                $attr['excerpt'] = RbSliderFunctions::getVal($this->postData, 'description');
                $attr['content'] = RbSliderFunctions::getVal($this->postData, 'description');

                $attr['date'] = RbSliderFunctionsPS::convertPostDate(
                    RbSliderFunctions::getVal($this->postData, 'upload_date')
                );

                $attr['likes'] = RbSliderFunctions::getVal($this->postData, 'stats_number_of_likes');
                $attr['views'] = RbSliderFunctions::getVal($this->postData, 'stats_number_of_plays');
                $attr['num_comments'] = RbSliderFunctions::getVal($this->postData, 'stats_number_of_comments');
                $attr['link'] = RbSliderFunctions::getVal($this->postData, 'url');
                $attr['author_name'] = RbSliderFunctions::getVal($this->postData, 'user_name');
                $attr['img_urls'] = array();

                if (!empty($img_sizes)) {
                    foreach ($img_sizes as $name => $vals) {
                        $attr['img_urls'][$name] = array(
                            'url' => RbSliderFunctions::getVal($this->postData, $name),
                            'tag' => '<img src="' . RbSliderFunctions::getVal($this->postData, $name) . '" data-no-retina />'
                        );
                    }
                }

                break;
        }

        return $attr;
    }

    public function findBiggestPhoto($image_urls, $wanted_size, $avail_sizes)
    {
        if (!$this->isEmpty(@$image_urls[$wanted_size])) {
            return $image_urls[$wanted_size];
        }

        $wanted_size_pos = array_search($wanted_size, $avail_sizes);

        for ($i = $wanted_size_pos; $i < 7; $i++) {
            if (!$this->isEmpty(@$image_urls[$avail_sizes[$i]])) {
                return $image_urls[$avail_sizes[$i]];
            }
        }

        for ($i = $wanted_size_pos; $i >= 0; $i--) {
            if (!$this->isEmpty(@$image_urls[$avail_sizes[$i]])) {
                return $image_urls[$avail_sizes[$i]];
            }
        }
    }

    public function isEmpty($stringOrArray)
    {
        if (is_array($stringOrArray)) {
            foreach ($stringOrArray as $value) {
                if (!$this->isEmpty($value)) {
                    return false;
                }
            }

            return true;
        }

        return !Tools::strlen($stringOrArray);
    }

    public function getFacebookTimelineImage()
    {
        $object_id = RbSliderFunctions::getVal($this->postData, 'object_id', '');
        $picture = RbSliderFunctions::getVal($this->postData, 'picture', '');

        if (!empty($object_id)) {
            return 'https://graph.facebook.com/' .
            RbSliderFunctions::getVal($this->postData, 'object_id', '') . '/picture';
        } elseif (!empty($picture)) {

            $image_url = $this->decodeFacebookUrl(
                RbSliderFunctions::getVal($this->postData, 'picture', '')
            );

            $image_url = parse_str(parse_url($image_url, PHP_URL_QUERY), $array);
            $image_url = explode('&', $array['url']);

            return $image_url[0];
        }

        return '';
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

    /**
     * Encode the flickr ID for URL (base58)
     *
     * @since    5.0
     * @param    string    $num 	flickr photo id
     */
    private function baseEncode(
        $num,
        $alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ'
    ) {
        $base_count = Tools::strlen($alphabet);
        $alphabet = str_split($alphabet);
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

    public function initByPostData($postData, RbSlide $slideTemplate, $sliderID)
    {
        $this->postData = $this->postData;
        $postID = $postData['id_product'];
        $arrWildcardsValues = RbOperations::getPostWilcardValues($postID);
        $slideTemplateID = UniteFunctionsRb::getVal($arrWildcardsValues, "slide_template");

        if (!empty($slideTemplateID) && is_numeric($slideTemplateID)) {
            try {
                $slideTemplateLocal = new RbSlide();
                $slideTemplateLocal->initByID($slideTemplateID);
                $this->initBySlide($slideTemplateLocal);
            } catch (Exception $e) {
                $this->initBySlide($slideTemplate);
            }
        } else {
            $this->initBySlide($slideTemplate);
        }

        $this->id = $postID;
        $this->params["title"] = UniteFunctionsRb::getVal($postData, "post_title");
        $status = $postData["active"];

        if ($status == 1) {
            $this->params["state"] = "published";
        } else {
            $this->params["state"] = "unpublished";
        }

        $rbSlider = new RbSlider();
        $getSliderImgSettings = $rbSlider->getSliderImgSettings($sliderID);

        if (!empty($postID)) {
            $this->setImageByImageID($postID, $getSliderImgSettings);
        }

        $this->setLayersByPostData($postData, $sliderID);
    }

    private function setImageSrc($postData = array())
    {
        $link = new Link();

        $lnk = $link->getImageLink(
            $postData['link_rewrite'],
            $postData['id_image']
        );

        if (@Rbthemeslider::getIsset($lnk) && !empty($lnk)) {
            return 'http://' . htmlspecialchars_decode($lnk);
        } else {
            return false;
        }
    }

    private function setCountDown($postData = array())
    {
        $html = '';

        if (@Rbthemeslider::getIsset($postData) &&
            @Rbthemeslider::getIsset($postData['specific_prices']) &&
            !empty($postData['specific_prices'])
        ) {
            $id_product = $postData['id_product'];
            $specific_prices = $postData['specific_prices'];
            $to_time = $specific_prices['to'];
            $to_time_str = strtotime($to_time);
            $to_time_y = date("Y", $to_time_str);
            $to_time_m = date("m", $to_time_str);
            $to_time_d = date("d", $to_time_str);
            $to_time_h = date("H", $to_time_str);
            $to_time_i = date("i", $to_time_str);
            $to_time_s = date("s", $to_time_str);
            $from_time = $specific_prices['from'];
            $now_time = date("Y-m-d H:i:s");

            if ($now_time <= $to_time && $now_time >= $from_time) {
                $html .= '<div class="product_count_down">
						<span class="turning_clock"></span>
						<div class="count_holder_small">
						<div class="count_info">
						</div>
						<div id="sds_rb_countdown_' . $id_product . '" class="count_content clearfix">
						</div>
						<div class="clear"></div>
						</div>
						</div>';
                $html .= "<script type='text/javascript'>
						$(function() {
                                      $('#sds_rb_countdown_" . $id_product . "').countdown({
                                          until: new Date(" . $to_time_y . "," . $to_time_m . " - 1," . $to_time_d . "," . $to_time_h . "," . $to_time_i . "," . $to_time_s . "), compact: false});
                                  });
		</script>";
                $countdown_js = __PS_BASE_URI__ . 'modules/rbthemeslider/js/countdown/jquery.countdown.js';
                $countdown_css = __PS_BASE_URI__ . 'modules/rbthemeslider/css/countdown/countdown.css';
                Context::getcontext()->controller->addJs($countdown_js);
                Context::getcontext()->controller->addCSS($countdown_css);
            }
        }

        return $html;
    }

    private function setLayersByPostData($postData, $sliderID)
    {
        $priceDisplay = Product::getTaxCalculationMethod((int) Context::getcontext()->cookie->id_customer);

        if (!$priceDisplay) {
            $productprice = Tools::displayPrice($postData["price"], Context::getContext()->currency);
        } else {
            $productprice = Tools::displayPrice($postData["price_tax_exc"], Context::getContext()->currency);
        }

        $postID = $postData["id_product"];
        $countdown = $this->setCountDown($postData);

        // $imgsrc = $this->setImageSrc($postData);

        $title = UniteFunctionsRb::getVal($postData, "name");

        $excerpt_limit = $this->getSliderParam(
            $sliderID,
            "excerpt_limit",
            55,
            RbSlider::VALIDATE_NUMERIC
        );

        $excerpt_lmit = (int) $excerpt_limit;
        $description = Tools::substr($postData["description"], $excerpt_limit);
        $description_short = $postData["description_short"];
        $link = $postData["link"];
        $date_add = $postData["date_add"];
        $date_upd = $postData["date_upd"];
        $default_category = $postData["default_category"];
        $linkobj = new Link();

        $addtocart = $linkobj->getPageLink(
            'cart',
            false,
            null,
            "add=1&amp;id_product=" . $postID,
            false
        );

        foreach ($this->arrLayers as $key => $layer) {
            $text = UniteFunctionsRb::getVal($layer, "text");
            $text = str_replace("%title%", $title, $text);
            $text = str_replace("%description_short%", $description_short, $text);
            $text = str_replace("%description%", $description, $text);
            $text = str_replace("%link%", $link, $text);
            $text = str_replace("%addtocart%", $addtocart, $text);
            $text = str_replace("%countdown%", $countdown, $text);
            $text = str_replace("%date%", $date_add, $text);
            $text = str_replace("%date_modified%", $date_upd, $text);
            $text = str_replace("%product_price%", $productprice, $text);
            $text = str_replace("%default_category%", $default_category, $text);
            $arrMatches = array();
            $text = str_replace('-', '_RBSLIDER_', $text);

            preg_match_all('/%product:\w+%/', $text, $arrMatches);

            foreach ($arrMatches as $matched) {
                foreach ($matched as $match) {
                    $meta = str_replace("%product:", "", $match);
                    $meta = str_replace("%", "", $meta);
                    $meta = str_replace('_RBSLIDER_', '-', $meta);

                    if (@Rbthemeslider::getIsset($postData[$meta]) && !empty($postData[$meta])) {
                        $metaValue = $postData[$meta];
                        $text = str_replace($match, $metaValue, $text);
                    }
                }
            }

            $text = str_replace('_RBSLIDER_', '-', $text);
            $extra_hook_meta_exec = array();

            Hook::exec('actionsdsrbinsertmetaexec', array(
                'extra_hook_meta_exec' => &$extra_hook_meta_exec,
                'id_product' => &$postID,
            ));

            if (@Rbthemeslider::getIsset($extra_hook_meta_exec) && !empty($extra_hook_meta_exec)) {
                foreach ($extra_hook_meta_exec as $svalue) {
                    $hook_title = "%" . $svalue['title'] . "%";
                    $hook_exec = $svalue['exec'];
                    $text = str_replace($hook_title, $hook_exec, $text);
                }
            }

            $layer["text"] = $text;
            $this->arrLayers[$key] = $layer;
        }
    }

    public function initByID($slideid)
    {
        if (strpos($slideid, 'static_') !== false) {
            $this->static_slide = true;
            $sliderID = str_replace('static_', '', $slideid);
            UniteFunctionsRb::validateNumeric($sliderID, "Slider ID");
            $sliderID = $this->db->escape($sliderID);

            $record = $this->db->fetch(
                GlobalsRbSlider::$table_static_slides,
                "slider_id=$sliderID"
            );

            if (empty($record)) {
                $slide_id = $this->createSlide($sliderID, "", true);

                $record = $this->db->fetch(
                    GlobalsRbSlider::$table_static_slides,
                    "slider_id=$sliderID"
                );

                $this->initByData($record[0]);
            } else {
                $this->initByData($record[0]);
            }
        } else {
            UniteFunctionsRb::validateNumeric($slideid, "Slide ID");
            $slideid = $this->db->escape($slideid);

            $record = $this->db->fetchSingle(
                GlobalsRbSlider::$table_slides,
                "id=$slideid"
            );

            $this->initByData($record);
        }
    }

    public function initByStaticID($slideid)
    {
        UniteFunctionsRb::validateNumeric($slideid, "Slide ID");
        $slideid = $this->db->escape($slideid);

        $record = $this->db->fetchSingle(
            GlobalsRbSlider::$table_static_slides,
            "id=$slideid"
        );

        $this->initByData($record);
    }

    public function getStaticSlideID($sliderID)
    {
        UniteFunctionsRb::validateNumeric($sliderID, "Slider ID");
        $sliderID = $this->db->escape($sliderID);

        $record = $this->db->fetch(
            GlobalsRbSlider::$table_static_slides,
            "slider_id=$sliderID"
        );

        if (empty($record)) {
            return false;
        } else {
            return $record[0]['id'];
        }
    }

    private function setImageByImageID($postID, $img_type = '')
    {
        $prdid_image = Product::getCover($postID);

        if (sizeof($prdid_image) > 0) {
            $prdimage = new Image($prdid_image['id_image']);
            $prdimage_url = _PS_BASE_URL_ . _THEME_PROD_DIR_;
            $prdimage_url .= $prdimage->getExistingImgPath() .
            (!empty($img_type) ? "-{$img_type}" : '') . ".jpg";
        }

        $this->imageID = 0;
        $this->imageUrl = $prdimage_url;
        $this->imageThumb = $prdimage_url;

        if (empty($this->imageUrl)) {
            return(false);
        }

        $this->params["background_type"] = "image";

        if (is_ssl()) {
            $this->imageUrl = str_replace("http://", "https://", $this->imageUrl);
        }

        $this->imageFilepath = $prdimage_url;
        $realPath = $prdimage_url;

        if (file_exists($realPath) == false || is_file($realPath) == false) {
            $this->imageFilepath = "";
        }

        $this->imageFilename = basename($this->imageUrl);
    }

    public function setArrChildren($arrChildren)
    {
        $this->arrChildren = $arrChildren;
    }

    public function getArrChildren()
    {
        $this->validateInited();

        if ($this->arrChildren === null) {
            $slider = new RbSlider();
            $slider->initByID($this->sliderID);
            $this->arrChildren = $slider->getArrSlideChildren($this->id);
        }

        return($this->arrChildren);
    }

    public function isFromPost()
    {
        return !empty($this->postData);
    }

    public function getPostData()
    {
        return($this->postData);
    }

    public function getArrChildrenPure()
    {
        return($this->arrChildren);
    }

    public function isParent()
    {
        $parentID = $this->getParam("parentid", "");

        return(!empty($parentID));
    }

    public function getLang()
    {
        $lang = $this->getParam("lang", "all");
        return($lang);
    }

    public function getParentSlide()
    {
        $parentID = $this->getParam("parentid", "");

        if (empty($parentID)) {
            return($this);
        }

        $parentSlide = new RbSlide();
        $parentSlide->initByID($parentID);

        return($parentSlide);
    }

    /**
     * return parent slide id
     * @since: 5.0
     */
    public function getParentSlideID()
    {
        $parentID = $this->getParam("parentid", "");

        return $parentID;
    }

    public function getArrChildrenIDs()
    {
        $arrChildren = $this->getArrChildren();
        $arrChildrenIDs = array();

        foreach ($arrChildren as $child) {
            $childID = $child->getID();
            $arrChildrenIDs[] = $childID;
        }

        return($arrChildrenIDs);
    }

    public function getArrChildrenLangs($includeParent = true)
    {
        $this->validateInited();
        $slideID = $this->id;

        if ($includeParent == true) {
            $lang = $this->getParam("lang", "all");
            $arrOutput = array();
            $arrOutput[] = array("slideid" => $slideID, "lang" => $lang, "isparent" => true);
        }

        $arrChildren = $this->getArrChildren();

        foreach ($arrChildren as $child) {
            $childID = $child->getID();
            $childLang = $child->getParam("lang", "all");
            $arrOutput[] = array("slideid" => $childID, "lang" => $childLang, "isparent" => false);
        }

        return($arrOutput);
    }

    public function getArrChildLangCodes($includeParent = true)
    {
        $arrLangsWithSlideID = $this->getArrChildrenLangs($includeParent);
        $arrLangCodes = array();

        foreach ($arrLangsWithSlideID as $item) {
            $lang = $item["lang"];

            $arrLangCodes[$lang] = $lang;
        }

        return($arrLangCodes);
    }

    public function getID()
    {
        return($this->id);
    }

    /**
     * get slide title
     */
    public function getTitle()
    {
        return($this->getParam("title", "Slide"));
    }

    public function temPostTypes()
    {
        return($this->slider->arrParams['post_types']);
    }

    public function getOrder()
    {
        $this->validateInited();

        return($this->slideOrder);
    }

    public function getLayers()
    {
        $this->validateInited();

        return($this->arrLayers);
    }

    public function getLayersForExport($useDummy = false)
    {
        $this->validateInited();
        $arrLayersNew = array();

        foreach ($this->arrLayers_export as $key => $layer) {
            $imageUrl = UniteFunctionsRb::getVal($layer, "image_url");

            if (!empty($imageUrl)) {
                $layer["image_url"] = 'uploads/' . basename($layer["image_url"]);
            }

            $arrLayersNew[] = $layer;
        }

        return($arrLayersNew);
    }

    public function getParamsForExport()
    {
        $arrParams = $this->getParams();
        $urlImage = UniteFunctionsRb::getVal($arrParams, "image");

        if (!empty($urlImage)) {
            $arrParams["image"] = UniteFunctionsPSRb::getImagePathFromURL($urlImage);
        }

        return($arrParams);
    }

    public function getLayersNormalizeText()
    {
        $arrLayersNew = array();

        foreach ($this->arrLayers as $key => $layer) {
            $text = $layer["text"];
            $text = addslashes($text);
            $layer["text"] = $text;
            $arrLayersNew[] = $layer;
        }

        return($arrLayersNew);
    }

    /**
     * get real slides number, from posts, social streams ect.
     */
    public function getNumRealSlides($publishedOnly = false, $type = 'post')
    {
        $numSlides = count($this->arrSlides);

        switch ($type) {
            case 'post':
                $this->getSlidesFromPosts($publishedOnly);
                $numSlides = count($this->arrSlides);
                break;
            case 'facebook':
                $numSlides = $this->getParam('facebook-count', count($this->arrSlides));
                break;
            case 'twitter':
                $numSlides = $this->getParam('twitter-count', count($this->arrSlides));
                break;
            case 'instagram':
                $numSlides = $this->getParam('instagram-count', count($this->arrSlides));
                break;
            case 'flickr':
                $numSlides = $this->getParam('flickr-count', count($this->arrSlides));
                break;
            case 'youtube':
                $numSlides = $this->getParam('youtube-count', count($this->arrSlides));
                break;
            case 'vimeo':
                $numSlides = $this->getParam('vimeo-count', count($this->arrSlides));
                break;
        }

        return($numSlides);
    }

    public function getParams()
    {
        $this->validateInited();

        return($this->params);
    }

    /**
     * get slide settings
     * @since: 5.0
     */
    public function getSettings()
    {
        $this->validateInited();

        return($this->settings);
    }

    public function getParam($name, $default = null)
    {
        if ($default === null) {
            if (!array_key_exists($name, $this->params)) {
                UniteFunctionsRb::throwError("The param <b>$name</b> not found in slide params.");
            }

            $default = "";
        }

        return UniteFunctionsRb::getVal($this->params, $name, $default);
    }

    /**
     * set parameter
     * @since: 5.0
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function getImageFilename()
    {
        return($this->imageFilename);
    }

    public function getImageFilepath()
    {
        return($this->imageFilepath);
    }

    public function getImageUrl()
    {
        return($this->imageUrl);
    }

    public function getImageID()
    {
        return($this->imageID);
    }

    public function getThumbUrl()
    {
        $thumbUrl = $this->imageUrl;
        $size = GlobalsRbSlider::IMAGE_SIZE_MEDIUM;
        $filename = basename($thumbUrl);
        $filerealname = Tools::substr($filename, 0, strrpos($filename, '.'));

        $fileext = Tools::substr(
            $filename,
            strrpos($filename, '.'),
            Tools::strlen($filename) - Tools::strlen($filerealname)
        );

        $nthumbUrl = str_replace($filename, "{$filerealname}-{$size}x{$size}{$fileext}", $thumbUrl);

        if (!empty($this->imageThumb)) {
            $nthumbUrl = $thumbUrl = $this->imageThumb;
        }

        return($nthumbUrl);
    }

    public function getSliderID()
    {
        return($this->sliderID);
    }

    private function getSliderParam($sliderID, $name, $default, $validate = null)
    {
        if (empty($this->slider)) {
            $this->slider = new RbSlider();

            $this->slider->initByID($sliderID);
        }

        $param = $this->slider->getParam($name, $default, $validate);

        return($param);
    }

    private function validateSliderExists($sliderID)
    {
        $slider = new RbSlider();
        $slider->initByID($sliderID);
    }

    private function validateInited()
    {
        if (empty($this->id)) {
            UniteFunctionsRb::throwError("The slide is not inited!!!");
        }
    }

    public function createSlide($sliderID, $obj = "", $static = false)
    {
        $imageID = null;

        if (is_array($obj)) {
            $urlImage = UniteFunctionsRb::getVal($obj, "url");
            $imageID = UniteFunctionsRn::getVal($obj, "id");
        } else {
            $urlImage = $obj;
        }

        $slider = new RbSlider();
        $slider->initByID($sliderID);
        $maxOrder = $slider->getMaxOrder();
        $order = $maxOrder + 1;
        $params = array();

        if (!empty($urlImage)) {
            $params["background_type"] = "image";
            $params["image"] = $urlImage;

            if (!empty($imageID)) {
                $params["image_id"] = $imageID;
            }
        } else {
            $params["background_type"] = "trans";
        }

        $jsonParams = Tools::jsonEncode($params);

        $arrInsert = array("params" => $jsonParams,
            "slider_id" => $sliderID,
            "layers" => ""
        );

        if (!$static) {
            $arrInsert["slide_order"] = $order;
        }

        if (!$static) {
            $slideID = $this->db->insert(GlobalsRbSlider::$table_slides, $arrInsert);
        } else {
            $slideID = $this->db->insert(GlobalsRbSlider::$table_static_slides, $arrInsert);
        }

        return($slideID);
    }

    public function updateSlideImageFromData($data)
    {
        $sliderID = UniteFunctionsRb::getVal($data, "slider_id");
        $slider = new RbSlider();
        $slider->initByID($sliderID);
        $slideID = UniteFunctionsRb::getVal($data, "slide_id");
        $urlImage = UniteFunctionsRb::getVal($data, "url_image");
        UniteFunctionsRb::validateNotEmpty($urlImage);
        $imageID = UniteFunctionsRb::getVal($data, "image_id");
        $this->initByID($slideID);
        $arrUpdate = array();
        $arrUpdate["image"] = $urlImage;
        $arrUpdate["image_id"] = $imageID;
        $this->updateParamsInDB($arrUpdate);

        return($urlImage);
    }

    private function updateParamsInDB($arrUpdate = array())
    {
        $this->validateInited();
        $this->params = array_merge($this->params, $arrUpdate);
        $jsonParams = Tools::jsonEncode($this->params);
        $arrDBUpdate = array("params" => $jsonParams);

        $this->db->update(GlobalsRbSlider::$table_slides, $arrDBUpdate, array("id" => $this->id));
    }

    private function updateLayersInDB($arrLayers = null)
    {
        $this->validateInited();

        if ($arrLayers === null) {
            $arrLayers = $this->arrLayers;
        }

        $jsonLayers = Tools::jsonEncode($arrLayers);
        $arrDBUpdate = array("layers" => $jsonLayers);
        $this->db->update(GlobalsRbSlider::$table_slides, $arrDBUpdate, array("id" => $this->id));
    }

    public function updateParentSlideID($parentID)
    {
        $arrUpdate = array();
        $arrUpdate["parentid"] = $parentID;
        $this->updateParamsInDB($arrUpdate);
    }

    private function sortLayersByOrder($layer1, $layer2)
    {
        $layer1 = (array) $layer1;
        $layer2 = (array) $layer2;
        $order1 = UniteFunctionsRb::getVal($layer1, "order", 1);
        $order2 = UniteFunctionsRb::getVal($layer2, "order", 2);

        if ($order1 == $order2) {
            return(0);
        }

        return($order1 > $order2);
    }

    /**
     * 
     * go through the layers and fix small bugs if exists
     */
    private function normalizeLayers($arrLayers)
    {
        usort($arrLayers, array($this, "sortLayersByOrder"));
        $arrLayersNew = array();

        foreach ($arrLayers as $key => $layer) {
            $layer = (array) $layer;
            //set type
            $type = RbSliderFunctions::getVal($layer, "type", "text");
            $layer["type"] = $type;

            if (!is_object($layer["left"])) {
                $layer["left"] = (object) $layer["left"];
            }

            if (!is_object($layer["top"])) {
                $layer["top"] = (object) $layer["top"];
            }
            //normalize position:
            if (is_object($layer["left"])) {
                foreach ($layer["left"] as $key => $val) {
                    $layer["left"]->$key = round($val);
                }
            } else {
                $layer["left"] = round($layer["left"]);
            }

            if (is_object($layer["top"])) {
                foreach ($layer["top"] as $key => $val) {
                    $layer["top"]->$key = round($val);
                }
            } else {
                $layer["top"] = round($layer["top"]);
            }

            //unset order
            unset($layer["order"]);

            //modify text
            $layer["text"] = stripcslashes($layer["text"]);
            $arrLayersNew[] = $layer;
        }

        return($arrLayersNew);
    }

    private function normalizeParams($params)
    {
        $urlImage = UniteFunctionsRb::getVal($params, "image_url");
        $params["image_id"] = UniteFunctionsRb::getVal($params, "image_id");
        $params["image"] = $urlImage;
        unset($params["image_url"]);

        if (@Rbthemeslider::getIsset($params["video_description"])) {
            $params["video_description"] = UniteFunctionsRb::normalizeTextareaContent(
                $params["video_description"]
            );
        }

        return($params);
    }

    public function updateSlideFromData($data)
    {
        $slideID = RbSliderFunctions::getVal($data, "slideid");
        $this->initByID($slideID);

        //treat params
        $params = RbSliderFunctions::getVal($data, "params");
        $params = $this->normalizeParams($params);

        //preserve old data that not included in the given data
        $params = array_merge($this->params, $params);
        //treat layers
        $layers = RbSliderFunctions::getVal($data, "layers");

        if (gettype($layers) == "string") {
            $layersStrip = Tools::stripslashes($layers);
            $layersDecoded = Tools::jsonDecode($layersStrip);

            if (empty($layersDecoded)) {
                $layersDecoded = Tools::jsonDecode($layers);
            }

            $layers = RbSliderFunctions::convertStdClassToArray($layersDecoded);
        }

        if (empty($layers) || gettype($layers) != "array") {
            $layers = array();
        }

        $layers = $this->normalizeLayers($layers);
        $settings = RbSliderFunctions::getVal($data, "settings");
        $arrUpdate = array();
        $arrUpdate["layers"] = Tools::jsonEncode($layers);
        $arrUpdate["params"] = Tools::jsonEncode($params);
        $arrUpdate["settings"] = Tools::jsonEncode($settings);

        $this->db->update(RbSliderGlobals::$table_slides, $arrUpdate, array("id" => $this->id));
    }

    public function updateStaticSlideFromData($data)
    {
        $slideID = UniteFunctionsRb::getVal($data, "slideid");
        $this->initByStaticID($slideID);

        //treat layers
        $layers = UniteFunctionsRb::getVal($data, "layers");

        if (gettype($layers) == "string") {
            $layersStrip = Tools::stripslashes($layers);
            $layersDecoded = Tools::jsonDecode($layersStrip);

            if (empty($layersDecoded)) {
                $layersDecoded = Tools::jsonDecode($layers);
            }

            $layers = UniteFunctionsRb::convertStdClassToArray($layersDecoded);
        }

        if (empty($layers) || gettype($layers) != "array") {
            $layers = array();
        }

        $layers = $this->normalizeLayers($layers);
        $arrUpdate = array();
        $arrUpdate["layers"] = Tools::jsonEncode($layers);

        $this->db->update(
            GlobalsRbSlider::$table_static_slides,
            $arrUpdate,
            array("id" => $this->id)
        );
    }

    public function deleteSlide()
    {
        $this->validateInited();
        $this->db->delete(GlobalsRbSlider::$table_slides, "id='" . $this->id . "'");
    }

    public function deleteChildren()
    {
        $this->validateInited();
        $arrChildren = $this->getArrChildren();

        foreach ($arrChildren as $child) {
            $child->deleteSlide();
        }
    }

    public function deleteSlideFromData($data)
    {
        $sliderID = UniteFunctionsRb::getVal($data, "sliderID");
        $slider = new RbSlider();
        $slider->initByID($sliderID);

        //delete slide
        $slideID = UniteFunctionsRb::getVal($data, "slideID");
        $this->initByID($slideID);
        $this->deleteChildren();
        $this->deleteSlide();
        RbOperations::updateDynamicCaptions();
    }

    public function setParams($params)
    {
        $params = $this->normalizeParams($params);
        $this->params = $params;
    }

    public function setLayers($layers)
    {
        $layers = $this->normalizeLayers($layers);
        $this->arrLayers = $layers;
    }

    public function toggleSlideStatFromData($data)
    {
        $sliderID = UniteFunctionsRb::getVal($data, "slider_id");
        $slider = new RbSlider();
        $slider->initByID($sliderID);
        $slideID = UniteFunctionsRb::getVal($data, "slide_id");
        $this->initByID($slideID);
        $state = $this->getParam("state", "published");
        $newState = ($state == "published") ? "unpublished" : "published";
        $arrUpdate = array();
        $arrUpdate["state"] = $newState;
        $this->updateParamsInDB($arrUpdate);

        return($newState);
    }

    private function updateLangFromData($data)
    {
        $slideID = UniteFunctionsRb::getVal($data, "slideid");
        $this->initByID($slideID);
        $lang = UniteFunctionsRb::getVal($data, "lang");
        $arrUpdate = array();
        $arrUpdate["lang"] = $lang;
        $this->updateParamsInDB($arrUpdate);
        $response = array();
        $response["url_icon"] = UnitePsmlRb::getFlagUrl($lang);
        $response["title"] = UnitePsmlRb::getLangTitle($lang);
        $response["operation"] = "update";

        return($response);
    }

    private function addLangFromData($data)
    {
        $sliderID = UniteFunctionsRb::getVal($data, "sliderid");
        $slideID = UniteFunctionsRb::getVal($data, "slideid");
        $lang = UniteFunctionsRb::getVal($data, "lang");

        //duplicate slide
        $slider = new RbSlider();
        $slider->initByID($sliderID);
        $newSlideID = $slider->duplicateSlide($slideID);

        //update new slide
        $this->initByID($newSlideID);
        $arrUpdate = array();
        $arrUpdate["lang"] = $lang;
        $arrUpdate["parentid"] = $slideID;
        $this->updateParamsInDB($arrUpdate);
        $urlIcon = UnitePsmlRb::getFlagUrl($lang);
        $title = UnitePsmlRb::getLangTitle($lang);
        $newSlide = new RbSlide();
        $newSlide->initByID($slideID);
        $arrLangCodes = $newSlide->getArrChildLangCodes();
        $isAll = UnitePsmlRb::isAllLangsInArray($arrLangCodes);

        $html = "<li>
        <img id=\"icon_lang_" . $newSlideID . "\" class=\"icon_slide_lang\" src=\"" . $urlIcon .
        "\" title=\"" . $title . "\" data-slideid=\"" . $newSlideID . "\" data-lang=\"" . $lang . "\">
        <div class=\"icon_lang_loader loader_round\" style=\"display:none\"></div></li>";

        $response = array();
        $response["operation"] = "add";
        $response["isAll"] = $isAll;
        $response["html"] = $html;

        return($response);
    }

    private function deleteSlideFromLangData($data)
    {
        $slideID = UniteFunctionsRb::getVal($data, "slideid");
        $this->initByID($slideID);
        $this->deleteSlide();
        $response = array();
        $response["operation"] = "delete";

        return($response);
    }

    public function doSlideLangOperation($data)
    {
        $operation = UniteFunctionsRb::getVal($data, "operation");

        switch ($operation) {
            case "add":
                $response = $this->addLangFromData($data);

                break;

            case "delete":
                $response = $this->deleteSlideFromLangData($data);

                break;

            case "update":

            default:
                $response = $this->updateLangFromData($data);

                break;
        }

        return($response);
    }

    public function getUrlImageThumb()
    {
        if (!empty($this->imageID)) {
            $urlImage = UniteFunctionsPSRb::getUrlAttachmentImage(
                $this->imageID, UniteFunctionsPSRb::THUMB_MEDIUM
            );
        } else {
            if (!empty($this->imageFilepath)) {
                $urlImage = UniteBaseClassRb::getImageUrl($this->imageFilepath, 200, 100, true);
            } else {
                $urlImage = $this->imageUrl;
            }
        }

        if (empty($urlImage)) {
            $urlImage = $this->imageUrl;
        }

        return($urlImage);
    }

    public function getImageAttributes($slider_type)
    {
        $params = $this->params;
        $bgType = UniteBaseClassRb::getVar($params, "background_type", "transparent");
        $bgColor = UniteBaseClassRb::getVar($params, "slide_bg_color", "transparent");
        $bgFit = UniteBaseClassRb::getVar($params, "bg_fit", "cover");
        $bgFitX = (int) (UniteBaseClassRb::getVar($params, "bg_fit_x", "100"));
        $bgFitY = (int) (UniteBaseClassRb::getVar($params, "bg_fit_y", "100"));
        $bgPosition = UniteBaseClassRb::getVar($params, "bg_position", "center top");
        $bgPositionX = (int) (UniteBaseClassRb::getVar($params, "bg_position_x", "0"));
        $bgPositionY = (int) (UniteBaseClassRb::getVar($params, "bg_position_y", "0"));
        $bgRepeat = UniteBaseClassRb::getVar($params, "bg_repeat", "no-repeat");
        $bgStyle = ' ';

        if ($bgFit == 'percentage') {
            $bgStyle .= "background-size: " . $bgFitX . '% ' . $bgFitY . '%;';
        } else {
            $bgStyle .= "background-size: " . $bgFit . ";";
        }

        if ($bgPosition == 'percentage') {
            $bgStyle .= "background-position: " . $bgPositionX . '% ' . $bgPositionY . '%;';
        } else {
            $bgStyle .= "background-position: " . $bgPosition . ";";
        }

        $bgStyle .= "background-repeat: " . $bgRepeat . ";";
        $thumb = '';
        $thumb_on = UniteBaseClassRb::getVar($params, "thumb_for_admin", 'off');

        switch ($slider_type) {
            case 'gallery':
                $imageID = UniteBaseClassRb::getVar($params, "image_id");
                if (empty($imageID)) {
                    $thumb = UniteBaseClassRb::getVar($params, "image");
                    $imgID = UniteBaseClassRb::getImageIdByUrl($thumb);

                    if ($imgID !== false) {
                        $thumb = UniteFunctionsPSRb::getUrlAttachmentImage(
                            $imgID,
                            UniteFunctionsPSRb::THUMB_MEDIUM
                        );
                    }
                } else {
                    $thumb = UniteFunctionsPSRb::getUrlAttachmentImage(
                        $imageID,
                        UniteFunctionsPSRb::THUMB_MEDIUM
                    );
                }

                if ($thumb_on == 'on') {
                    $thumb = UniteBaseClassRb::getVar($params, "slide_thumb", '');
                }

                break;
            case 'posts':
                $thumb = get_url() . '/views/img/images/sources/post.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'woocommerce':
                $thumb = get_url() . '/views/img/images/sources/wc.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'facebook':
                $thumb = get_url() . '/views/img/images/sources/fb.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'twitter':
                $thumb = get_url() . '/views/img/images/sources/tw.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'instagram':
                $thumb = get_url() . '/views/img/images/sources/ig.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'flickr':
                $thumb = get_url() . '/views/img/images/sources/fr.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'youtube':
                $thumb = get_url() . '/views/img/images/sources/yt.png';
                $bgStyle = 'background-size: cover;';
                break;
            case 'vimeo':
                $thumb = get_url() . '/views/img/images/sources/vm.png';
                $bgStyle = 'background-size: cover;';
                break;
        }

        if ($thumb == '') {
            $thumb = UniteBaseClassRb::getVar($params, "image");
        }

        $bg_fullstyle = '';
        $bg_extraClass = '';
        $data_urlImageForView = '';
        $data_urlImageForView = $thumb;
        $bg_fullstyle = $bgStyle;

        if ($bgType == "solid") {
            if ($thumb_on == 'off') {
                $bg_fullstyle = 'background-color:' . $bgColor . ';';
                $data_urlImageForView = '';
            } else {
                $bg_fullstyle = 'background-size: cover;';
            }
        }

        if ($bgType == "trans" || $bgType == "transparent") {
            $bg_extraClass = 'mini-transparent';
            $bg_fullstyle = 'background-size: inherit; background-repeat: repeat;';
        }

        return array(
            'url' => $data_urlImageForView,
            'class' => $bg_extraClass,
            'style' => $bg_fullstyle
        );
    }

    public function replaceImageUrls($urlFrom, $urlTo)
    {
        $this->validateInited();
        $urlImage = UniteFunctionsRb::getVal($this->params, "image");

        if (strpos($urlImage, $urlFrom) !== false) {
            $imageNew = str_replace($urlFrom, $urlTo, $urlImage);
            $this->params["image"] = $imageNew;
            $this->updateParamsInDB();
        }

        $isUpdated = false;

        foreach ($this->arrLayers as $key => $layer) {
            $type = UniteFunctionsRb::getVal($layer, "type");

            if ($type == "image") {
                $urlImage = UniteFunctionsRb::getVal($layer, "image_url");

                if (strpos($urlImage, $urlFrom) !== false) {
                    $newUrlImage = str_replace($urlFrom, $urlTo, $urlImage);
                    $this->arrLayers[$key]["image_url"] = $newUrlImage;
                    $isUpdated = true;
                }
            }
        }

        if ($isUpdated == true) {
            $this->updateLayersInDB();
        }
    }

    /**
     * get all used fonts in the current Slide
     * @since: 5.1.0
     */
    public function getUsedFonts($full = false)
    {
        $this->validateInited();
        $op = new RbSliderOperations();
        $fonts = array();
        $all_fonts = $op->getArrFontFamilys();

        if (!empty($this->arrLayers)) {
            foreach ($this->arrLayers as $key => $layer) {
                $def = (array) RbSliderFunctions::getVal($layer, 'deformation', array());
                $font = RbSliderFunctions::getVal($def, 'font-family', '');
                $static = (array) RbSliderFunctions::getVal($layer, 'static_styles', array());

                foreach ($all_fonts as $f) {
                    if (Tools::strtolower(str_replace(array('"', "'", ' '), '', $f['label'])) ==
                        Tools::strtolower(str_replace(array('"', "'", ' '), '', $font)) &&
                        $f['type'] == 'googlefont'
                    ) {
                        if (!@Rbthemeslider::getIsset($fonts[$f['label']])) {
                            $fonts[$f['label']] = array('variants' => array(), 'subsets' => array());
                        }
                        if ($full) {
                            $mv = array();

                            if (!empty($f['variants'])) {
                                foreach ($f['variants'] as $fvk => $fvv) {
                                    $mv[$fvv] = $fvv;
                                }
                            }

                            $fonts[$f['label']] = array('variants' => $mv, 'subsets' => $f['subsets']);
                        } else {
                            $fw = (array) RbSliderFunctions::getVal($static, 'font-weight', '400');
                            $fs = RbSliderFunctions::getVal($def, 'font-style', '');

                            if ($fs == 'italic') {
                                foreach ($fw as $mf => $w) {
                                    if ($w == '400') {
                                        if (array_search('italic', $f['variants']) !== false) {
                                            $fw[$mf] = 'italic';
                                        }
                                    } else {
                                        if (array_search($w . 'italic', $f['variants']) !== false) {
                                            $fw[$mf] = $w . 'italic';
                                        }
                                    }
                                }
                            }

                            foreach ($fw as $mf => $w) {
                                $fonts[$f['label']]['variants'][$w] = true;
                            }

                            $fonts[$f['label']]['subsets'] = $f['subsets'];
                        }

                        break;
                    }
                }
            }
        }

        return $fonts;
    }

    public function changeTransition($transition)
    {
        $this->validateInited();
        $this->params["slide_transition"] = $transition;
        $this->updateParamsInDB();
    }

    public function changeTransitionDuration($transitionDuration)
    {
        $this->validateInited();
        $this->params["transition_duration"] = $transitionDuration;
        $this->updateParamsInDB();
    }

    public function isStaticSlide()
    {
        return $this->static_slide;
    }

    /**
     * Returns all layer attributes that can have more than one setting due to desktop, tablet, mobile sizes
     * @since: 5.0
     */
    public static function translateIntoSizes()
    {
        return array(
            'align_hor',
            'align_vert',
            'top',
            'left',
            'font-size',
            'line-height',
            'font-weight',
            'color',
            'max_width',
            'max_height',
            'whitespace',
            'video_height',
            'video_width',
            'scaleX',
            'scaleY'
        );
    }

    /**
     * Translates all values that need more than one setting
     * @since: 5.0
     */
    public function translateLayerSizes($layers)
    {
        $translation = self::translateIntoSizes();

        if (!empty($layers)) {
            foreach ($layers as $l => $layer) {
                foreach ($translation as $trans) {
                    if (@Rbthemeslider::getIsset($layers[$l][$trans])) {
                        if (!is_array($layers[$l][$trans])) {
                            $layers[$l][$trans] = array('desktop' => $layers[$l][$trans]);
                        }
                    }
                }
            }
        }

        return $layers;
    }

    /**
     * Check if Slide Exists with given ID
     * @since: 5.0
     */
    public static function isSlideByID($slideid)
    {
        $db = new RbSliderDB();

        try {
            if (strpos($slideid, 'static_') !== false) {
                $sliderID = str_replace('static_', '', $slideid);
                RbSliderFunctions::validateNumeric($sliderID, "Slider ID");
                $sliderID = $db->escape($sliderID);
                $record = $db->fetch(RbSliderGlobals::$table_static_slides, "slider_id=$sliderID");

                if (empty($record)) {
                    return false;
                }

                return true;
            } else {
                $slideid = $db->escape($slideid);
                $record = $db->fetchSingle(RbSliderGlobals::$table_slides, "id=$slideid");

                if (empty($record)) {
                    return false;
                }

                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * set layers from client, do not normalize as this results in loosing the order
     * @since: 5.0
     */
    public function setLayersRaw($layers)
    {
        $this->arrLayers = $layers;
    }

    /**
     * save layers to the database
     * @since: 5.0
     */
    public function saveLayers()
    {
        $this->validateInited();
        $table = ($this->static_slide) ? RbSliderGlobals::$table_static_slides : RbSliderGlobals::$table_slides;
        $this->db->update($table, array('layers' => Tools::jsonEncode($this->arrLayers)), array('id' => $this->id));
    }

    /**
     * save params to the database
     * @since: 5.0
     */
    public function saveParams()
    {
        $this->validateInited();
        $table = ($this->static_slide) ? RbSliderGlobals::$table_static_slides : RbSliderGlobals::$table_slides;
        $this->db->update($table, array('params' => Tools::jsonEncode($this->params)), array('id' => $this->id));
    }

    /**
     * update the title of a Slide by Slide ID
     * @since: 5.0
     * */
    public function updateTitleByID($data)
    {
        if (!isset($data['slideID']) || !isset($data['slideTitle'])) {
            return false;
        }

        $this->initByID($data['slideID']);
        $arrUpdate = array();
        $arrUpdate['title'] = $data['slideTitle'];
        $this->updateParamsInDB($arrUpdate);
    }

    /**
     * get layers in json format
     * since: 5.0
     */
    public function getLayerIDByUniqueId($unique_id)
    {
        $this->validateInited();

        foreach ($this->arrLayers as $l) {
            $uid = RbSliderFunctions::getVal($l, 'unique_id');

            if ($uid == $unique_id) {
                return RbSliderFunctions::getVal($l, 'attrID');
            }
        }

        return '';
    }
}

class RbSliderSlide extends RbSlide
{
    
}
