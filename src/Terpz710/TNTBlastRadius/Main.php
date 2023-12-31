<?php

declare(strict_types=1);

namespace Terpz710\TNTBlastRadius;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use pocketmine\world\World;
use pocketmine\utils\Config;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use Terpz710\TNTBlastRadius\Command\TNTCommand;

class Main extends PluginBase implements Listener {

    private $blastRadius = [];
    private $worldData;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("tntradius", new TNTCommand($this));

        $this->worldData = new Config($this->getDataFolder() . "worlddata.yml", Config::YAML);

        foreach ($this->worldData->getAll() as $world => $radius) {
            $this->blastRadius[$world] = (int)$radius;
        }
    }

    public function onDisable(): void {
        foreach ($this->blastRadius as $world => $radius) {
            $this->worldData->set($world, $radius);
        }
        $this->worldData->save();
    }

    public function onEntityPreExplode(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $world = $tnt->getWorld()->getFolderName();
            $radius = isset($this->blastRadius[$world]) ? $this->blastRadius[$world] : 4;
            $event->setRadius($radius);
        }
    }

    public function openRadiusSelectorUI(Player $player) {
        $form = new CustomForm(function (Player $player, ?array $data) {
            if ($data !== null) {
                if (isset($data[1])) {
                    $radius = (int)$data[1];
                    if ($radius >= 1 && $radius <= 25) {
                        $this->sendConfirmationUI($player, $radius);
                    }
                }
            }
        });

        $form->setTitle("§l§4TNT §0Blast §8Radius §fSelector");
        $form->addLabel("§l§fSelect the TNT blast radius for all players:");
        $form->addSlider("§l§fRadius", 1, 25);

        $player->sendForm($form);
    }

    public function sendConfirmationUI(Player $player, int $radius) {
        $confirmForm = new SimpleForm(function (Player $player, ?int $data) use ($radius) {
            if ($data !== null) {
                if ($data === 0) {
                    $this->setTNTBlastRadius($player, $radius);
                    $player->sendTitle("§l§4Changed to §f{$radius}§4!");
                } else {
                    $player->sendMessage("§l§fRadius change canceled!");
                }
            }
        });

        $confirmForm->setTitle("§l§4Confirmation");
        $confirmForm->setContent("§l§fAre you sure to change the radius to §4{$radius}§f?");
        $confirmForm->addButton("Yes");
        $confirmForm->addButton("No");
        $player->sendForm($confirmForm);
    }

    public function setTNTBlastRadius(Player $player, int $radius) {
        $world = $player->getWorld()->getFolderName();
        $this->blastRadius[$world] = $radius;
        $this->worldData->set($world, $radius);
        $this->worldData->save();
        $player->sendMessage("§4TNT blast radius changed to §f{$radius}§4 in world §f{$world}§f!");
    }
}
