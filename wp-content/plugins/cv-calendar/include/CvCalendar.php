<?php

/**
 *
 */
class CvCalendar
{
  private $login;
  private $pass;
  private $isset;

  public function __construct()
  {
    // Insertion de la page d'administration
    add_action('admin_menu', array($this, 'add_admin_menu'));

    // On récupère s'ils existent, les identifiants.
    global $wpdb;

    if ($this -> is_Set()) {
      // Hydratation des attributs
      $this -> setIsset(true);
      $this -> setLogin($wpdb->get_var("SELECT `option_value` FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_login'"));
      $this -> setPass($wpdb->get_var("SELECT `option_value` FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_pass'"));
    }
  }

  public static function install() // Fonction appelée lors de l'activation du plugin
  {
    global $wpdb;
    $q = $wpdb->query("SELECT * FROM {$wpdb->prefix}options WHERE `option_name` LIKE '%cv-calendar%'");
    if ($q == 0){
      $wpdb->query("INSERT INTO {$wpdb->prefix}options (`option_name`, `option_value`, `autoload`) VALUES ('cv-calendar_login', 'NULL', 'yes');");
      $wpdb->query("INSERT INTO {$wpdb->prefix}options (`option_name`, `option_value`, `autoload`) VALUES ('cv-calendar_pass', 'NULL', 'yes');");
    }
  }
  public static function deactivation() // Fonction appelée lors de la désactivation du plugin
  {
    global $wpdb;
    if ($wpdb->query("SELECT * FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_set'")){
      $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_set' LIMIT 1");
    }
  }
  public static function uninstall() // Fonction appelée lors de la supression du plugin.
  {
    global $wpdb;

    $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_login' LIMIT 1");
    $wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_pass' LIMIT 1");
  }

  public function setLogin($login)
  {
    $this -> login = $login;
    return $this;
  }
  public function getLogin()
  {
    return $this -> login;
  }

  public function setPass($pass)
  {
    $this -> pass = $pass;
    return $this;
  }
  public function getPass()
  {
    return $this -> pass;
  }

  public function setIsset($isset)
  {
    $this -> isset = $isset;
    return $this;
  }
  public function getIsset()
  {
    return $this -> isset;
  }

  public function add_admin_menu()
  {
    add_menu_page('Calendrier Clé Vacances', 'CV-Calendar', 'manage_options', 'cv-calendar', array($this, 'menu_html'));
  }

  public function menu_html()
  {
    if ($this -> getIsset()) {
      $_GET['login'] = $this -> getLogin();
    }
    include_once(CVCALENDAR_INC."/admin.php");
  }

  // Sauvegarde en bdd les identifiants de connexion de l'API Clé Vacances
  public function setCredentials($login, $pass)
  {
    global $wpdb;
    $sql = $wpdb -> prepare("UPDATE {$wpdb->prefix}options SET `option_value` = %s WHERE `option_name` = 'cv-calendar_login'", $login);
    try {
      $wpdb->query($sql);
    } catch (Exception $e) {
      return false;
    }
    $sql = $wpdb->prepare("UPDATE {$wpdb->prefix}options SET `option_value` = %s WHERE `option_name` = 'cv-calendar_pass'", $pass);
    try {
      $wpdb->query($sql);
    } catch (Exception $e) {
      return false;
    }
    if (!$this -> getIsset()){
      try {
        $wpdb->query("INSERT INTO {$wpdb->prefix}options (`option_name`, `option_value`, `autoload`) VALUES ('cv-calendar_set', 'true', 'yes');");
      } catch (Exception $e) {
        return false;
      }
    }
    return true;
  }

  public static function is_Set()
  {
    global $wpdb;
    if ($wpdb->query("SELECT * FROM {$wpdb->prefix}options WHERE `option_name` = 'cv-calendar_set'")){
      return true;
    } else {
      return false;
    }
  }
  public function getAssets()
  {
    // Ressources pour le calendrier

  }

  public function getCalendar()
  {
    $r = file_get_contents(CVCALENDAR_DIR . '/templates/calendar.php');

    return $r;
  }
}
