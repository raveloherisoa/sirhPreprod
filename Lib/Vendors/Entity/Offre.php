<?php 
	
	/** 
	 * Entité Offre
	 *
	 * @author Voahirana 
	 *
	 * @since 14/10/19
	 */

	namespace Entity;

	class Offre
	{
		private $idOffre;
		private $idEntreprise;
		private $idSousDomaine;
		private $idContrat;
		private $idNiveauExperience;
		private $idNiveauEtude; 
		private $personnalite;  
		private $dateEmission; 
		private $dateLimite; 
		private $poste;
		private $mission;
		private $typeCompensation;

		/** 
		 * Initialisation d'une Offre
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
		public function getIdOffre()
		{
			return $this->idOffre;
		}

		public function getIdEntreprise()
		{
			return $this->idEntreprise;
		}

		public function getIdSousDomaine()
		{
			return $this->idSousDomaine;
		}
		
		public function getIdContrat()
		{
			return $this->idContrat;
		}
		
		public function getIdNiveauExperience()
		{
			return $this->idNiveauExperience;
		}

		public function getIdNiveauEtude()
		{
			return $this->idNiveauEtude;
		}

		public function getPersonnalite()
		{
			return $this->personnalite;
		}

		public function getDateEmission()
		{
			return $this->dateEmission;
		}

		public function getDateLimite()
		{
			return $this->dateLimite;
		}

		public function getPoste()
		{
			return $this->poste;
		}

		public function getMission()
		{
			return $this->mission;
		} 

		public function getTypeCompensation()
		{
			return $this->typeCompensation;
		}

	// Seters
		public function setIdOffre($idOffre)
		{
			$this->idOffre = $idOffre;
		}

		public function setIdEntreprise($idEntreprise)
		{
			$this->idEntreprise = $idEntreprise;
		}

		public function setIdSousDomaine($idSousDomaine)
		{
			$this->idSousDomaine = $idSousDomaine;
		}

		public function setIdContrat($idContrat)
		{
			$this->idContrat = $idContrat;
		}

		public function setIdNiveauExperience($idNiveauExperience)
		{
			$this->idNiveauExperience = $idNiveauExperience;
		}

		public function setIdNiveauEtude($idNiveauEtude)
		{
			$this->idNiveauEtude = $idNiveauEtude;
		}

		public function setPersonnalite($personnalite)
		{
			$this->personnalite = $personnalite;
		}

		public function setDateEmission($dateEmission)
		{
			$this->dateEmission = $dateEmission;
		}

		public function setDateLimite($dateLimite)
		{
			$this->dateLimite = $dateLimite;
		}

		public function setPoste($poste)
		{
			$this->poste = $poste;
		} 

		public function setMission($mission)
		{
			$this->mission = $mission;
		}

		public function setTypeCompensation($typeCompensation)
		{
			$this->typeCompensation = $typeCompensation;
		}

	}