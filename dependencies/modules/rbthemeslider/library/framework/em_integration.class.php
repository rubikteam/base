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

class UniteEmRb
{
    public function __construct()
    {
        $this->modules = new Rbthemeslider();
    }

    const DEFAULT_FILTER = "none";

    public static function isEventsExists()
    {
        if (defined("EM_VERSION") && defined("EM_PRO_MIN_VERSION")) {
            return(true);
        }

        return(false);
    }

    public static function getArrFilterTypes()
    {
        $arrEventsSort = array(
            "none" => $this->modules->l("All Events"),
            "today" => $this->modules->l("Today"),
            "tomorrow" => $this->modules->l("Tomorrow"),
            "future" => $this->modules->l("Future"),
            "past" => $this->modules->l("Past"),
            "month" => $this->modules->l("This Month"),
            "nextmonth" => $this->modules->l("Next Month")
        );

        return($arrEventsSort);
    }

    public static function getPSQuery($filterType, $sortBy)
    {
        $dayMs = 60*60*24;
        $response = array();
        $time = currentTime('timestamp');
        $todayStart = strtotime(date('Y-m-d', $time));
        $todayEnd = $todayStart + $dayMs-1;
        $tomorrowStart = $todayEnd+1;
        $tomorrowEnd = $tomorrowStart + $dayMs-1;
        $start_month = strtotime(date('Y-m-1', $time));
        $end_month = strtotime(date('Y-m-t', $time)) + 86399;
        $next_month_middle = strtotime('+1 month', $time);
        $start_next_month = strtotime(date('Y-m-1', $next_month_middle));
        $end_next_month = strtotime(date('Y-m-t', $next_month_middle)) + 86399;
        $query = array();

        switch ($filterType) {
            case self::DEFAULT_FILTER:

                break;

            case "today":
                $query[] = array( 'key' => '_start_ts', 'value' => $todayEnd, 'compare' => '<=' );
                $query[] = array( 'key' => '_end_ts', 'value' => $todayStart, 'compare' => '>=' );

                break;

            case "future":
                $query[] = array( 'key' => '_start_ts', 'value' => $time, 'compare' => '>' );

                break;

            case "tomorrow":
                $query[] = array( 'key' => '_start_ts', 'value' => $tomorrowEnd, 'compare' => '<=' );
                $query[] = array( 'key' => '_end_ts', 'value' => $todayStart, 'compare' => '>=' );

                break;

            case "past":
                $query[] = array( 'key' => '_end_ts', 'value' => $todayStart, 'compare' => '<' );

                break;

            case "month":
                $query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
                $query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );

                break;

            case "nextmonth":
                $query[] = array( 'key' => '_start_ts', 'value' => $end_next_month, 'compare' => '<=' );
                $query[] = array( 'key' => '_end_ts', 'value' => $start_next_month, 'compare' => '>=' );

                break;

            default:

                UniteFunctionsRb::throwError("Wrong event filter");

                break;
        }

        if (!empty($query)) {
            $response["meta_query"] = $query;
        }

        //convert sortby
        switch ($sortBy) {
            case "event_start_date":
                $response["orderby"] = "meta_value_num";
                $response["meta_key"] = "_start_ts";

                break;

            case "event_end_date":
                $response["orderby"] = "meta_value_num";
                $response["meta_key"] = "_end_ts";

                break;

        }

        return($response);
    }

    public static function getArrSortBy()
    {
        $arrSortBy = array();
        $arrSortBy["event_start_date"] = $this->modules->l("Event Start Date");
        $arrSortBy["event_end_date"] = $this->modules->l("Event End Date");

        return($arrSortBy);
    }
}
