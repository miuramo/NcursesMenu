<?php
namespace miuramo\NcursesMenu;
// namespace wapmorgan\NcursesObjects;
use wapmorgan\NcursesObjects as N;

/**
 * A window object that implements functionality for ncurses window resource
 * @author wapmorgan (wapmorgan@gmail.com)
 */
class MWindow extends N\Window{

	public function __construct($columns = 0, $rows = 0, $x = 0, $y = 0) {
		parent::__construct($columns,$rows,$x,$y);
        	ncurses_keypad($this->getWindow(), true);
	}

	static public function createTopLeftOf(Window $parentWindow, $columns, $rows) {
		$parentWindow->getSize($max_columns, $max_rows);
		return new N\Window($columns, $rows, $parentWindow->x+2, $parentWindow->y+2);
	}

        static public function createPosAt(Window $parentWindow, $columns, $rows, $posx, $posy) {
		$parentWindow->getSize($max_columns, $max_rows);
		return new N\Window($columns, $rows, $posx, $posy);
	}
    
}
