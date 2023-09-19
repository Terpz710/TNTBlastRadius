<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;

class TNTForm implements Listener {

    public static function execute(Player $player, int $blastRadius = 4): void {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                $radius = max(1, min(25, $data[0]));

                $confirmForm = new SimpleForm(function (Player $player, int $data) use ($radius) {
                    if ($data === 0) {
                        $scaledRadius = max(1, min(25, $radius));
                        $player->sendMessage("Blast radius set to: " . $scaledRadius);
                    } else {
                        $player->sendMessage("Blast radius change canceled.");
                    }
                });
                $confirmForm->setTitle("Confirm Radius");
                $confirmForm->setContent("Are you sure you want to set the TNT blast radius to $radius?");
                $confirmForm->addButton("Yes");
                $confirmForm->addButton("No");
                $player->sendForm($confirmForm);
            }
        });

        $form->setTitle("TNT Blast Radius");
        $form->addSlider("Radius:", 1, 25, 1, $blastRadius);
        $player->sendForm($form);
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $blastRadius = 4; // Default blast radius is 4. You can adjust this as needed.
            $scaledRadius = max(1, min(25, $blastRadius));
            $event->setRadius($scaledRadius);
        }
    }
}
