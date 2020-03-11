<?php 
	
	/** 
	 * Entité Employe
	 *
	 * @author Voahirana 
	 *
	 * @since 09/03/20
	 */

	namespace Entity;

	class Employe
	{
		private $idEmploye;
<<<<<<< Updated upstream
		private $idCandidat; 
		private $idEntreprise;
		private $statut;
=======
		private $idCompte; 
		private $idEntreprise;
		private $matricule;
		private $photo;
		private $civilite;
		private $nom;
		private $prenom;
		private $dateNaissance;
		private $lieuNaissance;
		private $adresse;
		private $ville;
		private $contact;
		private $email;
		private $personnalite;
		private $poste;
		private $chefHierarchique;
		private $numeroCnaps;
		private $statuCnaps;
		private $osie;
		private $avanceSalaire;
		private $avanceSpeciale;
		private $cin;
		private $residence;
		private $bulletin;
		private $cv;
		private $lettreMotivation;
		private $autreDossier;
>>>>>>> Stashed changes

		/** 
		 * Initialisation d'un Employe
		 *
		 * @param array $data Données à intialiser 
		 *
		 * @return empty
		 */
	    public function __construct($data = array())
	    {
	        if(!empty($data)) {
	        	$this->hydrate($data);
	        }           
	    }

	    /** 
		 * Remplir la structure d'un objet 
		 *
		 * @param array $data Données à remplir
		 *
		 * @return empty
		 */
	    public function hydrate($data)
	    {
	        foreach ($data as $attribut => $data) {
	            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
	            if (is_callable(array($this, $method))) {
	                $this->$method($data);
	            }
	        }
	    }

	    /** 
	     * Convertir un objet en tableau 
	     *
	     * @return array
	     */
	    public function toArray()
	    {
	    	return get_object_vars($this);
	    }

	// Getters
		public function getIdEmploye()
		{
			return $this->idEmploye;
		}

		public function getIdCandidat()
		{
			return $this->idCandidat;
		}

		public function getIdEntreprise()
<<<<<<< Updated upstream
=======
		{
			return $this->idEntreprise;
		}

		public function getMatricule()
>>>>>>> Stashed changes
		{
			return $this->idEntreprise;
		}

		public function getStatut()
		{
			return $this->statut;
		}
	// Seters
		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

<<<<<<< Updated upstream
		public function setIdCandidat($idCandidat)
=======
		public function setIdCompte($idCompte)
		{
			$this->idCompte = $idCompte;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setMatricule($matricule)
		{
			$this->matricule = $matricule;
		}

		public function setPhoto($photo)
		{
			$this->photo = $photo;
		}

		public function setCivilite($civilite)
		{
			$this->civilite = $civilite;
		}

		public function setNom($nom)
		{
			$this->nom = $nom;
		}

		public function setPrenom($prenom)
		{
			$this->prenom = $prenom;
		}

		public function setDateNaissance($dateNaissance)
		{
			$this->dateNaissance = $dateNaissance;
		}

		public function setLieuNaissance($lieuNaissance)
		{
			$this->lieuNaissance = $lieuNaissance;
		}

		public function setAdresse($adresse)
		{
			$this->adresse = $adresse;
		}

		public function setVille($ville)
		{
			$this->ville = $ville;
		}

		public function setContact($contact)
		{
			$this->contact = $contact;
		}

		public function setEmail($email)
		{
			$this->email = $email;
		}

		public function setPersonnalite($personnalite)
		{
			$this->personnalite = $personnalite;
		}

		public function setPoste($poste)
		{
			$this->poste = $poste;
		}

		public function setChefHierarchique($chefHierarchique)
		{
			$this->chefHierarchique = $chefHierarchique;
		}

		public function setNumeroCnaps($numeroCnaps)
		{
			$this->numeroCnaps = $numeroCnaps;
		}

		public function setStatutCnaps($statutCnaps)
		{
			$this->statutCnaps = $statutCnaps;
		}

		public function setOsie($osie)
		{
			$this->osie = $osie;
		}

		public function setAvanceSalaire($avanceSalaire)
		{
			$this->avanceSalaire = $avanceSalaire;
		}

		public function setAvanceSpeciale($avanceSpeciale)
		{
			$this->avanceSpeciale = $avanceSpeciale;
		}

		public function setCin($cin)
		{
			$this->cin = $cin;
		}

		public function setResidence($residence)
		{
			$this->residence = $residence;
		}

		public function setBulletin($bulletin)
		{
			$this->bulletin = $bulletin;
		}

		public function setCv($cv)
>>>>>>> Stashed changes
		{
			$this->idCandidat = $idCandidat;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setStatut($statut)
		{
			$this->statut = $statut;
		}

	}