<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\player\Player;
use pocketmine\form\SimpleForm;
use pocketmine\form\ModalForm;

class TNTForm {

    private $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function sendForm(): void {
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if ($data !== null) {
                $radius = max(1, min(25, $data));

                $confirmForm = new ModalForm(function (Player $player, bool $data) use ($radius) {
                    if ($data) {
                        $scaledRadius = max(1, min(25, $radius));
                        $player->sendMessage("Blast radius set to: " . $scaledRadius);
                    } else {
                        $player->sendMessage("Blast radius change canceled.");
                    }
                });
                $confirmForm->setTitle("Confirm Radius");
                $confirmForm->setContent("Are you sure you want to set the TNT blast radius to $radius?");
                $confirmForm->setButton1("Yes");
                $confirmForm->setButton2("No");
                $player->sendForm($confirmForm);
            }
        });

        $form->setTitle("TNT Blast Radius");
        $form->setContent("Adjust the TNT blast radius:");
        $form->addSlider("Radius:", 1, 25);
        $this->player->sendForm($form);
    }
}
