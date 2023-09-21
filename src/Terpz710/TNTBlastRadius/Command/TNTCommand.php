<?php

namespace Terpz710\TNTBlastRadius\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwnedTrait;
use Terpz710\TNTBlastRadius\Main;

class TNTCommand extends Command {
    use PluginOwnedTrait;

    public function __construct(Main $main) {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->setPermission("tntblastradius.cmd");
        $this->setAliases(["tntedit"]);
        $this->owningPlugin = $main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $this->owningPlugin->openRadiusSelectorUI($sender);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }
}
