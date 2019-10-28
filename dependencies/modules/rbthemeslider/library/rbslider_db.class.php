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

class RbDbEngine
{
    public static $psdb;
    public $mysqli;
    public $prefix;

    public function __construct()
    {
        $this->prefix = _DB_PREFIX_;
    }

    public function realEscape($string)
    {
        return Db::getInstance()->escape($string);
    }

    public function _escape($data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $data[$k] = $this->_escape($v);
                } else {
                    $data[$k] = $this->realEscape($v);
                }
            }
        } else {
            $data = $this->realEscape($data);
        }

        return $data;
    }

    public function query($sql)
    {
        $query = Db::getInstance()->execute($sql);

        if ($query) {
            return true;
        }

        return false;
    }

    public function update(
    	$table,
    	$data,
    	$where = '',
    	$limit = 0,
    	$null_values = false,
    	$use_cache = true,
    	$add_prefix = false
    ) {
        $wherestr = '';
        $c = 0;
        $sql = "UPDATE {$table} SET ";

        if (!empty($data)) {
            foreach ($data as $k => $d) {
                if ($c > 0) {
                    $sql .= ', ';
                }

                if (is_string($d)) {
                    $sql .= "$k=\"" . addslashes($d) . "\"";
                } else {
                    $sql .= "$k=$d";
                }

                $c++;
            }
        }

        $sql .= " ";

        $c = 0;

        if (!empty($where) && is_array($where)) {
            $sql .= "WHERE ";

            foreach ($where as $k => $val) {
                if ($c > 0) {
                    $wherestr .= " AND ";
                }

                $wherestr .= "{$k}=";

                if (is_string($val)) {
                    $wherestr .= '"' . $this->_escape($val) . '"';
                } else {
                    $wherestr .= $val;
                }

                $c++;
            }

            $sql .= $wherestr;
        }

        if (Db::getInstance()->execute($sql)) {
            return true;
        }

        return false;
    }

    public function insert(
    	$table,
    	$data,
    	$null_values = false,
    	$use_cache = true,
    	$type = 1,
    	$add_prefix = false
    ) {
        $c = 0;
        $cols = '';
        $vals = '';

        $sql = "INSERT INTO {$table}";

        if (!empty($data)) {
            $cols .= '(';
            $vals .= ' VALUES(';

            foreach ($data as $k => $d) {
                if ($c > 0) {
                    $cols .= ', ';
                    $vals .= ', ';
                }

                $cols .= $k;

                if (is_string($d)) {
                    $vals .= "'" . addslashes($d) . "'";
                } else {
                    $vals .= $d;
                }

                $c++;
            }

            $cols .= ')';
            $vals .= ')';
        }

        $sql .= "{$cols} {$vals}";

        if (Db::getInstance()->execute($sql)) {
            return $this->insertID();
        }

        return false;
    }

    public function insertID()
    {
        return Db::getInstance()->Insert_ID();
    }

    public function getVar($sql, $assoc = false)
    {
        $query = Db::getInstance()->getValue($sql);

        if (!empty($query)) {
            return $query;
        }

        return false;
    }

    public function getRow($sql, $assoc = false)
    {
        $query = Db::getInstance()->getRow($sql);

        if ($query) {
            return $query;
        }

        return false;
    }

    public function getResults($sql, $assoc = false)
    {
        $query = Db::getInstance()->ExecuteS($sql, true, false);

        if (!empty($query)) {
            return $query;
        }

        if (empty($query) && $assoc == ARRAY_A) {
            return array();
        }

        return false;
    }

    public static function rbDbInstance()
    {
        if (!self::$psdb instanceof RbDbEngine) {
            return self::$psdb = new RbDbEngine();
        }

        return self::$psdb;
    }
}

// @codingStandardsIgnoreStart
class RbDbClass extends RbDbEngine
{

}
