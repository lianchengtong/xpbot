<?php
/**
 * Created by JetBrains PhpStorm.
 *
 * @author  Kadet <kadet1090@gmail.com>
 * @package
 * @license WTFPL
 */

namespace XPBot\Commands;

use XPBot\Command;

class Plugin extends Command
{
    const PERMISSION = 9;

    public function execute($args)
    {
        if (!isset($args[1]))
            return $this->all($args);
        else
            return $this->{$args[1]}($args);
    }

    public function all($args)
    {
        $plugins = $this->_bot->getPlugins();

    }
}