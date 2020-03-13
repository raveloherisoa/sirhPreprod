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
            $manager = new ManagerBanque();
            if (reset($parameters) == "") {
                return $manager->ajouter($parameters);
            } else {
                return $manager->modifier($parameters);
            }
        } 
	}