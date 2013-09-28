<?php

/**
 * Bluewater MVC Core configuration file.
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
 * please refer to {@link http://web.bluewatermvc.org} for more information. *
 *
 * PHP version 5
 *
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Support
 * @link        http://web.bluewatermvc.org
 *
 * @copyright   Copyright (c) 2006 - 2010 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filename    $RCSfile: $
 * @version     $Revision: $
 * @modifiedby  $LastChangedBy: $
 * @date        $Date: $
 *
 */

// Make sure this file is not directly opened.
exit;

?>
# This is the settings page, do not modify the above line.

# Section headers are REQUIRED

# config settings in the LOCAL Application INI file will
# override the settings in this config file.

# ============================================================
# This section needs to be first in the file
[constants]
BW_VER  = 1.0b        ; Bluewater Version


# Logging settings. This is turned full on for develpment
# 'turn off' these levels in 'local.ini' file
LOGGER_SQL   = false
LOGGER_ERR   = false
LOGGER_TRACE = false


# Charater to use as the default text string to array delimeter
ARRAY_DELIM = ,

# Eventually, Bluewater will have different encryption methods,
# but for now, we only have MD5
ENCRYPT = md5

# Used to add 'salt' to encryption routines
# This can be anything you like, but be sure to change this
# in 'local.ini' file
SALT = mary_had_a_little_lamb


# File and Directory Modes
# These prefs used when checking and setting modes when working
# with the file system. Octal values should always be used to
# set the mode correctly.
FILE_READ_MODE   = 0644
FILE_WRITE_MODE  = 0666
DIR_READ_MODE    = 0755
DIR_WRITE_MODE   = 0777


# Cookie Constants - these are the parameters
# to the setcookie function call, change them
# if necessary to fit your website. If you need
# help, visit www.php.net for more info.
# <http://www.php.net/manual/en/function.setcookie.php>

COOKIE_EXPIRE  = 60*60*24*10   ; 10 days by default
COOKIE_PATH    = /             ; Available in whole domain


# Special Names and Level Constants - the admin
# page will only be accessible to the user with
# the admin name and also to those users at the
# admin user level.
# Levels must be digits between 1-9.

ADMIN_NAME   = Admin
GUEST_NAME   = Guest
SYSTEM_LEVEL = 10
ADMIN_LEVEL  = 9
MGR_LEVEL    = 3
SPVR_LEVEL   = 5
USER_LEVEL   = 1
GUEST_LEVEL  = 0


# The SYSTEM needs to be able to 'login' as well.
# These credentials should be the first record in the USER table.
# Use these credentials, but change the password!
SYSTEM_ID = System
SYSTEM_PW = 2hW*xp`5


# This boolean constant controls whether or not the script
# keeps track of active users and active guests who are 
# visiting the site.
TRACK_VISITORS = true


# Email Constants - these specify what goes in
# the FROM field in the emails that the script
# sends to users, and whether to send a
# welcome email to newly registered users.
EMAIL_FROM_NAME  = Administrator
EMAIL_FROM_ADDR  = admin@
EMAIL_WELCOME    = true


# DOC Types can be:
; - h4.1 = HTML 4.1
; - x1   = xHTML 1.0 strict
; - x1.1 = xHTML 1.1 strict
DOC_TYPE = x1


# General WARNING Icon to use where ever you need
WARN_ICON = <img type="image" src="{IMAGE_PATH}/icons/warning.png" alt="Warning" title="Warning" />


# Normally MVC is not a path based system; controllers are files in the
# 'Controller' dirctory. This flag changes the behavour to utilize a
# directory based controller system, with all associated files to a
# controller in the same directory as the controller.
# Modify this flag in the app level config file.
PATH_BASE = false


# End CONSTANT SECTION
# ============================================================


[general]
tz = America/Chicago

# ============================================================

# This section has not been implimented yet
[locale]
country  = US           ; United States
lang     = en           ; English
domain   = core         ; core translation files

# Where does the translation files reside
location = {APP_ROOT}/locale


# eof