<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
 
    define('QR_VECT', true);

    class QRvect {
    
        //----------------------------------------------------------------------
        public static function eps($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE, $back_color = 0xFFFFFF, $fore_color = 0x000000, $cmyk = false) 
        {
            $vect = self::vectEPS($frame, $pixelPerPoint, $outerFrame, $back_color, $fore_color, $cmyk);
            
            if ($filename === false) {
                header("Content-Type: application/postscript");
                header('Content-Disposition: filename="qrcode.eps"');
                echo $vect;
            } else {
                if($saveandprint===TRUE){
                    QRtools::save($vect, $filename);
                    header("Content-Type: application/postscript");
                    header('Content-Disposition: filename="qrcode.eps"');
                    echo $vect;
                }else{
                    QRtools::save($vect, $filename);
                }
            }
        }
        
    
        //----------------------------------------------------------------------
        private static function vectEPS($frame, $pixelPerPoint = 4, $outerFrame = 4, $back_color = 0xFFFFFF, $fore_color = 0x000000, $cmyk = false) 
        {
            $h = count($frame);
            $w = strlen($frame[0]);
            
            $imgW = $w + 2*$outerFrame;
            $imgH = $h + 2*$outerFrame;
            
            if ($cmyk)
            {
                // convert color value into decimal eps format
                $c = round((($fore_color & 0xFF000000) >> 16) / 255, 5);
                $m = round((($fore_color & 0x00FF0000) >> 16) / 255, 5);
                $y = round((($fore_color & 0x0000FF00) >> 8) / 255, 5);
                $k = round(($fore_color & 0x000000FF) / 255, 5);
                $fore_color_string = $c.' '.$m.' '.$y.' '.$k.' setcmykcolor'."\n";

                // convert color value into decimal eps format
                $c = round((($back_color & 0xFF000000) >> 16) / 255, 5);
                $m = round((($back_color & 0x00FF0000) >> 16) / 255, 5);
                $y = round((($back_color & 0x0000FF00) >> 8) / 255, 5);
                $k = round(($back_color & 0x000000FF) / 255, 5);
                $back_color_string = $c.' '.$m.' '.$y.' '.$k.' setcmykcolor'."\n";
            }
            else
            {
                // convert a hexadecimal color code into decimal eps format (green = 0 1 0, blue = 0 0 1, ...)
                $r = round((($fore_color & 0xFF0000) >> 16) / 255, 5);
                $b = round((($fore_color & 0x00FF00) >> 8) / 255, 5);
                $g = round(($fore_color & 0x0000FF) / 255, 5);
                $fore_color_string = $r.' '.$b.' '.$g.' setrgbcolor'."\n";

                // convert a hexadecimal color code into decimal eps format (green = 0 1 0, blue = 0 0 1, ...)
                $r = round((($back_color & 0xFF0000) >> 16) / 255, 5);
                $b = round((($back_color & 0x00FF00) >> 8) / 255, 5);
                $g = round(($back_color & 0x0000FF) / 255, 5);
                $back_color_string = $r.' '.$b.' '.$g.' setrgbcolor'."\n";
            }
            
            $output = 
            '%!PS-Adobe EPSF-3.0'."\n".
            '%%Creator: PHPQrcodeLib'."\n".
            '%%Title: QRcode'."\n".
            '%%CreationDate: '.date('Y-m-d')."\n".
            '%%DocumentData: Clean7Bit'."\n".
            '%%LanguageLevel: 2'."\n".
            '%%Pages: 1'."\n".
            '%%BoundingBox: 0 0 '.$imgW * $pixelPerPoint.' '.$imgH * $pixelPerPoint."\n";
            
            // set the scale
            $output .= $pixelPerPoint.' '.$pixelPerPoint.' scale'."\n";
            // position the center of the coordinate system
            
            $output .= $outerFrame.' '.$outerFrame.' translate'."\n";
           
           
            
            
            // redefine the 'rectfill' operator to shorten the syntax
            $output .= '/F { rectfill } def'."\n";
            
            // set the symbol color
            $output .= $back_color_string;
            $output .= '-'.$outerFrame.' -'.$outerFrame.' '.($w + 2*$outerFrame).' '.($h + 2*$outerFrame).' F'."\n";
            
            
            // set the symbol color
            $output .= $fore_color_string;

            // Convert the matrix into pixels

            for($i=0; $i<$h; $i++) {
                for($j=0; $j<$w; $j++) {
                    if( $frame[$i][$j] == '1') {
                        $y = $h - 1 - $i;
                        $x = $j;
                        $output .= $x.' '.$y.' 1 1 F'."\n";
                    }
                }
            }
            
            
            $output .='%%EOF';
            
            return $output;
        }
        
        //----------------------------------------------------------------------
        public static function svg($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4,$saveandprint=FALSE, $back_color, $fore_color) 
        {
            $vect = self::vectSVG($frame, $pixelPerPoint, $outerFrame, $back_color, $fore_color);
            
            if ($filename === false) {
                header("Content-Type: image/svg+xml");
                //header('Content-Disposition: attachment, filename="qrcode.svg"');
                echo $vect;
            } else {
                if($saveandprint===TRUE){
                    QRtools::save($vect, $filename);
                    header("Content-Type: image/svg+xml");
                    //header('Content-Disposition: filename="'.$filename.'"');
                    echo $vect;
                }else{
                    QRtools::save($vect, $filename);
                }
            }
        }
        
    
        //----------------------------------------------------------------------
        private static function vectSVG($frame, $pixelPerPoint = 4, $outerFrame = 4, $back_color = 0xFFFFFF, $fore_color = 0x000000) 
        {
            $h = count($frame);
            $w = strlen($frame[0]);
            
            $imgW = $w + 2*$outerFrame;
            $imgH = $h + 2*$outerFrame;
            
            
            $output = 
            '<?xml version="1.0" encoding="utf-8"?>'."\n".
            '<svg version="1.1" baseProfile="full"  width="'.$imgW * $pixelPerPoint.'" height="'.$imgH * $pixelPerPoint.'" viewBox="0 0 '.$imgW * $pixelPerPoint.' '.$imgH * $pixelPerPoint.'"
             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:ev="http://www.w3.org/2001/xml-events">'."\n".
            '<desc></desc>'."\n";

            $output =
            '<?xml version="1.0" encoding="utf-8"?>'."\n".
            '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">'."\n".
            '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" width="'.$imgW * $pixelPerPoint.'" height="'.$imgH * $pixelPerPoint.'" viewBox="0 0 '.$imgW * $pixelPerPoint.' '.$imgH * $pixelPerPoint.'">'."\n".
            '<desc></desc>'."\n";
                
            if(!empty($back_color)) {
                $backgroundcolor = str_pad(dechex($back_color), 6, "0", STR_PAD_LEFT);
                $output .= '<rect width="'.$imgW * $pixelPerPoint.'" height="'.$imgH * $pixelPerPoint.'" fill="#'.$backgroundcolor.'" cx="0" cy="0" />'."\n";
            }
                
            $output .= 
            '<defs>'."\n".
            '<rect id="p" width="'.$pixelPerPoint.'" height="'.$pixelPerPoint.'" />'."\n".
            '</defs>'."\n".
            '<g fill="#'.str_pad(dechex($fore_color), 6, "0", STR_PAD_LEFT).'">'."\n";
                
                
            // Convert the matrix into pixels

            for($i=0; $i<$h; $i++) {
                for($j=0; $j<$w; $j++) {
                    if( $frame[$i][$j] == '1') {
                        $y = ($i + $outerFrame) * $pixelPerPoint;
                        $x = ($j + $outerFrame) * $pixelPerPoint;
                        $output .= '<use x="'.$x.'" y="'.$y.'" xlink:href="#p" />'."\n";
                    }
                }
            }
            $output .= 
            '</g>'."\n".
            '</svg>';
            
            return $output;
        }
    }
    
    
