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

require_once(_PS_MODULE_DIR_.'rbthemedream/lib/control/rb-control.php');

class RbVideo extends RbControl
{
    public static $static;
    
	public function __construct()
    {
    	parent::__construct();
    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$this->addControl(
            'section_video',
            array(
                'label' => $module->l('Video'),
                'type' => 'section',
            )
        );

        $this->addControl(
            'video_type',
            array(
                'label' => $module->l('Video Type'),
                'type' => 'select',
                'section' => 'section_video',
                'default' => 'youtube',
                'options' => array(
                    'youtube' => $module->l('YouTube'),
                    'vimeo' => $module->l('Vimeo'),
                ),
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => $module->l('Link'),
                'type' => 'text',
                'section' => 'section_video',
                'placeholder' => $module->l('Enter your YouTube link'),
                'default' => 'https://www.youtube.com/watch?v=Kuz0A-wvx5c',
                'label_block' => true,
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'vimeo_link',
            array(
                'label' => $module->l('Vimeo Link'),
                'type' => 'text',
                'section' => 'section_video',
                'placeholder' => $module->l('Enter your Vimeo link'),
                'default' => 'https://vimeo.com/170933924',
                'label_block' => true,
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'hosted_link',
            array(
                'label' => $module->l('Link'),
                'type' => 'text',
                'section' => 'section_video',
                'placeholder' => $module->l('Enter your video link'),
                'default' => '',
                'label_block' => true,
                'condition' => array(
                    'video_type' => 'hosted',
                ),
            )
        );

        $this->addControl(
            'aspect_ratio',
            array(
                'label' => $module->l('Aspect Ratio'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    '169' => '16:9',
                    '43' => '4:3',
                    '32' => '3:2',
                ),
                'default' => '169',
                'prefix_class' => 'rb-aspect-ratio-',
            )
        );

        $this->addControl(
            'heading_youtube',
            array(
                'label' => $module->l('Video Options'),
                'type' => 'heading',
                'section' => 'section_video',
                'separator' => 'before',
            )
        );

        $this->addControl(
            'in_modal',
            array(
                'label' => $module->l('In modal'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'no' => $module->l('No'),
                    'yes' => $module->l('Yes'),
                ),
                'default' => 'no',
            )
        );

        $this->addControl(
            'section_style',
            array(
                'label' => $module->l('Modal Trigger'),
                'type' => 'section',
                'tab' => 'style',
                'condition' => array(
                    'in_modal' => 'yes',
                ),
            )
        );

        $this->addControl(
            'shape_size',
            array(
                'label' => $module->l('Shape Height'),
                'type' => 'slider',
                'default' => array(
                    'size' => 80,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 16,
                        'max' => 300,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .rb-video-open-modal i' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'tab' => 'style',
                'section' => 'section_style',
            )
        );

        $this->addControl(
            'modal_play_color',
            array(
                'label' => $module->l('Play Color'),
                'type' => 'color',
                'tab' => 'style',
                'section' => 'section_style',
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .rb-video-open-modal' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'in_modal' => 'yes',
                ),
            )
        );

        $this->addControl(
            'modal_play_align',
            array(
                'label' => $module->l('Alignment'),
                'type' => 'choose',
                'options' => array(
                    'left' => array(
                        'title' => $module->l('Left'),
                        'icon' => 'align-left',
                    ),
                    'center' => array(
                        'title' => $module->l('Center'),
                        'icon' => 'align-center',
                    ),
                    'right' => array(
                        'title' => $module->l('Right'),
                        'icon' => 'align-right',
                    ),
                ),
                'default' => 'center',
                'tab' => 'style',
                'section' => 'section_style',
                'condition' => array(
                    'in_modal' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'yt_autoplay',
            array(
                'label' => $module->l('Autoplay'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'no' => $module->l('No'),
                    'yes' => $module->l('Yes'),
                ),
                'condition' => array(
                    'video_type' => 'youtube',
                ),
                'default' => 'no',
            )
        );

        $this->addControl(
            'yt_loop',
            array(
                'label' => $module->l('Loop'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'no' => $module->l('No'),
                    'yes' => $module->l('Yes'),
                ),
                'condition' => array(
                    'video_type' => 'youtube',
                ),
                'default' => 'no',
            )
        );

        $this->addControl(
            'yt_rel',
            array(
                'label' => $module->l('Suggested Videos'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'no' => $module->l('Hide'),
                    'yes' => $module->l('Show'),
                ),
                'default' => 'no',
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'yt_controls',
            array(
                'label' => $module->l('Player Control'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'yes' => $module->l('Show'),
                    'no' => $module->l('Hide'),
                ),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'yt_showinfo',
            array(
                'label' => $module->l('Player Title & Actions'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'yes' => $module->l('Show'),
                    'no' => $module->l('Hide'),
                ),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'youtube',
                ),
            )
        );

        $this->addControl(
            'vimeo_autoplay',
            array(
                'label' => $module->l('Autoplay'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'no' => $module->l('No'),
                    'yes' => $module->l('Yes'),
                ),
                'default' => 'no',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_loop',
            array(
                'label' => $module->l('Loop'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'no' => $module->l('No'),
                    'yes' => $module->l('Yes'),
                ),
                'default' => 'no',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_title',
            array(
                'label' => $module->l('Intro Title'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'yes' => $module->l('Show'),
                    'no' => $module->l('Hide'),
                ),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_portrait',
            array(
                'label' => $module->l('Intro Portrait'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'yes' => $module->l('Show'),
                    'no' => $module->l('Hide'),
                ),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_byline',
            array(
                'label' => $module->l('Intro Byline'),
                'type' => 'select',
                'section' => 'section_video',
                'options' => array(
                    'yes' => $module->l('Show'),
                    'no' => $module->l('Hide'),
                ),
                'default' => 'yes',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'vimeo_color',
            array(
                'label' => $module->l('Controls Color'),
                'type' => 'color',
                'section' => 'section_video',
                'default' => '',
                'condition' => array(
                    'video_type' => 'vimeo',
                ),
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => $module->l('View'),
                'type' => 'hidden',
                'section' => 'section_video',
                'default' => 'youtube',
            )
        );

        $this->addControl(
            'section_image_overlay',
            array(
                'label' => $module->l('Image Overlay'),
                'type' => 'section',
                'condition' => array(
                    'in_modal' => 'no',
                ),
            )
        );

        $this->addControl(
            'show_image_overlay',
            array(
                'label' => $module->l('Image Overlay'),
                'type' => 'select',
                'default' => 'no',
                'options' => array(
                    'no' => $module->l('Hide'),
                    'yes' => $module->l('Show'),
                ),
                'condition' => array(
                    'in_modal' => 'no',
                ),
                'section' => 'section_image_overlay',
            )
        );

        $this->addControl(
            'image_overlay',
            array(
                'label' => $module->l('Image'),
                'type' => 'media',
                'default' => array(
                    'url' => _MODULE_DIR_ . 'rbthemedream/views/img/img.jpg',
                ),
                'section' => 'section_image_overlay',
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'in_modal' => 'no',
                ),
            )
        );

        $this->addControl(
            'show_play_icon',
            array(
                'label' => $module->l('Play Icon'),
                'type' => 'select',
                'default' => 'yes',
                'options' => array(
                    'yes' => $module->l('Yes'),
                    'no' => $module->l('No'),
                ),
                'section' => 'section_image_overlay',
                'condition' => array(
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                ),
            )
        );
    }

    public function getDataVideo()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Video',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'video'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
        self::$static = $instance;
        $this->current_instance = $instance;
        $video_html = 1;

        if ('hosted' !== $instance['video_type']) {
            $video_link = 'youtube' === $instance['video_type'] ? $instance['link'] : $instance['vimeo_link'];
            if (empty($video_link))
                return;

            $video_html = $this->videoParser($video_link, $this->getEmbedSettings());
        }

        $context = Context::getContext();
        $module = new Rbthemedream();

        $context->smarty->assign(array(
            'instance' => $instance,
            'current_instance' => $this->current_instance,
            'video_html' => $video_html,
        ));

        return $module->fetch('module:rbthemedream/views/templates/widget/rb-video.tpl');
    }

    public function videoParser($url, $settings, $wdth = 320, $hth = 320)
    {
        $params = '';
        if (strpos($url, 'youtube.com') !== FALSE) {
            $step1 = explode('v=', $url);
            $step2 = explode('&amp;', $step1[1]);

            if (isset($settings['autoplay']) && $settings['autoplay']) {
                $params .= '?autoplay=1';
            }
            else {
                $params .= '?autoplay=0';
            }
            if ($settings['loop']) {
                $params .= '&loop=1 &playlist='.$step2[0];
            }
            if (!$settings['rel']) {
                $params .= '&rel=0';
            }
            if (!$settings['controls']) {
                $params .= '&controls=0';
            }
            if (!$settings['showinfo']) {
                $params .= '&showinfo=0';
            }

            $iframe = '<iframe width="' . $wdth . '" height="' . $hth . '" src="https://www.youtube.com/embed/' . $step2[0] . $params . '" frameborder="0" allowfullscreen></iframe>';

        } else if (strpos($url, 'vimeo') !== FALSE) {
            if (isset($settings['autoplay']) && $settings['autoplay']) {
                $params .= '?autoplay=1';
            }
            else {
                $params .= '?autoplay=0';
            }
            if ($settings['loop']) {
                $params .= '&loop=1';
            }
            if (!$settings['title']) {
                $params .= '&title=0';
            }
            if (!$settings['portrait']) {
                $params .= '&portrait=0';
            }
            if (!$settings['byline']) {
                $params .= '&byline=0';
            }
            if ($settings['color'] != '') {
                $params .= '&color='.$settings['color'];
            }
            $id = preg_replace("/[^\/]+[^0-9]|(\/)/", "", rtrim($url, "/"));
            $embedurl = "https://player.vimeo.com/video/" . $id;
            $iframe = '<iframe src="'.$embedurl.$params .'"  width="' . $wdth . '" height="' . $hth . '"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        }

        return $iframe;
    }


    public function getEmbedSettings()
    {
        $params = array();

        if ('youtube' === $this->current_instance['video_type']) {
            $youtube_options = array('autoplay', 'loop', 'rel', 'controls', 'showinfo');

            foreach ($youtube_options as $option) {
                if ( 'autoplay' === $option && $this->allowAutoplay() )
                    continue;

                $value = ('yes' === $this->current_instance['yt_' . $option]) ? '1' : '0';
                $params[ $option ] = $value;
            }

            $params['wmode'] = 'opaque';
        }

        if ('vimeo' === $this->current_instance['video_type']) {
            $vimeo_options = array('autoplay', 'loop', 'title', 'portrait', 'byline');

            foreach ($vimeo_options as $option) {
                if ('autoplay' === $option && $this->allowAutoplay())
                    continue;

                $value = ('yes' === $this->current_instance[ 'vimeo_' . $option ]) ? '1' : '0';
                $params[ $option ] = $value;
            }

            $params['color'] = str_replace('#', '', $this->current_instance['vimeo_color']);
            $params['autopause'] = '0';
        }

         return $params;
    }

    public function allowAutoplay()
    {
        return !empty($this->current_instance['image_overlay']['url'] ) &&
        'yes' === $this->current_instance['show_image_overlay'] ||
        ('yes' === $this->current_instance['in_modal']);
    }

    public static function hasImageOverlay()
    {
        return !empty(self::$static['image_overlay']['url']) &&
        self::$static['show_image_overlay'] === 'yes';
    }
}
