<?php
    
    /**
     * Manager du modules Compte du Frontend
     *
     * @author Voahirana
     *
     * @since 29/10/19 
     */

	use \Core\DbManager;
    use \Model\ManagerSuperadmin;
    use \Model\ManagerEntreprise;
    use \Model\ManagerCandidat;
    use \Model\ManagerCompte;
    use \Model\ManagerPersonnalite;
    use \Model\ManagerNiveauEntretien;
    use \Model\ManagerEmailContact;

	class ManagerModuleCompte extends DbManager
	{

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
         * Ajouter un candidat
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        public function ajouterCandidat($parameters)
        {
            $candidat               = "";
            $compte                 = $this->getCompte();
            $parameters['idCompte'] = $compte->getIdCompte();
            if (!empty($parameters['idCompte'])) {
                $manager  = new ManagerCandidat();
                $candidat = $manager->ajouter($parameters);
            }
            return $candidat;
        }

        /** 
         * Ajouter un entreprise
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        public function ajouterEntreprise($parameters)
        {
            $entreprise             = "";
            $compte                 = $this->getCompte();
            $parameters['idCompte'] = $compte->getIdCompte();
            if (!empty($parameters['idCompte'])) {
                $manager    = new ManagerEntreprise();
                $entreprise = $manager->ajouter($parameters);
                $data       = [
                    "service"      => "Nom de votre service entretien",
                    "idEntreprise" => $entreprise->getIdEntreprise(),
                    "ordre"        => 1
                ];
                $manager   = new ManagerNiveauEntretien();
                $manager->ajouter($data);
            }
            return $entreprise;            
        }

        /** 
         * Récupérer le compte qu'on vient d'insérer
         *
         * @param array $parameters Les données à ajouter 
         *
         * @return object
         */
        private function getCompte()
        {
            $compte = "";
            $dataCompte = $_SESSION['variable'];
            if (!empty($dataCompte)) {
                $dataCompte['motDePasse'] = md5($dataCompte['motDePasse']);
                $manager                  = new ManagerCompte();
                $compte                   = $manager->creerCompte($dataCompte);
            }
            return $compte;            
        }


        /** 
         * Envoyer le message sur le formulaire de contact
         *
         * @param array $parameters Les données à envoyé 
         *
         * @return empty
         */
        public function sendMessage($parameters)
        {
            $to      = "";
            $subject = "Message venant du visiteur de site";
            $message = "visiteur : " . strtoupper($parameters['nom']) . " " . ucwords($parameters['prenom']) . "\n\n Téléphone : " . $parameters['phone'] . "\n\n Email : " . $parameters['email'] . "\n\n" . ucfirst($parameters['message']);
            $headers = "From: " . $parameters['email'];
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == 'contact') {
                        $to = $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
                $_SESSION['info']['success'] = "Message envoyé";
            } else {
                $_SESSION['info']['danger'] = "Message non envoyé";
            }
        }

        /** 
         * Envoyer un email à un utilisateur
         *
         * @param string $to l'email destinataire
         * @param string $subject le sujet de l'email
         * @param string $message le message à envoyer 
         * @param string $headers ce qu'on va préciser l'émetteur 
         *
         * @return empty
         */
        public function sendEmail($to, $subject, $message)
        {
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == 'information') {
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $email->getEmail();
                        mail($to, $subject, $message, $headers);
                    }
                }
            } 
        }

        /** 
         * Envoyer un email au application suite à une création d'un compte entreprise
         *
         * @param string $entreprise l'entreprise qui vient de s'inscrire
         *
         * @return empty
         */
        public function notifierApp($entreprise)
        {
            $manager = new ManagerEmailContact();
            $emails  = $manager->lister();
            if (!empty($emails)) {
                foreach ($emails as $email) {
                    if ($email->getType() == 'information') {
                        $headers = "Content-type: text/html" . "\r\n" . "From: " . $entreprise->getEmail();
                        $subject = "Information sur l'inscription";
                        $message = "<html><body>
                                        <div class='container'>
                                            <label>Bonjour !</label><br><br>
                                            <label>L'entreprise " . $entreprise->getNom() . " vien de s'inscrire sur votre site.</label><br><br>
                                        </div>
                                    </body></html>";
                        mail($email->getEmail(), $subject, $message, $headers);
                    }
                }
            } 
        }
    }