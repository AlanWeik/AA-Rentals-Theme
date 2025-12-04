<?php
/**
 * The base configuration for WordPress
 *
 * Denna fil är städad för att fungera optimalt i LocalWP utan frysningar.
 */

// Öka minnet för PHP inuti WordPress (inte bara servern)
define( 'WP_MEMORY_LIMIT', '512M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );

// Stäng av Autosave så den inte äter minne i bakgrunden
define( 'AUTOSAVE_INTERVAL', 86400 ); // En gång per dygn (i praktiken avstängt)

// ** 1. VIKTIGT: PRESTANDA-INSTÄLLNINGAR ** //
// Sätt CONCATENATE till true för att slå ihop filer (minskar antalet anrop)
define( 'CONCATENATE_SCRIPTS', true );

// Sätt SCRIPT_DEBUG till false. 
// VIKTIGT: Detta fixar frysningen! 'true' får Chrome att krascha av minnesbrist.
define( 'SCRIPT_DEBUG', false );

// ** 2. STÄNG AV CACHE VID UTVECKLING ** //
// Du vill inte cacha trasig kod när du bygger nytt.
define( 'WP_CACHE', false );

// ** 3. DEBUG-INSTÄLLNINGAR ** //
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false ); // Sätt till false så fel inte pajar designen på frontend, kolla debug.log istället.

// ** Database settings ** //
define( 'DB_NAME', 'local' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/** Authentication unique keys and salts. */
define( 'AUTH_KEY',          '(`4A!ku.fSykK}rDY.&3X-{hrz5I+1M5l25e|2-l>OZ*@4`feX&<AwV@#q.6k.$;' );
define( 'SECURE_AUTH_KEY',   'M5<}!e;A*i(%E=s6qxqJK8?>!5e46hx!G^|I{,YOsq&X ~/DG*]5h.8G7AAR4}7=' );
define( 'LOGGED_IN_KEY',     '^${pN jQcEENCr#q{rL@uMQu}AK*xSYYu8`[INU6I*`rlBW4,Qid:+neT*6`W.:N' );
define( 'NONCE_KEY',         'Z/naGa-lqXSQS)L Eyr3|V2-5^XA_<EB_jg#Au]QCCqxMrF!_f:LnrZ;Qt,UN*)V' );
define( 'AUTH_SALT',         'l&)#;;ZV2vJ|8(E.AL+[Q8iYO&Tx,2)LbCHZhG+*CzN,K4cnq-I1ek_}Vk_;g|K&' );
define( 'SECURE_AUTH_SALT',  '55{0rh7MM[4%PpGo+6>d2IZTNEK@Gn,^M_?oCn{&Bq*T+V5)2%ztSyf2Tt#4x8gd' );
define( 'LOGGED_IN_SALT',    ' BI+n=LqoI*-BrV4k4cLx0-|q  lGLK Cx>={y4SzyL{{(dc^i`1VSvE9VT2!RVh' );
define( 'NONCE_SALT',        'AlS/-o[9WX{X66joK^M[&69=LPG7?5IhVNwF2{qp/skM!,Hm1Wsu;F_@N{aC%u%f' );
define( 'WP_CACHE_KEY_SALT', 'x*fF^f&=%=iTk/|2-[4{6E&`7_c}XnQfDNRb4[`)rL^A>s>!43ZqLjSF_6H!oR}e' );

$table_prefix = 'wp_';

define( 'WP_ENVIRONMENT_TYPE', 'local' );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';