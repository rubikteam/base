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

class RbSliderParams extends UniteElementsBaseRb
{
    public function updateFieldInDB($name, $value)
    {
        $arr = $this->db->fetch(GlobalsRbSlider::$table_settings);

        if (empty($arr)) {
            $arrInsert = array();
            $arrInsert["general"] = "";
            $arrInsert["params"] = "";
            $arrInsert[$name] = $value;

            $this->db->insert(GlobalsRbSlider::$table_settings, $arrInsert);
        } else {
            $arrUpdate = array();
            $arrUpdate[$name] = $value;
            $id = $arr[0]["id"];

            $this->db->update(GlobalsRbSlider::$table_settings, $arrUpdate, array("id" => $id));
        }
    }

    public function getFieldFromDB($name)
    {
        $arr = $this->db->fetch(GlobalsRbSlider::$table_settings);

        if (empty($arr)) {
            return("");
        }

        $arr = $arr[0];

        if (array_key_exists($name, $arr) == false) {
            UniteFunctionsRb::throwError("The settings db should cotnain field: $name");
        }

        $value = $arr[$name];

        return($value);
    }
}
