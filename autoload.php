<?php
/**
 * PSR-4 autoload
 *
 * @package     wedsite
 * @author      Liam Kelly <https://github.com/likel>
 * @copyright   2018 Liam Kelly
 * @link        https://github.com/likel/wedsite
 * @version     1.0.0
 */
define("VERSION", "1.0.0");

// Set up the autoloader
spl_autoload_register(function ($class_name)
{
    $models_dir = __DIR__ . '/models/';
    // Change these depending on the project
    $project_prefix = 'Likel\\';
    // Helper variables used in the autoloader
    $project_prefix_length = strlen($project_prefix);
    $relative_class = substr($class_name, $project_prefix_length);

    // Return if the requested class does not include the prefix
    if (strncmp($project_prefix, $class_name, $project_prefix_length) !== 0) {
        return;
    }

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the class name and append with .php
    $file = $models_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});
