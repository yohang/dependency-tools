<?php

namespace Yohang;

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\ExecutableFinder;
use Composer\Script\Event;

/**
 * Simple static class that installs non-composer dependencies
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 * @author Maxime Veber <nek.dev@gmail.com>
 */
class DependencyTools
{
    /**
     * @param Event $event
     * @throws \RuntimeException
     */
    public static function installDeps(Event $event)
    {
        static::setup($event, 'install');
    }

    /**
     * @param Event $event
     * @throws \RuntimeException
     */
    public static function updateDeps(Event $event)
    {
        static::setup($event, 'update');
    }

    /**
     * @param Event  $event
     * @param string $type
     * @throws \RuntimeException
     */
    protected static function setup(Event $event, $type)
    {
        $options = static::getOptions($event);
        if (false !== $options['npm']) {
            echo "Installing NPM dependencies\n";
            static::execCommand(
                $options['npm'],
                'npm',
                array($type),
                'An error occuring when installing NPM dependencies'
            );
        }
        if (false !== $options['bower']) {
            echo "Installing Bower dependencies\n";
            static::execCommand(
                $options['bower'],
                'bower',
                array($type),
                'An error occuring when installing Bower dependencies'
            );
        }
    }


    /**
     * @param $event
     * @return array
     */
    protected static function getOptions($event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        return array_merge(
            array(
                'npm'   => false,
                'bower' => false
            ),
            isset($extra['dependency-tools']) ? $extra['dependency-tools'] : array()
        );
    }

    /**
     * @param array  $options
     * @param string $cmd
     * @param array  $args
     * @param string $ifError
     *
     * @throws \RuntimeException
     */
    protected static function execCommand($options, $cmd, array $args, $ifError)
    {
        if (is_array($options) && isset($options['path'])) {
            $cmd = $options['path'];
        } else {
            $executableFinder = new ExecutableFinder;
            $cmd = $executableFinder->find($cmd);
        }

        $cmd = static::guessCorrectPath($cmd);

        $out = '';
        $process = ProcessBuilder::create(array_merge(array($cmd), $args))->getProcess();
        if (isset($options['timeout'])) {
            $process->setTimeout($options['timeout']);
        }
        $process->run(function($type, $buffer) use (&$out) { $out .= $buffer; });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($ifError."\n\n".$out);
        }
    }

    /**
     * This is a fix for windows that need path with "\" instead of "/"
     *
     * @param string $path
     * @return string
     */
    protected static function guessCorrectPath($path)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            if (!file_exists($path)) {
                $path = str_replace('\\', '/', $path);
            }
        } else {
            if (!file_exists($path)) {
                $path = str_replace('/', '\\', $path);
            }
        }

        return $path;
    }
}
