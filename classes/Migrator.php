<?PHP

class Migrator {

  public static function migrate() {

    $last_migration_number = get_option( 'steampixel_locations_last_migration', 0);

    // Scan migration files
    $migration_file_names = scandir(__DIR__.'/../migrations');

    foreach($migration_file_names as $migration_file_name) {
      if(is_file(__DIR__.'/../migrations/'.$migration_file_name)) {

        $migration_file_number = basename($migration_file_name,'.php');

        // Execute migration
        if($migration_file_number>$last_migration_number){
          include(__DIR__.'/../migrations/'.$migration_file_name);
        }

      }
    }

    // Remember migrations
    update_option('steampixel_locations_last_migration', $migration_file_number);

  }

}
