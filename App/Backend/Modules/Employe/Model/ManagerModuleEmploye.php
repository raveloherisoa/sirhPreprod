<?php
    
    /**
     * Manager du modules Employe du Backend
     *
     * @author Voahirana
     *
     * @since 13/03/20 
     */

	use \Core\DbManager;
    use \Model\ManagerBanque;
    use \Model\ManagerEmploye;
    use \Model\ManagerEntreprisePoste;
    use \Model\ManagerPersonnalite;

	class ManagerModuleEmploye extends DbManager
	{
        /** 
         * Lister les banques
         * 
         * @return array
         */
        public function listerBanques()
        {
            $resultats = array();
            $manager   = new ManagerBanque();
            $banques   = $manager->lister();
            if (!empty($banques)) {
                foreach ($banques as $banque) {
                    $resultats[] = $banque;
                }
            }
            return $resultats;
        }

        /** 
         * Afficher la formulaire d'une banque
         * 
         * @param array $parameters Les données à récupérer
         *
         * @return Objet
         */
        public function afficherFormBanque($parameters)
        {
            $manager = new ManagerBanque();
            if (isset($parameters)) {
                return $manager->chercher($parameters);
            } else {
                return $manager->initialiser();
            } 
        } 

        /** 
         * Mettre à jour une banque
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return empty
         */
        public function mettreAJourBanque($parameters)
        {
            $parameters['codeBanque'] = strtoupper($parameters['codeBanque']);
            $manager                  = new ManagerBanque();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 

        /** 
         * Afficher le formulaire d'un employé
         * 
         * @param $parameters Les donnée à récupérer
         * 
         * @return object
         */
        public function afficherFormEmploye($parameters)
        {
            $resultats = array();
            $manager   = new ManagerEmploye();
            if (isset($parameters)) {
                $employe = $manager->chercher($parameters);
            } else {
                $employe = $manager->initialiser();
            }
            $employes      = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager       = new ManagerEntreprisePoste();
            $postes        = $manager->lister(['idEntreprise' => $_SESSION['user']['idEntreprise']]);
            $manager       = new ManagerPersonnalite();
            $personnalites = $manager->lister();
            $manager       = new ManagerBanque();
            $banques       = $manager->lister();
            return [
                'chefs'         => $employes,
                'postes'        => $postes,
                'employe'       => $employe,
                'banques'       => $banques,
                'personnalites' => $personnalites
            ];
        }        

        /** 
         * Mettre à jour un employe
         * 
         * @param array $parameters Les données à mettre à jour
         *
         * @return Object
         */
        public function mettreAJourEmploye($parameters)
        {
            if (isset($parameters['qualite'])) {
                unset($parameters['qualite']);
            }
            if (isset($parameters['autreQualite'])) {
                unset($parameters['autreQualite']);
            }
            echo "<pre>";
            var_dump($parameters);
            exit();
        }
	}