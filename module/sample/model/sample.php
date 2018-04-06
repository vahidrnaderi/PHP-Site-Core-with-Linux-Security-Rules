<?php
class c_sample extends masterModule{

	public $active = 1;

	public function __construct() {
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
	}
	
	###########################
	# Category                #
	###########################
	// Add Category
	public function m_addCategory($name, $category, $description=null){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// Edit Category
	public function m_editCategory($id, $name, $category=null, $description=null){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// Del Category
	public function m_delCategory($id){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// List Category
	public function m_listCategory(){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// Activate Category
	public function m_activateCategory(){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");

	}
	// Deactivate Category
	public function m_deactivateCategory(){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");

	}
	###########################
	# Object (sample)        #
	###########################
	// Add Object
	public function m_addObject($values, $show=false){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// Edit Object
	public function m_editObject($values){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// Del Object
	public function m_delObject($id, $name=null){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// List Object
	public function m_listObject($viewMode, $filter=null){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");
		
	}
	// Activate Object
	public function m_activateObject(){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");

	}
	// Deactivate Object
	public function m_deactivateObject(){
		global $settings;
		debug($settings['debugFile'], "chrM", "	Module-Function=>  Module >> model/sample.php-> __construct()\n");

	}
}
?>