<?php
/**
 * Helper to store individual CSS file properties
 *
 * @package     wedsite
 * @author      Liam Kelly <https://github.com/likel>
 * @copyright   2018 Liam Kelly
 * @link        https://github.com/likel/wedsite
 * @version     1.0.0
 */
namespace Likel\CSS;

/**
 * Helper to store individual CSS file properties
 */
class File
{
    /** @var string The file path of the CSS file */
    private $file_path;
    /** @var bool Minify this file or not */
    private $minify;
    /** @var int Mobile sizing min width to show file */
    private $mobile_min_size;
    /** @var int Mobile sizing max width to show file */
    private $mobile_max_size;

    /**
     * Generate a css file with properties
     * @param string $file The css file or files to add to the manager
     * @param bool $minify To minify this particular file or not
     * @param int $mobile_min_size Mobile sizing min width to show file
     * @param int $mobile_max_size Mobile sizing max width to show file
     * @return void
     */
    public function __construct($file, $minify, $mobile_min_size, $mobile_max_size)
    {
        $this->file_path = $file;
        $this->minify = $minify;
        $this->mobile_min_size = $mobile_min_size;
        $this->mobile_max_size = $mobile_max_size;
    }

    /**
     * Simply return the file's filepath
     * @return string
     */
    public function getFilePath()
    {
        return $this->file_path;
    }

    /**
     * Simply return whether or not to minify the file
     * @return bool
     */
    public function getMinify()
    {
        return $this->minify;
    }

    /**
     * Simply return the file's min mobile size
     * @return float
     */
    public function getMobileMinSize()
    {
        return $this->mobile_min_size;
    }

    /**
     * Simply return the file's max mobile size
     * @return float
     */
    public function getMobileMaxSize()
    {
        return $this->mobile_max_size;
    }
}
