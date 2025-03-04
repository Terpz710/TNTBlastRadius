<?php

declare(strict_types=1);

namespace terpz710\tntblastradius;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;

use pocketmine\entity\object\PrimedTNT;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use terpz710\tntblastradius\command\TNTCommand;

use terpz710\pocketforms\CustomForm;
use terpz710\pocketforms\ModalForm;

class Main extends PluginBase implements Listener {

    protected static self $instance;
    
    private array $blastRadius = [];
    private array $messages;
    private array $formSelector;
    private array $formConfirmation;

    private Config $worldData;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("TNTBlastRadius", new TNTCommand());

        $this->worldData = new Config($this->getDataFolder() . "worlddata.json", Config::JSON);
        $this->messages = $this->getConfig()->get("messages", []);
        $this->formSelector = $this->getConfig()->get("form_selector", []);
        $this->formConfirmation = $this->getConfig()->get("form_confirmation", []);

        foreach ($this->worldData->getAll() as $world => $radius) {
            $this->blastRadius[$world] = (int) $radius;
        }
    }

    protected function onDisable() : void{
        foreach ($this->blastRadius as $world => $radius) {
            $this->worldData->set($world, $radius);
        }
        $this->worldData->save();
    }

    public static function getInstance() : self{
        return self::$instance;
    }

    public function onEntityPreExplode(EntityPreExplodeEvent $event) : void{
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $world = $tnt->getWorld()->getFolderName();
            $radius = $this->blastRadius[$world] ?? 4;
            $event->setRadius($radius);
        }
    }

    public function openRadiusSelectorUI(Player $player) : void{
        $form = new CustomForm();
        $form->setTitle($this->formSelector['title'])
            ->addLabel($this->formSelector['label'])
            ->addSlider($this->formSelector['slider'], 1, 25)
            ->setCallback(function (Player $player, ?array $data) {
                if ($data !== null && isset($data[1])) {
                    $radius = (int) $data[1];
                    if ($radius >= 1 && $radius <= 25) {
                        $this->sendConfirmationUI($player, $radius);
                    }
                }
            });

        $player->sendForm($form);
    }

    public function sendConfirmationUI(Player $player, int $radius): void {
        $confirmForm = new ModalForm();
        $confirmForm->setTitle($this->formConfirmation['title'])
            ->setContent(str_replace("{radius}", (string) $radius, $this->formConfirmation['content']))
            ->setButton1($this->formConfirmation['yes_button'])
            ->setButton2($this->formConfirmation['no_button'])
            ->setCallback(function (Player $player, ?bool $data) use ($radius) {
                if ($data !== null) {
                    if ($data) {
                        $this->setTNTBlastRadius($player, $radius);
                        $player->sendTitle(str_replace("{radius}", (string) $radius, $this->messages['radius_changed_title']));
                    } else {
                        $player->sendMessage($this->messages['radius_change_canceled']);
                    }
                }
            });

        $player->sendForm($confirmForm);
    }

    public function setTNTBlastRadius(Player $player, int $radius) : void{
        $world = $player->getWorld()->getFolderName();
        $this->blastRadius[$world] = $radius;
        $this->worldData->set($world, $radius);
        $this->worldData->save();
        $player->sendMessage(str_replace(
            ["{radius}", "{world}"],
            [(string) $radius, $world],
            $this->messages['radius_change_message']
        ));
    }
}
