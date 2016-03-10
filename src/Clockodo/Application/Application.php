<?php

namespace Clockodo\Application;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

class Application extends BaseApplication
{
    const NAME = 'Clockodo command-line interface';
    const VERSION = '0.1';

    protected $rootDir;

    /**
     * Initialize application.
     *
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        parent::__construct(static::NAME, static::VERSION);

        $this->rootDir = $rootDir;
        $this->getDefinition()->addOption(new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'));
    }

    /**
     * Get project root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Add command classes providing a directory and namespace.
     *
     * @param string $dir
     * @param string $namespace
     */
    public function registerCommandsDirectory($dir, $namespace)
    {
        $finder = new Finder();
        $finder->files()->name('*Command.php')->in($dir);

        // this was taken from Symfony\Component\HttpKernel\Bundle\Bundle
        $prefix = $namespace;
        foreach ($finder as $file) {
            $ns = $prefix;
            if ($relativePath = $file->getRelativePath()) {
                $ns .= '\\'.strtr($relativePath, '/', '\\');
            }
            $class = $ns.'\\'.$file->getBasename('.php');
            $r = new \ReflectionClass($class);
            if ($r->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') && !$r->isAbstract() && !$r->getConstructor()->getNumberOfRequiredParameters()) {
                $command = $r->newInstance();
                $this->add($command);
            }
        }
    }
}
