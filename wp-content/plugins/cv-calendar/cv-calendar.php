<?php
/*
Plugin Name: CV-Calendar
Plugin URI: http://www.geograph.fr/cv-calendar
Description: Ce plugin affiche dans un calendrier l'état des réservations pour un gite affilié à "Clé vacances".
Version: 0.1
Author: Olivier Fillol - géoGraph
Author URI: http://www.geograph.fr/olivier-fillol
License: GPL2
*/

define( 'CVCALENDAR_FILE', __FILE__ ); // This file
define( 'CVCALENDAR_BASENAME', plugin_basename( CVCALENDAR_FILE ) ); // plugin name as known by WP
define( 'CVCALENDAR_DIR', dirname( CVCALENDAR_FILE ) ); // our directory

define ('CVCALENDAR_URL', '/wp-content/plugins/' . dirname(CVCALENDAR_BASENAME)); // Adresse générale du plugin

define( 'CVCALENDAR_INC', CVCALENDAR_DIR . '/include');
define( 'CVCALENDAR_ASSETS', CVCALENDAR_DIR . '/assets');

require_once(CVCALENDAR_INC . '/CvCalendar.php');

$cvCalendar = new CvCalendar();

// Traitement du formulaire s'il a été envoyé
if (isset($_POST['ok'])){
  if (isset($_POST['login']) && isset($_POST['pass'])){
    $login = $_POST['login'];
    $pass  = $_POST['pass'];
    if (!empty($login) && !empty($pass)){
      if (!$cvCalendar->setCredentials($login, $pass)){
        throw new Exception("Il y a eu un problème lors de la sauvegarde de vos données.", 1);
      }
    }
  }
}
// Méthodes pour le backend
// Activation / Supression du plugin
register_activation_hook(__FILE__, array('CvCalendar', 'install'));
register_deactivation_hook(__FILE__, array('CvCalendar', 'deactivation'));
register_uninstall_hook(__FILE__, array('CvCalendar', 'uninstall'));

// Méthodes pour le frontend
add_shortcode( 'cvCalendar', 'cvCalendar' );
add_action('wp_enqueue_scripts', 'styles');

function cvCalendar() {
  $cvCalendar = new CvCalendar();
  if ($cvCalendar -> is_Set()){
    // Récupérer la valeur du calendrier

    $r = $cvCalendar -> getCalendar();
    return $r;
  } else {
    return "Le plugin n'est pas configuré.";
  }
}

function styles()
{
  wp_register_style('fullcalendar_css', CVCALENDAR_URL . '/assets/fullcalendar/fullcalendar.css', '', false, 'screen');
  wp_register_script('moment_js', CVCALENDAR_URL . '/assets/js/moment.js', '', '', true);
  wp_register_script('fullcalendar_js', CVCALENDAR_URL . '/assets/fullcalendar/fullcalendar.js', '', '', true);
  wp_register_script('locale-all_js', CVCALENDAR_URL . '/assets/fullcalendar/locale-all.js', '', '', true);
  wp_register_script('cv-calendar_js', CVCALENDAR_URL . '/assets/js/cv-calendar.js', '', '', true);

  wp_enqueue_style('fullcalendar_css');
  wp_enqueue_script('moment_js');
  wp_enqueue_script('fullcalendar_js');
  wp_enqueue_script('locale-all_js');
  wp_enqueue_script('cv-calendar_js');
}
