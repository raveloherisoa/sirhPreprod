<?php
    
    /**
     * Manager de l'entité Contrat
     *
     * @author Voahirana
     *
     * @since 14/10/19 
     */

    namespace Model;

    use \Core\DbManager;
    use \Entity\Contrat;

    class ManagerContrat extends DbManager
    {
       /**
         * Lister les contrats
         *
         * @return array
         */
        public function lister() 
        {
            $contrats  = array();
            $string    = " ORDER BY designation ASC";
            $resultats = $this->findAll('contrat', null, $string);
            if (!empty($resultats)) {
                foreach ($resultats as $resultat) {
                    $contrat    = new Contrat($resultat);
                    $contrats[] = $contrat;
                }
            }            
            return $contrats;
        }

         /** 
         * Créer un objet vide
         *
         * @return object
         */
        public function initialiser()
        {
            return new Contrat();
        }

        /** 
         * Chercher un contrat
         *
         * @param array $parameters Critères des données à chercher
         *
         * @return object|empty
         */
        public function chercher($parameters)
        {
            $fields  = array();
            $values  = array();
            $contrat = "";
            foreach ($parameters as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_INT) == true) {
                    $value = $value;
                } else {
                    $value =  "'" . $value . "'";
                }
                $fields [] = $key;
                $values [] = $value;
            }            
            $resultat = $this->findOne('contrat', $fields, $values);
            if (!empty($resultat)) {
                $contrat = new Contrat($resultat);
            }
            return $contrat;
        }

        /** 
         * Chercher le dérnier identifiant d'un contrat
         *
         * @return array
         */
        public function chercherDernierId()
        {            
            return $this->findLast('contrat', 'idContrat');
        }

        /**
         * Ajouter un contrat
         *
         * @param array $parameters Les données à ajouter
         *
         * @return object
         */
        public function ajouter($parameters) 
        { 
            $parameters['idContrat'] = $this->insert('contrat', $parameters);
            return new Contrat($parameters);
        }

        /**
         * Modifier un contrat
         *
         * @param array $parameters Les données à modifier
         *
         * @return object
         */
        public function modifier($parameters) 
        {
            $this->update('contrat', $parameters);
            return new Contrat($parameters);
        }

        /**
         * Supprimer un contrat
         *
         * @param array $parameters Critères des données à supprimer
         *
         * @return empty
         */
        public function supprimer($parameters) 
        {
            return $this->delete('contrat', $parameters);
        }
    }