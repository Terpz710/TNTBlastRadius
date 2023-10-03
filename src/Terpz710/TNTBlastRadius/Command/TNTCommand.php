<?php

namespace Terpz710\TNTBlastRadius\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;

class TNTCommand extends Command implements PluginOwned {

    /** @var Plugin */
    private $plugin;

    public function __construct(Plugin $plugin) {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->plugin = $plugin;
        $this->setPermission("tntblastradius.cmd");
        $this->setAliases(["tntedit"]);
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $this->plugin->openRadiusSelectorUI($sender);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }
}
