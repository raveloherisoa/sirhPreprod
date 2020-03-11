<?php
    
    /**
     * Manager du modules Recrutement Backend
     *
     * @author Voahirana 
     *
     * @since 09/10/19 
     */

    use \Core\DbManager;

    use \Model\ManagerCandidat;
    use \Model\ManagerCompte;
    use \Model\ManagerEntreprise;
    use \Model\ManagerSousDomaine;
    use \Model\ManagerDomaine;
    use \Model\ManagerExperience;
    use \Model\ManagerFormation;
    use \Model\ManagerDiplome;
    use \Model\ManagerContrat;
    use \Model\ManagerNiveauExperience;
    use \Model\ManagerNiveauEtude;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerOffre;
    use \Model\ManagerCandidature;
    use \Model\ManagerEntretien;

    class ManagerModuleRecrutement extends DbManager
    {
        /**
         * Lister toutes les offres disponibles
         *
         * @return array
         */
        public function listerOffres($parameters) 
        {
            $tabOffres    = array();
            $manager      = new ManagerOffre();
            $offres       = $manager->lister($parameters);
            $manager      = new ManagerSousDomaine();
            $sousDomaines = $manager->lister(null);
            $manager      = new ManagerContrat();
            $contrats     = $manager->lister();
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $manager     = new ManagerEntreprise();
                    $entreprise  = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                    $manager     = new Managercompte();
                    $compte      = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                    if ($compte->getStatut() == "active") {
                        $manager     = new ManagerContrat();
                        $contrat     = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                        $tabOffres[] = [
                            'offre'       => $offre, 
                            'entreprise'  => $entreprise,
                            'contrat'     => $contrat
                        ];     
                    }                      
                } 
            }
            return [
                "offres"       => $tabOffres,
                "sousDomaines" => $sousDomaines,
                "contrats"     => $contrats
            ];
        }

        /** 
         * Voir le détail d'une offre 
         *
         * @param array $parameters Critères des données à voir 
         * 
         * @return objet 
         */
        public function voirOffre($parameters)
        {
            $resultat    = array(); 
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher($parameters); 
            $candidature = "";
            if (!empty($offre)) {
                $manager          = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                $manager          = new Managercompte();
                $compte           = $manager->chercher(['idCompte' => $entreprise->getIdCompte()]);
                if ($compte->getStatut() == "active") {
                    $manager          = new ManagerSousDomaine();
                    $sousDomaine      = $manager->chercher(['idSousDomaine' => $offre->getIdSousDomaine()]);
                    $manager          = new ManagerDomaine();
                    $domaine          = $manager->chercher(['idDomaine' => $sousDomaine->getIdDomaine()]);
                    $manager          = new ManagerContrat();
                    $contrat          = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                    $manager          = new ManagerNiveauExperience();
                    $niveauExperience = $manager->chercher(['idNiveauExperience' => $offre->getIdNiveauExperience()]);
                    $manager          = new ManagerNiveauEtude();
                    $niveauEtude      = $manager->chercher(['idNiveauEtude' => $offre->getIdNiveauEtude()]);
                    if ($_SESSION['compte']['identifiant'] == "candidat") {
                        $manager = new ManagerCandidat();
                        $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
                        $manager  = new ManagerCandidature();
                        $candidature = $manager->chercher(['idOffre'    => $offre->getIdOffre(),
                                                           'idCandidat' => $candidat->getIdCandidat()]);
                    }
                    $resultat = [  
                        'offre'            => $offre, 
                        'entreprise'       => $entreprise, 
                        'sousDomaine'      => $sousDomaine, 
                        'domaine'          => $domaine, 
                        'contrat'          => $contrat, 
                        'niveauExperience' => $niveauExperience, 
                        'niveauEtude'      => $niveauEtude,
                        'candidature'      => $candidature
                    ];   
                }
                
            }
            return $resultat;
        }

    }