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
    use \Model\ManagerNiveauEntretien;    
    use \Model\ManagerEmailContact;   
    use \Model\ManagerInterlocuteur;   
    use \Model\ManagerInterlocuteurNiveauEntretien;
    use \Model\ManagerService;
    use \Model\ManagerPoste;
    use \Model\ManagerEntrepriseService;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerServicePoste;

    class ManagerModuleRecrutement extends DbManager
    {
        /*const STATUT_OFFRE = array("envoye", "accepte", "refuse");*/
        /** 
         * Lister les expériences 
         * 
         * @return array
         */
        public function listerDomaines()
        {
            $manager   = new ManagerDomaine(); 
            return $manager->lister();
        } 

        /** 
         * Lister les expériences 
         * 
         * @return array
         */
        public function listerExperiences()
        {
            $resultats     = array();
            $tabExperience = array();
            $manager       = new ManagerCandidat();
            $candidat      = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager       = new ManagerExperience(); 
                $experiences   = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($experiences)) {
                    foreach ($experiences as $experience) {
                        $manager         = new ManagerSousDomaine(); 
                        $sousDomaine     = $manager->chercher(['idSousDomaine' => $experience->getIdSousDomaine()]);
                        $tabExperience[] = [
                            'experience'  => $experience, 
                            'sousDomaine' => $sousDomaine
                        ];                      
                    }
                    $resultats = [
                        'candidat'    => $candidat, 
                        'experiences' => $tabExperience
                    ];
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les formations 
         * 
         * @return array
         */
        public function listerFormations()
        {
            $resultats  = array();
            $manager    = new ManagerCandidat();
            $candidat   = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager    = new ManagerFormation(); 
                $formations = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($formations)) {
                    foreach ($formations as $formation) {
                        $manager         = new ManagerSousDomaine(); 
                        $sousDomaine     = $manager->chercher(['idSousDomaine' => $formation->getIdSousDomaine()]);
                        $tabFormation[]  = [
                            'formation'   => $formation, 
                            'sousDomaine' => $sousDomaine
                        ];
                    }
                    $resultats = [
                        'candidat'   => $candidat, 
                        'formations' => $tabFormation
                    ];
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les diplômes 
         * 
         * @return array 
         */
        public function listerDiplomes()
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $candidat  = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $manager   = new ManagerDiplome(); 
                $diplomes  = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($diplomes)) {
                    foreach ($diplomes as $diplome) {
                        $manager      = new ManagerDomaine(); 
                        $domaine      = $manager->chercher(['idDomaine' => $diplome->getIdDomaine()]);
                        $manager      = new ManagerNiveauEtude();
                        $niveauEtude  = $manager->chercher(['idNiveauEtude' => $diplome->getIdNiveauEtude()]);
                        $tabDiplome[] = [
                            'diplome'     => $diplome, 
                            'domaine'     => $domaine, 
                            'niveauEtude' => $niveauEtude
                        ];
                    }
                    $resultats = [
                        'candidat' => $candidat, 
                        'diplomes' => $tabDiplome
                    ];
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les niveaux expériences 
         * 
         * @return array
         */
        public function listerNiveauxExperiences()
        {
            $manager = new ManagerNiveauExperience();
            return $manager->lister();
        }

        /** 
         * Lister les niveaux d'études 
         * 
         * @return array
         */
        public function listerNiveauxEtudes()
        {
            $manager = new ManagerNiveauEtude();
            return $manager->lister();
        }

        /** 
         * Lister les contrats 
         * 
         * @return array
         */
        public function listerContrats()
        {
            $manager = new ManagerContrat();
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
         * Lister les services 
         * 
         * @return array
         */
        public function listerServices()
        {
            $manager = new ManagerService();
            return $manager->lister();
        }

        /** 
         * Lister les postes 
         * 
         * @return array
         */
        public function listerPostes()
        {
            $manager = new ManagerPoste();
            return $manager->lister();
        }

        /** 
         * Lister les offres d'une entreprise
         *
         * @return array
         */
        public function listerOffres()
        {
            $tabOffre   = array();
            $resultats  = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager    = new ManagerOffre();
                $offres     = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager     = new ManagerSousDomaine();
                        $sousDomaine = $manager->chercher(['idSousDomaine' => $offre->getIdSousDomaine()]);
                        $manager     = new ManagerContrat();
                        $contrat     = $manager->chercher(['idContrat' => $offre->getIdContrat()]);
                        $tabOffre[]  = [
                            'offre'       => $offre, 
                            'sousDomaine' => $sousDomaine, 
                            'contrat'     => $contrat
                        ];                        
                    } 
                    $resultats = [
                        'entreprise' => $entreprise, 
                        'offres'     => $tabOffre
                    ];
                }
            }
            return $resultats;
        }

        /** 
         * Lister les candidatures d'un candidat 
         * 
         * @return array
         */
        public function listerCandidatures()
        {
            $resultats                = array();
            $parameters               = array();
            $manager                  = new ManagerCandidat();
            $candidat                 = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                  $parameters['idCandidat'] = $candidat->getIdCandidat();
                if (!empty($parameters)) {
                    $manager      = new ManagerCandidature();
                    $candidatures = $manager->lister($parameters);
                    if (!empty($candidatures)) {
                        foreach ($candidatures as $candidature) {
                            $manager         = new ManagerOffre();
                            $offre           = $manager->chercher(['idOffre' => $candidature->getIdOffre()]);
                            $manager         = new ManagerEntreprise();
                            $entreprise      = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
                            $manager         = new ManagerCandidat();
                            $candidat        = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                            $manager         = new ManagerEntretien();
                            $entretien       = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                            $manager         = new ManagerNiveauEntretien();
                            $niveauEntretien = "";
                            $interlocuteurs  = "";
                            if (!empty($entretien)) {
                                $niveauEntretien = $manager->chercher(['idNiveauEntretien' => $entretien->getIdNiveauEntretien()]);
                                if (!empty($niveauEntretien)) {
                                    $manager        = new ManagerInterlocuteur();
                                    $interlocuteurs = $manager->lister(['idNiveauEntretien' => $niveauEntretien->getIdNiveauEntretien()]);
                                }
                            }
                            $resultats[]     = [
                                'candidature'     => $candidature, 
                                'entretien'       => $entretien,
                                'offre'           => $offre, 
                                'entreprise'      => $entreprise,
                                'candidat'        => $candidat,
                                'niveauEntretien' => $niveauEntretien,
                                'interlocuteurs'  => $interlocuteurs
                            ];
                        }
                    }
                }       
            }  
            return $resultats;
        } 

        /** 
         * Lister les candidatures d'une offre
         * 
         * @param array $parameters critères des données à lister
         * 
         * @return array
         */
        public function listerOffreCandidatures($parameters)
        {
            $resultats  = array();
            $manager      = new ManagerCandidature();
            $candidatures = $manager->lister($parameters);
            if (!empty($candidatures)) {
                foreach ($candidatures as $candidature) {
                    $manager     = new ManagerEntretien();
                    $entretien   = $manager->chercher(['idCandidature' => $candidature->getIdCandidature()]);
                    $manager     = new ManagerCandidat();
                    $candidat    = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                    $profils     = $manager->specifierProfilCandidat(['idCandidat' => $candidature->getIdCandidat()]);
                    $manager     = new ManagerOffre();
                    $offre       = $manager->chercher($parameters);
                    $profilOffre = $manager->specifierProfilOffre(['idOffre' => $parameters['idOffre']]);
                    if (!empty($profils)) {
                        $experienceCandidat = array();
                        $diplomeCandidat    = array();
                        $tabCandidat        = [ 
                            'candidat'    => $candidat, 
                            'experience'  => $experienceCandidat,
                            'diplome'     => $diplomeCandidat
                        ];
                        extract($profils);
                        if (!empty($experiences)) {
                            foreach ($experiences as $experience) {
                                if ($experience['sousDomaine'] == $profilOffre['experience']['sousDomaine'] && $experience['annee'] >= $profilOffre['experience']['annee'] ) {
                                    $experienceCandidat = $experience;
                                    break;
                                }
                            }
                        }
                        if (!empty($diplomes)) {
                            foreach ($diplomes as $diplome) {
                                if ($diplome['domaine'] == $profilOffre['diplome']['domaine'] && $diplome['niveau'] >= $profilOffre['diplome']['niveau']) {
                                    $diplomeCandidat = $diplome;
                                    break;
                                } else if (!empty($experienceCandidat) && $diplome['domaine'] == $profilOffre['diplome']['domaine']) {
                                    $diplomeCandidat = $diplome;
                                    break;
                                }
                            }
                        } 
                        if (!empty($experienceCandidat) || !empty($diplomeCandidat)) {
                            $tabCandidat = [
                                'candidat'    => $candidat, 
                                'experience'  => $experienceCandidat,
                                'diplome'     => $diplomeCandidat
                            ];
                        }
                    }
                    $resultats[] = [
                        'candidat'    => $tabCandidat, 
                        'candidature' => $candidature,
                        'entretien'   => $entretien
                    ];
                }
            }
            return $resultats;
        } 

        /** 
         * Lister les entretiens 
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        public function listerEntretiens($parameters)
        {
            $resultats         = array();
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager           = new ManagerNiveauEntretien();
                $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                $minOrdre          = $manager->getMinOrdre($entreprise->getIdEntreprise());
                extract($minOrdre);
                if (!empty($niveauxEntretiens) && !isset($parameters['idNiveauEntretien'])) {
                    foreach ($niveauxEntretiens as $niveauEntretien) {
                        if ($niveauEntretien->getOrdre() == $minOrdre) {
                            $parameters['idNiveauEntretien'] = $niveauEntretien->getIdNiveauEntretien();
                        }
                    }
                }
                if (isset($parameters['idOffre'])) {
                    $resultats = [
                        'niveauxEntretiens' => $niveauxEntretiens,
                        'entretiens'        => $this->listerEntretiensParOffre($parameters)
                    ];
                } else {
                    $resultats = [
                        'niveauxEntretiens' => $niveauxEntretiens,
                        'entretiens'        => $this->listerEntretiensParEntreprise($parameters)
                    ];
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entretiens par offre
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        private function listerEntretiensParOffre($parameters)
        {
            $resultats    = array();
            $manager      = new ManagerOffre();
            $offre        = $manager->chercher(['idOffre' => $parameters['idOffre']]);
            $engagedPost  = $manager->getEngagedPoste(['idOffre' => $parameters['idOffre']]);
            $manager      = new ManagerEntretien();
            $entretiens   = $manager->lister(['idNiveauEntretien' => $parameters['idNiveauEntretien']]);
            if (!empty($entretiens)) {
                foreach ($entretiens as $entretien) {
                    $manager     = new ManagerCandidature();
                    $candidature = $manager->chercher(['idCandidature' => $entretien->getIdCandidature()]);
                    if (!empty($candidature) && $offre->getIdOffre() == $candidature->getIdOffre()) {
                        $manager     = new ManagerCandidat();
                        $candidat    = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                        $resultats[] = [
                            'engagedPost' => $engagedPost,
                            'offre'       => $offre,
                            'candidat'    => $candidat,
                            'entretien'   => $entretien
                        ];
                    }                            
                }
            }
            return $resultats;
        }

        /** 
         * Lister les entretiens d'une entreprise
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        private function listerEntretiensParEntreprise($parameters)
        {
            $resultats         = array();
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager           = new ManagerOffre();
                $offres            = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager      = new ManagerCandidature();
                        $candidatures = $manager->lister(['idOffre' => $offre->getIdOffre()]);
                        if (!empty($candidatures)) {
                            foreach ($candidatures as $candidature) {
                                $manager   = new ManagerCandidat();
                                $candidat  = $manager->chercher(['idCandidat' => $candidature->getIdCandidat()]);
                                $manager   = new ManagerEntretien();
                                $entretien = $manager->chercher(['idCandidature' => $candidature->getIdCandidature(), 'idNiveauEntretien' => $parameters['idNiveauEntretien']]);
                                if (!empty($entretien)) {
                                    $manager = new ManagerOffre();
                                    $engagedPost  = $manager->getEngagedPoste(['idOffre' => $offre->getIdOffre()]);
                                    $resultats[] = [
                                        'engagedPost' => $engagedPost,
                                        'offre'       => $offre,
                                        'candidat'    => $candidat,
                                        'entretien'   => $entretien
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les niveaux entretiens
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        public function listerNiveauxEntretiens()
        {
            $resultats  = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager           = new ManagerNiveauEntretien();
                $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
                if (!empty($niveauxEntretiens)) {
                    foreach ($niveauxEntretiens as $niveauEntretien) {
                        $resultats[] = $niveauEntretien;
                    }
                }
            }
            return $resultats;
        }

        /** 
         * Lister les services d'une entreprise
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        public function listerEntrepriseServices()
        {
            $services   = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager  = new ManagerEntrepriseService();
                $services = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            }
            return $services;
        }

        /** 
         * Lister les postes d'une entreprise
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        public function listerEntreprisePostes()
        {
            $postes     = array();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($entreprise)) {
                $manager  = new ManagerEntreprisePoste();
                $postes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            }
            return $postes;
        }

        /** 
         * Lister les offres suggérés à un candidat
         *
         * @return array
         */
        public function listerSuggestOffres()
        {
            $resultats      = Array();
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $profilCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                extract($profilCandidat);
                $managerOffre = new ManagerOffre();
                $offres       = $managerOffre->lister(null);
                if (!empty($offres)) {
                    foreach ($offres as $offre) {
                        $manager = new ManagerCandidature();
                        $candidature = $manager->chercher(['idOffre' => $offre->getIdOffre(), 'idCandidat' => $candidat->getIdCandidat()]);
                        if (empty($candidature)) {
                            if ($offre->getDateLimite() >= date('Y-m-d')) {
                                $profilOffre = $managerOffre->specifierProfilOffre(['idOffre' => $offre->getIdOffre()]);
                                if (!empty($experiences)) {
                                    foreach ($experiences as $experience) {
                                        if ($profilOffre['experience']['sousDomaine'] == $experience['sousDomaine'] && $profilOffre['experience']['annee'] <= $experience['annee']) {
                                            echo "test"; exit();
                                            $resultats[] = $this->voirDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                } else if (!empty($diplomes)) {
                                    foreach ($diplomes as $diplome) {
                                        if ($profilOffre['diplome']['domaine'] == $diplome['domaine'] && $profilOffre['diplome']['niveau'] <= $diplome['niveau']) {
                                            $resultats[] = $this->voirDetailOffre(['idOffre' => $offre->getIdOffre()]);
                                        }
                                    }
                                }
                            }
                        }
                        
                    }
                }                
                return $this->trierOffres($candidat, $resultats);
            }
            return "";
        }

        /**
         * Trier les offres correspondantes à un candidat
         *
         * @param object $candidat Le candidat concerné
         * @param array $offre Les offres correspondantes
         *
         * @return array
         */
        public function trierOffres($candidat, $offres)
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            $tabPerso  = $manager->getPersonnalitesCandidat(['idCandidat' => $candidat->getIdCandidat()]);
            if (!empty($offres)) {
                foreach ($offres as $offre) {
                    $manager       = new ManagerOffre();
                    $personnalites = $manager->getPersonnalitesOffre(['idOffre' => $offre['offre']->getIdOffre()]);
                    $length        = count(array_intersect($tabPerso, $personnalites));
                    $resultats[]   = [
                        'priori'      => $length, 
                        'offre'       => $offre
                    ];
                }
            }
            if (!empty($resultats)) {
                // Tri d'un tableau à partir d'une de ses clés
                foreach ($resultats as $key => $row) {
                    $priori[$key]  = $row['priori'];
                }            
                array_multisort($priori, SORT_DESC, $resultats);
            }
            return $resultats;

        }

        /** 
         * Lister les candidats suggérés à une offre
         *
         * @param array $parameters Critères des données à lister
         *
         * @return array
         */
        public function listerSuggestCandidats($parameters)
        {
            $resultats    = array();
            $manager      = new ManagerOffre();
            $offre        = $manager->chercher($parameters);
            $profilOffre  = $manager->specifierProfilOffre(['idOffre' => $parameters['idOffre']]);
            $manager      = new ManagerCompte();
            $compteActifs = $manager->lister(['identifiant' => 'candidat', 'statut' => 'active']);
            if (!empty($compteActifs) && !empty($offre)) {
                foreach ($compteActifs as $actif) {
                    $manager         = new ManagerCandidat();
                    $candidat        = $manager->chercher(['idCompte' => $actif->getIdCompte()]);
                    $profilsCandidat = $manager->specifierProfilCandidat(['idCandidat' => $candidat->getIdCandidat()]);
                    $manager         = new ManagerCandidature();
                    $candidature     = $manager->chercher(['idCandidat' => $candidat->getIdCandidat(), 'idOffre' => $offre->getIdOffre()]);
                    if (!empty($profilsCandidat) && $candidat->getPublique() == 1 && empty($candidature)) {
                        $experienceCandidat = "";
                        $diplomeCandidat    = "";
                        extract($profilsCandidat);
                        if (!empty($experiences)) {
                            foreach ($experiences as $experience) {
                                if ($experience['sousDomaine'] == $profilOffre['experience']['sousDomaine'] /*&& $experience['annee'] >= $profilOffre['experience']['annee']*/ ) {
                                    $experienceCandidat = $experience;
                                    break;
                                }
                            }
                        }
                        if (!empty($diplomes)) {
                            foreach ($diplomes as $diplome) {
                                if ($diplome['domaine'] == $profilOffre['diplome']['domaine'] && $diplome['niveau'] >= $profilOffre['diplome']['niveau']) {
                                    $diplomeCandidat = $diplome;
                                    break;
                                } else if (!empty($experienceCandidat) && $diplome['domaine'] == $profilOffre['diplome']['domaine']) {
                                    $diplomeCandidat = $diplome;
                                    break;
                                }
                            }
                        } 
                        if (!empty($experienceCandidat) || !empty($diplomeCandidat)) {
                            $resultats[] = [
                                'candidat'   => $candidat, 
                                'experience' => $experienceCandidat,
                                'diplome'    => $diplomeCandidat
                            ];
                        }
                    }
                }
            }
            return $this->trierCandidats($offre, $resultats);
        }

        /**
         * Trier les candidats correspondants à une offre
         * 
         * @param objet $offre L'offre concerné
         * @param array $candidats Les candidats correspondants
         *
         * @return array
         */
        public function trierCandidats($offre, $candidats)
        {
            $resultats = array();
            $manager   = new ManagerCandidat();
            if (!empty($candidats)) { 
                foreach ($candidats as $candidat) {
                    $length = $candidat['diplome']['niveau'];
                    if (!empty($candidat['experience'])) {
                        $niveauExperience = (int)$candidat['experience']['annee'];
                     } else {
                        $niveauExperience = 0;
                     }
                    $resultats[] = [
                        'priori'   => $length, 
                        'candidat' => $candidat, 
                        'niveau'   => $niveauExperience
                    ];
                }
            } 
            if (!empty($resultats)) {
                // Tri d'un tableau à partir d'une de ses clés
                foreach ($resultats as $key => $row) {
                    $priori[$key] = $row['priori'];
                    $niveau[$key] = $row['niveau'];
                }
                $filtre = array_filter($niveau);
                if (!empty($filtre)) {
                    array_multisort($niveau, SORT_DESC, $resultats);
                } else {
                    array_multisort($priori, SORT_DESC, $resultats);
                }
                
            }
            return $resultats;
        }

        /** 
         * Lister les interlocuteurs
         * 
         * @param array $parameters Critères des données à lister
         * 
         * @return array
         */
        public function listerInterlocuteurs()
        {
            $resultats      = array();
            $manager        = new ManagerInterlocuteur();
            return $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
        }

        /** 
         * Afficher la formulaire d'un domaine
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
         */
        public function afficherFormDomaine($parameters)
        {
            $manager = new ManagerDomaine();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'un sous domaine
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
         */
        public function afficherFormSousdomaine($parameters)
        {
            $manager = new ManagerSousDomaine();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            }
        } 

        /** 
         * Afficher la formulaire d'une experience
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
         */
        public function afficherFormExperience($parameters)
        {
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager  = new ManagerExperience();
            if (isset($parameters)) {
                $experience = $manager->chercher($parameters);
            } else {
                $experience = $manager->initialiser();
            }
            $manager      = new ManagerSousDomaine();
            $sousDomaines = $manager->lister(null);
            $manager      = new ManagerDomaine();
            $domaines     = $manager->lister(null);
            return [
                'candidat'     => $candidat, 
                'experience'   => $experience, 
                'sousDomaines' => $sousDomaines,
                'domaines'     => $domaines
            ];
        }

        /** 
         * Afficher la formulaire d'une formation
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
         */
        public function afficherFormFormation($parameters)
        {
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager  = new ManagerFormation();
            if (isset($parameters)) {
                $formation = $manager->chercher($parameters);
            } else {
                $formation = $manager->initialiser();
            }
            $manager      = new ManagerSousDomaine();
            $sousDomaines = $manager->lister(null);
            $manager      = new ManagerDomaine;
            $domaines     = $manager->lister(null);
            return [
                'candidat'     => $candidat, 
                'formation'    => $formation, 
                'sousDomaines' => $sousDomaines,
                'domaines'     => $domaines
            ];
        } 

        /** 
         * Afficher la formulaire d'une diplôme
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
         */
        public function afficherFormDiplome($parameters)
        {
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager  = new ManagerDiplome();
            if (isset($parameters)) {
                $diplome = $manager->chercher($parameters);
            } else {
                $diplome = $manager->initialiser();
            }
            $manager       = new ManagerDomaine();
            $domaines      = $manager->lister();
            $manager       = new ManagerNiveauEtude();
            $niveauxEtudes = $manager->lister();
            return [
                'candidat'      => $candidat, 
                'diplome'       => $diplome, 
                'domaines'      => $domaines, 
                'niveauxEtudes' => $niveauxEtudes];
        } 

        /** 
         * Afficher la formulaire d'un niveau d'expérience
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return object
         */
        public function afficherFormNiveauExperience($parameters)
        {
            $manager  = new ManagerNiveauExperience();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'un niveau d'étude
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormNiveauEtude($parameters)
        {
            $manager  = new ManagerNiveauEtude();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'un contrat
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormContrat($parameters)
        {
            $manager  = new ManagerContrat();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'une personnalite
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormPersonnalite($parameters)
        {
            $manager  = new ManagerPersonnalite();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'un service
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormService($parameters)
        {
            $manager  = new ManagerService();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'un service d'une entreprise
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormEntrepriseService($parameters)
        {
            $resultat    = array();
            $manager     = new ManagerService();
            $allServices = $manager->lister();
            $manager     = new ManagerEntreprise();
            $entreprise  = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager     = new ManagerEntrepriseService();
            if (!empty($entreprise)) {
                if (isset($parameters)) {
                    $entrepriseService = $manager->chercher($parameters);
                } else {
                    $entrepriseService = $manager->initialiser();
                } 
                $resultat = [
                    "entreprise"        => $entreprise,
                    "allServices"       => $allServices,
                    "entrepriseService" => $entrepriseService
                ];
            }
            return $resultat;
        } 

        /** 
         * Afficher la formulaire d'un poste d'une entreprise
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormEntreprisePoste($parameters)
        {
            $manager    = new ManagerPoste();
            $allPostes  = $manager->lister();
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager    = new ManagerEntreprisePoste();
            if (isset($parameters)) {
                $entreprisePoste = $manager->chercher($parameters);
            } else {
                $entreprisePoste = $manager->initialiser();
            } 
            return [
                "entreprise"      => $entreprise,
                "allPostes"       => $allPostes,
                "entreprisePoste" => $entreprisePoste
            ];
        } 

        /** 
         * Afficher la formulaire d'un poste d'un service
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormServicePoste($parameters)
        {
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);          
            $manager = new ManagerServicePoste();
            if (isset($parameters)) {
                $servicePoste = $manager->chercher($parameters);
            } else {
                $servicePoste = $manager->initialiser();
            } 
            $manager   = new ManagerEntreprisePoste();
            $allPostes = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $entreprisePoste = $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]);
            return [
                "entreprisePoste" => $entreprisePoste,
                "servicePoste"    => $servicePoste,
                "allPostes"       => $allPostes
            ];
        } 

        /** 
         * Afficher la formulaire d'un poste
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Object
         */
        public function afficherFormPoste($parameters)
        {
            $manager  = new ManagerPoste();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Afficher la formulaire d'une offre
         *
         * @param array $parameters Les données à récupérer
         * 
         * @return array 
         */
        public function afficherFormOffre($parameters)
        {
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager    = new ManagerOffre();
            if (isset($parameters)) {
                $offre = $manager->chercher($parameters);
            } else {
                $offre = $manager->initialiser();
            }
            $manager            = new ManagerSousDomaine();
            $sousDomaines       = $manager->lister(null);
            $manager            = new ManagerDomaine();
            $domaines           = $manager->lister();
            $manager            = new ManagerContrat();
            $contrats           = $manager->lister();
            $manager            = new ManagerNiveauExperience();
            $niveauxExperiences = $manager->lister();
            $manager            = new ManagerNiveauEtude();
            $niveauxEtudes      = $manager->lister();
            $manager            = new ManagerPersonnalite();
            $personnalites      = $manager->lister();
            /*$manager            = new ManagerServicePoste();
            $allPostes          = $manager->lister();*/
            return [ 
                'entreprise'         => $entreprise, 
                'offre'              => $offre, 
                'sousDomaines'       => $sousDomaines,
                'domaines'           => $domaines, 
                'contrats'           => $contrats, 
                'niveauxExperiences' => $niveauxExperiences, 
                'niveauxEtudes'      => $niveauxEtudes, 
                'personnalites'      => $personnalites
            ];
        }

        /** 
         * Afficher la formulaire d'une entretien
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
         */
        public function afficherFormEntretien($parameters)
        {
            $resultat          = array();
            $entretien         = "";
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager           = new ManagerNiveauEntretien();
            $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $minOrdre          = $manager->getMinOrdre($entreprise->getIdEntreprise());
            extract($minOrdre);
            $niveauEntretien   = $manager->chercher(['ordre' => $minOrdre]);
            if (!empty($niveauEntretien)) {
                    $_SESSION['variable']['idNiveauEntretien'] = $niveauEntretien->getIdNiveauEntretien();
            }
            $manager = new ManagerEntretien();
            if (isset($parameters['idEntretien'])) {
                $entretien = $manager->chercher($parameters);
            } else {
                if (isset($parameters['idCandidature'])) {
                    $_SESSION['variable']['idCandidature'] = $parameters['idCandidature'];
                }
                $entretien = $manager->initialiser();
            } 
            if (!empty($niveauxEntretiens)) {
                $resultat = [
                    "niveauxEntretiens" => $niveauxEntretiens,
                    "entretien"         => $entretien
                ];
            }
            return $resultat;
        } 

        /** 
         * Afficher la formulaire d'un niveau entretien
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return array
         */
        public function afficherFormNiveauEntretien($parameters)
        {
            $niveauEntretien = "";
            $manager         = new ManagerEntreprise();
            $entreprise      = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]); 
            $manager         = new ManagerNiveauEntretien();
            if (isset($parameters)) {
                $niveauEntretien = $manager->chercher($parameters);
            } else {
                $niveauEntretien = $manager->initialiser();
            } 
            return [
                    'entreprise'      => $entreprise,
                    'niveauEntretien' => $niveauEntretien
            ];
        } 

        /** 
         * Afficher la formulaire d'un interlocuteur
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
         */
        public function afficherFormInterlocuteur($parameters)
        {
            $manager = new ManagerInterlocuteur();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            }
        } 

        /** 
         * Mettre à jour un domaine
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourDomaine($parameters)
        {
            $manager = new ManagerDomaine();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un sous domaine
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourSousdomaine($parameters)
        {
            $manager = new ManagerSousDomaine();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Vérifier l'éxistance du parametre sous domaine
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
         */
        private function verifierParamsSousDomaine($parameters)
        {
            $sousDomaine = "";
            $idDomaine   = "";
            if ($parameters['idSousDomaine'] == "autre" && $parameters['nomSousDomaine'] != "") {
                $domaine   = "";
                $idDomaine = $parameters['idDomaine'];
                if ($idDomaine == "autre" && $parameters['nomDomaine'] != "") {
                    $manager = new ManagerDomaine();
                    $domaine = $manager->chercher(['nomDomaine' => $parameters['nomDomaine']]);
                    if (empty($domaine)) {
                        $domaine = $manager->ajouter(['nomDomaine' => $parameters['nomDomaine']]);
                    }
                    $idDomaine = $domaine->getIdDomaine();
                }
                $manager     = new ManagerSousDomaine();
                $sousDomaine = $manager->chercher(['nomSousDomaine' => $parameters['nomSousDomaine']]);
                if (empty($sousDomaine)) {
                    $sousDomaine = $manager->ajouter(['idDomaine'      => $idDomaine, 
                                                      'nomSousDomaine' => $parameters['nomSousDomaine']]
                                                    );
                }
                $parameters['idSousDomaine'] = $sousDomaine->getIdSousDomaine();         
            }             
            unset($parameters['nomSousDomaine']);
            unset($parameters['idDomaine']);
            unset($parameters['nomDomaine']);
            return $parameters;
        }

        /** 
         * Mettre à jour une experience
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourExperience($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $manager    = new ManagerExperience();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour une formation
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourFormation($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $manager    = new ManagerFormation();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Vérifier l'éxistance du parametre domaine
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
         */
        private function verifierParamsDomaine($parameters)
        {
            $domaine = "";
            if ($parameters['idDomaine'] == "autre" && $parameters['nomDomaine'] != "") {
                $manager = new ManagerDomaine();
                $domaine = $manager->chercher(['nomDomaine' => $parameters['nomDomaine']]);
                if (empty($domaine)) {
                    $domaine = $manager->ajouter(['nomDomaine' => $parameters['nomDomaine']]);
                }
                $parameters['idDomaine'] = $domaine->getIdDomaine();    
            }
            unset($parameters['nomDomaine']);
            return $parameters;
        }

        /** 
         * Vérifier l'éxistance du parametre d'un niveau d'étude
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
         */
        private function verifierParamsNiveauEtude($parameters)
        {
            $niveauEtude = "";
            if ($parameters['idNiveauEtude'] == "autre" && $parameters['niveau'] != "") {
                $manager = new ManagerNiveauEtude();
                $niveauEtude = $manager->chercher(['niveau' => $parameters['niveau']]);
                if (empty($niveauEtude)) {
                    $niveauEtude = $manager->ajouter(['niveau' => $parameters['niveau']]);
                }
                $parameters['idNiveauEtude'] = $niveauEtude->getIdNiveauEtude();      
            }
            unset($parameters['niveau']);
            return $parameters;
        }

        /** 
         * Vérifier l'éxistance du parametre contrat
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
         */
        private function verifierParamsContrat($parameters)
        {
            $contrat = "";
            if ($parameters['idContrat'] == "autre" && $parameters['designation'] != "") {
                $manager = new ManagerContrat();
                $contrat = $manager->chercher(['designation' => $parameters['designation']]);
                if (empty($contrat)) {
                    $contrat = $manager->ajouter(['designation' => $parameters['designation']]);
                }
                $parameters['idContrat'] = $contrat->getIdContrat();      
            }
            unset($parameters['designation']);
            return $parameters;
        }

        /** 
         * Vérifier l'éxistance du parametre d'un niveau d'expérience
         * 
         * @param array $parameters Les paramètres à vérifier
         *
         * @return array 
         */
        private function verifierParamsNiveauExperience($parameters)
        {
            $niveauExperience = "";
            if ($parameters['idNiveauExperience'] == "autre" && $parameters['niveauExperience'] != "") {
                $manager = new ManagerNiveauExperience();
                $niveauExperience = $manager->chercher(['niveau' => $parameters['niveauExperience']]);
                if (empty($niveauExperience)) {
                    if (is_numeric($parameters['niveauExperience']) && $parameters['niveauExperience'] != "0") {
                        $parameters['niveauExperience'] = $parameters['niveauExperience'] . " ans";
                    }
                    $niveauExperience = $manager->ajouter(['niveau' => $parameters['niveauExperience']]);
                }
                $parameters['idNiveauExperience'] = $niveauExperience->getIdNiveauExperience();      
            }
            unset($parameters['niveauExperience']);
            return $parameters;
        }

        /** 
         * Mettre à jour un diplôme
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourDiplome($parameters)
        {
            $parameters = $this->verifierParamsDomaine($parameters);
            $parameters = $this->verifierParamsNiveauEtude($parameters);
            $manager = new ManagerDiplome();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un niveau d'expérience
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourNiveauExperience($parameters)
        {
            $manager = new ManagerNiveauExperience();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour un niveau d'étude
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourNiveauEtude($parameters)
        {
            $manager = new ManagerNiveauEtude();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un contrat
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourContrat($parameters)
        {
            $manager = new ManagerContrat();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour une personnalite
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourPersonnalite($parameters)
        {
            $manager = new ManagerPersonnalite();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un service
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourService($parameters)
        {
            $parameters['nomService'] = strtolower($parameters['nomService']);
            $manager = new ManagerService();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un poste
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourPoste($parameters)
        {
            $parameters['nomPoste'] = strtolower($parameters['nomPoste']);
            $manager = new ManagerPoste();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        }

        /** 
         * Mettre à jour une offre
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourOffre($parameters)
        {
            $parameters = $this->verifierParamsSousDomaine($parameters);
            $parameters = $this->verifierParamsContrat($parameters); 
            $parameters = $this->verifierParamsNiveauExperience($parameters);
            $parameters = $this->verifierParamsNiveauEtude($parameters);
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            if (!empty($parameters['autrePersonnalite'])) {
                $qualites = explode("_", $parameters['autrePersonnalite']);
                foreach ($qualites as $qualite) {
                    if (!empty($qualite)) {
                        $manager = new ManagerPersonnalite();
                        $search  = $manager->chercher(['qualite' => $qualite]);
                        if (empty($search)) {
                            $manager->ajouter(['qualite' => ucfirst($qualite)]);
                        }
                    }
                }
            }
            unset($parameters['autrePersonnalite']);
            unset($parameters['qualite']);
            $manager = new ManagerOffre();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un entretien
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourEntretien($parameters)
        {
            $entretien = "";
            $action    = "";
            $manager   = new ManagerEntretien();
            if (reset($parameters) == "") {
                $action               = "ajouter";
                $entretien = $manager->ajouter($parameters);
                $_SESSION['info']['success'] = "Entretien fixé avec succès";
            } else {
                $action    = "modifier";
                $entretien = $manager->chercher(['idEntretien' => $parameters['idEntretien']]);
                if (!empty($parameters['idNiveauEntretien'])/* && $entretien->getIdNiveauEntretien() != $parameters['idNiveauEntretien']*/) {
                    $parameters['statut']  = "en attente";
                    $parameters['nbFois'] += 1;
                }
                $manager->modifier($parameters);
                $entretien = $manager->chercher(['idEntretien' => $parameters['idEntretien']]);
            }
            $this->envoyerEmailEntretien($entretien, $action);
            return $entretien;
        } 

        private function getInterlocuteurs($parameters)
        {
            $interlocuteurs = array();
            $manager = new ManagerInterlocuteurNiveauEntretien();
            $responses = $manager->lister($parameters);
            if (!empty($responses)) {
                foreach ($responses as $response) {
                    $manager = new ManagerInterlocuteur();
                    $interlocuteurs[] = $manager->chercher(['idInterlocuteur' => $response->getIdInterlocuteur()]);
                }
            }
            return $interlocuteurs;
        }

        /** 
         * Envoyer un email sur un entretien 
         *
         * @param object $entretien la candidature concernée
         * @param string $action l'action faite sur l'entretien
         *
         * @return empty
         */
        private function envoyerEmailEntretien($entretien, $action)
        {
            $to             = "";
            $subject        = "";
            $message        = "";
            $headers        = "";
            $nbFois         = "";
            $manager        = new ManagerCandidature();
            $candidature    = $manager->chercher(["idCandidature" => $entretien->getIdCandidature()]);
            $manager        = new ManagerNiveauEntretien();
            $nivEntretien   = $manager->chercher(["idNiveauEntretien" => $entretien->getIdNiveauEntretien()]);
            if ($nivEntretien) {
                $interlocuteurs = $this->getInterlocuteurs(['idNiveauEntretien' => $nivEntretien->getIdNiveauEntretien()]);
            }
            $manager        = new ManagerCandidat();
            $candidat       = $manager->chercher(["idCandidat" => $candidature->getIdCandidat()]);
            $manager        = new ManagerOffre();
            $offre          = $manager->chercher(["idOffre" => $candidature->getIdOffre()]);
            $manager        = new ManagerEntreprise();
            $entreprise     = $manager->chercher(["idEntreprise" => $offre->getIdEntreprise()]);
            $manager        = new ManagerEmailContact();
            $emails         = $manager->lister();
            $cvCandidat     = $this->voirDetailCvCandidat(["idCandidat" => $candidat->getIdCandidat()]);
            if ($entretien->getStatut() == "en attente") {
                if (!empty($emails)) {
                    if ($entretien->getNbFois() > 1) {
                        $nbFois = $entretien->getNbFois() ."ème";
                    } else {
                        $nbFois = $entretien->getNbFois() . "er";
                    }
                    foreach ($emails as $email) {
                        if ($email->getType() == "information") {                            
                            if ($_GET['page'] != "manage/cancel-entretien") {
                                $subject = "Réponse d'une candidature envoyée";
                                $message = "<html><body>
                                                <div class='container'>
                                                    <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                    <label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
                                                    <label>
                                                      Nous avons le plaisir de vous informer que votre candidature a été consultée <br> par la société " . $entreprise->getNom() . "
                                                    </label><br><br>
                                                    <label>
                                                        Elle vous donne un rendez-vous pour un " . $nbFois . " entretien ce " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " prochain à " . $entretien->getHeure() . "<br> au " .
                                                        $entretien->getLieu() . ".
                                                    </label><br><br>";
                                if (!empty($interlocuteurs)) {
                                    foreach ($interlocuteurs as $interlocuteur) {
                                        $collaborateurs .= $interlocuteur->getCivilite() . " " . ucwords($interlocuteur->getNom()) . ", ";
                                    }
                                    $message .= "<label>
                                                    Votre entretien sera avec " . substr($collaborateurs, 0, -2) . "
                                                </label><br><br>";
                                }
                                $message .= "<label>Cordialement, </label><br><br>
                                            <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label></div></body></html>";
                                $to = $candidat->getEmail();                            
                                $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                                mail($to, $subject, $message, $headers);
                                if (!empty($interlocuteurs)) {
                                    $subject = "Information pour un entretien";
                                    $headers = "Content-type: text/html" . "\r\n" . "From: " . $entreprise->getEmail();
                                    foreach ($interlocuteurs as $interlocuteur) {
                                        $to      = $interlocuteur->getEmail();
                                        $message = "<html><body>
                                                    <div class='container'>
                                                        <label>Bonjour</label><br><br>
                                                        <label>" .
                                                            $interlocuteur->getCivilite() . " " . ucwords($interlocuteur->getNom()) . ", on vous informe que vous recevrez un entretien <br>
                                                            avec " . $candidat->getCivilite() . " " . strtoupper($candidat->getNom()) . " " . ucwords($candidat->getPrenom()) . " ce " .
                                                            date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " à " . $entretien->getHeure() . ".
                                                        </label><br><br>
                                                        <label>
                                                          Merci de noter votre entretien.
                                                        </label><br>
                                                        <label>Cordialement, </label><br><br>
                                                    </div>
                                                </body></html>";
                                        mail($to, $subject, $message, $headers);
                                    }
                                }
                            } else {
                                $subject = "Information sur un entretien";
                                $message = "<html><body>
                                                <div class='container'>
                                                    <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                    <label>
                                                      Nous avons le plaisir de vous informer que le statut de votre entretien du " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " au  " .
                                                        $entretien->getLieu() . " <br> a été modifié par la société " . $entreprise->getNom() . "
                                                    </label><br><br>
                                                    <label>
                                                        Pour plus d'information, veuillez-vous connecter sur le site en cliquant <a href='" . HOST . "/connexion'>ici</a>
                                                    </label><br><br>
                                                    <label>Cordialement, </label><br><br>
                                                    <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label>
                                            </div></body></html>";
                                $to = $candidat->getEmail();                            
                                $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                                mail($to, $subject, $message, $headers);
                            }
                            
                        }
                    }
                }
            }
            else if ($entretien->getStatut() == "valide") {
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($email->getType() == "information") {
                            $to = $candidat->getEmail();
                            $subject = "Réponse d'une entretien passée";
                            $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                <label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
                                                <label>
                                                  Nous avons le plaisir de vous informer que votre entretien du " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " au <br>" .
                                                  $entretien->getLieu() . " a été validée par la société " . $entreprise->getNom() . "
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label></div>
                                        </body></html>";
                            mail($to, $subject, $message, $headers);
                        }
                    }
                }
            }
            else if ($entretien->getStatut() == "rejete") {
                if (!empty($emails)) {
                    foreach ($emails as $email) {
                        if ($email->getType() == "information") {
                            $to = $candidat->getEmail();
                            $subject = "Réponse d'une entretien passée";
                            $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . ucwords($candidat->getPrenom()) . ", </label><br><br>
                                                <label>Bienvenu sur notre nouvelle plateforme.</label><br><br>
                                                <label>
                                                  Nous avons le regret de vous informer que votre entretien passée le " . date("d/m/Y", strtotime(str_replace("-", "/", $entretien->getDate()))) . " au <br>" .
                                                  $entretien->getLieu() . " a été rejetée par la société " . $entreprise->getNom() . "
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label></div>
                                        </body></html>";
                            mail($to, $subject, $message, $headers);
                        }
                    }
                }
            }
        }

        /** 
         * Mettre à jour un niveau d'entretien
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourNiveauEntretien($parameters)
        {
            $manager = new ManagerNiveauEntretien();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un service d'une entreprise
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourEntrepriseService($parameters)
        {
            unset($parameters['nomService']);
            $parameters['service'] = strtolower($parameters['service']);
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager = new ManagerEntrepriseService();
                $exist = $manager->chercher(['service' => $parameters['service'], 'idEntreprise' => $entreprise->getIdEntreprise()]);
                if (empty($exist)) {
                    if (reset($parameters) == "") {
                        $manager->ajouter($parameters);
                        $_SESSION['info']['success'] = "Service ajouté avec succès";
                    } else {
                        $manager->modifier($parameters);
                        $_SESSION['info']['success'] = "Service modifié avec succès";
                    }
                } else {
                    $_SESSION['info']['danger'] = ucfirst($parameters['service']) . " est déjà dans la liste";
                }
            }
        } 

        /** 
         * Mettre à jour un poste d'une entreprise
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourEntreprisePoste($parameters)
        {
            unset($parameters['nomPoste']);
            $parameters['poste'] = strtolower($parameters['poste']);
            $manager = new ManagerEntreprise();
            $entreprise = $manager->chercher(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            if (!empty($entreprise)) {
                $manager = new ManagerEntreprisePoste();
                $exist = $manager->chercher(['poste' => $parameters['poste'], 'idEntreprise' => $entreprise->getIdEntreprise()]);
                if (empty($exist)) {
                    if (reset($parameters) == "") {
                        $manager->ajouter($parameters);
                        $_SESSION['info']['success'] = "Poste ajouté avec succès";
                    } else {
                        $manager->modifier($parameters);
                        $_SESSION['info']['success'] = "Poste modifié avec succès";
                    }
                } else {
                    $_SESSION['info']['danger'] = ucfirst($parameters['poste']) . " est déjà dans la liste";
                }
            }
            
        } 

        /** 
         * Modifier le niveau d'entretien 
         * 
         * @param array $parameters Les données à modifier
         *
         * @return Object
         */
        public function modifierNiveauEntretien($parameters)
        {
            $resultat          = "";
            $manager           = new ManagerEntretien();
            $entretien         = $manager->chercher($parameters);
            $manager           = new ManagerEntreprise();
            $entreprise        = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            $manager           = new ManagerNiveauEntretien();
            $niveauxEntretiens = $manager->lister(['idEntreprise' => $entreprise->getIdEntreprise()]);
            $nivEntretien      = $manager->chercher(['idNiveauEntretien' => $entretien->getIdNiveauEntretien()]);
            if (!empty($niveauxEntretiens)) {
                foreach ($niveauxEntretiens as $niveauEntretien) {
                    if ($nivEntretien->getOrdre() < $niveauEntretien->getOrdre()) {
                        $resultat = $niveauEntretien; break;
                    }
                }
            }
            return $resultat;
        } 

        /** 
         * Mettre à jour un interlocuteur
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourInterlocuteur($parameters)
        {
            $manager = new ManagerInterlocuteur();
            if (reset($parameters) == "") {
                $interlocuteur = $manager->ajouter($parameters);
                if (isset($_SESSION['variable']['idNiveauEntretien'])) {
                    $attributes['idInterlocuteur'] = (int)$interlocuteur;
                    $attributes['idNiveauEntretien'] = $_SESSION['variable']['idNiveauEntretien'];
                    $manager = new ManagerInterlocuteurNiveauEntretien();
                    $manager->ajouter($attributes);
                }
            } else {
                $manager->modifier($parameters);
            }
        } 

        /** 
         * Mettre à jour un interlocuteur d'un niveau d'entretien
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourInterlocuteurNiveauEntretien($parameters)
        {
            $parameters['idNiveauEntretien'] = $_SESSION['variable']['idNiveauEntretien'];
            $manager = new ManagerInterlocuteurNiveauEntretien();
            $data = $manager->chercher($parameters);
            if (empty($data)) {
                $manager->ajouter($parameters);
                $_SESSION['info']['success'] = "Interlocuteur ajouté avec succès";
            } else {
                $manager = new ManagerInterlocuteur();
                $interlocuteur = $manager->chercher(['idInterlocuteur' => $data->getIdInterlocuteur()]);
                $_SESSION['info']['danger'] = ucwords($interlocuteur->getNom()) . " est déjà dans la liste";
            }
        } 

        /** 
         * Mettre à jour un poste d'un service
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourServicePoste($parameters)
        {
            $parameters['idEntrepriseService'] = $_SESSION['variable']['idEntrepriseService'];
            if ($parameters['idEntreprisePoste'] == "autre") {
                $manager = new ManagerEntreprisePoste();
                $exist = $manager->chercher(['poste' => strtolower($parameters['poste']), 'idEntreprise' => $_SESSION['user']['idEntreprise']]);
                if (empty($exist)) {
                    $exist = $manager->ajouter(['poste' => strtolower($parameters['poste']), 'idEntreprise' => $_SESSION['user']['idEntreprise']]);
                    $parameters['idEntreprisePoste'] = $exist->getIdEntreprisePoste();
                }
            }
            unset($parameters['poste']);
            $manager = new ManagerServicePoste();
            $exist = $manager->chercher(['idEntrepriseService' => $parameters['idEntrepriseService'], 'idEntreprisePoste' => $parameters['idEntreprisePoste']]);
            if (empty($exist)) {
                if (reset($parameters) == "") {
                    $manager->ajouter($parameters);
                    $_SESSION['info']['success'] = "Poste ajouté avec succès";
                } else {
                    $manager->modifier($parameters);
                    $_SESSION['info']['success'] = "Poste modifié avec succès";
                }
            } else {
                $_SESSION['info']['danger'] = "Le poste est déjà dans la liste";
            }
            
        }

        /** 
         * Voir le détail d'un domaine 
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
         */
        public function voirDetailDomaine($parameters)
        {
            $resultat       = array(); 
            $tabSousDomaine = array();
            $manager        = new ManagerDomaine();
            $domaine        = $manager->chercher($parameters);
            if (!empty($domaine)) {
                $manager = new ManagerSousDomaine();
                $sousDomaines = $manager->lister(['idDomaine' => $domaine->getIdDomaine()]);
                if (!empty($sousDomaines)) {
                    foreach ($sousDomaines as $sousDomaine) {
                        $tabSousDomaine[] = $sousDomaine;
                    }
                    $resultat = [
                        'domaine'      => $domaine, 
                        'sousDomaines' => $tabSousDomaine
                    ];
                } else {
                    $resultat = ['domaine' => $domaine];
                }
            }
            return $resultat;
        }

        /** 
         * Voir le détail d'une offre 
         * 
         * @param array $parameters Critères des données à voir  
         * 
         * @return array 
         */
        public function voirDetailOffre($parameters)
        {
            $resultat    = array(); 
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher($parameters);
            $candidature = "";
            if (!empty($offre)) {
                $manager  = new ManagerEntreprise();
                $entreprise       = $manager->chercher(['idEntreprise' => $offre->getIdEntreprise()]);
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
                $manager          = new ManagerCandidature();
                if ($_SESSION['compte']['identifiant'] == "candidat") {
                    $manager = new ManagerCandidat();
                    $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
                    $manager  = new ManagerCandidature();
                    $candidature = $manager->chercher(['idOffre'    => $offre->getIdOffre(),
                                                       'idCandidat' => $candidat->getIdCandidat()]);
                }
                $resultat         = [  
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
            return $resultat;
        }

        /** 
         * Voir le détail d'un candidat suggéré 
         * 
         * @param array $parameters Critères des données à voir
         * 
         * @return array 
         */
        public function voirDetailCvCandidat($parameters)
        {
            $resultat = array(); 
            $candidat = "";
            $manager  = new ManagerCandidat();
            if (isset($parameters)) {
                $candidat = $manager->chercher($parameters);
            } else {
                $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            }
            if (!empty($candidat)) {
                $tabDiplome    = array();
                $tabFormation  = array();
                $tabExperience = array();
                $manager       = new ManagerDiplome();
                $diplomes      = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                $manager       = new ManagerFormation();
                $formations    = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                $manager       = new ManagerExperience();
                $experiences   = $manager->lister(['idCandidat' => $candidat->getIdCandidat()]);
                if (!empty($diplomes)) {
                    foreach ($diplomes as $diplome) {
                        $manager      = new ManagerDomaine();
                        $domaine      = $manager->chercher(['idDomaine' => $diplome->getIdDomaine()]);
                        $manager      = new ManagerNiveauEtude();
                        $niveauEtude  = $manager->chercher(['idNiveauEtude' => $diplome->getIdNiveauEtude()]);
                        $tabDiplome[] = [
                            'diplome'     => $diplome, 
                            'domaine'     => $domaine, 
                            'niveauEtude' => $niveauEtude
                        ];
                    }
                }
                if (!empty($formations)) {
                    foreach ($formations as $formation) {
                        $manager        = new ManagerSousDomaine();
                        $sousDomaine    = $manager->chercher(['idSousDomaine' => $formation->getIdSousDomaine()]);
                        $tabFormation[] = [
                            'formation'   => $formation, 
                            'sousDomaine' => $sousDomaine
                        ];
                    }
                }
                if (!empty($experiences)) {
                    foreach ($experiences as $experience) {
                        $manager         = new ManagerSousDomaine();
                        $sousDomaine     = $manager->chercher(['idSousDomaine' => $experience->getIdSousDomaine()]);
                        $tabExperience[] = ['experience' => $experience, 'sousDomaine' => $sousDomaine];
                    }
                }
                $resultats = ['candidat'    => $candidat, 
                              'diplomes'    => $tabDiplome,
                              'formations'  => $tabFormation,
                              'experiences' => $tabExperience];
            }
            return $resultats;
        } 

        /** 
         * Voir le détail d'un niveau d'entretien 
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
         */
        public function voirDetailNiveauEntretien($parameters)
        {
            $resultat                   = array(); 
            $interlocuteursNivEntretien = "";
            $interlocuteurs             = array();
            $manager                    = new ManagerInterlocuteur();
            $allIntelocuteurs           = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager                    = new ManagerNiveauEntretien();
            $nivEntretien               = $manager->chercher($parameters);
            if (!empty($nivEntretien)) {
                $manager = new ManagerInterlocuteurNiveauEntretien();
                $interlocuteursNivEntretien = $manager->lister(['idNiveauEntretien' => $nivEntretien->getIdNiveauEntretien()]);
                if (!empty($interlocuteursNivEntretien)) {
                    foreach ($interlocuteursNivEntretien as $interlocuteurNivEntretien) {
                        $manager = new ManagerInterlocuteur();
                        $interlocuteur = $manager->chercher(['idInterlocuteur' => $interlocuteurNivEntretien->getIdInterlocuteur()]);
                        if (!empty($interlocuteur)) {
                            $interlocuteurs[] = [
                                'interlocuteur'            => $interlocuteur,
                                'interlocuteurNivEntretien' => $interlocuteurNivEntretien
                            ];
                        }
                    }
                }
                $resultat = [
                    "niveauEntretien"  => $nivEntretien,
                    "interlocuteurs"   => $interlocuteurs,
                    "allIntelocuteurs" => $allIntelocuteurs
                ];
            }
            return $resultat;
        }

        /** 
         * Voir le détail d'un service d'une entreprise
         * 
         * @param array $parameters Critères des données à voir 
         * 
         * @return array 
         */
        public function voirDetailEntrepriseService($parameters)
        {
            $resultat = "";
            $tabPoste = array();
            $manager = new ManagerEntrepriseService();
            $entrepriseService = $manager->chercher($parameters);
            if (!empty($entrepriseService)) {
                $manager = new ManagerServicePoste();
                $servicePostes = $manager->lister(['idEntrepriseService' => $entrepriseService->getIdEntrepriseService()]);
                if (!empty($servicePostes)) {
                    foreach ($servicePostes as $servicePoste) {
                        $manager = new ManagerEntreprisePoste();
                        $tabPoste[] = [
                            "entreprisePoste" => $manager->chercher(['idEntreprisePoste' => $servicePoste->getIdEntreprisePoste()]),
                            "servicePoste"     => $servicePoste
                        ];
                    }
                }
                $resultat = [
                    "entrepriseService" => $entrepriseService,
                    "entreprisePostes"  => $tabPoste
                ];
            } 
            return $resultat;
        }

        /** 
         * Ajouter une candidature
         *
         * @return Object
         */
        public function mettreAJourCandidature($parameters)
        {
            $candidature = "";
            $idCandidature = "";
            $action = "";
            $manager  = new ManagerCandidat();
            $candidat = $manager->chercher(['idCompte' => $_SESSION['compte']['idCompte']]);
            if (!empty($candidat)) {
                $parameters['idCandidat'] = $candidat->getIdCandidat();
            }
            $manager = new ManagerCandidature();   
            if (isset($parameters['idCandidature'])) {
                $manager->modifier($parameters);
                $candidature = $manager->chercher(["idCandidature" => $parameters['idCandidature']]);
                $action = "modifier";
            } else {
                $parameters['dateCandidature'] = date('Y-m-d');
                $parameters['statut']          = "envoye";
                $manager->ajouter($parameters);
                $idCandidature = $manager->chercherDernierId();
                extract($idCandidature);
                $candidature = $manager->chercher(["idCandidature" => $id]);
                $action = "ajouter";
            }
            return $this->envoyerEmailCandidature($candidature, $action);
        } 

        /** 
         * Envoyer un email sur la candidature 
         *
         * @param object $candidature la candidature concernée
         * @param string $action l'action faite sur la candidature
         *
         * @return empty
         */
        private function envoyerEmailCandidature($candidature, $action)
        {   
            $subject    = "";
            $to         = "";
            $message    = "";
            $headers    = "";  
            $manager    = new ManagerCandidat();
            $candidat   = $manager->chercher(["idCandidat" => $candidature->getIdCandidat()]);
            $manager    = new ManagerOffre();
            $offre      = $manager->chercher(["idOffre" => $candidature->getIdOffre()]);
            $manager    = new ManagerEntreprise();
            $entreprise = $manager->chercher(["idEntreprise" => $offre->getIdEntreprise()]);
            $manager    = new ManagerEmailContact();
            $emails     = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == "information") {
                        if ($action == "ajouter") {
                            $to      = $entreprise->getEmail();
                            $subject = "Candidature postée";
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour,</label><br><br>
                                                <label>Nous informons que " . strtoupper($candidat->getNom()) . " " . ucwords($candidat->getPrenom()) . " a postulé pour le poste de
                                                <br> " . ucfirst($offre->getPoste()) .".</label><br><br>
                                                <label>Pour visualiser sa candidature, veuillez vous connecter sur votre compte en cliquant <a href='" . HOST . "/connexion'>ici</a></label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        } else if ($candidature->getStatut() == "refuse") {
                            $to      = $candidat->getEmail();
                            $subject = "Réponse d'une candidature envoyée";
                            $message = "<html><body>
                                            <div class='container'>
                                                <label>Bonjour " . ucwords($candidat->getPrenom()) . ",</label><br><br>
                                                <label>Nous avons le regret de vous informer que votre candidature a été consultée <br>
                                                par la société " . $entreprise->getNom() . "</label><br><br>
                                                <label>
                                                    Malheureusement, celle-ci n'a pas été retenue votre profil pour le poste de <br>" .
                                                    ucfirst($offre->getPoste()) . "
                                                </label><br><br>
                                                <label>Cordialement, </label><br><br>
                                                <label> L'équipe <a href='" . HOST . "'>Human Cart'Office</a></label>
                                            </div>
                                        </body></html>";
                        }
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
            }
        }

        /** 
         * Supprimer un Offre 
         * 
         * @param array $parameters Critères des données à supprimer
         * 
         * @return empty
         */
        public function supprimerOffre($parameters)
        {
            $manager     = new ManagerCandidature();
            $candidature = $manager->chercher($parameters);
            if (empty($candidature)) {
                $manager = new ManagerOffre();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer cette offre ';
            }
        }

         /** 
         * Supprimer un domaine 
         * 
         * @param array $parameters Critères des données à supprimer
         * 
         * @return empty
         */
        public function supprimerDomaine($parameters)
        {
            $manager     = new ManagerDomaine();
            $domaine     = $manager->chercher($parameters);
            $manager     = new ManagerSousDomaine();
            $sousDomaine = $manager->chercher(['idDomaine' => $domaine->getIdDomaine()]);
            $manager     = new ManagerDiplome();
            $diplome     = $manager->chercher(['idDomaine' => $domaine->getIdDomaine()]);
            if (empty($sousDomaine) && empty($diplome)) {
                $manager = new ManagerDomaine();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le domaine "' . $domaine->getNomDomaine() . '"';
            }
        }

        /** 
         * Supprimer un sous domaine 
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerSousdomaine($parameters)
        {
            $manager     = new ManagerSousDomaine();
            $sousDomaine = $manager->chercher($parameters);
            $manager     = new ManagerFormation();
            $formation   = $manager->chercher(['idSousDomaine' => $sousDomaine->getIdSousDomaine()]);
            $manager     = new ManagerExperience();
            $experience  = $manager->chercher(['idSousDomaine' => $sousDomaine->getIdSousDomaine()]);
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher(['idSousDomaine' => $sousDomaine->getIdSousDomaine()]);
            if (empty($formation) && empty($experience) && empty($offre)) {
                $manager = new ManagerSousDomaine();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le sous domaine "' . $sousDomaine->getNomSousDomaine() . '"';
            }
        }

        /** 
         * Supprimer une personnalité
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerPersonnalite($parameters)
        {
            $existe       = false;
            $manager      = new ManagerPersonnalite();
            $personnalite = $manager->chercher($parameters);
            $manager      = new ManagerCandidat();
            $candidats    = $manager->lister();
            $manager      = new ManagerOffre(); 
            $offres       = $manager->lister();
            if (!empty($candidats) || !empty($offres)) {
                foreach ($candidats as $candidat) {
                    if (strstr($candidat->getPersonnalite(), $personnalite->getQualite())) {
                        $existe = true;
                        break;
                    }
                }
                foreach ($offres as $offre) {
                    if (strstr($offre->getPersonnalite(), $personnalite->getQualite())) {
                        $existe = true;
                        break;
                    }
                }
            }
            if (!$existe) {
                $manager = new ManagerPersonnalite();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer la personnalité "' . $personnalite->getQualite() . '"';
            }
        }

        /** 
         * Supprimer un contrat
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerContrat($parameters)
        {
            $manager = new ManagerContrat();
            $contrat = $manager->chercher($parameters);
            $manager = new ManagerOffre();
            $offre   = $manager->chercher(['idContrat' => $contrat->getIdContrat()]);
            if (empty($offre)) {
                $manager = new ManagerContrat();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le contrat "' . $contrat->getDesignation() . '"';
            }
        }

        /** 
         * Supprimer un niveau d'expérience
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerNiveauExperience($parameters)
        {
            $manager          = new ManagerNiveauExperience();
            $niveauExperience = $manager->chercher($parameters);
            $manager          = new ManagerOffre();
            $offre            = $manager->chercher(['idNiveauExperience' => $niveauExperience->getIdNiveauExperience()]);
            if (empty($offre)) {
                $manager = new ManagerNiveauExperience();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le niveau d\'expérience "' . $niveauExperience->getNiveau() . '"';
            }
        } 

        /** 
         * Supprimer un niveau d'étude
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerNiveauEtude($parameters)
        {
            $manager     = new ManagerNiveauEtude();
            $niveauEtude = $manager->chercher($parameters);
            $manager     = new ManagerOffre();
            $offre       = $manager->chercher(['idNiveauEtude' => $niveauEtude->getIdNiveauEtude()]);
            $manager     = new ManagerDiplome();
            $diplome     = $manager->chercher(['idNiveauEtude' => $niveauEtude->getIdNiveauEtude()]);
            if (empty($offre) && empty($diplome)) {
                $manager = new ManagerNiveauEtude();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer le niveau d\'étude "' . $niveauEtude->getNiveau() . '"';
            }
        }

        /** 
         * Supprimer un niveau d'entretien
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerNiveauEntretien($parameters)
        {
            $manager   = new ManagerEntretien();
            $entretien = $manager->chercher($parameters);
            $manager   = new ManagerInterlocuteurNiveauEntretien;
            $interlocuteurNivEntretien = $manager->chercher($parameters);
            if (empty($entretien) && empty($interlocuteurNivEntretien)) {
                $manager = new ManagerNiveauEntretien();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer ce niveau d\'entretien';
            }
        }

        /** 
         * Supprimer un interlocuteur
         * 
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimerInterlocuteur($parameters)
        {
            $manager   = new ManagerInterlocuteurNiveauEntretien();
            $interlocuteur = $manager->chercher($parameters);
            if (empty($interlocuteur)) {
                $manager = new ManagerInterlocuteur();
                $manager->supprimer($parameters);
                $_SESSION['info']['success'] = "Suppression avec succès";
            } else {
                $_SESSION['info']['danger'] = 'On ne peut pas encore supprimer cet interlocuteur';
            }
        }

        /**
         * Parametrer les données d'un graphe des offres
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
         */
        public function parametrerGrapheOffre($parameters)
        {   
            $manager = new ManagerOffre();
            return $manager->recupererStatistique($parameters);
        }

        /**
         * Parametrer les données d'un graphe des interlocuteurs
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
         */
        public function parametrerGrapheInterlocuteur($parameters)
        {   
            $manager = new ManagerInterlocuteur();
            return $manager->recupererStatistique($parameters);
        }

        /**
         * Parametrer les données d'un graphe des entretiens
         * 
         * @param arary $parameters Les critères des données du graphe
         *
         * @return empty
         */
        public function parametrerGrapheEntretien($parameters)
        {   
            $manager   = new ManagerEntretien();
            return $manager->recupererStatistique($parameters);
        }

        /**
         * Spécification de retour des données par rapport à l'url
         *
         * @param string $url l'url concerné
         * @param array $data Les données à spécifier
         *
         * @return array
         */
        public function specifierDonnees($url, $data)
        {
            $valueData = array();
            if (!empty($data)) {
                foreach ($data as $tabData) {
                    if ($url == "offre") {
                        if ($tabData['name'] == "envoye") {
                          $tabData['color'] = "#00BFFF";
                        } else if ($tabData['name'] == "accepte") {
                          $tabData['color'] = "#00FA9A";
                        } else {
                          $tabData['color'] = "#FA8072";
                        }
                        $tabData['type'] = "stackedColumn";
                        $tabData['showInLegend'] = true;
                        $tabData['axisYType'] = "secondary";
                        $valueData[] = $tabData;
                    } else if ($url == "entretien" || $url == "interlocuteur") {
                        if ($tabData['name'] == "en attente") {
                          $tabData['color'] = "#00BFFF";
                        } else if ($tabData['name'] == "valide") {
                          $tabData['color'] = "#00FA9A";
                        } else {
                          $tabData['color'] = "#FA8072";
                        }
                        $tabData['type'] = "stackedBar";
                        $tabData['showInLegend'] = true;
                        $tabData['axisYType'] = "secondary";
                        $valueData[] = $tabData;
                    }
                }
            }
            return $valueData;
        }
    }