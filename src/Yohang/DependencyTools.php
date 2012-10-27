<?php

namespace Yohang;

use Symfony\Component\Process\ProcessBuilder;

/**
 * Simple static class that installs non-composer dependencies
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class DependencyTools
{
    /**
     * @param $event
     * @throws \RuntimeException
     */
    public static function installDeps($event)
    {
        $options = static::getOptions($event);
        if (false !== $options['npm']) {
            echo "Installing NPM dependencies\n";
            static::execCommand(
                $options['npm'],
                'npm',
                array('install'),
                'An error occuring when installing NPM dependencies'
            );
        }
        if (false !== $options['bower']) {
            echo "Installing Bower dependencies\n";
            static::execCommand(
                $options['bower'],
                'bower',
                array('install'),
                'An error occuring when installing Bower dependencies'
            );
        }
    }

    /**
     * @param $event
     *
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
     * @param arrat  $args
     * @param string $ifError
     * @throws \RuntimeException
     */
    protected static function execCommand($options, $cmd, array $args, $ifError)
    {
        if (is_array($options) && isset($options['path'])) {
            $cmd = $options['path'];
        }

        $out = '';
        $process = ProcessBuilder::create(array_merge(array($cmd), $args))->getProcess();
        $process->run(function($type, $buffer) use (&$out) { $out .= $buffer; });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($ifError."\n\n".$out);
        }
    }
}
