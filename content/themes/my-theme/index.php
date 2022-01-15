<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <div id="app"></div>

  <div id="wp">
    <?php
      if ( have_posts() ) :
      while ( have_posts() ) : the_post();

        echo '<article '; post_class(); echo '>' . PHP_EOL;
        the_title('<h2>','</h2>');
        the_content();
        echo '</article>' . PHP_EOL;

        endwhile;
        endif;

        ?>
    </div>

<?php wp_footer(); ?>
</body>
</html>
