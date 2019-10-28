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

require_once(_PS_MODULE_DIR_.'rbthemedream/rbthemedream.php');

class SchemeColor
{
	const COLOR_1 = '1';
	const COLOR_2 = '2';
	const COLOR_3 = '3';
	const COLOR_4 = '4';
	public $color_value;

    public function __construct()
    {
    	$this->module = new Rbthemedream();

    	$this->color_title = array(
    		self::COLOR_1 => $this->module->l('Primary'),
			self::COLOR_2 => $this->module->l('Secondary' ),
			self::COLOR_3 => $this->module->l('Text'),
			self::COLOR_4 => $this->module->l('Accent'),
    	);

    	$this->color_value = array(
    		self::COLOR_1 => '#6ec1e4',
			self::COLOR_2 => '#54595f',
			self::COLOR_3 => '#7a7a7a',
			self::COLOR_4 => '#61ce70',
    	);
    }

	public function getColorDefault()
	{
		$data = array();

		foreach ($this->color_title as $key_c => $color) {
			$data[] = array(
				'title' => $color,
				'value' => $this->color_value[$key_c],
			);
		}

		return $data;
	}

	public static function getSystemScheme()
	{
		return array(
			'joker' => array(
				'title' => 'Joker',
				'items' => array(
					self::COLOR_1 => '#000000',
					self::COLOR_2 => '#b7b4b4',
					self::COLOR_3 => '#707070',
					self::COLOR_4 => '#f6121c',
				)
			),
			'ocean' => array(
				'title' => 'Ocean',
				'items' => array(
					self::COLOR_1 => '#1569ae',
					self::COLOR_2 => '#1569ae',
					self::COLOR_3 => '#969696',
					self::COLOR_4 => '#fdd247',
				)
			),
			'royal' => array(
				'title' => 'Royal',
				'items' => array(
					self::COLOR_1 => '#d5ba7f',
					self::COLOR_2 => '#902729',
					self::COLOR_3 => '#95938f',
					self::COLOR_4 => '#302a8c',
				)
			),
			'violet' => array(
				'title' => 'Violet',
				'items' => array(
					self::COLOR_1 => '#747476',
					self::COLOR_2 => '#ebca41',
					self::COLOR_3 => '#6f1683',
					self::COLOR_4 => '#a43cbd',
				)
			),
			'sweet' => array(
				'title' => 'Sweet',
				'items' => array(
					self::COLOR_1 => '#6ccdd9',
					self::COLOR_2 => '#763572',
					self::COLOR_3 => '#919ca7',
					self::COLOR_4 => '#f12184',
				)
			),
			'urban' => array(
				'title' => 'Urban',
				'items' => array(
					self::COLOR_1 => '#db6159',
					self::COLOR_2 => '#3b3b3b',
					self::COLOR_3 => '#7a7979',
					self::COLOR_4 => '#2abf64',
				)
			),
			'earth' => array(
				'title' => 'Earth',
				'items' => array(
					self::COLOR_1 => '#882021',
					self::COLOR_2 => '#c48e4c',
					self::COLOR_3 => '#825e24',
					self::COLOR_4 => '#e8c12f',
				)
			),
			'river' => array(
				'title' => 'River',
				'items' => array(
					self::COLOR_1 => '#8dcfc8',
					self::COLOR_2 => '#565656',
					self::COLOR_3 => '#50656e',
					self::COLOR_4 => '#dc5049',
				)
			),
			'pastel' => array(
				'title' => 'Pastel',
				'items' => array(
					self::COLOR_1 => '#f27f6f',
					self::COLOR_2 => '#f4cd78',
					self::COLOR_3 => '#a5b3c1',
					self::COLOR_4 => '#aac9c3',
				)
			),
		);
	}
}