<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\form\SimpleForm;
use pocketmine\form\ModalForm;

class TNTCommand extends Command {

    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("tntradius", "Adjust the TNT blast radius");
        $this->plugin = $plugin;
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
