<?php
	
	/**
	 * Controlleur du Module Employe dans Backend
	 *
	 * @author Voahirana
	 *
	 * @since 13/03/20 
	 */

	namespace App\Backend\Modules\Employe;

	use \Core\BackController;
	
    //use \Model\ManagerEmploye;

	require(__DIR__ . "/Model/ManagerModuleEmploye.php");

	class EmployeController extends BackController
	{
		/** 
		 * Lister les données
		 *
		 * @return array
		 */
		public function executeLister()
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleEmploye();
			$method  = "lister" . $methodName;
			return $manager->$method();
		}

		/** 
		 * Afficher les formulaires 
		 *
		 * @param array $parameters Les donnée a récupérer
		 *
		 * @return Object
		 */
		public function executeAfficherForm($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleEmploye();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);

		}

		/** 
		 * Mettre à jour une ligne dans une table
		 *
		 * @param array $parameters Desc: Données 
		 *
		 * @return Object
		 */
		public function executeMettreAJour($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}			
			$manager = new \ManagerModuleEmploye();
			$method  = "mettreAJour" . $methodName;
			$result = $manager->$method($parameters);
			$redirect = $url[1] . "s";
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}
	}
