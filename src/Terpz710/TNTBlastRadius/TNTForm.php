<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\player\Player;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use Terpz710\TNTBlastRadius\Main;
use Terpz710\TNTBlastRadius\TNTCommand;

class TNTForm {

    private $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function sendForm(): void {
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
        $form->addSlider("Radius:", 1, 25);
        $this->player->sendForm($form);
    }
}
