<?php
/**
 * @author: Liam Kelly <liamkelly>
 * @created: 10/01/2017, 8:41:21 pm
 * @project: HMA
 * @file: js.class.php
 * @last modified by: liamkelly
 * @last modified: 20/06/2017, 7:30:11 pm
 * @copyright: Liam Kelly
 */

class JS {
		private $JS_DIRECTORIES = array("js/core/", "veil/");

		private $files = array();
		private $minify = array();
		private $modFiles = array();
		private $modMinify = array();
		private $outputFileName;

		/**
     * Add a js file to the list.
     */
		public function add($js, $minify = true) {
				$this->files[] = strpos($js, '/') !== false ? $js : CFG::dir('root') . '/' . $this->JS_DIRECTORIES[0] . $js;
				$this->minify[] = $minify;
		}

		/**
     * Append any module's js files.
     */
		public function modJS($js, $minify = true) {
				$this->modFiles[] = strpos($js, '/') !== false ? $js : CFG::dir('root') . '/' . $this->JS_DIRECTORIES[0] . $js;
				$this->modMinify[] = $minify;
		}

		/**
     * Return the combined js file.
     */
		public function output($fileName = null) {
				if(!$fileName) $fileName = $this->outputFileName;

				$content = '';

				$files = $this->files;
				$modFiles = $this->modFiles;

				require_once CFG::dir('root') . "/js/core/squeeze.php";

				foreach ($files as $f => $file) {
						if(file_exists($file)) {
								$get = file_get_contents($file);
								if($this->minify[$f]) {
										// Minify the contents of the file
										$jSqueeze = new JSqueeze();
										$content .= $jSqueeze->squeeze($get, true, false);
								} else {
										$content .= $get;
								}
						}
				}

				foreach ($modFiles as $m => $mFile) {
						if(file_exists($mFile)) {
								$get = file_get_contents($mFile);
								if($this->modMinify[$m]) {
										// Minify the contents of the file
										$jSqueeze = new JSqueeze();
										$content .= $jSqueeze->squeeze($get, true, false);
								} else {
										$content .= $get;
								}
						}
				}

				$version = CFG::val('version');
				$date = CFG::val('date');
				$content = "/* v{$version} {$date} developed by Liam Kelly (c) Liam Kelly */ " . $content;

				file_put_contents(CFG::dir('root') . '/' . $fileName, $content);
				echo "<script src=\"$fileName\"></script>";

				$this->destruct();
		}

		/**
     * Test existence in cache.
     */
		public function exists($fileName, $return = true, $encrypt = true) {
				$realName = $encrypt !== false ? md5($fileName . CFG::val('version') . CFG::val('date') . 'xCdF342js') . '.js' : $fileName;

				$fileName = strpos($fileName, '/') !== false ? $fileName : "/" . $this->JS_DIRECTORIES[1] . $realName;

				if(file_exists(CFG::dir('root') . '/' . $fileName) && $return && !CFG::val('editing')) {
						echo "<script src=\"$fileName\"></script>";
						$this->destruct();
						return true;
				} else {
						$this->outputFileName = $fileName;
						return false;
				}
		}

		/**
     * Destruct our js class for future use.
     */
		public function destruct() {
				unset($this->files);
				unset($this->minify);
				unset($this->outputFileName);
		}
}
?>
