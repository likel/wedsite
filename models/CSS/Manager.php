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
namespace Likel\CSS;

/**
 * Minify and cache CSS files
 */
class Manager
{
    /** @var string const Where the CSS files are stored */
    private $CSS_DIRECTORY = "css/";
    /** @var string const Where the CSS files are cached */
    private $CACHE_DIRECTORY = "veil/";

    /** @var array The array of CSS_File objects */
    private $files = array();
    private $output_file_name;

    /** @var bool  */
    private $EDITING = true;

    /**
     * Add a css file to the list.
     * @param string $file The css file to add to the manager
     * @param bool $minify To minify this particular file or not
     * @param int $mobile_min_size Mobile sizing min width to show file
     * @param int $mobile_max_size Mobile sizing max width to show file
     * @return void
     */
    public function add($file, $minify = true, $mobile_min_size = 0, $mobile_max_size = 0)
    {
        $file_path = strpos($file, '/') !== false ? $file : $this->CSS_DIRECTORY . $file;
        $this->files[] = new File($file_path, $minify, $mobile_min_size, $mobile_max_size);
    }

    /**
     * Simple helper method to extend the add method for an array of files
     * @param string $files The css files to add to the manager
     * @param bool $minify To minify this particular file or not
     * @param int $mobile_min_size Mobile sizing min width to show file
     * @param int $mobile_max_size Mobile sizing max width to show file
     * @return void
     */
    public function addMultiple($files, $minify = true, $mobile_min_size = 0, $mobile_max_size = 0)
    {
        foreach($files as $file) {
            $this->add($file, $minify, $mobile_min_size, $mobile_max_size);
        }
    }

    /**
     * Return the combined css file.
     */
    public function output($seed, $encrypt = true)
    {
        $file_name = $this->generateFinalFile($seed, $encrypt);
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file_name\">";
        $this->destruct();
    }

    /**
     * Generate the CSS file
     */
    private function generateFinalFile($seed, $encrypt)
    {
        $file_path = $this->generateFilePath($seed, $encrypt);

        if(file_exists($file_path) && empty($this->EDITING)) {
            return $file_path;
        } else {
            $content = '';
            $files = $this->files;
            foreach ($files as $file) {
                if(file_exists($file->getFilePath())) {
                    $get = file_get_contents($file->getFilePath());
                    if($file->getMinify()) {
                        // Minify the contents of the file
                        $get = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $get);
                        $get = str_replace(': ', ':', $get);
                        $get = preg_replace('/\s+/', ' ', $get);
                    }

                    if($file->getMobileMaxSize() && $file->getMobileMinSize()) {
                        $get = "@media only screen and (max-width: {$file->getMobileMaxSize()}px) and (min-width: {$file->getMobileMinSize()}px) { ". $get . "} ";
                    } else {
                        echo $file->getMobileMaxSize();
                        //if($file->getMobileMaxSize()) {
                            $get = "@media only screen and (max-width: {$file->getMobileMaxSize()}px) { ". $get . "} ";
                        //}
                    }

                    $content .= $get;
                }
            }

            $version = VERSION;
            $date = date('Y/m/d');
            $content = "/* v{$version} {$date} developed by Liam Kelly <likel> (c) Liam Kelly */ " . $content;

            file_put_contents($file_path, $content);
            return $file_path;
        }
    }

    /**
     * Test existence in cache
     */
    public function generateFilePath($seed, $encrypt)
    {
        $real_name = ($encrypt !== false) ? md5($seed . VERSION . 'css') . '.css' : $seed . '.css';
        $file_name = $this->CACHE_DIRECTORY . $real_name;
        return $file_name;
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
