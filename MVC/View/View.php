<?php

namespace MVC\View {
	use MVC\Config\ViewConfig;
	use MVC\View\ViewModel;

	class View {
		protected $viewFile;
		
		// получаем путь к представлению
		public function __construct($controllerClass, $action) {			
			$this->viewFile = ViewConfig::getFile($controllerClass, $action);
		}
		
		// вывод
		public function output(ViewModel $viewModel, $template = "layouts/main") {
			$templateFile = ViewConfig::getTemplate($template);
			
			if (\file_exists ( $this->viewFile )) {
				if ($template) { // включаем шаблон
					if (\file_exists ( $templateFile )) {
						require ($templateFile);
					} else {
						require ViewConfig::getErrorFile('template');					
					}
				} else { // мы не используем шаблон представления, поэтому просто выводим представление
					require ($this->viewFile);
				}
			} else {
				require ViewConfig::getErrorFile('view');
			}
		}
	}
}