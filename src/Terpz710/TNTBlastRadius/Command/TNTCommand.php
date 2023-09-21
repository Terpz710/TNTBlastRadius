<?php

namespace Terpz710\TNTBlastRadius\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use Terpz710\TNTBlastRadius\Main;

class TNTCommand extends Command implements PluginOwned {
    use PluginOwnedTrait;

    public function __construct(Main $plugin) {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->setPermission("tntradius.command");
        $this->setAliases(["tntedit"]);
        $this->initPlugin($plugin);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $this->getPlugin()->openRadiusSelectorUI($sender);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }
}
