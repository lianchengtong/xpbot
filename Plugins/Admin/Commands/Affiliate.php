<?php
/**
 * Created by JetBrains PhpStorm.
 *
 * @author Kadet <kadet1090@gmail.com>
 * @package
 * @license WTFPL
 */

namespace XPBot\Plugins\Admin\Commands;

use XPBot\Bot\Command;
use XPBot\Bot\CommandException;
use XPBot\System\Utils\Delegate;
use XPBot\System\Xmpp\Jid;

class Affiliate extends Command
{
    const PERMISSION = 7;

    public function execute($args)
    {
        if(count($args) < 2)
            throw new commandException('Too few arguments.', __('errTooFewArguments', $this->_lang));

        if(count($args) == 2)
            $this->_list($args);
        else
            $this->_set($args);
    }

    private function _set($args) {
        $jid = isset($this->_author->room->users[$args[2]]) ?
            $this->_author->room->users[$args[2]]->jid :
            $args[2];

        try {
            $this->_author->room->affiliate($jid, $args[1], $args[3]);
        } catch (\InvalidArgumentException $exception) {
            if($exception->getMessage() == 'affiliation')
                throw new commandException('Wrong affiliation.', __('errWrongAffiliation', $this->_lang));
        }
    }

    private function _list($args) {
        try {
            $this->_author->room->affiliationList($args[1], new Delegate(function ($packet) use ($args) {
                $users = array();
                foreach($packet->query->item as $user)
                    $users[] = $args['j'] ? $user['jid'] : strstr($user['jid'], '@', true);

                $args['p'] ?
                    $this->_author->room->message(implode(', ', $users)) :
                    $this->_author->privateMessage(implode(', ', $users));
            }));
        } catch (\InvalidArgumentException $exception) {
            if($exception->getMessage() == 'affiliation')
                throw new commandException('Wrong affiliation.', __('errWrongAffiliation', $this->_lang));
        }
    }
}