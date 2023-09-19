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

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            $form = new TNTForm($sender);
            $form->sendForm();
        } else {
            $sender->sendMessage("This command can only be used in-game.");
        }
        return true;
    }
}
