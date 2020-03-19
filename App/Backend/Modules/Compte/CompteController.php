<?php
	
	/**
	 * Controlleur du Module Compte dans Backend
	 *
	 * @author Voahirana
	 *
	 * @since 30/09/19 
	 */

	namespace App\Backend\Modules\Compte;

	use \Core\BackController;
	
    use \Model\ManagerCompte;
    use \Model\ManagerCandidat;

	require(__DIR__ . "/Model/ManagerModuleCompte.php");

	class CompteController extends BackController
	{		
		/** 
		 * Lister les utilisateurs
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
			$manager = new \ManagerModuleCompte();
			$method  = "lister" . $methodName;
			return $manager->$method();
		}

		/** 
		 * Voir le profil de l'utilisateur connecté
		 *
		 * @return object
		 */
		public function executeVoirProfil()
		{
			$url     = explode('/', $_GET['page']);
			$manager = new \ManagerModuleCompte();
			$method  = "voirProfil" . ucfirst($url[1]);
			return $manager->$method();
		}

		/** 
		 * Voir les détails d'un utilisateur
		 *
		 * @param array $parameters L'utilisateur concerné
		 *
		 * @return object
		 */
		public function executeVoirDetail($parameters)
		{
			$url     = explode('/', $_GET['page']);
			$manager = new \ManagerModuleCompte();
			$method  = "voirDetail" . ucfirst($url[1]); 
			return $manager->$method($parameters);
		}

		/** 
		 * Afficher les formulaires d'un utilisateur
		 *
		 * @param array $parameters Les donnée a récupérer
		 *
		 * @return Object
		 */
		public function executeAfficherForm($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleCompte();
			$method  = "afficherForm" . $methodName;
			return $manager->$method($parameters);

		}

		/** 
		 * Mettre à jour une ligne dans une table
		 *
		 * @param array $parameters Les données à insérer ou modifier 
		 *
		 * @return empty
		 */
		public function executeMettreAJour($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$method  = "";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager = new \ManagerModuleCompte();			
			if ($url[1] == "candidat" || $url[1] == "superadmin") {
				$field    = "photo";
			} else if ($url[1] == "entreprise") {
				$field    = "logo";
			}
			
			if (!empty($_FILES[$field]['name'])) { 
                $target             = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . basename($_FILES[$field]['name']);
                move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                $parameters[$field] = basename($_FILES[$field]['name']);
            }
            if (isset($parameters['qualite'])) {
            	unset($parameters['qualite']);
            }

			if (reset($parameters) == '') {
				$method = "ajouter" . $methodName;
				$manager->$method($parameters);
				$_SESSION['info']['success'] = "Enregistrement avec succès ;)";
				if ($url[1] == "email_contact") {
					header("Location:" . HOST . "manage/emails_contacts");
				} else {
					header("Location:" . HOST . "manage/" . $url[1] . "s");
				}
			} else {
				$method = "modifier" . $methodName;
				$manager->$method($parameters);
				$_SESSION['info']['success'] = "Modification avec succès ;)";
				if ($url[1] == "email_contact") {
					header("Location:" . HOST . "manage/emails_contacts");
				} else {
					header("Location:" . HOST . "manage/" . $url[1] . "/dashboard");
				}
			}
			exit();
		}	

		/** 
		 * Mettre à jour une la colonne "publique" de la table candidat
		 *
		 * @param array $parameters Les données à mettre à jour		 *
		 * @return empty
		 */
		public function executeMettreAJourPubliqueCandidat($parameters)
		{
			$manager = new \ManagerModuleCompte();			
			$manager->modifierPublique($parameters);
			header("Location:" . HOST . "manage/candidat/dashboard");
			exit();
		}		

		/** 
		 * Modifier le statut d'une compte
		 *
		 * @param array $parameters Le compte concerné
		 *
		 * @return empty
		 */
		public function executeModifierCompte($parameters)
		{
			$url     = explode('-', $_GET['page']);
			$manager = new ManagerCompte();
			if ($url[1] == "compte") {
				$compte  = $manager->chercher($parameters);
				if ($url[0] == "manage/archive") {
					if ($compte->getStatut() == "archive") {
						$parameters['statut'] = "active";
					} else {
						$parameters['statut'] = "archive";
					}
				} else {
					if ($compte->getStatut() == "desactive" || $compte->getStatut() == "archive") {
						$parameters['statut'] = "active";
					} else if ($compte->getStatut() == "active") {
						$parameters['statut'] = "desactive";
					}
				}
				$manager = new \ManagerModuleCompte();
				$manager->modifierCompte($compte, $parameters);
				header("Location:" . $_SERVER['HTTP_REFERER']);
				exit();
			} else {
				$compte  = $manager->chercher(['login' => $parameters['login']]);
				if (empty($compte)) {
					$manager->modifier($parameters);
					$_SESSION['compte']['login'] = $parameters['login'];
					$_SESSION['info']['success'] = "Pseudo modifié avec succès";
					header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
					exit();
				} else {
					$_SESSION['info']['warning'] = "Pseudo existe déjà";
					header("Location:" . $_SERVER['HTTP_REFERER']);
					exit();
				}
			}
		}

		/** 
		 * Modifier le mot de passe
		 *
		 * @param array $parameters L'utilisateur concerné
		 *
		 * @return empty
		 */
		public function executeModifierPassword($parameters)
		{
			$manager = new ManagerCompte();
			$compte  = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
			if (count($parameters) != 1) {
				if ($compte->getMotDePasse() == md5($parameters['ancienMotDePasse'])) {
					if ($parameters['motDePasse'] == $parameters['confirmation']) {
						$parameters['motDePasse'] = md5($parameters['motDePasse']);
						unset($parameters['confirmation']); 
						unset($parameters['ancienMotDePasse']);
						$manager->modifier($parameters);
						$_SESSION['info']['success'] = "Mot de passe modifié avec succès";
						header("Location:" . HOST . "manage/" . $_SESSION['compte']['identifiant'] . "/dashboard");
						exit();
					} else {
						$_SESSION['info']['warning'] = "Confirmation nouveau mot de passe incorrect :/";
					}
				} else {
					$_SESSION['info']['danger'] = "Ancien mot de passe incorrect !";
				}
			}
		}

		/** 
		 * Supprimer une ligne dans une table
		 *
		 * @param array $parameters Données 
		 *
		 * @return empty
		 */
		public function executeSupprimer($parameters)
		{
			$url        = explode('-', $_GET['page']);
			$method     = "";
			$methodName = "";
			if (strstr($url[1], "_")) {
				$methodName = str_replace(" ", "", ucwords(str_replace("_", " ", $url[1])));
			} else {
				$methodName = ucfirst($url[1]);
			}
			$manager  = new \ManagerModuleCompte();
			$method   = "supprimer" . $methodName;
			$resultat = $manager->$method($parameters);
			if ($url[1] == "email_contact") {
				header("Location:" . HOST . "manage/emails_contacts");
			} else {
				header("Location:" . HOST . "manage/" . $url[1] . "s");
			}
			exit();
		}

		/** 
		 * Afficher le form de personnalité
		 *
		 * @param array $parameters Données à afficher
		 *
		 * @return array
		 */
		public function executeAfficherFormPersonnalite($parameters)
		{

			$resultats     = array();
			$manager       = new \ManagerModuleCompte();
			$personnalites = $manager->listerPersonnalites();
			if (isset($parameters)) {
				$candidat  = $manager->chercherCandidat($parameters);
				$resultats = [
					'candidat' => $candidat, 
					'personnalites' => $personnalites
				];
			}
			return $resultats;
		}

		/** 
		 * Créer un comtpe
		 *
		 * @param array $parameters Les données à créer
		 *
		 * @return empty
		 */
		public function executeCreerCompte($parameters)
		{
			$manager = new ManagerCompte();
			if (count($parameters) != 1) {
				$login = $manager->chercher(['login' => $parameters['login']]);
				if (empty($login)) {
					if ($parameters['identifiant'] != 'candidat') {
						$parameters['statut'] = 'desactive';
					} 
					$_SESSION['variable'] = [
						"identifiant" => $parameters['identifiant'],
						"login"       => $parameters['login'],
						"motDePasse"  => $parameters['motDePasse'],
						"statut"      => $parameters['statut']
					];
					header("Location:" . HOST . "manage/register-" . $_SESSION['variable']['identifiant']);
					exit();
				} else {
					$_SESSION['info']['warning'] = "Pseudo existe déjà";
				}
				
			}
		}

	}
