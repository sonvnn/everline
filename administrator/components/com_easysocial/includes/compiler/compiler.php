<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class SocialCompiler extends EasySocial
{
	static $instance = null;
	public $version;
	public $cli = false;

	// These script files should be rendered externally and not compiled together
	// Because they are either too large or only used in very minimal locations.
	public $excludeFiles = array(
								"bbcode.js",
								"stars.js",
								"draggable.js",
								"sortable.js",
								"masonry.js",
								"droppable.js",
								"datepicker.js",
								"timepicker.js",
								"flot.js",
								"prism.js",
								"videojs.js",
								"swiper.js",
								"pressure.js",
								"chosen.js",
								"imgareaselect.js",
								"easing.js",
								"videojs",
								"jquery.js"
						);

	/**
	 * Allows caller to compile a script file on the site, given the section
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compile($section = 'admin', $minify = true, $jquery = true)
	{
		// Get the file name that should be used after compiling the scripts
		$fileName = ES::scripts()->getFileName($section, $jquery);

		$files = $this->getFiles($section, $jquery);

		$contents = '';

		$contents .= $this->compileBootloader();

		// 1. Core file contents needs to be placed at the top
		$contents .= $this->compileCoreFiles($files->core);

		// 2. Libraries should be appended next
		$contents .= $this->compileLibraries($files->libraries);

		// 3. Compile the normal scripts
		$contents .= $this->compileScripts($files->scripts);

		$result = new stdClass();
		$result->section = $section;
		$result->minify = $minify;

		// Store the uncompressed version
		$standardPath = SOCIAL_SCRIPTS . '/' . $fileName . '.js';
		$this->write($standardPath, $contents);

		$result->standard = $standardPath;
		$result->minified = false;

		// Compress the script and minify it
		if ($minify) {
			$closure = $this->getClosure();

			// 1. Minify the main library
			$contents = $closure->minify($contents);

			// Store the minified version
			$minifiedPath = SOCIAL_SCRIPTS . '/' . $fileName . '.min.js';
			$this->write($minifiedPath, $contents);

			// 2. Since excluded files are running on their own, we would need to minify them so that it
			// runs on the compressed version rather than the uncompressed version
			$excludedFiles = $this->getExcludedFiles();

			foreach ($excludedFiles as $excludedFile) {
				$targetPath = str_ireplace('.js', '.min.js', $excludedFile);
				$excludedContents = JFile::read($excludedFile);

				$excludedContents = $closure->minify($excludedContents);

				$this->write($targetPath, $excludedContents);
			}

			// Note: As there are too many momentjs language files, it's not ideal to recompile this on the fly.
			// Since these files would hardly change, we could just store these minified versions in the repository.
			// $excludedFiles = $this->getMomentLanguageFiles();

			// foreach ($excludedFiles as $excludedFile) {
			// 	$targetPath = str_ireplace('.js', '.min.js', $excludedFile);
			// 	$excludedContents = JFile::read($excludedFile);

			// 	$excludedContents = $closure->minify($excludedContents);

			// 	$this->write($targetPath, $excludedContents);
			// }

			$result->minified = $minifiedPath;
		}

		return $result;
	}

	/**
	 * Retrieves momentjs language files so that we could minify them
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getMomentLanguageFiles()
	{
		$path = SOCIAL_SCRIPTS . '/vendors/moment';
		$files = JFolder::files($path, '.', true, true);

		return $files;
	}

	/**
	 * Retrieves a list of excluded script files from the compiler
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getExcludedFiles()
	{
		$path = SOCIAL_SCRIPTS . '/vendors';
		$pattern = implode('|^', $this->excludeFiles);

		$files = JFolder::files($path, $pattern, true, true);

		return $files;
	}

	/**
	 * Compiles core files
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compileCoreFiles($files)
	{
		$contents = '';

		foreach ($files as $file) {
			$contents .= JFile::read($file);
		}

		return $contents;
	}

	/**
	 * Retrieves contents from the bootloader file
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compileBootloader()
	{
		$file = JPATH_ROOT . '/media/com_easysocial/scripts/bootloader.js';

		$contents = JFile::read($file);

		return $contents;
	}

	/**
	 * Compiles all libraries
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compileLibraries($files)
	{
		$modules = array();

		// Get the prefix so that we can get the proper namespace
		$prefix = SOCIAL_SCRIPTS . '/vendors';

		foreach ($files as $file) {
			$fileName = ltrim(str_ireplace($prefix, '', $file), '/');
			$modules[] = str_ireplace('.js', '', $fileName);
		}

		$modules = json_encode($modules);

ob_start();
?>
FD40.plugin("static", function($) {
	$.module(<?php echo $modules;?>);

	// Now we need to retrieve the contents of each files
	<?php foreach ($files as $file) { ?>
		<?php echo $this->getContents($file); ?>
	<?php } ?>
});
<?php
$contents = ob_get_contents();
ob_end_clean();

		return $contents;
	}

	/**
	 * Compiles script files
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compileScripts($files)
	{
		$modules = array();

		foreach ($files as $file) {
			$namespace = str_ireplace(SOCIAL_SCRIPTS, 'easysocial', $file);

			$modules[] = str_ireplace('.js', '', $namespace);
		}

		$modules = json_encode($modules);
ob_start();
?>
// Prepare the script definitions
FD40.installer('EasySocial', 'definitions', function($) {
	$.module(<?php echo $modules;?>);
});

// Prepare the contents of all the scripts
FD40.installer('EasySocial', 'scripts', function($) {
	<?php foreach ($files as $file) { ?>
		<?php echo $this->getContents($file); ?>
	<?php } ?>
});
<?php
$contents = ob_get_contents();
ob_end_clean();

		return $contents;
	}


	/**
	 * Only creates this instance once
	 *
	 * @since	2.0
	 * @access	public
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Retrieves the contents of a particular file
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getContents($file)
	{
		$contents = JFile::read($file);

		return $contents;
	}

	/**
	 * Retrieves the closure compiler
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getClosure()
	{
		require_once(__DIR__ . '/closure.php');
		$closure = new SocialCompilerClosure();

		return $closure;
	}

	/**
	 * Retrieves a list of files for specific sections
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getFiles($section, $jquery = true)
	{
		$files = new stdClass();

		// Get a list of core files
		$coreFiles = ES::scripts()->getDependencies(true, $jquery);
		$files->core = $coreFiles;

		// Get a list of libraries
		$files->libraries = $this->getLibraryFiles();

		// Get a list of shared scripts that is used across sections
		$scriptFiles = array();
		$scriptFiles = array_merge($scriptFiles, $this->getSharedFiles());

		// Get script files from the particular section
		$scriptFiles = array_merge($scriptFiles, $this->getScriptFiles($section));

		$files->scripts = $scriptFiles;

		return $files;
	}

	/**
	 * Retrieves a list of library files used on the site
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getLibraryFiles()
	{
		// Retrieve core dependencies
		$excludes = array('moment');

		// Add exclusion files
		foreach ($this->excludeFiles as $exclusion) {
			$excludes[] = $exclusion;

			// Excluded files may also contain a .min.js
			$excludes[] = str_ireplace('.js', '.min.js', $exclusion);
		}

		// Exclude dependencies
		$dependencies = ES::scripts()->getDependencies();
		$excludes = array_merge($excludes, $dependencies);

		$path = SOCIAL_SCRIPTS . '/vendors';
		$files = JFolder::files($path, '.js$', true, true, $excludes);

		// $this->debug($files);

		return $files;
	}

	/**
	 * Retrieves list of shared files that is used across all sections
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getSharedFiles()
	{
		$files = array();

		// Retrieve core dependencies
		$dependencies = ES::scripts()->getDependencies();

		$folderExclusion = array('.git', '.svn', 'CVS', '.DS_Store', '__MACOSX', 'admin', 'site', 'unused', 'vendors');
		$folders = JFolder::folders(SOCIAL_SCRIPTS, '.', false, true, $folderExclusion);

		foreach ($folders as $folder) {
			$files = array_merge($files, JFolder::files($folder, '.js$', true, true, $this->excludeFiles));
		}

		// $this->debug($files);

		return $files;
	}

	/**
	 * Retrieves list of scripts that is only used in the particular section
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function getScriptFiles($section)
	{
		$path = SOCIAL_SCRIPTS . '/' . $section;
		$files = JFolder::files($path, '.js$', true, true, $this->excludeFiles);

		// $this->debug($files);

		return $files;
	}

	/**
	 * Saves the contents into a file
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function write($path, $contents)
	{
		if (JFile::exists($path)) {
			JFile::delete($path);
		}

		return JFile::write($path, $contents);
	}

	/**
	 * For debugging purposes only. @dump does not display everything
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function debug($items)
	{
		echo '<pre>';
		print_r($items);
		echo '</pre>';
		exit;
	}
}
