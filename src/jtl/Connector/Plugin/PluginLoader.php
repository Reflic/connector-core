<?php
namespace jtl\Connector\Plugin;

use \jtl\Connector\Core\IO\Path;
use \Symfony\Component\Finder\Finder;
use \jtl\Connector\Plugin\IPlugin;
use \Symfony\Component\EventDispatcher\EventDispatcher;

class PluginLoader
{
    public function load(EventDispatcher $dispatcher)
    {
        $dir = Path::combine(CONNECTOR_DIR, 'plugins');
        $finder = new Finder();
        $finder->directories()->in($dir);

        foreach ($finder as $directory) {
            if (file_exists(Path::combine($dir, $directory->getRelativePathname()))) {
                include (Path::combine($dir, $directory->getRelativePathname(), 'bootstrap.php'));

                $class = sprintf('%s_%s', ucfirst($directory->getRelativePathname()), ucfirst('Bootstrap'));
                if (class_exists($class)) {
                    $plugin = new $class();
                    if ($plugin instanceof IPlugin) {
                        $plugin->registerListener($dispatcher);
                    }
                }
            }
        }
    }
}