<?php
    
    /**
     * Manager du modules Compte du Backend
     *
     * @author Voahirana
     *
     * @since 30/09/19 
     */

	use \Core\DbManager;
    use \Model\ManagerSuperadmin;
    use \Model\ManagerEntreprise;
    use \Model\ManagerCandidat;
    use \Model\ManagerCompte;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerEmailContact;
    use \Model\ManagerCandidature;
    use \Model\ManagerHistorique;
    use \Model\ManagerEmploye;

	class ManagerModuleCompte extends DbManager
	{
        /** 
         * Lister les candidats actifs
         * 
         * @return array
         */
        public function listerActiveCandidats()
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $candidats = $manager->lister();
            if (!empty($candidats)) {
                foreach ($candidats as $candidat) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $candidat->getIdCompte()]);
                    if ($compte->getStatut() != "archive") {
                        $resultats[] = [
                            'candidat'=> $candidat, 
                            'compte'  => $compte];
                    } 
                }
            }
            return $resultats;
        }

        /** 
         * Lister les archives des candidats
         * 
         * @return array
         */
        public function listerArchiveCandidats()
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $candidats = $manager->lister();
            if (!empty($candidats)) {
                foreach ($candidats as $candidat) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $candidat->getIdCompte()]);
                    if ($compte->getStatut() == "archive") {
                        $resultats[] = [
                            'candidat' => $candidat, 
                            'compte'   => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entreprises actifs
         * 
         * @return array
         */
        public function listerActiveEntreprises()
        {
            $resultats   = array();
            $manager     = new ManagerEntreprise();
            $entreprises = $manager->lister();
            if (!empty($entreprises)) {
                foreach ($entreprises as $entreprise) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "active") {
                        $resultats[] = [
                            'entreprise' => $entreprise, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entreprises inactifs
         * 
         * @return array
         */
        public function listerInactiveEntreprises()
        {
            $resultats   = array();
            $manager     = new ManagerEntreprise();
            $entreprises = $manager->lister();
            if (!empty($entreprises)) {
                foreach ($entreprises as $entreprise) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "desactive") {
                        $resultats[] = [
                            'entreprise' => $entreprise, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les archives des entreprises
         * 
         * @return array
         */
        public function listerArchiveEntreprises()
        {
            $resultats = array();
            $manager   = new ManagerEntreprise();
            $entreprises = $manager->lister();
            if (!empty($entreprises)) {
                foreach ($entreprises as $entreprise) {
                    $manager = new ManagerCompte();
                    $compte  = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "archive") {
                        $resultats[] = [
                            'entreprise' => $entreprise, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les superadmins
         * 
         * @return array
         */
        public function listerSuperadmins()
        {
            $resultats   = array();
            $manager     = new ManagerSuperadmin();
            $superadmins = $manager->lister();
            if (!empty($superadmins)) {
                foreach ($superadmins as $superadmin) {
                    if ($superadmin->getIdCompte() != $_SESSION['compte']['idCompte']) {
                        $manager     = new ManagerCompte();
                        $compte      = $manager->chercher(['idCompte' => $superadmin->getIdCompte()]);
                        $resultats[] = [
                            'superadmin' => $superadmin, 
                            'compte'     => $compte
                        ];
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les Historiques
         * 
         * @return array
         */
        public function listerHistoriques()
        {
            $resultats   = array();
            $entity      = "";
            $manager     = new ManagerHistorique();
            $historiques = $manager->lister(null);
            if (!empty($historiques)) {
                foreach ($historiques as $historique) {
                    $manager    = new ManagerCompte();
                    $compte     = $manager->chercher(['idCompte' => $historique->getIdCompte()]);
                    if (!empty($compte)) {
                        $manager    = new ManagerSuperadmin();
                        $superadmin = $manager->chercher(['idSuperadmin' => $historique->getIdSuperadmin()]);
                        $entity     = "\Model\Manager" . ucfirst($compte->getIdentifiant());
                        $manager    = new $entity();
                        $user       = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                        if (!empty($user)) {
                            $resultats[] = [
                                'historique' => $historique,
                                'compte'     => $compte,
                                'user'       => $user,   
                                'superadmin' => $superadmin
                            ];
                        }
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les emails de contact
         * 
         * @return array
         */
        public function listerEmailsContacts()
        {
            $manager = new ManagerEmailContact();
            return $manager->lister();
        }

        /** 
         * Lister les personnalités 
         * 
         * @return array
         */
        public function listerPersonnalites()
        {
            $manager = new ManagerPersonnalite();
            return $manager->lister();
        }

        /** 
         * Voir le profil d'un Candidat
         * 
         * @return objet
         */
        public function voirProfilCandidat()
        {
            $manager = new ManagerCandidat();
            return $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
        }

        /** 
         * Voir le profil d'une entreprise
         * 
         * @return objet
         */
        public function voirProfilEntreprise()
        {
            $manager = new ManagerEntreprise(); 
            return $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
        }

        /** 
         * Voir le profil d'un superadmin
         * 
         * @return objet|empty
         */
        public function voirProfilSuperadmin()
        {
            $manager = new ManagerSuperadmin();
            return $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
        }

        /** 
         * Voir le détail d'un Candidat
         * 
         * @param array $parameters Le candidat concerné
         *
         * @return array
         */
        public function voirDetailCandidat($parameters)
        {
            $resultat = array();
            $manager  = new ManagerCandidat();
            if (isset($parameters)) {
                $candidat = $manager->chercher($parameters);
                if (!empty($candidat)) {
                    $manager  = new ManagerCompte(); 
                    $compte   = $manager->chercher(['idCompte' => $candidat->getIdCompte()]);
                    $resultat = [
                        'candidat' => $candidat, 
                        'compte'   => $compte];
                }
            } 
            return $resultat;
        } 

        /** 
         * Voir le détail d'une entreprise
         *
         * @param array $parameters L'entreprise concernée
         *
         * @return array
         */
        public function voirDetailEntreprise($parameters)
        {
            $resultat = array();
            $manager  = new ManagerEntreprise();
            if (isset($parameters)) {
                $entreprise = $manager->chercher($parameters);
                if (!empty($entreprise)) {
                    $manager  = new ManagerCompte(); 
                    $compte   = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    $resultat = [
                        'entreprise' => $entreprise, 
                        'compte'     => $compte];
                }
            } 
            return $resultat;
        }

        /** 
         * Voir le détail d'un superadmin
         * 
         * @param array $parameters Le superadmin concerné
         *
         * @return array
         */
        public function voirDetailSuperadmin($parameters)
        {
            $resultat = array();
            $manager  = new ManagerSuperadmin();
            $superadmin = $manager->chercher($parameters);
            if (!empty($superadmin)) {
                $manager  = new ManagerCompte(); 
                $compte   = $manager->chercher(['idCompte' => $superadmin->getIdCompte()]);
                $resultat = [
                    'superadmin' => $superadmin, 
                    'compte'     => $compte];
            } 
            return $resultat;
        }

        /** 
         * Voir le détail historique
         * 
         * @param array $parameters l'historique à détailler
         *
         * @return array
         */
        public function voirDetailHistorique($parameters)
        {
            $resultat = array();
            $manager  = new ManagerHistorique();
            $historique = $manager->chercher($parameters);
            if (!empty($historique)) {
                $manager  = new ManagerCompte(); 
                $compte   = $manager->chercher(['idCompte' => $historique->getIdCompte()]);
                $manager  = new ManagerSuperadmin(); 
                $superadmin   = $manager->chercher(['idSuperadmin' => $historique->getIdSuperadmin()]);
                $resultat = [
                    'historique' => $historique,
                    'superadmin' => $superadmin, 
                    'compte'     => $compte];
            } 
            return $resultat;
        }

        /** 
         * Afficher le formulaire d'un candidat
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormCandidat($parameters)
        {
            $manager = new ManagerCandidat();
            if (isset($parameters)) {
                $candidat = $manager->chercher($parameters);
            } else {
                $candidat = $manager->initialiser();
            }
            return $candidat;
        }

        /**
         * Chercher un candidat 
         * 
         * @param array $parameters Le candidat concerné
         * 
         * @return object
         */
        public function chercherCandidat($parameters)
        {
            $manager = new ManagerCandidat();
            return $manager->chercher($parameters);
        }

        /** 
         * Afficher le formulaire d'une entreprise
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormEntreprise($parameters)
        {
            $manager = new ManagerEntreprise();
            if (isset($parameters)) {
                $entreprise = $manager->chercher($parameters);
            } else {
                $entreprise = $manager->initialiser();
            }
            return $entreprise;
        }

        /** 
         * Afficher le formulaire d'un superadmin
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormSuperadmin($parameters)
        {
            $manager = new ManagerSuperadmin();
            if (isset($parameters)) {
                $superadmin = $manager->chercher($parameters);
            } else {
                $superadmin = $manager->initialiser();
            }
            return $superadmin;
        }

        /** 
         * Afficher le formulaire d'un compte
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormPseudo($parameters)
        {
            $manager = new ManagerCompte();
            if (isset($parameters)) {
                $compte = $manager->chercher($parameters);
            } else {
                $compte = $manager->initialiser();
            }
            return $compte;
        }

        /** 
         * Afficher le formulaire d'un email de contact
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            if (isset($parameters)) {
                $emailContact = $manager->chercher($parameters);
            } else {
                $emailContact = $manager->initialiser();
            }
            return $emailContact;
        }

        /** 
         * Supprimer un superadmin
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerSuperadmin($parameters)
        {
            $resultat = "";
            $managerSuperadmin  = new ManagerSuperadmin();
            if (isset($parameters)) {
                $superadmin = $managerSuperadmin->chercher($parameters);
                if (!empty($superadmin)) {
                    $manager = new ManagerHistorique();
                    $historique = $manager->chercher(['idSuperadmin' => $superadmin->getIdSuperadmin()]);
                    $managerCompte = new ManagerCompte();
                    $compte        = $managerCompte->chercher(['idCompte' => $superadmin->getIdCompte()]);
                    if (!empty($compte) && $compte->getStatut() == "desactive" && empty($historique)) {
                        $listes = $manager->lister(['idCompte' => $superadmin->getIdCompte()]);
                        if (!empty($listes)) {
                            foreach ($listes as $liste) {
                                $manager->supprimer(['idCompte' => $liste->getIdCompte()]);
                            }
                        }
                        $managerCompte->supprimer(['idCompte' => $superadmin->getIdCompte()]);
                        $managerSuperadmin->supprimer($parameters);
                        $_SESSION['info']['success'] = "Suppression avec succès";
                    } else {
                        $_SESSION['info']['warning'] = "On ne peut pas encore supprimer ce compte";
                    }
                }
            }
        }

        /** 
         * Ajouter un superadmin
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        public function ajouterSuperadmin($parameters)
        {
            $superadmin = "";
            $compte     = "";
            $dataCompte = $_SESSION['variable'];
            if (!empty($dataCompte)) {
                $dataCompte['motDePasse'] = md5($dataCompte['motDePasse']);
                $manager                  = new ManagerCompte();
                $compte                   = $manager->creerCompte($dataCompte);
            }
            $parameters['idCompte'] = $compte->getIdCompte();
            if (!empty($parameters['idCompte'])) {
                $manager    = new ManagerSuperadmin();
                $superadmin = $manager->ajouter($parameters);
            }
            return $superadmin;
        }

         /** 
         * Modifier un superadmin
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
         */
        public function modifierSuperadmin($parameters)
        {
            $manager = new ManagerSuperadmin();
            $manager->modifier($parameters);
            $_SESSION['user'] = $manager->chercher(['idSuperadmin' => $parameters['idSuperadmin']])->toArray();
        }

        /** 
         * Ajouter un email de contact
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return empty
         */
        public function ajouterEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            $manager->ajouter($parameters);
        }

        /** 
         * Modifier un email de contact
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
         */
        public function modifierEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            $manager->modifier($parameters);
        }

        /** 
         * Supprimer un email de contact
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerEmailContact($parameters)
        {
            $manager = new ManagerEmailContact();
            $manager->supprimer($parameters);
            $_SESSION['info']['success'] = "Suppression avec succès";
        }

        /** 
         * Modifier un candidat
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
         */
        public function modifierCandidat($parameters)
        {
            $stringPersonnalite = "";            
            $tabPersonnalite = explode("_", $parameters['personnalite']);
            array_pop($tabPersonnalite);
            if (isset($parameters['autrePersonnalite'])) {
                if (!empty($parameters['autrePersonnalite'])) {
                    $qualites = explode("_", $parameters['autrePersonnalite']);
                    foreach ($qualites as $qualite) {
                        if (!empty($qualite)) {
                            $manager = new ManagerPersonnalite();
                            $existe = $manager->chercher(['qualite' => $qualite]);
                            if (empty($existe)) {
                                $manager->ajouter(['qualite' => ucfirst($qualite)]);
                            } 
                        }
                    }
                }
                unset($parameters['autrePersonnalite']);
            }
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            for ($i = 0; $i < count($tabPersonnalite) ; $i++) {
                if (!(strstr($stringPersonnalite, $tabPersonnalite[$i]."_"))) {
                    $stringPersonnalite .= $tabPersonnalite[$i] . "_";
                }
            }
            if (isset($parameters['personnalite'])) {
                $parameters["personnalite"] = $stringPersonnalite;
            }
            $manager = new ManagerCandidat();
            $manager->modifier($parameters);
            $_SESSION['user'] = $manager->chercher(['idCandidat' => $parameters['idCandidat']])->toArray();
        }

        /** 
         * Modifier un l'état du publicité d'un profil candidat
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
         */
        public function modifierPublique($parameters)
        {
            $manager = new ManagerCandidat();
            $candidat = $manager->chercher($parameters);
            if ($candidat->getPublique() == 1) {
                $parameters['publique'] = 0;
            } else {
                $parameters['publique'] = 1;
            }
            $manager->modifier($parameters);
        }

         /** 
         * Modifier un entreprise
         *
         * @param array $parameters Les données à modifier 
         *
         * @return empty
         */
        public function modifierEntreprise($parameters)
        {
            $manager = new ManagerEntreprise();
            $manager->modifier($parameters);
            $_SESSION['user'] = $manager->chercher(['idEntreprise' => $parameters['idEntreprise']])->toArray();
        }

        /** 
         * Modifier un compte
         *
         * @param array $compte le compte à modifier
         * @param array $parameters critères des données à modifier 
         *
         * @return empty
         */
        public function modifierCompte($compte, $parameters)
        {
            if ($compte->getIdentifiant() == "candidat") {
                $manager = new ManagerCandidat();
                $candidat = $manager->chercher(['idCompte' => $compte->getIdCompte()]);
                $manager = new ManagerCandidature();
                $candidature = $manager->chercher(['idCandidat' => $candidat->getIdCandidat()]);
                if (empty($candidature)) {
                    $this->mettreAJourCompte($compte, $parameters);
                } else {
                    $_SESSION['info']['danger'] = "On ne peut pas encore désactiver ce compte";
                }
            } else {
                $this->mettreAJourCompte($compte, $parameters);
            }            
        }

        /** 
         * Executer la modification d'un compte
         *
         * @param object $compte le compte concerné
         * @param array $parameters les données à modifier 
         *
         * @return empty
         */
        private function mettreAJourCompte($compte, $parameters)
        {
            $to      = "";
            $subject = "";
            $message = "";
            $etat    = "";
            $headers = "";

            $manager = new ManagerCompte();
            $newCompte = $manager->modifier($parameters);
            if ($newCompte->getStatut() == "active") {
                $etat = "activé";
            } else if ($newCompte->getStatut() == "desactive") {
                $etat = "désactivé";
            } else if ($newCompte->getStatut() == "archive"){
                $etat = "archivé";
            }
            $_SESSION['info']['success'] = "Compte " . $etat;
            $compte  = $manager->chercher(['idCompte' => $compte->getIdCompte()]);

            $entity  = "\Model\Manager" . ucfirst($compte->getIdentifiant());
            $manager = new $entity();
            $user    = $manager->chercher(['idCompte' => $parameters['idCompte']]);
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == "information" && !empty($user)) {
                        $to      = $user->getEmail();
                        $subject = "Une information pour vous";
                        $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . strtoupper($user->getNom()) . " !,</label><br><br>
                                                <label>Nous informons que votre compte sur le site de la société
                                                <br>Human Cart'Office est désormais " . $etat . "</label><br><br>
                                                <label>Pour plus d'information, veuillez vous contacter sur le site en cliquant <a href='" . HOST . "/accueil#contact'>ici</a></label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
            }
            $this->historiserCompte($compte);
        }

        /** 
         * Historiser les compte
         *
         * @param object $compte le compte concerné
         *
         * @return empty
         */
        private function historiserCompte($compte)
        {
            $parameters = array();
            $manager = new ManagerSuperadmin();
            $superadmin = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($superadmin)) {
                $parameters['date'] = date("Y-m-d");
                $parameters['action'] = $compte->getStatut();
                $parameters['idCompte'] = $compte->getIdCompte();
                $parameters['idSuperadmin'] = $superadmin->getIdSuperadmin();
            } 
            if (!empty($parameters)) {
                $manager = new ManagerHistorique();
                $manager->ajouter($parameters);
            }
        }

	}