<?php

declare(strict_types=1);

namespace terpz710\tntblastradius\command;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use terpz710\tntblastradius\Main;

use CortexPE\Commando\BaseCommand;

class TNTCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("tntblastradius.cmd");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }
        Main::getInstance()->openRadiusSelectorUI($sender);
    }
}
