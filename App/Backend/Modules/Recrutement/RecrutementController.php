<?php
	
	/**
	 * Desc: Controlleur du Module Recrutement dans Backend
	 *
	 * @author Voahirana
	 *
	 * @since 07/10/19 
	 */

	namespace App\Backend\Modules\Recrutement;

	use \Core\BackController;

	use \Model\ManagerOffre;

	require(__DIR__ . "/Model/ManagerModuleRecrutement.php");

	class RecrutementController extends BackController
	{
		/** 
		 * Desc: Lister les entités
		 *
		 * @return Collection d'objet 
		 */
		public function executeLister($parameters) 
		{
			$url        = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleRecrutement();
			$method  = "lister" . $methodName;
			if ($url[1] == "suggest_candidats" || $url[1] == "offre_candidatures" || ($url[1] == "entretiens" && isset($parameters['idOffre']))) {
				return [
					'data'   => $manager->$method($parameters),
					'params' => $parameters
				];
			} else {
				return $manager->$method($parameters);
			}
			
		} 

		/** 
		 * Desc: Afficher les forms
		 *
		 * @param array $parameters Desc: Données 
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
			$manager = new \ManagerModuleRecrutement();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);
		}

		/** 
		 * Desc: Mettre à jour une ligne dans une table
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
			$manager = new \ManagerModuleRecrutement();
			$method  = "mettreAJour" . $methodName;
			$result = $manager->$method($parameters);
			if ($url[1] == "sousdomaine") {
				$redirect = "domaine?idDomaine=" . $_SESSION['variable']['idDomaine'];
			} else if ($url[1] == "niveau_experience") {
				$redirect = "niveaux_experiences";
			} else if ($url[1] == "niveau_etude") {
				$redirect = "niveaux_etudes";
			} else if ($url[1] == "niveau_entretien") {
				$redirect = "niveaux_entretiens";
			}  else if ($url[1] == "entretien") {
				$redirect = "entretiens?idNiveauEntretien=" . $result->getIdNiveauEntretien();
			} else if ((isset($_SESSION['variable']['idNiveauEntretien']) && $url[1] == "interlocuteur") || $url[1] == "interlocuteur_niveau_entretien") {
				$redirect = "niveau_entretien?idNiveauEntretien=" . $_SESSION['variable']['idNiveauEntretien'];
			} else  {
				$redirect = $url[1] . "s";
			} 
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/** 
		 * Desc: Voir les détails des entités
		 *
		 * @param array $parameters Desc: Les données 
		 *
		 * @return object
		 */
		public function executeVoirDetail($parameters)
		{
			$url     = explode('/', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleRecrutement();
			$method  = "voirDetail" . $methodName; 
			return $manager->$method($parameters);
		}

		/** 
		 * Desc: Supprimer une ligne dans une table
		 *
		 * @param array $parameters Desc: Données 
		 *
		 * @return empty
		 */
		public function executeSupprimer($parameters)
		{
			$url = explode('-', $_GET['page']);			
			$managerName = "\Model\Manager" . str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			$manager     = new $managerName();
			$manager->supprimer($parameters);
			if ($url[1] == "interlocuteur_niveau_entretien") {
				$redirect = "niveau_entretien?idNiveauEntretien=" . $_SESSION['variable']['idNiveauEntretien'];
			} else  {
				$redirect = $url[1] . "s";
			}
			$_SESSION['info']['success'] = "Suppression avec succès";
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}

		/** 
		 * Desc: Supprimer une ligne dans une table exeptionnelle
		 *
		 * @param array $parameters Desc: Données 
		 *
		 * @return empty
		 */
		public function executeSupprimerExcept($parameters)
		{
			$url = explode('-', $_GET['page']);			
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleRecrutement(); 
			$method  = "supprimer" . $methodName;
			$manager->$method($parameters); 
			if ($url[1] == "sousdomaine") {
				$redirect = "domaine?idDomaine=" . $_SESSION['variable']['idDomaine'];
			} else if ($url[1] == "niveau_experience") {
				$redirect = "niveaux_experiences";
			} else if ($url[1] == "niveau_etude") {
				$redirect = "niveaux_etudes";
			} else if ($url[1] == "niveau_entretien") {
				$redirect = "niveaux_entretiens";
			} else {
				$redirect = $url[1] . "s";
			}
			header("Location:" . HOST . "manage/" . $redirect);
			exit();
		}	

		/**
		 * Desc: Mettre à jour une offre
		 * 
		 * @param array $parmameters Desc: les données à mettre à jour
		 * 
		 * @return empty
		 */
		public function executeMettreAJourOffre($parameters)
		{
			$manager                  = new ManagerOffre();
			$offre = $manager->chercher($parameters);
			$parameters['dateEmission'] = date("Y-m-d");
			if ($offre->getDateLimite() < $parameters['dateEmission']) {
				$_SESSION['info']['warning'] = "La date limite de cet offre est dépassé. Veuillez la modifier avant de l'actualiser.";
			} else {
				$manager->modifier($parameters);
			}
			header("Location:" . $_SERVER['HTTP_REFERER']);
			exit();
		}	

		/**
		 * Desc: Mettre à jour une candidature
		 * 
		 * @param array $parmameters Desc: les données à mettre à jour
		 * 
		 * @return empty
		 */
		public function executeMettreAJourCandidature($parameters)
		{
			$url     = explode('/', $_GET['page']);	
			$manager =  new \ManagerModuleRecrutement();
			if ($url[1] == "create-candidature") {
				$candidature = $manager->mettreAJourCandidature($parameters);
				header("Location:" . $_SERVER['HTTP_REFERER']);
			} else if ($url[1] == "refuse-candidature") {
                $parameters['statut'] = "refuse";
				$candidature = $manager->mettreAJourCandidature($parameters);
				header("Location:" . $_SERVER['HTTP_REFERER']);
			} else if ($url[1] == "accept-candidature") {
				$parameters['statut'] = "accepte";
				$candidature = $manager->mettreAJourCandidature($parameters);
				$_SESSION['variable']['idCandidature'] = $parameters['idCandidature'];
				$_SESSION['info']['info'] 			   = "Candidature accépté! \n Veuillez renseigner l'entretien sur cette candidature";
				header("Location: " . HOST . "manage/create-entretien");
				exit();
			}
		}

		/**
		 * Mettre à jour une entretien
		 * 
		 * @param array $parmameters Desc: les données à mettre à jour
		 * 
		 * @return empty
		 */
		public function executeMettreAJourEntretien($parameters)
		{	
			$niveauEntretien = "";
			$url     		 = explode('/', $_GET['page']);
			$manager 		 = new \ManagerModuleRecrutement();
			if ($url[1] == "validate-entretien") {
            	$parameters['statut'] 	  = "valide";
            	$_SESSION['info']['info'] = "Entretien valide";				
			} else if ($url[1] == "reject-entretien") {
				$parameters['statut']       = "rejete";
                $_SESSION['info']['danger'] = "Entretien rejeté";
			} else if ($url[1] == "cancel-entretien") {
				$parameters['statut'] = "en attente";
			}
			$manager->mettreAJourEntretien($parameters);
			header("Location:" . $_SERVER['HTTP_REFERER']);
			exit();
		}

		/**
		 * Parametrer les données d'un graphe
		 * 
		 * @param arary $parameters Les critères des données du graphe
		 *
		 * @return empty
		 */
		public function executeParametrerGraphe($parameters)
		{	
			$url = explode('-', $_GET['page']);
			$method = "parametrerGraphe".ucfirst($url[1]);
			$manager = new \ManagerModuleRecrutement();
			$resultats = $manager->$method($parameters);
			return $manager->specifierDonnees($url[1], $resultats);			
		}

		
	}