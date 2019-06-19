<?php

/*
 * Проект: MVC
 * Назначение: класс для дополнительных данных возвращаемых методами модели которые контроллер посылает в представление.
 */
namespace MVC\View {

	class ViewModel {
		
		// динамическое добавление свойсв или методов к экземпляру ViewModel
		public function set($name, $val) {
			$this->$name = $val;
		}
		
		// возвращает значение запрашиваемого свойства
		public function get(string $name) {
			if (isset ( $this->{$name} )) {
				return $this->{$name};
			} else {
				return null;
			}
		}
	}
}