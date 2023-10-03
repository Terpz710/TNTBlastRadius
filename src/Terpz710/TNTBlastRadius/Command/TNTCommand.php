<?php

namespace Terpz710\TNTBlastRadius\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Terpz710\TNTBlastRadius\Main;

class TNTCommand extends Command {

    /** @var Plugin */
    private $Main;

    public function __construct(Main $main) {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->setPermission("tntblastradius.cmd");
        $this->setAliases(["tntedit"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            /** @var Main $main */
            $main = $this->getOwningPlugin();
            $main->openRadiusSelectorUI($sender);
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }

    public function getOwningPlugin(): Plugin {
        return $this->Main;
    }
}
