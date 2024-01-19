<?php
	// ---- Incluimos las librer�as originales de Smarty
	include_once dirname(__FILE__).'/../vendor/smarty/smarty/libs/Smarty.class.php';
	
	class newSmarty extends Smarty
	{
		public function __construct($PPlantilla='views') 
		{
		    parent::__construct();
		    
		    $DirBase=dirname(__FILE__).'/../';
			$nombre_directorio_temporal='TmpSmarty/';
			
			// ----- Revisa que existan los directorios
			/*$Lista_directorios=array($DirBase.$nombre_directorio_temporal,
									 $DirBase.$nombre_directorio_temporal.$PPlantilla.'/');
			reset($Lista_directorios);
			foreach ($Lista_directorios as $Directorio) 
			{
				//echo($Directorio.'<br>');
				if(!file_exists($Directorio))
				{
					mkdir($Directorio);
					chmod($Directorio,0777);
				}
			}*/
			
			// ---- Inicializa la plantilla y los tag de marcas
			$this->left_delimiter 	= '[s/';
			$this->right_delimiter 	= '/s]';

			// ----- Directorios de trabajo para Smarty
			$this->template_dir 	= dirname(__FILE__).'/../'.$PPlantilla."/";
			$this->compile_dir 		= $DirBase.'/'.$nombre_directorio_temporal.$PPlantilla.'/';
			$this->config_dir 		= $DirBase.'/'.$nombre_directorio_temporal.$PPlantilla.'/'.'configs/';
			$this->cache_dir 		= $DirBase.'/'.$nombre_directorio_temporal.$PPlantilla.'/'.'cache/';	
			
			// ---- Limpiar caches
			//$this->clear_compiled_tpl();
		}
		
	} // Fin de la Clase
?>