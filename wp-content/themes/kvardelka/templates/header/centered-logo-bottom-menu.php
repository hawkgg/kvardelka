<?php
/**
 * Header Template file
 *
 * @package marketing
 * @since 1.0
 */
?>

<!-- HEADER -->
<header class="tt-header">
  <div class="tt-logo-module">
    <div class="container">
      <div class="top-inner clearfix text-center float-none">
        <div class="top-inner-container">
          <?php marketing_logo('logo', get_template_directory_uri().'/img/logo.png'); ?>
          <button class="cmn-toggle-switch"><span></span></button>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="toggle-block float-none">
      <div class="toggle-block-container text-center">
        <nav class="main-nav clearfix">
          <?php marketing_main_menu(); ?>
        </nav>
      </div>
    </div>
  </div>
</header>
<?php marketing_header_height(marketing_get_opt('header-height-switch')); ?>