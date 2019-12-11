<?PHP

namespace Sp;

// The seeder class will help us to seed the plugin with some default data
// Every seed will only be executed once and never again
// So all seeds will be executed on the first install or on updates if the seed was not already executed

class Seeder {

  public static function seed() {

    $last_seeds = get_option( 'steampixel_locations_last_seeds', []);

    // Scan seed files
    $seed_file_names = scandir(__DIR__.'/../seeds');

    foreach($seed_file_names as $seed_file_name) {
      if(is_file(__DIR__.'/../seeds/'.$seed_file_name)) {

        $seed_name = basename($seed_file_name,'.php');

        // Execute seed
        if(!in_array($seed_name, $last_seeds)) {
          include(__DIR__.'/../seeds/'.$seed_file_name);
          array_push($last_seeds, $seed_name);
        }

      }
    }

    // Remember migrations
    update_option('steampixel_locations_last_seeds', $last_seeds);

  }

}
