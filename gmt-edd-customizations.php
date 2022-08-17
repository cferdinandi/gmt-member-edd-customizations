<?php

/**
 * Plugin Name: GMT Member EDD Customizations
 * Plugin URI: https://github.com/cferdinandi/gmt-member-edd-customizations/
 * GitHub Plugin URI: https://github.com/cferdinandi/gmt-member-edd-customizations/
 * Description: Customizations to Easy Digital Downloads for Lean Web Club.
 * Version: 1.0.0
 * Author: Chris Ferdinandi
 * Author URI: http://gomakethings.com
 * License: GPLv3
 */

// Security
if (!defined('ABSPATH')) exit;

// Require files
require_once('settings.php');
require_once('cart.php');
require_once('email-tags.php');