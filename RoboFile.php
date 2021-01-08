<?php

include '.tk/RoboFileBase.php';

class RoboFile extends RoboFileBase {

	public function directoriesStructure() {
		return array( 'admin', 'class', 'languages', 'patch', 'templates' );
	}

	public function fileStructure() {
		return array( 'wc4bp-basic-integration.php', 'composer.json', 'license.txt', 'readme.txt' );
	}

	/**
	 * @return array List of relative paths from the root folder of the plugin
	 */
	public function cleanPhpDirectories() {
		return array();
	}

	public function pluginMainFile() {
		return 'wc4bp-basic-integration';
	}

	public function pluginFreemiusId() {
		return 425;
	}

	public function minifyImagesDirectories() {
		return array();
	}

	public function minifyAssetsDirectories() {
		return array();
	}

	/**
	 * @return array Pair list of sass source directory and css target directory
	 */
	public function sassSourceTarget() {
		return array( array( 'scss/source' => 'assets/css' ) );
	}

	/**
	 * @return string Relative paths from the root folder of the plugin
	 */
	public function sassLibraryDirectory() {
		return 'scss/library';
	}
}
