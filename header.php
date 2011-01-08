<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Throughout 
Description: A two-column, fixed-width design for 1024x768 screen resolutions.
Version    : 1.0
Released   : 20100423

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $title ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="/js/jquery.slidertron-0.1.js"></script>
<script type="text/javascript" src="/js/main.js"></script>
<?php if ($is_admin) { ?>
<script type="text/javascript" src="/js/admin.js"></script>
<link href="/css/admin.css" rel="stylesheet" type="text/css" media="screen" />
<?php } ?>
<link href="/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="/css/main.css" rel="stylesheet" type="text/css" media="screen" />
<style type="text/css">
@import "/css/slidertron.css";
@import "/css/mediagroove.css";
</style>
</head>
<body>
<!-- end #header-wrapper -->
<div id="logo">
  <h1><a href="/">Angel Foods</a></h1>
</div>
<div id="header">
  <div id="menu">
    <ul>
      <li><a href="/" class="first">Homepage</a></li>
      <li><a href="/about.php">About</a></li>
      <li><a href="/contact.php">Contact</a></li>
      <?php if ($is_admin == true) { ?>
      <li><a href="/admin/index.php">Admin</a></li>
      <?php } ?>
    </ul>
  </div>
  <!-- end #menu -->
  <div id="search">
    <form method="get" action="http://www.google.com/search">
      <fieldset>
        <input type="text" name="q" id="search-text" size="15" />
      </fieldset>
      <input type="hidden" name="as_sitesearch" value="angelfoodsgv.com" />
    </form>
  </div>
  <!-- end #search -->
</div>
<!-- end #header -->
<hr />
<?php
if ($show_calendar) {
  // XXX - make content inclusion more general
  include('cal.php');
}
?>
<div id="page">
  <div id="page-bgtop">
