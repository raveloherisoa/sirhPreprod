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
		private $typePaiement;
		private $avanceSalaire;
		private $avanceSpeciale;
		private $cin;
		private $residence;
		private $bulletin;
		private $cv;
		private $lettreMotivation;
		private $autreDossier;

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

		public function getIdCompte()
		{
			return $this->idCompte;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getMatricule()
		{
			return $this->matricule;
		}

		public function getPhoto()
		{
			return $this->photo;
		}

		public function getCivilite()
		{
			return $this->civilite;
		}

		public function getNom()
		{
			return $this->nom;
		}

		public function getPrenom()
		{
			return $this->prenom;
		}

		public function getDateNaissance()
		{
			return $this->dateNaissance;
		}

		public function getLieuNaissance()
		{
			return $this->lieuNaissance;
		}

		public function getAdresse()
		{
			return $this->adresse;
		}

		public function getVille()
		{
			return $this->ville;
		}

		public function getContact()
		{
			return $this->contact;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getPersonnalite()
		{
			return $this->personnalite;
		}

		public function getPoste()
		{
			return $this->poste;
		}

		public function getChefHierarchique()
		{
			return $this->chefHierarchique;
		}

		public function getNumeroCnaps()
		{
			return $this->numeroCnaps;
		}

		public function getStatutCnaps()
		{
			return $this->statutCnaps;
		}

		public function getOsie()
		{
			return $this->osie;
		}

		public function getTypePaiement()
		{
			return $this->typePaiement;
		}

		public function getAvanceSalaire()
		{
			return $this->avanceSalaire;
		}

		public function getAvanceSpeciale()
		{
			return $this->avanceSpeciale;
		}

		public function getCin()
		{
			return $this->cin;
		}

		public function getResidence()
		{
			return $this->residence;
		}

		public function getBulletin()
		{
			return $this->bulletin;
		}

		public function getCv()
		{
			return $this->cv;
		}

		public function getLettreMotivation()
		{
			return $this->lettreMotivation;
		}

		public function getAutreDossier()
		{
			return $this->autreDossier;
		}

	// Seters
		public function setIdEmploye($idEmploye)
		{
			$this->idEmploye = $idEmploye;
		}

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

		public function setTypePaiement($typePaiement)
		{
			$this->typePaiement = $typePaiement;
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
		{
			$this->idCandidat = $idCandidat;
		}

		public function setLettreMotivation($lettreMotivation)
		{
			$this->lettreMotivation = $lettreMotivation;
		}

		public function setAutreDossier($autreDossier)
		{
			$this->autreDossier = $autreDossier;
		}

	}