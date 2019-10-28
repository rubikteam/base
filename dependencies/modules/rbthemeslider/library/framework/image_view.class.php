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

class UniteImageViewRb
{
    private $pathCache;
    private $pathImages;
    private $urlImages;
    private $filename = null;
    private $maxWidth = null;
    private $maxHeight = null;
    private $type = null;
    private $effect = null;
    private $effect_arg1 = null;
    private $effect_arg2 = null;

    const EFFECT_BW = "bw";
    const EFFECT_BRIGHTNESS = "bright";
    const EFFECT_CONTRAST = "contrast";
    const EFFECT_EDGE = "edge";
    const EFFECT_EMBOSS = "emboss";
    const EFFECT_BLUR = "blur";
    const EFFECT_BLUR3 = "blur3";
    const EFFECT_MEAN = "mean";
    const EFFECT_SMOOTH = "smooth";
    const EFFECT_DARK = "dark";
    const TYPE_EXACT = "exact";
    const TYPE_EXACT_TOP = "exacttop";

    private $jpg_quality = 81;

    public function __construct($pathCache, $pathImages, $urlImages)
    {
        $this->pathCache = $pathCache;
        $this->pathImages = $pathImages;
        $this->urlImages = $urlImages;
    }

    public function setJPGQuality($quality)
    {
        $this->jpg_quality = $quality;
    }

    public static function getUrlThumb(
        $urlBase,
        $filename,
        $width = null,
        $height = null,
        $exact = false,
        $effect = null,
        $effect_param = null
    ) {
        $filename = urlencode($filename);
        $url = $urlBase."&img=$filename";

        if (!empty($width)) {
            $url .= "&w=".$width;
        }

        if (!empty($height)) {
            $url .= "&h=".$height;
        }

        if ($exact == true) {
            $url .= "&t=".self::TYPE_EXACT;
        }

        if (!empty($effect)) {
            $url .= "&e=".$effect;

            if (!empty($effect_param)) {
                $url .= "&ea1=".$effect_param;
            }
        }

        return($url);
    }

    private function throwError($message, $code = null)
    {
        UniteFunctionsRb::throwError($message, $code);
    }

    private function getThumbFilename()
    {
        $info = pathInfo($this->filename);
        $postfix = "";
        $dirname = UniteFunctionsRb::getVal($info, "dirname");

        if (!empty($dirname)) {
            $postfix = str_replace("/", "-", $dirname);
        }

        $ext = $info["extension"];
        $name = $info["filename"];
        $width = ceil($this->maxWidth);
        $height = ceil($this->maxHeight);
        $thumbFilename = $name."_".$width."x".$height;

        if (!empty($this->type)) {
            $thumbFilename .= "_" . $this->type;
        }

        if (!empty($this->effect)) {
            $thumbFilename .= "_e" . $this->effect;

            if (!empty($this->effect_arg1)) {
                $thumbFilename .= "x" . $this->effect_arg1;
            }
        }

        if (!empty($postfix)) {
            $thumbFilename .= "_".$postfix;
        }

        $thumbFilename .= ".".$ext;

        return($thumbFilename);
    }

    private function getThumbFilepath()
    {
        $filename = $this->getThumbFilename();
        $filepath = $this->pathCache .$filename;

        return($filepath);
    }

    private function outputEmptyImageCode()
    {
        echo "empty image";

        exit();
    }

    private function outputImage($filepath)
    {
        $info = UniteFunctionsRb::getPathInfo($filepath);
        $ext = $info["extension"];
        $filetime = filemtime($filepath);
        $ext = Tools::strtolower($ext);

        if ($ext == "jpg") {
            $ext = "jpeg";
        }

        $numExpires = 31536000;
        $strExpires = @date('D, d M Y H:i:s', time()+$numExpires);
        $strModified = @date('D, d M Y H:i:s', $filetime);
        $contents = Tools::file_get_contents($filepath);
        $filesize = Tools::strlen($contents);

        header("Last-Modified: $strModified GMT");
        header("Expires: $strExpires GMT");
        header("Cache-Control: public");
        header("Content-Type: image/$ext; charset=utf-8");
        header("Content-Length: $filesize");

        echo $contents;

        exit();
    }

    private function getGdSrcImage($filepath, $type)
    {
        $src_img = false;

        switch ($type) {

            case IMAGETYPE_JPEG:

                $src_img = @imagecreatefromjpeg($filepath);

                break;

            case IMAGETYPE_PNG:

                $src_img = @imagecreatefrompng($filepath);

                break;

            case IMAGETYPE_GIF:

                $src_img = @imagecreatefromgif($filepath);

                break;

            case IMAGETYPE_BMP:

                $src_img = @imagecreatefromwbmp($filepath);

                break;

            default:

                $this->throwError("wrong image format, can't resize");

                break;

        }

        if ($src_img == false) {
            $this->throwError("Can't resize image");
        }

        return($src_img);
    }

    private function saveGdImage($dst_img, $filepath, $type)
    {
        $successSaving = false;

        switch ($type) {

            case IMAGETYPE_JPEG:

                $successSaving = imagejpeg($dst_img, $filepath, $this->jpg_quality);

                break;

            case IMAGETYPE_PNG:

                $successSaving = imagepng($dst_img, $filepath);

                break;

            case IMAGETYPE_GIF:

                $successSaving = imagegif($dst_img, $filepath);

                break;

            case IMAGETYPE_BMP:

                $successSaving = imagewbmp($dst_img, $filepath);

                break;

        }

        return($successSaving);
    }

    private function cropImageSaveNew($filepath, $filepathNew)
    {
        $imgInfo = getimagesize($filepath);
        $imgType = $imgInfo[2];
        $src_img = $this->getGdSrcImage($filepath, $imgType);
        $width = imageSX($src_img);
        $height = imageSY($src_img);

        //crop the image from the top
        $startx = 0;
        $starty = 0;

        //find precrop width and height:
        $percent = $this->maxWidth / $width;
        $newWidth = $this->maxWidth;
        $newHeight = ceil($percent * $height);

        if ($this->type == "exact") {
            $startx = 0;
            $starty = ($newHeight-$this->maxHeight)/2 / $percent;
        }

        if ($newHeight < $this->maxHeight) {
            $percent = $this->maxHeight / $height;
            $newHeight = $this->maxHeight;
            $newWidth = ceil($percent * $width);

            if ($this->type == "exact") {
                $startx = ($newWidth - $this->maxWidth) /2 / $percent;
                $starty = 0;
            }
        }

        //resize the picture:
        $tmp_img = ImageCreateTrueColor($newWidth, $newHeight);
        $this->handleTransparency($tmp_img, $imgType, $newWidth, $newHeight);

        imagecopyresampled(
            $tmp_img,
            $src_img,
            0,
            0,
            $startx,
            $starty,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        $this->handleImageEffects($tmp_img);

        //crop the picture:
        $dst_img = ImageCreateTrueColor($this->maxWidth, $this->maxHeight);
        $this->handleTransparency($dst_img, $imgType, $this->maxWidth, $this->maxHeight);
        imagecopy($dst_img, $tmp_img, 0, 0, 0, 0, $newWidth, $newHeight);

        //save the picture
        $is_saved = $this->saveGdImage($dst_img, $filepathNew, $imgType);
        imagedestroy($dst_img);
        imagedestroy($src_img);
        imagedestroy($tmp_img);

        return($is_saved);
    }
    private function handleTransparency(&$dst_img, $imgType, $newWidth, $newHeight)
    {
        if ($imgType == IMAGETYPE_PNG || $imgType == IMAGETYPE_GIF) {
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
            $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
            imagefilledrectangle($dst_img, 0, 0, $newWidth, $newHeight, $transparent);
        }
    }

    private function handleImageEffects(&$imgHandle)
    {
        if (empty($this->effect)) {
            return(false);
        }

        switch ($this->effect) {

            case self::EFFECT_BW:

                if (defined("IMG_FILTER_GRAYSCALE")) {
                    imagefilter($imgHandle, IMG_FILTER_GRAYSCALE);
                }

                break;

            case self::EFFECT_BRIGHTNESS:

                if (defined("IMG_FILTER_BRIGHTNESS")) {
                    if (!is_numeric($this->effect_arg1)) {
                        $this->effect_arg1 = 50;
                    }

                    UniteFunctionsRb::validateNumeric($this->effect_arg1, "'ea1' argument");
                    imagefilter($imgHandle, IMG_FILTER_BRIGHTNESS, $this->effect_arg1);
                }

                break;

            case self::EFFECT_DARK:
                if (defined("IMG_FILTER_BRIGHTNESS")) {
                    if (!is_numeric($this->effect_arg1)) {
                        $this->effect_arg1 = -50;
                    }

                    UniteFunctionsRb::validateNumeric($this->effect_arg1, "'ea1' argument");
                    imagefilter($imgHandle, IMG_FILTER_BRIGHTNESS, $this->effect_arg1);
                }

                break;

            case self::EFFECT_CONTRAST:

                if (defined("IMG_FILTER_CONTRAST")) {
                    if (!is_numeric($this->effect_arg1)) {
                        $this->effect_arg1 = -5;
                    }

                    imagefilter($imgHandle, IMG_FILTER_CONTRAST, $this->effect_arg1);
                }

                break;

            case self::EFFECT_EDGE:
                if (defined("IMG_FILTER_EDGEDETECT")) {
                    imagefilter($imgHandle, IMG_FILTER_EDGEDETECT);
                }

                break;

            case self::EFFECT_EMBOSS:

                if (defined("IMG_FILTER_EMBOSS")) {
                    imagefilter($imgHandle, IMG_FILTER_EMBOSS);
                }

                break;

            case self::EFFECT_BLUR:

                $this->effectBlur($imgHandle, 5);

                break;

            case self::EFFECT_MEAN:

                if (defined("IMG_FILTER_MEAN_REMOVAL")) {
                    imagefilter($imgHandle, IMG_FILTER_MEAN_REMOVAL);
                }

                break;

            case self::EFFECT_SMOOTH:

                if (defined("IMG_FILTER_SMOOTH")) {
                    if (!is_numeric($this->effect_arg1)) {
                        $this->effect_arg1 = 15;
                    }

                    imagefilter($imgHandle, IMG_FILTER_SMOOTH, $this->effect_arg1);
                }

                break;

            case self::EFFECT_BLUR3:

                $this->effectBlur($imgHandle, 5);

                break;

            default:

                $this->throwError("Effect not supported: <b>".$this->effect."</b>");

                break;

        }
    }

    private function effectBlur(&$gdimg, $radius = 0.5)
    {
        $radius = round(max(0, min($radius, 50)) * 2);

        if (!$radius) {
            return false;
        }

        $w = ImageSX($gdimg);
        $h = ImageSY($gdimg);

        if ($imgBlur = ImageCreateTrueColor($w, $h)) {
            for ($i = 0; $i < $radius; $i++) {
                ImageCopy($imgBlur, $gdimg, 0, 0, 1, 1, $w - 1, $h - 1);            // up left
                ImageCopyMerge($imgBlur, $gdimg, 1, 1, 0, 0, $w, $h, 50.00000);  // down right
                ImageCopyMerge($imgBlur, $gdimg, 0, 1, 1, 0, $w - 1, $h, 33.33333);  // down left
                ImageCopyMerge($imgBlur, $gdimg, 1, 0, 0, 1, $w, $h - 1, 25.00000);  // up right
                ImageCopyMerge($imgBlur, $gdimg, 0, 0, 1, 0, $w - 1, $h, 33.33333);  // left
                ImageCopyMerge($imgBlur, $gdimg, 1, 0, 0, 0, $w, $h, 25.00000);  // right
                ImageCopyMerge($imgBlur, $gdimg, 0, 0, 0, 1, $w, $h - 1, 20.00000);  // up
                ImageCopyMerge($imgBlur, $gdimg, 0, 1, 0, 0, $w, $h, 16.666667); // down
                ImageCopyMerge($imgBlur, $gdimg, 0, 0, 0, 0, $w, $h, 50.000000); // center
                ImageCopy($gdimg, $imgBlur, 0, 0, 0, 0, $w, $h);
            }

            return true;
        }

        return false;
    }

    private function resizeImageSaveNew($filepath, $filepathNew)
    {
        $imgInfo = getimagesize($filepath);
        $imgType = $imgInfo[2];
        $src_img = $this->getGdSrcImage($filepath, $imgType);
        $width = imageSX($src_img);
        $height = imageSY($src_img);
        $newWidth = $width;
        $newHeight = $height;

        //find new width
        if ($height > $this->maxHeight) {
            $procent = $this->maxHeight / $height;
            $newWidth = ceil($width * $procent);
            $newHeight = $this->maxHeight;
        }

        //if the new width is grater than max width, find new height, and remain the width.
        if ($newWidth > $this->maxWidth) {
            $procent = $this->maxWidth / $newWidth;
            $newHeight = ceil($newHeight * $procent);
            $newWidth = $this->maxWidth;
        }

        //if the image don't need to be resized, just copy it from source to destanation.
        if ($newWidth == $width && $newHeight == $height && empty($this->effect)) {
            $success = copy($filepath, $filepathNew);

            if ($success == false) {
                $this->throwError("can't copy the image from one path to another");
            }
        } else {
            $dst_img = ImageCreateTrueColor($newWidth, $newHeight);
            $this->handleTransparency($dst_img, $imgType, $newWidth, $newHeight);

            //copy the new resampled image:
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $this->handleImageEffects($dst_img);
            $is_saved = $this->saveGdImage($dst_img, $filepathNew, $imgType);
            imagedestroy($dst_img);
        }

        imagedestroy($src_img);

        return(true);
    }

    public function setEffect($effect, $arg1 = "")
    {
        $this->effect = $effect;
        $this->effect_arg1 = $arg1;
    }

    private function showImage($filename, $maxWidth = -1, $maxHeight = -1, $type = "")
    {
        if (empty($filename)) {
            $this->throwError("image filename not found");
        }

        if ($type == self::TYPE_EXACT || $type == self::TYPE_EXACT_TOP) {
            if ($maxHeight == -1) {
                $this->throwError("image with exact type must have height!");
            }

            if ($maxWidth == -1) {
                $this->throwError("image with exact type must have width!");
            }
        }

        $filepath = $this->pathImages.$filename;

        if (!is_file($filepath)) {
            $this->outputEmptyImageCode();
        }

        //if gd library doesn't exists - output normal image without resizing.
        if (function_exists("gd_info") == false) {
            $this->throwError("php must support GD Library");
        }

        //check conditions for output original image
        if (empty($this->effect)) {
            if ((is_numeric($maxWidth) == false || is_numeric($maxHeight) == false)) {
                $this->outputImage($filepath);
            }

            if ($maxWidth == -1 && $maxHeight == -1) {
                $this->outputImage($filepath);
            }
        }

        if ($maxWidth == -1) {
            $maxWidth = 1000000;
        }

        if ($maxHeight == -1) {
            $maxHeight = 100000;
        }

        //init variables
        $this->filename = $filename;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->type = $type;
        $filepathNew = $this->getThumbFilepath();

        if (is_file($filepathNew)) {
            $this->outputImage($filepathNew);

            exit();
        }

        try {
            if ($type == self::TYPE_EXACT || $type == self::TYPE_EXACT_TOP) {
                $isSaved = $this->cropImageSaveNew($filepath, $filepathNew);
            } else {
                $isSaved = $this->resizeImageSaveNew($filepath, $filepathNew);
            }

            if ($isSaved == false) {
                $this->outputImage($filepath);

                exit();
            }
        } catch (Exception $e) {
            $this->outputImage($filepath);
        }

        if (is_file($filepathNew)) {
            $this->outputImage($filepathNew);
        } else {
            $this->outputImage($filepath);
        }

        exit();
    }

    public function showImageFromGet()
    {
        $imageFilename = UniteFunctionsRb::getGetVar("img");
        $maxWidth = UniteFunctionsRb::getGetVar("w", -1);
        $maxHeight = UniteFunctionsRb::getGetVar("h", -1);
        $type = UniteFunctionsRb::getGetVar("t", "");

        //set effect
        $effect = UniteFunctionsRb::getGetVar("e");
        $effectArgument1 = UniteFunctionsRb::getGetVar("ea1");

        if (!empty($effect)) {
            $this->setEffect($effect, $effectArgument1);
        }

        $this->showImage($imageFilename, $maxWidth, $maxHeight, $type);
    }

    public function downloadImage($filename)
    {
        $filepath = $this->urlImages."/".$filename;

        if (!is_file($filepath)) {
            echo "file doesn't exists";
            exit();
        }

        $this->outputImageForDownload($filepath, $filename);
    }

    private function outputImageForDownload($filepath, $filename, $mimeType = "")
    {
        $contents = Tools::file_get_contents($filepath);
        $filesize = Tools::strlen($contents);

        if ($mimeType == "") {
            $info = UniteFunctionsRb::getPathInfo($filepath);
            $ext = $info["extension"];
            $mimeType = "image/$ext";
        }

        header("Content-Type: $mimeType");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Length: $filesize");

        echo $contents;

        exit();
    }
    
    public function validateType($type)
    {
        switch ($type) {

            case self::TYPE_EXACT:

            case self::TYPE_EXACT_TOP:

                break;

            default:

                $this->throwError("Wrong image type: ".$type);

                break;

        }
    }
}
