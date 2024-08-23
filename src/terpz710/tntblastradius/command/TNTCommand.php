<?php

declare(strict_types=1);

namespace terpz710\tntblastradius\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;

use terpz710\tntblastradius\Main;

class TNTCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->setPermission("tntblastradius.cmd");
        $this->setAliases(["tntedit"]);
    }

    public function getOwningPlugin() : Plugin{
        return Main::getInstance();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
        if ($sender instanceof Player) {
                Main::getInstance()->openRadiusSelectorUI($sender);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }
}
