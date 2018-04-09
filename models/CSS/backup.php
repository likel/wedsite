<?php
/**
 * Include this file like so:
 * $CSS = new CSS_Manager();
 *
 * @package     wedsite
 * @author      Liam Kelly <https://github.com/likel>
 * @copyright   2018 Liam Kelly
 * @link        https://github.com/likel/wedsite
 * @version     1.0.0
 */
namespace Likel;

/**
 * Minify and cache CSS files
 */
class CSS_Manager
{
    /** @var array const The CSS directories */
    private $CSS_DIRECTORIES = array("css/", "veil/");

    /** @var array The array of CSS_File objects */
    private $files = array();
    private $output_file_name;

    /**
     * Add a css file to the list.
     * @param string $file The css file to add to the manager
     * @param bool $minify To minify this particular file or not
     * @param int $mobile_max_size Mobile sizing max width to show file
     * @param int $mobile_min_size Mobile sizing min width to show file
     * @return void
     */
    public function add($file, $minify = true, $mobile_max_size = 0, $mobile_min_size = 0)
    {
        $file_path = strpos($file, '/') !== false ? $file : $this->CSS_DIRECTORIES[0] . $file;
        $this->files[] = new CSS_File($file_path, $minify, $mobile_size_max, $mobile_size_min);
    }

    /**
     * Simple helper method to extend the add method for an array of files
     * @param string $files The css files to add to the manager
     * @param bool $minify To minify this particular file or not
     * @param int $mobile_max_size Mobile sizing max width to show file
     * @param int $mobile_min_size Mobile sizing min width to show file
     * @return void
     */
    public function addMultiple($files, $minify = true, $mobile_size_max = 0, $mobile_size_min = 0)
    {
        foreach($files as $file) {
            $this->add($file, $minify, $mobile_size_max, $mobile_size_min);
        }
    }

    /**
     * Return the combined css file.
     */
    public function output($file_name = null)
    {

        if(!$file_name) {
            $file_name = $this->$output_file_name;
        }

        $content = '';

        $files = $this->files;

        foreach ($files as $f => $file) {
            if(file_exists($file)) {
            $get = file_get_contents($file);
            if($this->minify[$f]) {
            // Minify the contents of the file
            $get = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $get);
            $get = str_replace(': ', ':', $get);
            $get = preg_replace('/\s+/', ' ', $get);
            /*$get = str_replace('{ ', '{', $get);
            $get = str_replace(' }', '}', $get);*/
            }
            if($this->mobilesize[$f]) {
            $get = "@media only screen and (max-width: {$this->mobilesize[$f][0]}px) and (min-width: {$this->mobilesize[$f][1]}px) { ". $get . "} "; //and (min-device-width: 480px)
            }
            $content .= $get;
            }
        }

        $version = CFG::val('version');
        $date = CFG::val('date');
        $content = "/* v{$version} {$date} developed by Liam Kelly (c) Liam Kelly */ " . $content;

        file_put_contents(CFG::dir('root') . '/' . $fileName, $content);
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$fileName\">";

        $this->destruct();
    }

    /**
     * Test existence in cache.
     */
    public function exists($file_name, $mobile = false, $return = true, $encrypt = true)
    {
        $real_name = $encrypt !== false ? md5($file_name . CFG::val('version') . CFG::val('date') . 'css') . '.css' : $file_name;
        $file_name = strpos($file_name, '/') !== false ? $file_name : "/" . $this->CSS_DIRECTORIES[1] . $real_name;

        if(file_exists(CFG::dir('root') . '/' . $file_name) && $return && !CFG::val('editing')) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file_name\">";
            $this->destruct();
            return true;
        } else {
            $this->output_file_name = $file_name;
            return false;
        }
    }

    /**
     * Destruct our CSS class for future use.
     * @return void
     */
    public function destruct()
    {
        unset($this->files);
        unset($this->minify);
        unset($this->output_file_name);
        unset($this->mobile_size);
        unset($this->mobile);
    }
}
