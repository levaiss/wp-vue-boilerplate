<?php

/**
 * Enqueue Scripts & Development Toggle
 *
 * Overrides the theme script & styles with the dev server version.
 * Include in your functions.php with `get_template_part('wordpress_enqueue');`
 */

session_start();


if ( empty($_SESSION['__devReload']) ) {
  $_SESSION['__devReload'] = false;
}

if ( !empty($_GET['toggleDev']) ) {
  if ( $_GET['toggleDev'] == 'false' ) {
    $_SESSION['__devReload'] = false;
  } else {
    $_SESSION['__devReload'] = $_GET['toggleDev'] == 'true' ? 'localhost:8080' : $_GET['toggleDev'];
  }
}

function toggle_dev_button($wp_admin_bar){
  global $dev_server;
  if ( is_super_admin() ) {

    $indicator = '';

    if ( !empty($_SESSION['__devReload']) ) {
      $color = '#E03E36';

      $matches = array();
      preg_match('/:(\d+)/', $_SESSION['__devReload'], $matches);
      $dev_port = $matches[1];

      if ( $fp = @fsockopen($_SESSION['__devReload'], $dev_port, $errCode, $errStr, 0.1) ) {
        $color = '#2EAC6D';
        fclose($fp);
      }

      $indicator = '<style>.dev-indicator:before { content: " "; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: inherit; opacity: 0.2 }</style> <b class="dev-indicator" style="display: inline-block; vertical-align: middle; width: 1em; height: 1em; border-radius: 50%; margin-right: 0.5em; background: '.$color.'; position: relative;"></b>';
    }

    $wp_admin_bar->add_node(array(
      'id' => 'toggle-dev-button',
      'title' => $indicator . 'Turn Dev Refreshing ' . ( $_SESSION['__devReload'] == true ? 'Off' : 'On' ),
      'href' => '?toggleDev=' . ( $_SESSION['__devReload'] == true ? 'false' : 'true' )
    ));

  }
}
add_action('admin_bar_menu', 'toggle_dev_button', 80);


function vue_theme_scripts() {
  global $dev_server;
  $root = get_stylesheet_directory_uri();

  if (
    //is_super_admin() &&
   !empty($_SESSION) && $_SESSION['__devReload'] ) {
    wp_enqueue_script('main-dev', 'http://' . $_SESSION['__devReload'] . '/assets/main.js', NULL, NULL, TRUE);
  } else {
    wp_enqueue_style( 'main', $root . '/assets/style.css', NULL, THEME_VERSION, 'all' );
  	wp_enqueue_script('vendor', $root . '/assets/vendor.js', NULL, THEME_VERSION, TRUE);
  	wp_enqueue_script('main', $root . '/assets/main.js', array('vendor'), THEME_VERSION, TRUE);
  }
}

add_action( 'wp_enqueue_scripts', 'vue_theme_scripts' );