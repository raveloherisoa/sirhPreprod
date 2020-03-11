<?php
	
	/**
	 * Définition de l'application Backend
	 *
	 * @author Voahirana
	 *
	 * @since 25/09/19
	 */

	namespace App\Backend;

	use \Core\Application;
	use \Core\View;
	use \Modules\Recrutement\RecrutementController;

	class BackendApplication extends Application
	{	
		protected $appName = "Backend";
		protected $request;

		/**
		 * Construire de BackendApplication
		 *
		 * @param array $request URL de l'application
		 *
		 * @return empty
		 */
		function __construct($request) 
		{
			$this->request = $request;
		}

		/**
		 * Exécuter l'application
		 * 
		 * @return empty
		 */
		public function run() 
		{
			$module = ucfirst($this->getModule());
			$user   = $this->getUser();
			if (!empty($module)) {
				$action            = $this->getAction();
				$controller        = '\App\\' . $this->appName . '\Modules\\' . $module . '\\' . $this->getController();				
				$currentController = new $controller();
				$method			   = $currentController->execute($action);
				$parameters 	   = $this->getParameters();
				$data 			   = $currentController->$method($parameters);
			} else {
				$action = $this->request['page'];
				$data 	= null;
			}
			
			$view = new View($action);
			$view->send($this->appName, $module, $data, $user);
				
		}
	}