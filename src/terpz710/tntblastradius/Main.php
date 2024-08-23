<?php

declare(strict_types=1);

namespace terpz710\tntblastradius;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use terpz710\tntblastradius\command\TNTCommand;

class Main extends PluginBase implements Listener {

    private static $instance;
    private $blastRadius = [];
    private $worldData;
    private $messages;
    private $formSelector;
    private $formConfirmation;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("TNTBlastRadius", new TNTCommand());

        $this->worldData = new Config($this->getDataFolder() . "worlddata.json", Config::JSON);
        $this->messages = $this->getConfig()->get("messages", []);
        $this->formSelector = $this->getConfig()->get("form_selector", []);
        $this->formConfirmation = $this->getConfig()->get("form_confirmation", []);

        foreach ($this->worldData->getAll() as $world => $radius) {
            $this->blastRadius[$world] = (int)$radius;
        }
    }

    protected function onDisable(): void {
        foreach ($this->blastRadius as $world => $radius) {
            $this->worldData->set($world, $radius);
        }
        $this->worldData->save();
    }

    public static function getInstance() : self{
        return self::$instance;
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

        $form->setTitle($this->formSelector['title']);
        $form->addLabel($this->formSelector['label']);
        $form->addSlider($this->formSelector['slider'], 1, 25);

        $player->sendForm($form);
    }

    public function sendConfirmationUI(Player $player, int $radius) {
        $confirmForm = new ModalForm(function (Player $player, ?bool $data) use ($radius) {
            if ($data !== null) {
                if ($data) {
                    $this->setTNTBlastRadius($player, $radius);
                    $player->sendTitle(str_replace("{radius}", (string)$radius, $this->messages['radius_changed_title']));
                } else {
                    $player->sendMessage($this->messages['radius_change_canceled']);
                }
            }
        });

        $confirmForm->setTitle($this->formConfirmation['title']);
        $confirmForm->setContent(str_replace("{radius}", (string)$radius, $this->formConfirmation['content']));
        $confirmForm->setButton1($this->formConfirmation['yes_button']);
        $confirmForm->setButton2($this->formConfirmation['no_button']);
        $player->sendForm($confirmForm);
    }

    public function setTNTBlastRadius(Player $player, int $radius) {
        $world = $player->getWorld()->getFolderName();
        $this->blastRadius[$world] = $radius;
        $this->worldData->set($world, $radius);
        $this->worldData->save();
        $player->sendMessage(str_replace(
            ["{radius}", "{world}"],
            [(string)$radius, $world],
            $this->messages['radius_change_message']
        ));
    }
}
