<?php
namespace miuramo\NcursesMenu;
// namespace wapmorgan\NcursesObjects;

/**
 * A window object that implements functionality for ncurses window resource
 * @author wapmorgan (wapmorgan@gmail.com)
 */
class MenuWin extends MWindow {
    static public $current;
    static public $root;
    public $parent;
    private $menu;
    public $children = [];
    //    public $win;
    public $isVisible = false;
    public $cur = 0;
    public $level;
    public $follow = false;
    
	public function __construct(&$parent, $level, $y, &$menu){
        if (isset($menu[0])){
            $this->menu = $menu;
        } else {
            $this->menu = array_keys($menu);
        }
        $this->parent = &$parent;
        $this->level = $level;
        $this->isVisible = true;
        //        $this->win = Window::createPosAt($parent, 40, 5, $level*3+1, $level*3+1);
        parent::__construct(40,count($menu)+2,$level*5+1,$y);
        $this->border();
        foreach($this->menu as $n=>$k){
            if (isset($menu[$k]) && is_array($menu[$k])){
                $this->add($n, $y+$n+2, $menu[$k]); 
            }
        }
	}
    public function add($pos, $y, $menuary){
        $this->children[$pos] = new MenuWin($this, $this->level + 1, $y, $menuary);
    }

	/**
	 * Destructs a window and associated panel
	 */
	public function __destruct() {
	}

    public function show(){
        if ($this->isVisible){
            $this->border();
            foreach($this->menu as $n=>$m){
                $str = str_repeat($m,1);
                if ($this->cur == $n){
                    $this->moveCursor(2,$n+1)
                        ->drawStringHere($str,NCURSES_A_REVERSE);
                } else {
                    $this->moveCursor(2,$n+1)
                        ->drawStringHere($str);
                }
            }
            $this->refresh();
            if ($this->follow){
                $this->children[$this->cur]->show();
            }
        }
    }
    public function up(){
        $this->cur--;
        if ($this->cur < 0) $this->cur = 0;
    }
    public function down(){
        $this->cur++;
        if ($this->cur >= count($this->menu) ) $this->cur = count($this->menu)-1;
    }
    public function right(){
        if (isset($this->children[$this->cur]) && 
            $this->children[$this->cur] !== null){
            $this->follow = true;
            return $this->children[$this->cur];
        }
    }
    public function left(){
        //        $this->isVisible = false;
        if ($this->parent !== null){
            if ($this->parent instanceof MenuWin){
                $this->parent->show();
                $this->parent->follow = false;
                return $this->parent;
            }
        }
        return null;
    }
    public function clear(){
        ncurses_wclear($this->getWindow());
    }
}
