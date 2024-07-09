<?php

namespace Drupal\radix_starterkit;

use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Theme\StarterKitInterface;
use Symfony\Component\Filesystem\Filesystem;

final class StarterKit implements StarterKitInterface {

  public static function postProcess(string $working_dir, string $machine_name, string $theme_name): void {
    self::updateInfoFile($working_dir, $machine_name);
    self::renameSchemaFile($working_dir, $machine_name);
    self::renameFunctionsInIncludes($working_dir, $machine_name);
    self::updatePackageJson($working_dir, $machine_name);
    self::renameConfigFiles($working_dir, $machine_name);
    self::cleanup($working_dir);
  }

  private static function updateInfoFile(string $working_dir, string $machine_name) {
    $info_file = "$working_dir/$machine_name.info.yml";
    $info = Yaml::decode(file_get_contents($info_file));
    $info['interface translation project'] = $machine_name;
    $info['interface translation server pattern'] = "themes/custom/{$machine_name}/translations/%language.po";
    file_put_contents($info_file, Yaml::encode($info));
  }

  private static function renameSchemaFile(string $working_dir, string $machine_name) {
    $fs = new Filesystem();
    $fs->rename("$working_dir/config/schema/radix_starterkit.schema.yml", "$working_dir/config/schema/$machine_name.schema.yml");
    $schema_file = "$working_dir/config/schema/$machine_name.schema.yml";
    $schema = Yaml::decode(file_get_contents($schema_file));
    $schema["$machine_name.settings"] = $schema['radix_starterkit.settings'];
    unset($schema['radix_starterkit.settings']);
    $schema["$machine_name.settings"]['label'] = "$machine_name settings";
    file_put_contents($schema_file, Yaml::encode($schema));
  }

  private static function renameFunctionsInIncludes(string $working_dir, string $machine_name) {
    $iterator = new \IteratorIterator(new \RegexIterator(
      new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator("$working_dir/includes/"),
        \RecursiveIteratorIterator::LEAVES_ONLY
      ),
      '/' . preg_quote('.inc') . '$/'
    ));

    foreach ($iterator as $include_file => $contents) {
      $content = file_get_contents($include_file);
      $content = preg_replace("/(radix_starterkit)/", $machine_name, $content);
      file_put_contents($include_file, $content);
    }
  }

  private static function updatePackageJson(string $working_dir, string $machine_name) {
    $packageJsonFile = "$working_dir/package.json";
    if (file_exists($packageJsonFile)) {
      $content = file_get_contents($packageJsonFile);
      $content = str_replace('radix_starterkit', $machine_name, $content);
      file_put_contents($packageJsonFile, $content);
    }
  }

  private static function renameConfigFiles(string $working_dir, string $machine_name) {
    $fs = new Filesystem();
    $configDir = "$working_dir/config/optional";

    if (is_dir($configDir)) {
      foreach (glob("$configDir/*radix_starterkit*.yml") as $filePath) {
        $newPath = str_replace('radix_starterkit', $machine_name, $filePath);
        $fs->rename($filePath, $newPath);

        if (file_exists($newPath)) {
          $content = file_get_contents($newPath);
          $updatedContent = str_replace('radix_starterkit', $machine_name, $content);
          file_put_contents($newPath, $updatedContent);
        }
      }
    }
  }

  private static function cleanup(string $working_dir): void {
    $starterKitFilePath = $working_dir . '/src/StarterKit.php';
    if (file_exists($starterKitFilePath)) {
      unlink($starterKitFilePath);
    }
  }
}
