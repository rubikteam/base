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

class RbHeading extends RbControl
{
	public function __construct()
    {
    	parent::__construct();
    	$this->setControl();
    }

    public function setControl()
    {
    	$module = new Rbthemedream();

    	$this->addControl(
			'section_title',
			array(
				'label' => $module->l('Title'),
				'type' => 'section',
			)
		);

		$this->addControl(
			'title',
			array(
				'label' => $module->l('Title'),
				'type' => 'textarea',
				'placeholder' => $module->l('Enter your title'),
				'default' => $module->l('This is heading element'),
				'section' => 'section_title',
			)
		);

		$this->addControl(
			'link',
			array(
				'label' => $module->l('Link'),
				'type' => 'url',
				'placeholder' => 'https://www.youtube.com/watch?v=Kuz0A-wvx5c',
				'default' => array(
					'url' => '',
				),
				'section' => 'section_title',
				'separator' => 'before',
			)
		);

		$this->addControl(
			'size',
			array(
				'label' => $module->l('Size'),
				'type' => 'select',
				'default' => 'default',
				'options' => array(
					'default' => $module->l('Default'),
					'small' => $module->l('Small'),
					'medium' => $module->l('Medium'),
					'large' => $module->l('Large'),
					'xl' => $module->l('XL'),
					'xxl' => $module->l('XXL'),
				),
				'section' => 'section_title',
			)
		);

		$this->addControl(
			'header_size',
			array(
				'label' => $module->l('HTML Tag'),
				'type' => 'select',
				'options' => array(
					'h1' => $module->l('H1'),
					'h2' => $module->l('H2'),
					'h3' => $module->l('H3'),
					'h4' => $module->l('H4'),
					'h5' => $module->l('H5'),
					'h6' => $module->l('H6'),
					'div' => $module->l('div'),
					'span' => $module->l('span'),
					'p' => $module->l('p'),
				),
				'default' => 'h2',
				'section' => 'section_title',
			)
		);

		$this->addControl(
			'header_style',
			array(
				'label' => $module->l('Inherit from global'),
				'type' => 'select',
				'options' => array(
					'none' => $module->l('None'),
					'page-title' => $module->l('Page title'),
					'section-title' => $module->l('Section title'),
					'block-title' => $module->l('Block title'),
				),
				'default' => 'none',
				'section' => 'section_title',
			)
		);

		$this->addResponsiveControl(
			'align',
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
					'justify' => array(
						'title' => $module->l('Justified'),
						'icon' => 'align-justify',
					),
				),
				'default' => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
				'section' => 'section_title',
			)
		);

		$this->addControl(
			'view',
			array(
				'label' => $module->l('View'),
				'type' => 'hidden',
				'default' => 'traditional',
				'section' => 'section_title',
			)
		);

		$this->addControl(
			'section_title_style',
			array(
				'label' => $module->l('Title'),
				'type' => 'section',
				'tab' => 'style',
			)
		);

		$this->addControl(
			'title_color',
			array(
				'label' => $module->l('Text Color'),
				'type' => 'color',
				'scheme' => array(
				    'type' => 'color',
				    'value' => 1,
				),
				'tab' => 'style',
				'section' => 'section_title_style',
				'selectors' => array(
					'{{WRAPPER}} .rb-heading-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->addGroupControl(
			'typography',
			array(
				'name' => 'typography',
				'scheme' => 1,
				'tab' => 'style',
				'section' => 'section_title_style',
				'selector' => '{{WRAPPER}} .rb-heading-title',
			)
		);
    }
    
    public function getDataHeading()
    {
    	$controls = $this->getControls();

        $data = array(
    		'title' => 'Title',
    		'controls' => $controls,
    		'tabs_controls' => $this->tabs_controls,
    		'categories' => array('basic'),
    		'keywords' => '',
    		'icon' => 'heading'
    	);

    	return $data;
    }

    public function rbRender($instance = array())
    {
    	if (empty($instance['title']))
			return;

		$this->addRenderAttribute(
			'heading',
			'class',
			'rb-heading-title'
		);

		if (!empty($instance['size'])) {
			$this->addRenderAttribute(
				'heading',
				'class',
				'rb-size-' . $instance['size']
			);
		}

		if (!empty($instance['header_style'])) {
			$this->addRenderAttribute(
				'heading',
				'class',
				$instance['header_style']
			);
		}

		if (!empty($instance['link']['url'])) {
			$this->addRenderAttribute(
				'url',
				'href',
				$instance['link']['url']
			);

			if ($instance['link']['is_external']) {
				$this->addRenderAttribute('url', 'target', '_blank');
				$this->addRenderAttribute('url', 'rel', 'noopener noreferrer');
			}

			$url = sprintf('<a %1$s>%2$s</a>', $this->getRenderAttributeString('url'), $instance['title']);

			$title_html = sprintf(
				'<%1$s %2$s>%3$s</%1$s>',
				$instance['header_size'],
				$this->getRenderAttributeString('heading'),
				$url
			);
		} else {
			$title_html = sprintf(
				'<%1$s %2$s>%3$s</%1$s>',
				$instance['header_size'],
				$this->getRenderAttributeString('heading' ), '<span>'.$instance['title'].'</span>'
			);
		}

		return $title_html;
    }
}	
