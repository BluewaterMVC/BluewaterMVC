<?php

/**
 * Class to handle Images
 *
 * This file is part of Bluewater MVC.<br />
 * <i>Copyright (c) 2006 - 2010 Walter Torres <walter@torres.ws></i>
 *
 * <b>NOTICE OF LICENSE</b><br />
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at:
 * {@link http://opensource.org/licenses/osl-3.0.php}.<br />
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@bluewatermvc.org so one can sent to you immediately.
 *
 * <b>DISCLAIMER</b><br />
 * Do not modify to this file if you wish to upgrade Bluewater MVC
 * in the future. If you wish to customize Bluewater MVC for your needs
 * please refer to {@link http://web.bluewatermvc.org} for more information.
 *
 * PHP version 5
 *
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Httpd
 * @link        http://web.bluewatermvc.org
 *
 * @copyright   Copyright (c) 2006 - 2010 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author      Walter Torres <walter@torres.ws>
 * @filename    $RCSfile: $
 * @version     $Revision: $
 * @modifiedby  $LastChangedBy: $
 * @date        $Date: $
 *
 */

/**
 * Httpd Image generation.
 *
 * @package     Bluewater_Core
 * @subpackage  Httpd
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 */
class Bluewater_Httpd_Image
{
    static public final function send_image( $_image_path = false )
    {
        if ( ($_image_path !== false)
          && (file_exists($_image_path))
           )
        {
            
/*
            $_imagedata = file_get_contents($_image_path);
            $_length = strlen($_imagedata);
            $_info = pathinfo($_image_path);

            // file extention converstion
            switch ($_info['extension'])
            {
                case 'jpg':
                    $_info['extension'] = 'jpeg';
                break;
            }
*/

            $_details = getimagesize($_image_path);

            ob_start();
                header('Last-Modified: ' . date('r'), true, 200);
//            header('Content-Type: text/plain');
                header('Content-Type: ' . $_details['mime']);
                echo  file_get_contents($_image_path);
            ob_end_flush();

/*
            ob_start();
            header('Last-Modified: ' . date('r'), true, 200);
            header('Accept-Ranges: bytes');
            header('Content-Length: ' . $_length);
//            header('Content-Type: text/plain');
            header('Content-Type: ' . $_details['mime']);
            echo($_imagedata);
            ob_end_flush();
*/
        }
    }

};
?>
