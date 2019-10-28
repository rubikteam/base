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

class UniteZipRb
{
    private $zip;

    public static function isZipExists()
    {
        $exists = class_exists("ZipArchive");

        return $exists;
    }

    private function addItem($basePath, $path)
    {
        $rel_path = str_replace($basePath . "/", "", $path);

        if (is_dir($path)) {
            if ($basePath != $path) {
                $this->zip->addEmptyDir($rel_path);
            }

            $files = scandir($path);

            foreach ($files as $file) {
                if ($file == "." || $file == ".." || $file == ".svn") {
                    continue;
                }

                $filepath = $path . "/" . $file;
                $this->addItem($basePath, $filepath);
            }
        } else {
            if (!file_exists($path)) {
                throwError("filepath: '$path' don't exists, can't zip");
            }

            $this->zip->addFile($path, $rel_path);
        }
    }

    public function makeZip($srcPath, $zipFilepath, $additionPaths = array())
    {
        if (!is_dir($srcPath)) {
            throwError("The path: '$srcPath' don't exists, can't zip");
        }

        $this->zip = new ZipArchive;
        $success = $this->zip->open($zipFilepath, ZipArchive::CREATE);

        if ($success == false) {
            throwError("Can't create zip file: $zipFilepath");
        }

        $this->addItem($srcPath, $srcPath);

        if (gettype($additionPaths) != "array") {
            throwError("Wrong additional paths variable.");
        }

        //add additional paths
        if (!empty($additionPaths)) {
            foreach ($additionPaths as $path) {
                if (!is_dir($path)) {
                    throwError("Path: $path not found, can't zip");
                }

                $this->addItem($path, $path);
            }
        }

        $this->zip->close();
    }

    public function extract($src, $dest)
    {
        $zip = new ZipArchive;

        if ($zip->open($src) === true) {
            $zip->extractTo($dest);

            $zip->close();

            return true;
        }

        return false;
    }
}
