
<?php
/**
 * Step 1: Require the Pakd Framework
 *
 * If you are not using Composer, you need to require the
 * Pakd Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Pakd/Pakd.php';
\Pakd\Pakd::registerAutoloader();

/**
 * Step 2: Instantiate a Pakd application
 *
 * This example instantiates a Pakd application using
 * its default settings. However, you will usually configure
 * your Pakd application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$pakd = new \Pakd\Pakd();

$pakd->run();