<?php

use spitfire\autoload\AutoLoad;
use spitfire\App;

class adminApp extends App
{
	private $beans = Array();
	private $dashboard_modules = Array();
	
	/**
	 * The table this app uses to identify users that try to log into the admin
	 * console.
	 * 
	 * @var spitfire\storage\database\Table
	 */
	private $userTable = null;
	
	public function putBean($name) {
		$this->beans[] = $name;
		return $this;
	}
	
	public function getBeans() {
		return $this->beans;
	}
	
	/**
	 * Defines which table should be used by this app to identify users that 
	 * log into the admin console.
	 * 
	 * @param \spitfire\storage\database\Table $table
	 * @return \adminApp
	 * @throws privateException
	 */
	public function setUserTable(\spitfire\storage\database\Collection$table) {
		
		$this->userTable = $table;
		return $this;
	}
	
	/**
	 * Returns the database table that holds the information about the users that
	 * are available on the system. Only those with admin set to 1 should be allowed
	 * to retrieve information from the admin panel.
	 * 
	 * @return \spitfire\storage\database\Table $table
	 */
	public function getUserTable() {
		return $this->userTable;
	}

	public function enable() {
		AutoLoad::getInstance()->registerLocator(new \spitfire\autoload\NamespacedClassLocator('\M3W\admin', dirname(__FILE__) . '/controllers', 'Controller'));
		AutoLoad::registerClass('M3W\admin\authController', $this->getBaseDir() . 'auth.php');
	}
	
	public function moduleGroup() {
		return $this->dashboard_modules[] = new M3W\admin\DashboardModuleGroup($this);
	}
	
	public function getModules() {
		return $this->dashboard_modules;
	}
	
	public function getAssetsDirectory() {
		return $this->getBaseDir() . 'assets/';
	}

	public function getTemplateDirectory() {
		return $this->getBaseDir() . 'templates/';
	}
	
	public function getNameSpace() {
		return "M3W\\admin\\";
	}
	
	public function createRoutes() {
		\spitfire\core\router\Router::getInstance()->request("/{$this->getURISpace()}/:method|lst|edit", "/{$this->getURISpace()}/home/:method");
		
		#Add the default ones too
		parent::createRoutes();
	}
}