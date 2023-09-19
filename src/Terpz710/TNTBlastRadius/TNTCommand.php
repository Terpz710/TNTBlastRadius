<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Terpz710\BlastRadius\Main;
use Terpz710\BlastRadius\TNTForm;

class TNTCommand extends Command {

    public function __construct() {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->setPermission("tntradius.command");
        $this->setAliases(["tntedit"]);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
    if ($sender instanceof Player) {
        if ($command->getName() === "tntradius" || $command->getName() === "tntedit") {
            TNTForm::execute($sender);
        }
    } else {
        $sender->sendMessage("This command can only be used in-game.");
        }
    return true;
    }
}
