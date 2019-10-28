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

class UniteDBRb
{
    private $psdb;
    private $lastRowID;

    public function __construct()
    {
        if (class_exists('Rbthemeslider')) {
            $this->psdb = Rbthemeslider::$psdb;
        } else {
            $this->psdb = rbDbClass::rbDbInstance();
        }
    }

    private function throwError($message, $code = -1)
    {
        UniteFunctionsRb::throwError($message, $code);
    }

    private function checkForErrors($prefix = "")
    {
        $errno = Db::getInstance()->getNumberError();

        if (!empty($errno)) {
            $message = Db::getInstance()->getMsgError();
            $message = $prefix . ' - <b>' . $message . '</b>';

            $this->throwError($message);
        }
    }

    public function insert($table, $arrItems)
    {
        $link = $this->psdb->insert($table, $arrItems);
        $this->checkForErrors("Insert query error");
        $this->lastRowID = false;

        if (!empty($link)) {
            $this->lastRowID = $link;
        }

        return($this->lastRowID);
    }

    public function getLastInsertID()
    {
        $this->lastRowID = $this->psdb->insertID();

        return($this->lastRowID);
    }

    public function delete($table, $where)
    {
        UniteFunctionsRb::validateNotEmpty($table, "table name");
        UniteFunctionsRb::validateNotEmpty($where, "where");
        $query = "delete from $table where $where";
        $this->psdb->query($query);
        $this->checkForErrors("Delete query error");
    }

    public function runSql($query)
    {
        $this->psdb->query($query);
        $this->checkForErrors("Regular query error");
    }

    public function update($table, $arrItems, $where)
    {
        $response = $this->psdb->update($table, $arrItems, $where);

        if ($response === false) {
            UniteFunctionsRb::throwError("no update action taken!");
        }

        $this->checkForErrors("Update query error");

        return $response;
    }

    public function fetch(
        $tableName,
        $where = "",
        $orderField = "",
        $groupByField = "",
        $sqlAddon = ""
    ) {
        $query = "select * from $tableName";

        if (!empty($where)) {
            $query .= " where $where";
        }

        if (!empty($orderField)) {
            $query .= " order by $orderField";
        }

        if (!empty($groupByField)) {
            $query .= " group by $groupByField";
        }

        if (!empty($sqlAddon)) {
            $query .= " " . $sqlAddon;
        }

        $response = $this->psdb->getResults($query, ARRAY_A);
        $this->checkForErrors("fetch");

        return($response);
    }

    public function fetchSingle(
        $tableName,
        $where = "",
        $orderField = "",
        $groupByField = "",
        $sqlAddon = ""
    ) {
        $response = $this->fetch($tableName, $where, $orderField, $groupByField, $sqlAddon);

        if (empty($response)) {
            $this->throwError("Record not found");
        }

        $record = $response[0];

        return($record);
    }

    public function escape($string)
    {
        $string = escSql($string);

        return($string);
    }
}

class RbSliderDB extends UniteDBRb
{
    
}
