<?php
	
	/**
	 * Desc: Controlleur du Module Compte dans Frontend
	 *
	 * @author Voahirana
	 *
	 * @since 26/09/19 
	 */

	namespace App\Frontend\Modules\Compte;

	use \Core\BackController;

    use \Model\ManagerCompte;

    require(__DIR__ . "/Model/ManagerModuleCompte.php");
    
	class CompteController extends BackController
	{

		/** 
		 * Desc: se connecter 
		 *
		 * @param array $parameters Desc: Les données postées
		 *
		 * @return empty
		 */
		public function executeConnecter($parameters)
		{	
			$userManager = "";
			$user        = "";
			$manager     = new ManagerCompte();
			if (!empty($parameters)) {
				$parameters['motDePasse'] = md5($parameters['motDePasse']);
				$compte = $manager->chercher($parameters);
				if (!empty($compte)) {
					if ($compte->getStatut() == "active") {
						// si compte activé
						$userManager        = "\Model\Manager" . ucfirst($compte->getIdentifiant());
						$manager            = new $userManager();
						$user               = $manager->chercher(['idCompte' => $compte->getIdCompte()]);						
						$_SESSION['compte'] = $compte->toArray();
						$_SESSION['user']   = $user->toArray();
						header("Location:" . HOST . "manage/" . $compte->getIdentifiant() . "/dashboard");
						exit();
		            } else {
		            	$_SESSION['info']['danger'] = "Votre compte est actuellement désactivé ";
		            }
	            } else {
	            	$_SESSION['info']['danger'] = "Pseudo ou mot de passe incorrect";
	            }
			}
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
					header("Location:" . HOST . "register-" . $_SESSION['variable']['identifiant']);
					exit();
				} else {
					$_SESSION['info']['warning'] = "Pseudo existe déjà";
				}
				
			}
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
			$manager = new \ManagerModuleCompte();
			$method  = "afficherForm" . ucwords($url[1]);
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
			$manager = new \ManagerModuleCompte();			
			if ($url[1] == "candidat") {
				$field = "photo";
			} else {
				$field = "logo";
			}
			
			if (!empty($_FILES[$field]['name'])) { 
                $target             = DOC_ROOT. 'Ressources/images/' . $url[1] . 's/' . basename($_FILES[$field]['name']);
                move_uploaded_file( $_FILES[$field]['tmp_name'], $target);
                $parameters[$field] = basename($_FILES[$field]['name']);
            }            
            $method   = "ajouter" . ucwords($url[1]);
			$customer = $manager->$method($parameters);
			$msg = $this->getMessage($customer);
			if (!empty($msg)) {
				extract($msg);
				$manager->sendEmail($customer->getEmail(), $subject, $message);
			}
			$_SESSION['variable']['email'] = $customer->getEmail();
			$_SESSION['info']['success'] = "Vous êtes maintenant inscrit ;)";
			header("Location:" . HOST . "connexion");
			exit();
		}

		/** 
		 * Message envoyé par un superadmin après inscription
		 *
		 * @param object $customer le client qu'on va inscrire
		 *
		 * @return array 
		 */
		private function getMessage($customer)
		{
			$resultat = array();
			$manager  = new ManagerCompte();
			$compte   = $manager->chercher(['idCompte' => $customer->getIdCompte()]);
			if ($compte->getIdentifiant() == "candidat") {
				$resultat['message'] = "<html><body>
											<div class='container'>
												<label>Bonjour " . ucwords($customer->getPrenom()) . ", </label><br><br>
												<label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
												<label>
												  Votre compte candidat vous permettra de recevoir et d’être suggéré automatiquement<br> à toutes les offres d’emploi en adéquation avec votre profil.<br>
												  Nous vous invitons à bien remplir votre profil pour plus de chance.
												</label><br><br>
												<label>Ci-après votre login et mot de passe :</label><br><br>
												<strong>Login : </strong> " . $compte->getLogin() . "<br>
												<strong>Mot de passe : </strong> " . $_SESSION['variable']['motDePasse'] . "<br><br>
												<label>Lien d'accès sur votre compte : <a href='http://sirh.s187620.mos2.atester.fr/connexion'>http://sirh.s187620.mos2.atester.fr</a></label><br><br>
												<label>Cordialement, </label><br><br>
												<label> L'équipe <a href='http://sirh.s187620.mos2.atester.fr/'>Human Cart'Office</a></label>
											</div>
										</body></html>";
				$resultat['subject'] = "Ouverture de votre compte candidat_Human Cart'Office";
			} else if ($compte->getIdentifiant() == "entreprise") {
				$resultat['message'] = "<html><body>
											<div class='container'>
												<label>Bonjour " . strtoupper($customer->getNom()) . ", </label><br><br>
												<label>Human Cart’Office vous souhaite la bienvenue sur sa nouvelle plateforme de recrutement.</label><br><br>
												<label>
												  Votre compte entreprise vous permettra de publier vos offres emplois, de recevoir les candidats <br>qui postulent d’une part et des candidats suggérés d’autre part.
												</label><br><br>
												<label>Ci-après votre login et mot de passe :</label><br><br>
												<strong>Login : </strong> " . $compte->getLogin() . "<br>
												<strong>Mot de passe : </strong> " . $_SESSION['variable']['motDePasse'] . "<br><br>
												<label>Lien d'accès sur votre compte : <a href='http://sirh.s187620.mos2.atester.fr/connexion'>http://sirh.s187620.mos2.atester.fr</a></label><br><br>
												<label>Cordialement, </label><br><br>
												<label> L'équipe <a href='http://sirh.s187620.mos2.atester.fr/'>Human Cart'Office</a></label>
											</div>
										</body></html>";
				$resultat['subject'] = "Ouverture de votre compte entreprise_Human Cart'Office";
				$manager = new \ManagerModuleCompte();
				$manager->notifierApp($customer);
			} 
			unset($_SESSION['variable']);
			return $resultat;
		}

		/** 
		 * Se déconnecter 
		 *
		 * @param array $parameters Données passées en paramètres
		 *
		 * @return empty
		 */
		public function executeDeconnecter($parameters)
		{	
			session_destroy();
			header("Location:" . HOST . "accueil");
		}

		/** 
		 * Annuler une inscription 
		 *
		 * @param array $parameters Données à supprimer
		 *
		 * @return empty
		 */
		public function executeCancelCompte($parameters)
		{	
			unset($_SESSION['variable']); 
			$_SESSION['info']['danger'] = "inscription annulée";
			header("Location:" . HOST . "accueil");
			exit();
		}

		/** 
		 * Envoyer le message sur le formulaire de contact  
		 *
		 * @param array $parameters les messages à envoyer
		 *
		 * @return empty
		 */
		public function executeSendMessage($parameters)
		{	
			$manager = new \ManagerModuleCompte();
			$manager->sendMessage($parameters);
			header("Location:" . HOST . "accueil#contact");
			exit();
		}
	}
