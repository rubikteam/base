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

ob_start();

$modules = new Rbthemeslider();
$sliderID = RbGlobalObject::getVar('sliderID');
$total_size = 0;
$do_ssl = (is_ssl()) ? 'http:' : 'https:';
$slider = new RbSliderSlider();
$slider->initByID($sliderID);
$slides = $slider->getSlidesForExport();
$static_slides = $slider->getStaticSlideForExport();

if (!empty($static_slides) && is_array($static_slides)) {
    foreach ($static_slides as $s_slide) {
        $slides[] = $s_slide;
    }
}

$used_images = array();
$used_videos = array();
$used_captions = array();
$using_kenburns = false;
$using_parallax = false;
$using_carousel = false;
$using_navigation = false;
$using_videos = false;
$using_actions = false;
$using_layeranim = false;
$img_size = 0;
$video_size = 0;
$slide_counter = 0;
$firstslide_size = 0;
$smartslide_size = 0;

if ($slider->getParam("use_parallax", "off") == 'on') {
    $using_parallax = true;
}

if ($slider->getParam("slider-type", "standard") == 'carousel') {
    $using_carousel = true;
}

$enable_arrows = $slider->getParam('enable_arrows', 'off');
$enable_bullets = $slider->getParam('enable_bullets', 'off');
$enable_tabs = $slider->getParam('enable_tabs', 'off');
$enable_thumbnails = $slider->getParam('enable_thumbnails', 'off');

if ($enable_arrows == 'on' ||
    $enable_bullets == 'on' ||
    $enable_tabs == 'on' ||
    $enable_thumbnails == 'on'
) {
    $using_navigation = true;
}

if (!empty($slides) && count($slides) > 0) {
    foreach ($slides as $key => $slide) {
        if (@Rbthemeslider::getIsset($slide['params']['state']) &&
            $slide['params']['state'] != 'published'
        ) {
            continue;
        }

        if (!@Rbthemeslider::getIsset($slide['id'])) {
            continue;
        }

        $slide_counter++;
        $slide_id = $slide['id'];

        if (@Rbthemeslider::getIsset($slide['params']['kenburn_effect']) &&
            $slide['params']['kenburn_effect'] == 'on'
        ) {
            $using_kenburns = true;
        }

        if (!@Rbthemeslider::getIsset($slide['params']['image_source_type'])) {
            $slide['params']['image_source_type'] = 'full';
        }

        if (@Rbthemeslider::getIsset($slide['params']['image']) &&
            $slide['params']['image'] != ''
        ) {
            $infos = array();
            $urlImage = false;

            switch ($slide['params']['background_type']) {
                case 'streamyoutube':
                case 'streaminstagram':
                case 'streamvimeo':
                case 'youtube':
                case 'vimeo':
                    $using_videos = true;
                    break;
            }

            if (@Rbthemeslider::getIsset($slide['params']['image_id'])) {
                $cur_img_id = $slide['params']['image_id'];

                $urlImage = psGetAttachmentImageSrc(
                    $slide['params']['image_id'],
                    $slide['params']['image_source_type']
                );
            }

            if ($urlImage === false) {
                $cur_img_id = getImageIdByUrl($slide['params']['image']);

                if ($cur_img_id !== false) {
                    $urlImage = psGetAttachmentImageSrc(
                        $cur_img_id, $slide['params']['image_source_type']
                    );
                }
            }

            if ($urlImage !== false) {
                $infos['id'] = $cur_img_id;
                $file = getAttachedFile($cur_img_id);
                $infos['info'] = pathinfo($file);

                if (file_exists($file)) {
                    $infos['size'] = filesize($file);
                    $infos['size-format'] = size_format($infos['size'], 2);
                    $img_size += $infos['size'];

                    if ($slide_counter == 1) {
                        $firstslide_size += $infos['size'];
                    }

                    if ($slide_counter == 1 ||
                        $slide_counter == 2 ||
                        $slide_counter == count($slides)
                    ) {
                        $smartslide_size += $infos['size'];
                    }
                } else {
                    $infos['id'] = false;
                }
            } else {
                $infos['id'] = 'external';
            }

            if (strpos($slide_id, 'static_') !== false) {
                $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                    RbSliderAdmin::VIEW_SLIDE, 'id=static_' . $sliderID
                );
            } else {
                $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                    RbSliderAdmin::VIEW_SLIDE,
                    'id=' . $slide_id
                );
            }

            $used_images[$slide['params']['image']] = $infos;
        }

        if (@Rbthemeslider::getIsset($slide['layers']) && !empty($slide['layers']) && count($slide['layers']) > 0) {
            $using_layeranim = true;

            foreach ($slide['layers'] as $lKey => $layer) {
                switch ($layer['type']) {
                    case 'image':
                        $infos = array();
                        if (@Rbthemeslider::getIsset($layer['image_url']) && trim($layer['image_url']) != '') {
                            $cur_img_id = getImageIdByUrl($layer['image_url']);
                            if ($cur_img_id !== false) {
                                if (!@Rbthemeslider::getIsset($layer['layer-image-size']) || $layer['layer-image-size'] == 'auto') {
                                    $layer['layer-image-size'] = $slide['params']['image_source_type'];
                                }

                                $urlImage = psGetAttachmentImageSrc($cur_img_id, $layer['layer-image-size']);

                                if ($urlImage !== false) {
                                    $infos['id'] = $cur_img_id;
                                    $file = getAttachedFile($cur_img_id);
                                    $infos['info'] = pathinfo($file);

                                    if (file_exists($file)) {
                                        $infos['size'] = filesize($file);
                                        $infos['size-format'] = size_format($infos['size'], 2);
                                        $img_size += $infos['size'];
                                        if ($slide_counter == 1) {
                                            $firstslide_size += $infos['size'];
                                        }
                                        if ($slide_counter == 1 || $slide_counter == 2 || $slide_counter == count($slides)) {
                                            $smartslide_size += $infos['size'];
                                        }
                                    } else {
                                        $infos['id'] = false;
                                    }
                                } else {
                                    $infos['id'] = 'external';
                                }
                            } else {
                                $infos['id'] = 'external';
                            }

                            if (strpos($slide_id, 'static_') !== false) {
                                $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                                    RbSliderAdmin::VIEW_SLIDE, 'id=static_' . $sliderID
                                );
                            } else {
                                $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                                    RbSliderAdmin::VIEW_SLIDE, 'id=' . $slide_id
                                );
                            }

                            $used_images[$layer['image_url']] = $infos;
                        }
                        break;
                    case 'video':
                        $using_videos = true;

                        //get cover image if existing
                        $infos = array();
                        $poster_img = array();

                        if (@Rbthemeslider::getIsset($layer['video_data']) &&
                            @Rbthemeslider::getIsset($layer['video_data']->urlPoster)
                        ) {
                            $poster_img[] = $layer['video_data']->urlPoster;
                        }

                        if (@Rbthemeslider::getIsset($layer['video_image_url']) &&
                            @Rbthemeslider::getIsset($layer['video_image_url'])
                        ) {
                            $poster_img[] = $layer['video_image_url'];
                        }

                        if (!empty($poster_img)) {
                            foreach ($poster_img as $img) {
                                if (trim($img) == '') {
                                    continue;
                                }

                                $cur_img_id = getImageIdByUrl($img);

                                if ($cur_img_id !== false) {
                                    if (!@Rbthemeslider::getIsset($layer['layer-image-size']) ||
                                        $layer['layer-image-size'] == 'auto'
                                    ) {
                                        $layer['layer-image-size'] = $slide['params']['image_source_type'];
                                    }

                                    $urlImage = psGetAttachmentImageSrc($cur_img_id, $layer['layer-image-size']);

                                    if ($urlImage !== false) {
                                        $infos['id'] = $cur_img_id;
                                        $file = getAttachedFile($cur_img_id);
                                        $infos['info'] = pathinfo($file);

                                        if (file_exists($file)) {
                                            $infos['size'] = filesize($file);
                                            $infos['size-format'] = size_format($infos['size'], 2);
                                            $img_size += $infos['size'];

                                            if ($slide_counter == 1) {
                                                $firstslide_size += $infos['size'];
                                            }

                                            if ($slide_counter == 1 ||
                                                $slide_counter == 2 ||
                                                $slide_counter == count($slides)
                                            ) {
                                                $smartslide_size += $infos['size'];
                                            }
                                        } else {
                                            $infos['id'] = false;
                                        }
                                    } else {
                                        $infos['id'] = 'external';
                                    }
                                } else {
                                    $infos['id'] = 'external';
                                }

                                if (strpos($slide_id, 'static_') !== false) {
                                    $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                                        RbSliderAdmin::VIEW_SLIDE, 'id=static_' . $sliderID
                                    );
                                } else {
                                    $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                                        RbSliderAdmin::VIEW_SLIDE, 'id=' . $slide_id
                                    );
                                }

                                $used_images[$img] = $infos;
                            }
                        }

                        $infos = array();

                        if (@Rbthemeslider::getIsset($layer['video_type'])) {
                            if (@Rbthemeslider::getIsset($layer['video_data'])) {
                                $video_arr = array();
                                $max_video_size = 0;

                                if (strpos($slide_id, 'static_') !== false) {
                                    $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                                        RbSliderAdmin::VIEW_SLIDE, 'id=static_' . $sliderID
                                    );
                                } else {
                                    $infos['url'] = RbSliderBaseAdmin::getViewUrl(
                                        RbSliderAdmin::VIEW_SLIDE, 'id=' . $slide_id
                                    );
                                }

                                switch ($layer['video_type']) {
                                    case 'html5':
                                        if (@Rbthemeslider::getIsset($layer['video_data']->urlMp4) &&
                                            !empty($layer['video_data']->urlMp4)
                                        ) {
                                            $video_arr['mp4'] = $layer['video_data']->urlMp4;
                                        }

                                        if (@Rbthemeslider::getIsset($layer['video_data']->urlWebm) &&
                                            !empty($layer['video_data']->urlWebm)
                                        ) {
                                            $video_arr['webm'] = $layer['video_data']->urlWebm;
                                        }

                                        if (@Rbthemeslider::getIsset($layer['video_data']->urlOgv) &&
                                            !empty($layer['video_data']->urlOgv)
                                        ) {
                                            $video_arr['mp4'] = $layer['video_data']->urlOgv;
                                        }

                                        if (!empty($video_arr)) {
                                            foreach ($video_arr as $type => $url) {
                                                $cur_id = getImageIdByUrl($url);

                                                if ($cur_id !== false) {
                                                    $infos['id'] = $cur_id;
                                                    $file = getAttachedFile($cur_id);
                                                    $infos['info'] = pathinfo($file);
                                                    if (file_exists($file)) {
                                                        $infos['size'] = filesize($file);
                                                        $infos['size-format'] = size_format($infos['size'], 2);

                                                        if ($infos['size'] > $max_video_size) {
                                                            $max_video_size = $infos['size'];
                                                        }
                                                    } else {
                                                        $infos['id'] = 'external';
                                                    }
                                                } else {
                                                    $infos['id'] = 'external';
                                                }

                                                $used_videos[$url] = $infos;
                                            }

                                            $video_size += $max_video_size;
                                        }
                                        break;
                                    case 'youtube':
                                        $infos['id'] = 'external';
                                        if (!@Rbthemeslider::getIsset($layer['video_data']->id) || empty($layer['video_data']->id)) {
                                            continue;
                                        }
                                        $used_videos[$do_ssl . '//www.youtube.com/watch?v=' . $layer['video_data']->id] = $infos;
                                        break;
                                    case 'vimeo':
                                        if (!@Rbthemeslider::getIsset($layer['video_data']->id) || empty($layer['video_data']->id)) {
                                            continue;
                                        }
                                        $infos['id'] = 'external';
                                        $used_videos[$do_ssl . '//vimeo.com/' . $layer['video_data']->id] = $infos;
                                        break;
                                }
                            }
                        }
                        break;
                }

                //check captions for actions
                if (@Rbthemeslider::getIsset($layer['layer_action']) &&
                    !empty($layer['layer_action'])
                ) {
                    $a_action = RbSliderFunctions::cleanStdClassToArray(
                        RbSliderFunctions::getVal($layer['layer_action'], 'action', array())
                    );

                    $a_link_type = RbSliderFunctions::cleanStdClassToArray(
                        RbSliderFunctions::getVal(
                            $layer['layer_action'],
                            'link_type',
                            array()
                        )
                    );

                    if (!empty($a_action)) {
                        foreach ($a_action as $num => $action) {
                            if ($using_actions == true) {
                                break;
                            }

                            if ($action !== 'link') {
                                $using_actions = true;
                            } else {
                                if ($a_link_type[$num] == 'jquery') {
                                    $using_actions = true;
                                }
                            }
                        }
                    }
                }

                if (@Rbthemeslider::getIsset($layer['style']) && $layer['style'] != '') {
                    $used_captions[$layer['style']] = true;
                }
            }
        }
    }
}

$total_size += $img_size;
$img_counter = 0;
$issues = "";

?>

<span class="tp-clearfix" style="height:15px"></span>
<hr>
<span class="tp-clearfix" style="height:25px"></span>

<!-- HEADER OF MONITORING -->
<span class="tp-monitor-performance-title"><?php echo $modules->l("Overall Slider Performance");

?></span>
<span class="tp-monitor-performace-wrap">
    <span id="image-performace-bar" style="width: %overall_performance%%" class="tp-monitor-performance-bar mo-%overall_color%-col"></span>
    <span class="tp-monitor-slow"><?php echo $modules->l("Slow");

?></span>
    <span class="tp-monitor-ok"><?php echo $modules->l("Ok");

?></span>
    <span class="tp-monitor-fast"><?php echo $modules->l("Fast");

?></span>
</span>
<span class="tp-clearfix" style="height:50px"></span>

<span  class="tp-monitor-speed-table tp-monitor-single-speed">
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed UMTS:");

?></span>
        <span class="tp-monitor-total-subsize" id="umts-speed">%umtsspeed-single%</span>
    </span>
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed DSL:");

?></span>
        <span class="tp-monitor-total-subsize" id="dsl-speed">%dslspeed-single%</span>
    </span>
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed T1:");

?></span>
        <span class="tp-monitor-total-subsize" id="t1-speed">%t1speed-single%</span>
    </span>
</span>

<span  class="tp-monitor-speed-table tp-monitor-smart-speed">
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed UMTS:");

?></span>
        <span class="tp-monitor-total-subsize" id="umts-speed">%umtsspeed-smart%</span>
    </span>
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed DSL:");

?></span>
        <span class="tp-monitor-total-subsize" id="dsl-speed">%dslspeed-smart%</span>
    </span>
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed T1:");

?></span>
        <span class="tp-monitor-total-subsize" id="t1-speed">%t1speed-smart%</span>
    </span>
</span>

<span class="tp-monitor-speed-table tp-monitor-all-speed">
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed UMTS:");

?></span>
        <span class="tp-monitor-total-subsize" id="umts-speed">%umtsspeed-all%</span>
    </span>
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed DSL:");

?></span>
        <span class="tp-monitor-total-subsize" id="dsl-speed">%dslspeed-all%</span>
    </span>
    <span class="tp-monitor-speed-cell">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Load Speed T1:");

?></span>
        <span class="tp-monitor-total-subsize" id="t1-speed">%t1speed-all%</span>
    </span>
</span>

<span class="tp-clearfix" style="height:25px"></span>
<span style="float:left;width:165px">
    <span class="tp-monitor-smalllabel"><?php echo $modules->l("Total Slider Size:");

?></span>
    <span class="tp-monitor-fullsize">%overall_size%</span>
    <a class="button-primary rbblue tp-monitor-showdetails" data-target="#performance_overall_details" style="float:right; width:160px;vertical-align:top"><i class="eg-icon-chart-bar"></i>Show Full Statistics</a>
</span>
<span style="float:right; width:165px">
    <span class="tp-monitor-smalllabel"><?php echo $modules->l("Preloaded Slides Size:");

?></span>
    <span class="tp-monitor-fullsize tp-monitor-single-speed">%firstslide_size%</span>
    <span class="tp-monitor-fullsize tp-monitor-smart-speed">%smartslide_size%</span>
    <span class="tp-monitor-fullsize tp-monitor-all-speed">%allslide_size%</span>		
    <a class="button-primary rbred tp-monitor-showdetails" data-target="#monitor-problems" style="float:right; width:160px;vertical-align:top;"><i class="eg-icon-info"></i>Show All Issues</a>
</span>		
<span class="tp-clearfix" style="height:15px"></span>
<hr>
<span class="tp-clearfix" style="height:25px"></span>

<div id="monitor-problems" style="display:none">
    <span class="tp-monitor-performance-title"><?php echo $modules->l("Need Some Attention");

?></span>			
    <span class="tp-clearfix" style="height:25px"></span>
    <ul class="tp-monitor-list" id="monitor-problem-details" style="margin-bottom:15px;">
        %issues%
    </ul>
    <span class="tp-clearfix" style="height:15px"></span>
    <hr>
    <span class="tp-clearfix" style="height:25px"></span>
</div>

<div id="performance_overall_details" style="display:none">
    <?php
    if (!empty($used_images)) {

        ?>
        <!-- THE IMAGE PERFORMANCE MESSING -->
        <span class="tp-monitor-performance-title"><?php echo $modules->l("Image Performance");

        ?></span>
        <span class="tp-monitor-performace-wrap">
            <span id="image-performace-bar" style="width: %image_performance%%" class="tp-monitor-performance-bar mo-%image_color%-col"></span>
            <span class="tp-monitor-slow"><?php echo $modules->l("Slow");

        ?></span>
            <span class="tp-monitor-ok"><?php echo $modules->l("Ok");

        ?></span>
            <span class="tp-monitor-fast"><?php echo $modules->l("Fast");

        ?></span>
        </span>

        <span class="tp-clearfix" style="height:35px"></span>

        <span style="float:left;width:40%">
            <span class="tp-monitor-smalllabel"><?php echo $modules->l("Images Loaded:");

        ?></span>
            <span class="tp-monitor-imageicon"></span>
            <span id="image_sub_size" class="tp-monitor-total-subsize"><?php echo size_format($img_size, 2);

        ?></span>
        </span>
        <span style="float:left;width:60%; text-align:right;">
            <span class="tp-monitor-showdetails" data-target="#monitor-image-details" data-open="</span><?php echo $modules->l("Hide Details");

        ?>" data-close="</span><?php echo $modules->l("Show Details");

        ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo $modules->l("Show Details");

        ?></span></span>
        </span>
        <span class="tp-clearfix" style="height:15px"></span>
        <ul class="tp-monitor-list" id="monitor-image-details" style="display:none;margin-bottom:15px;">
            <?php
            foreach ($used_images as $path => $image) {
                $_li = '<li class="tp-monitor-listli">';


                if (@Rbthemeslider::getIsset($image['size'])) {
                    $img_counter++;
                    if ($image['size'] < 200000) {
                        $_li .= '<span class="tp-monitor-good"></span>';
                    } elseif ($image['size'] < 400000) {
                        $_li .= '<span class="tp-monitor-well"></span>';
                    } else {
                        $_li .= '<span class="tp-monitor-warning"></span>';
                    }

                    if ($image['size'] > 1000000) {
                        $_li .= '<span class="tp-monitor-size">' . size_format($image['size'], 2) . '</span>';
                    } else {
                        $_li .= '<span class="tp-monitor-size">' . size_format($image['size'], 0) . '</span>';
                    }
                } else {
                    if ($image['id'] == 'external') {
                        $_li .= '<span class="tp-monitor-neutral"></span><span class="tp-monitor-size">' . $modules->l('extern') . '</span>';
                    } else {
                        $_li .= '<span class="tp-monitor-warning"></span><span class="tp-monitor-size">' . $modules->l('missing') . '</span>';
                    }
                }

                $_li .= '<span class="tp-monitor-file">';
                if (!@Rbthemeslider::getIsset($image['info']['basename']) || empty($image['info']['basename'])) {
                    $_li .= '...' . Tools::substr($path, -20);
                } else {
                    $_li .= Tools::substr($image['info']['basename'], -20);
                }
                $_li .= '</span>';


                if (@Rbthemeslider::getIsset($image['url'])) {
                    $_li .= ' <a href="' . $image['url'] . '" target="_blank" class="tp-monitor-linktoslide"></a>';
                }

                $_li .= '</li>';
                echo $_li;
                if ((@Rbthemeslider::getIsset($image['size']) &&
                    $image['size'] > 199999) ||
                    (!@Rbthemeslider::getIsset($image['size']) &&
                        !$image['id'] == 'external')
                ) {
                    $issues .= $_li;
                }
            }

            ?>
        </ul>

        <?php
    }

    ?>

    <?php
    if (!empty($used_videos)) {

        ?>
        <span class="tp-clearfix" style="height:15px"></span>
        <hr>
        <span class="tp-clearfix" style="height:25px"></span>
        <span class="tp-monitor-performance-title"><?php echo $modules->l("Video Performance");

        ?></span>
        <span class="tp-monitor-performace-wrap">
            <span id="video-performace-bar" style="width:50%" class="tp-monitor-performance-bar mo-neutral-col"></span>
            <span class="tp-monitor-slow"><?php echo $modules->l("Slow");

        ?></span>
            <span class="tp-monitor-ok"><?php echo $modules->l("Ok");

        ?></span>
            <span class="tp-monitor-fast"><?php echo $modules->l("Fast");

        ?></span>
        </span>

        <span class="tp-clearfix" style="height:35px"></span>

        <!-- FULL SIZE OF SUBCATEGORY && SHOW/HIDE LIST -->
        <span style="float:left;width:40%; display:block">				
            <span class="tp-monitor-smalllabel"><?php echo $modules->l("Videos Loaded (max):");

        ?></span>
                <?php if ($video_size > 0) {

                    ?>				
                <span class="tp-monitor-imageicon"></span>
                <span id="video_sub_size" class="tp-monitor-total-subsize"><?php echo size_format($video_size, 2);

                    ?></span>
                    <?php
            } else {

                ?>
                <span class="tp-monitor-imageicon"></span>
                <span class="tp-monitor-total-subsize"><?php echo $modules->l("Unknown");

                ?></span>
                    <?php
            }

            ?>
        </span>
        <span style="float:left;width:60%; text-align:right;">
            <span class="tp-monitor-showdetails" data-target="#monitor-video-details" data-open="</span><?php echo $modules->l("Hide Details");

            ?>" data-close="</span><?php echo $modules->l("Show Details");

            ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo $modules->l("Show Details");

            ?></span></span>
        </span>
        <span class="tp-clearfix" style="height:15px"></span>

        <ul class="tp-monitor-list" id="monitor-video-details" style="margin-bottom:15px;display:none;">
            <?php
            foreach ($used_videos as $path => $video) {
                $_li = '<li class="tp-monitor-listli">';

                if (@Rbthemeslider::getIsset($video['size'])) {
                    $_li .= '	<span class="tp-monitor-neutral"></span>';

                    if ($video['size'] > 1000000) {
                        $_li .= '<span class="tp-monitor-size">' . size_format($video['size'], 2) . '</span>';
                    } else {
                        $_li .= '<span class="tp-monitor-size">' . size_format($video['size'], 0) . '</span>';
                    }
                } else {
                    if ($video['id'] == 'external') {
                        $_li .= '<span class="tp-monitor-neutral"></span><span class="tp-monitor-size">' . $modules->l('extern') . '</span>';
                    } else {
                        $_li .= '<span class="tp-monitor-warning"></span><span class="tp-monitor-size">' . $modules->l('missing') . '</span>';
                    }
                }

                $_li .= '<span class="tp-monitor-file">';
                if (!@Rbthemeslider::getIsset($video['info']['basename']) || empty($video['info']['basename'])) {
                    $_li .= '...' . Tools::substr($path, -20);
                } else {
                    $_li .= Tools::substr($video['info']['basename'], -20);
                }
                $_li .= '</span>';

                if (@Rbthemeslider::getIsset($image['url'])) {
                    $_li .= ' <a href="' . $video['url'] . '" target="_blank" class="tp-monitor-linktoslide"></a>';
                }

                $_li .= '</li>';
                if (!@Rbthemeslider::getIsset($video['size']) && !$video['id'] == 'external') {
                    $issues .= $_li;
                }
                echo $_li;
            }

            ?>
        </ul>
        <?php
    }


    $css_size = 0;

    ?>

    <span class="tp-clearfix" style="height:15px"></span>
    <hr>
    <span class="tp-clearfix" style="height:25px"></span>

    <!-- THE IMAGE PERFORMANCE MESSING -->
    <span class="tp-monitor-performance-title"><?php echo $modules->l("CSS Performance");

    ?></span>
    <span class="tp-monitor-performace-wrap">
        <span id="image-performace-bar" style="width:%css_performance%%" class="tp-monitor-performance-bar mo-%css_color%-col"></span>
        <span class="tp-monitor-slow"><?php echo $modules->l("Slow");

    ?></span>
        <span class="tp-monitor-ok"><?php echo $modules->l("Ok");

    ?></span>
        <span class="tp-monitor-fast"><?php echo $modules->l("Fast");

    ?></span>
    </span>

    <span class="tp-clearfix" style="height:35px"></span>

    <span style="float:left;width:40%">
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("CSS Loaded:");

    ?></span>
        <span class="tp-monitor-cssicon"></span><span id="css_sub_size" class="tp-monitor-total-subsize">%css_size%</span>
    </span>
    <span style="float:left;width:60%; text-align:right;">
        <span class="tp-monitor-showdetails" data-target="#monitor-CSS-details" data-open="</span><?php echo $modules->l("Hide Details");

    ?>" data-close="</span><?php echo $modules->l("Show Details");

    ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo $modules->l("Show Details");

    ?></span></span>
    </span>
    <span class="tp-clearfix" style="height:15px"></span>

    <?php
    //get css files
    echo '<ul class="tp-monitor-list" id="monitor-CSS-details" style="margin-bottom:15px;display:none;">';

    if (file_exists(PS_CONTENT_DIR . '/public/assets/css/settings.css')) {
        $fs = filesize(PS_CONTENT_DIR . '/public/assets/css/settings.css');
        $_li = '<li class="tp-monitor-listli">';
        if ($fs < 60000) {
            $_li .= '<span class="tp-monitor-good"></span>';
        } elseif ($fs < 100000) {
            $_li .= '<span class="tp-monitor-well"></span>';
        } else {
            $_li .= '<span class="tp-monitor-warning"></span>';
        }

        $_li .= '<span class="tp-monitor-size">' . size_format($fs, 0) . '</span>';
        $_li .= '<span class="tp-monitor-file">';
        $_li .= $modules->l('css/settings.css');
        $_li .= '</span>';

        $_li .= '</li>';

        if ($fs > 99999) {
            $issues .=$_li;
        }

        echo $_li;

        $total_size += $fs;
        $css_size += $fs;
    }

    $custom_css = RbSliderOperations::getStaticCss();
    $custom_css = RbSliderCssParser::compressCss($custom_css);

    $_li = '<li class="tp-monitor-listli">';
    if (Tools::strlen($custom_css) < 50000) {
        $_li .= '<span class="tp-monitor-good"></span>';
    } elseif (Tools::strlen($custom_css) < 100000) {
        $_li .= '<span class="tp-monitor-well"></span>';
    } else {
        $_li .= '<span class="tp-monitor-warning"></span>';
    }

    $_li .= '<span class="tp-monitor-size">' . size_format(Tools::strlen($custom_css), 0) . '</span>';
    $_li .= '<span class="tp-monitor-file">';
    $_li .= $modules->l('Static Styles');
    $_li .= '</span>';
    $_li .= '</li>';

    if (Tools::strlen($custom_css) > 49999) {
        $issues .=$_li;
    }

    echo $_li;

    $total_size += Tools::strlen($custom_css);
    $css_size += Tools::strlen($custom_css);

    if (!empty($used_captions)) {
        $captions = array();
        foreach ($used_captions as $class => $val) {
            $cap = RbSliderOperations::getCaptionsContentArray($class);
            if (!empty($cap)) {
                $captions[] = $cap;
            }
        }
        $styles = RbSliderCssParser::parseArrayToCss($captions, "\n");
        $styles = RbSliderCssParser::compressCss($styles);

        $_li = '<li class="tp-monitor-listli">';
        if (Tools::strlen($styles) < 50000) {
            $_li .= '<span class="tp-monitor-good"></span>';
        } elseif (Tools::strlen($styles) < 100000) {
            $_li .= '<span class="tp-monitor-well"></span>';
        } else {
            $_li .= '<span class="tp-monitor-warning"></span>';
        }

        $_li .= '<span class="tp-monitor-size">' . size_format(Tools::strlen($styles), 0) . '</span>';
        $_li .= '<span class="tp-monitor-file">';
        $_li .= $modules->l('Dynamic Styles');
        $_li .= '</span>';

        $_li .= '</li>';
        if (Tools::strlen($styles) > 49999) {
            $issues .=$_li;
        }

        echo $_li;

        $total_size += Tools::strlen($styles);
        $css_size += Tools::strlen($styles);
    }
    echo '</ul>';
    echo ' <span style="display:none" id="css-size-hidden">' . size_format($css_size, 2) . '</span>';



    $js_size = 0;

    ?>
    <span class="tp-clearfix" style="height:15px"></span>
    <hr>
    <span class="tp-clearfix" style="height:25px"></span>

    <!-- THE jQuery PERFORMANCE MESSING -->
    <span class="tp-monitor-performance-title"><?php echo $modules->l("jQuery Performance");

    ?></span>
    <span class="tp-monitor-performace-wrap">
        <span id="video-performace-bar" style="width:%js_performance%%" class="tp-monitor-performance-bar mo-%js_color%-col"></span>
        <span class="tp-monitor-slow"><?php echo $modules->l("Slow");

    ?></span>
        <span class="tp-monitor-ok"><?php echo $modules->l("Ok");

    ?></span>
        <span class="tp-monitor-fast"><?php echo $modules->l("Fast");

    ?></span>
    </span>

    <span class="tp-clearfix" style="height:35px"></span>

    <span style="float:left;width:40%; display:block">				
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("jQuery Loaded:");

    ?></span>				
        <span class="tp-monitor-imageicon"></span><span id="jquery_sub_size" class="tp-monitor-total-subsize">%js_size%</span>				
    </span>
    <span style="float:left;width:60%; text-align:right;">
        <span class="tp-monitor-showdetails" data-target="#monitor-jquery-details" data-open="</span><?php echo $modules->l("Hide Details");

    ?>" data-close="</span><?php echo $modules->l("Show Details");

    ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo $modules->l("Show Details");

    ?></span></span>
    </span>
    <span class="tp-clearfix" style="height:15px"></span>

    <?php
    echo '<ul class="tp-monitor-list" id="monitor-jquery-details" style="margin-bottom:15px;display:none">';

    $jsfiles = array(
        'jquery.themepunch.tools.min.js' => PS_CONTENT_DIR . '/views/js/rs-plugin/js/jquery.themepunch.tools.min.js',
        'jquery.themepunch.rbthemeslider.min.js' => PS_CONTENT_DIR . '/views/js/rs-plugin/js/jquery.themepunch.rbthemeslider.min.js',
    );

    //check which js files will be used by the Slider
    if ($using_kenburns == true) {
        $jsfiles['rbthemeslider.extension.kenburn.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.kenburn.min.js';
    }
    if ($using_parallax == true) {
        $jsfiles['rbthemeslider.extension.parallax.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.parallax.js';
    }
    if ($using_navigation == true) {
        $jsfiles['rbthemeslider.extension.navigation.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.navigation.min.js';
    }
    if ($using_videos == true) {
        $jsfiles['rbthemeslider.extension.video.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.video.min.js';
    }
    if ($using_actions == true) {
        $jsfiles['rbthemeslider.extension.actions.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.actions.min.js';
    }
    if ($using_layeranim == true) {
        $jsfiles['rbthemeslider.extension.layeranimation.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.layeranimation.min.js';
    }
    if ($using_carousel == true) {
        $jsfiles['rbthemeslider.extension.carousel.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.carousel.min.js';
    } else {
        $jsfiles['rbthemeslider.extension.slideanims.min.js'] = PS_CONTENT_DIR . '/views/js/rs-plugin/js/extensions/rbthemeslider.extension.slideanims.min.js';
    }

    //get the js files

    foreach ($jsfiles as $name => $path) {
        if (file_exists($path)) {
            $fs = filesize("$path");
            echo '<li class="tp-monitor-listli">';
            echo '<span class="tp-monitor-good"></span>';
            echo '<span class="tp-monitor-size">' . size_format($fs, 0) . '</span>';
            echo '<span class="tp-monitor-file">';
            echo $name;
            echo '</span>';
            echo '</li>';
            $total_size += $fs;
            $js_size += $fs;
        }
    }

    echo '</ul>';
    echo ' <span style="display:none" id="css-size-hidden">' . size_format($js_size, 2) . '</span>';

    $http = (is_ssl()) ? 'https' : 'http';
    $operations = new RbSliderOperations();
    $arrValues = $operations->getGeneralSettingsValues();
    $set_diff_font = RbSliderFunctions::getVal($arrValues, "change_font_loading", '');

    if ($set_diff_font !== '') {
        $font_url = $set_diff_font;
    } else {
        $font_url = $http . '://fonts.googleapis.com/css?family=';
    }

    $my_fonts = $slider->getParam('google_font', array());

    ?>
    <span class="tp-clearfix" style="height:15px"></span>
    <hr>
    <span class="tp-clearfix" style="height:25px"></span>

    <span class="tp-monitor-performance-title"><?php echo $modules->l("Google Fonts Performance");

    ?></span>
    <span class="tp-monitor-performace-wrap">
        <span id="video-performace-bar" style="width:%font_performance%%" class="tp-monitor-performance-bar mo-%font_color%-col"></span>
        <span class="tp-monitor-slow"><?php echo $modules->l("Slow");

    ?></span>
        <span class="tp-monitor-ok"><?php echo $modules->l("Ok");

    ?></span>
        <span class="tp-monitor-fast"><?php echo $modules->l("Fast");

    ?></span>
    </span>

    <span class="tp-clearfix" style="height:35px"></span>

    <span style="float:left;width:40%; display:block">				
        <span class="tp-monitor-smalllabel"><?php echo $modules->l("Fonts Loaded:");

    ?></span>				
        <span class="tp-monitor-jsicon"></span><span class="tp-monitor-total-subsize">%font_size%</span>				
    </span>
    <span style="float:left;width:60%; text-align:right;">
        <span class="tp-monitor-showdetails" data-target="#monitor-fonts-details" data-open="</span><?php echo $modules->l("Hide Details");

    ?>" data-close="</span><?php echo $modules->l("Show Details");

    ?>"><span class="tp-monitor-openclose"></span><span class="tp-show-inner-btn"><?php echo $modules->l("Show Details");

    ?></span></span>
    </span>
    <span class="tp-clearfix" style="height:15px"></span>

    <?php
    //echo '<span class="tp-monitor-smalllabel">'.$font_url.'</span>';

    echo '<ul class="tp-monitor-list" id="monitor-fonts-details" style="margin-bottom:15px;display:none">';
    $all_font_count = 0;
    if (!empty($my_fonts)) {
        foreach ($my_fonts as $c_font) {
            $fcount = RbSliderBase::getFontWeightCount($c_font);

            $_li = '<li class="tp-monitor-listli">';

            if ($fcount < 4) {
                $_li .= '<span class="tp-monitor-good"></span>';
            } elseif ($fcount < 7) {
                $_li .= '<span class="tp-monitor-well"></span>';
            } else {
                $_li .= '<span class="tp-monitor-warning"></span>';
            }


            $_li .= '<span class="tp-monitor-file">';
            $_li .= strip_tags($c_font);
            $_li .= '</span>';
            $_li .= '</li>';
            if ($fcount > 4) {
                $issues .= $_li;
            }
            echo $_li;
            $all_font_count += $fcount;
        }
    }
    echo '</ul>';

    ?>
</div>

<script>
    jQuery(document).on("ready", function() {

        jQuery('body').on('click', '.tp-monitor-showdetails', function() {
            var bt = jQuery(this);
            if (bt.hasClass("selected")) {
                bt.find('.tp-show-inner-btn').html(bt.data('close'));
                bt.removeClass("selected");
                jQuery(bt.data('target')).slideUp(200);
            } else {
                bt.find('.tp-show-inner-btn').html(bt.data('open'));
                bt.addClass("selected");
                jQuery(bt.data('target')).slideDown(200);
            }

        })
    })
</script>

<?php
$content = ob_get_contents();
ob_end_clean();

if ($img_counter == 0) {
    $img_counter = 1;
}
if ($slide_counter == 0) {
    $slide_counter = 1;
}

$overall = RbSliderOperations::getPerformance($total_size / $slide_counter, 0, 400000); // 400KB / Slide is ok
$image = RbSliderOperations::getPerformance($img_size / $img_counter, 0, 100000); // 100KB Image OK
$css = RbSliderOperations::getPerformance($css_size, 0, 150000); // 150KB CSS OK
$js = RbSliderOperations::getPerformance($js_size, 0, 250000); // 250KB Image OK
$font = RbSliderOperations::getPerformance($all_font_count, 0, 15); // 250KB Image OK
$firstslide_size += $js_size;
$firstslide_size += $css_size;
$smartslide_size += $js_size;
$smartslide_size += $css_size;

$content = str_replace("%overall_performance%", $overall["proc"], $content);
$content = str_replace("%overall_color%", $overall["col"], $content);
$content = str_replace("%overall_size%", size_format($total_size, 2), $content);

$content = str_replace("%image_performance%", $image["proc"], $content);
$content = str_replace("%image_color%", $image["col"], $content);

$content = str_replace("%css_performance%", $css["proc"], $content);
$content = str_replace("%css_color%", $css["col"], $content);
$content = str_replace("%css_size%", size_format($css_size, 2), $content);

$content = str_replace("%js_performance%", $js["proc"], $content);
$content = str_replace("%js_color%", $js["col"], $content);
$content = str_replace("%js_size%", size_format($js_size, 2), $content);

$content = str_replace("%font_performance%", $font["proc"], $content);
$content = str_replace("%font_color%", $font["col"], $content);
$content = str_replace("%font_size%", $all_font_count, $content);

$content = str_replace("%issues%", $issues, $content);
$content = str_replace("%firstslide_size%", size_format($firstslide_size, 2), $content);
$content = str_replace("%smartslide_size%", size_format($smartslide_size, 2), $content);
$content = str_replace("%allslide_size%", size_format($total_size, 2), $content);

$total_size = $total_size / 1000;
$content = str_replace("%umtsspeed-all%", gmdate('i:s', $total_size / 48), $content);
$content = str_replace("%dslspeed-all%", gmdate('i:s', $total_size / 307), $content);
$content = str_replace("%t1speed-all%", gmdate('i:s', $total_size / 1180), $content);

$firstslide_size = $firstslide_size / 1000;
$content = str_replace("%umtsspeed-single%", gmdate('i:s', $firstslide_size / 48), $content);
$content = str_replace("%dslspeed-single%", gmdate('i:s', $firstslide_size / 307), $content);
$content = str_replace("%t1speed-single%", gmdate('i:s', $firstslide_size / 1180), $content);

$smartslide_size = $smartslide_size / 1000;
$content = str_replace("%umtsspeed-smart%", gmdate('i:s', $smartslide_size / 48), $content);
$content = str_replace("%dslspeed-smart%", gmdate('i:s', $smartslide_size / 307), $content);
$content = str_replace("%t1speed-smart%", gmdate('i:s', $smartslide_size / 1180), $content);
echo $content;
