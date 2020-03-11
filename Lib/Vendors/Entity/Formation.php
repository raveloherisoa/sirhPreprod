<?php 
	
	/** 
	 * Entité Formation
	 *
	 * @author Voahirana 
	 *
	 * @since 08/10/19
	 */

	namespace Entity;

	class Formation
	{
		private $idFormation;
		private $idCandidat;
		private $idSousDomaine;
		private $dateDebut;  
		private $dateFin; 
		private $theme; 
		private $description;

		/** 
		 * Initialisation d'un Formation
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
		public function getIdFormation()
		{
			return $this->idFormation;
		}

		public function getIdCandidat()
		{
			return $this->idCandidat;
		}

		public function getIdSousDomaine()
		{
			return $this->idSousDomaine;
		}

		public function getDateDebut()
		{
			return $this->dateDebut;
		}

		public function getDateFin()
		{
			return $this->dateFin;
		}

		public function getTheme()
		{
			return $this->theme;
		}

		public function getDescription()
		{
			return $this->description;
		}

	// Seters
		public function setIdFormation($idFormation)
		{
			$this->idFormation = $idFormation;
		}

		public function setIdCandidat($idCandidat)
		{
			$this->idCandidat = $idCandidat;
		}

		public function setIdSousDomaine($idSousDomaine)
		{
			$this->idSousDomaine = $idSousDomaine;
		}

		public function setDateDebut($dateDebut)
		{
			$this->dateDebut = $dateDebut;
		}

		public function setDateFin($dateFin)
		{
			$this->dateFin = $dateFin;
		}

		public function setTheme($theme)
		{
			$this->theme = $theme;
		}

		public function setDescription($description)
		{
			$this->description = $description;
		}

	}