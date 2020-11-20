<?PHP

namespace Sp;

// The Migrator class will help us to migrate our data to the next version of this plugin
// Every migration will only run once
// Every migration will only run, if the version of the migration file is higher than the last done migration
// Furthermore a migration will only run if the current version of the software is the same as the migration or higher

class Migrator {

  public static function migrate() {

    $last_migration_number = get_option( 'steampixel_locations_last_migration', 0);

    // Scan migration files
    $migration_file_names = scandir(__DIR__.'/../migrations');

    foreach($migration_file_names as $migration_file_name) {
      if(is_file(__DIR__.'/../migrations/'.$migration_file_name)) {

        $migration_file_number = basename($migration_file_name,'.php');

        // Execute migration
        // If the migration was not already migrated
        if($migration_file_number>$last_migration_number) {
          // Migrate only if the current plugin version is the same or higher
          if(version_compare(SP_LOCATIONS_VERSION, $migration_file_number)>=0) {
            include(__DIR__.'/../migrations/'.$migration_file_name);
          }
        }

      }
    }

    // Remember migrations
    update_option('steampixel_locations_last_migration', $migration_file_number);

  }

}
