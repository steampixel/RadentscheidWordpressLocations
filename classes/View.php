<?PHP

namespace Sp;

class View {

  public static function render ($v0738238615_name, $arguments = []) {
  	ob_start();
  	if(is_array($arguments)){
  		extract($arguments);
  	}

  	// The name variable was prefixed because extract could override it
  	include(__DIR__.'/../views/'.$v0738238615_name.'.php');
  	return ob_get_clean();
  }

}
