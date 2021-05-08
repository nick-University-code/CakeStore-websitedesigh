<?php
    if(!isset($_SESSION)){ session_start(); } //檢查SESSION是否啟動
        $_SESSION['checkvercode'] = ''; //設置存放檢查碼的SESSION

    header("Content-type: image/PNG");
    
       $nums=5;         //幾個數字
       $width=120;      //寬度
       $high=30;        //高
       //
        //驗證碼出現字元  去掉0 1 l O o 避免辨認不清
        $str = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMPQRSTUBWXYZ";
        
        $code = '';
        for ($i = 0; $i < $nums; $i++) {
            $code .= $str[mt_rand(0, strlen($str)-1)];
        }

        $_SESSION['checkvercode'] = $code;

        //建立圖示，設置寬度及高度與顏色等等條件
        $image = imagecreate($width, $high);
        $black = imagecolorallocate($image, mt_rand(0, 220), mt_rand(0, 220), mt_rand(0, 220));
        $border_color = imagecolorallocate($image, 0, 0, 0);
        $background_color = imagecolorallocate($image, 240, 240, 240);

        //建立圖示背景
        imagefilledrectangle($image, 0, 0, $width, $high, $background_color);

        //建立圖示邊框
        imagerectangle($image, 0, 0, $width-1, $high-1, $border_color);

        //在圖示布上隨機產生大量躁點
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $high), $black);
        }
       
        $strx = rand(3, 8);
        for ($i = 0; $i < $nums; $i++) {
            $strpos = rand(1, 6);
            imagestring($image, 5, $strx, $strpos, substr($code, $i, 1), $black);
            $strx += rand(15, 30);
        }

        imagepng($image);
?>