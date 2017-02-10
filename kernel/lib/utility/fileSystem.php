<?php

class fileSystem{
	
	function fileSystem(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> fileSystem.php-> fileSystem()\n");
		

	}

	public function scanDirectories($rootDir, $allowExt) {
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> fileSystem.php-> scanDirectories($rootDir, $allowExt)\n");
		
		$dirContent = scandir($rootDir);
		foreach($dirContent as $key => $content) {
			if($content == '.' || $content == '..'){
				continue;
			}
			$path = $rootDir . '/' . $content;
			if(is_file($path) && is_readable($path)) {
				$ext = substr($content, strrpos($content, '.') + 1);
				if(in_array($ext, $allowExt)) {
					$allData[substr($content, 0, strrpos($content, '.'))] = $path;
				}
			}elseif(is_dir($path) && is_readable($path)) {
				$allData[$content] = $this->scanDirectories($path, $allowExt);
			}
		}
		return $allData;
	}

	public function emptyDirectory($rootDir){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> fileSystem.php-> emptyDirectory($rootDir)\n");
		
		$dirContent = scandir($rootDir);
		foreach ($dirContent as $key => $content){
			if ($content != "." && $content != "..") {
				@unlink($rootDir . "/" . $content);
			}
		}
	}

}

?>