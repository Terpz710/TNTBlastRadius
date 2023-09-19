<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use Terpz710\TNTBlastRadius\TNTCommand;
use Terpz710\TNTBlastRadius\TNTForm;

class Main extends PluginBase implements Listener {

    private $pendingRadiusChange = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("tntradius", new TNTCommand($this));
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            
            $exploder = $tnt->getOwningEntity();

            if ($exploder instanceof Player) {
                if (isset($this->pendingRadiusChange[$exploder->getName()])) {
                    $radius = $this->pendingRadiusChange[$exploder->getName()];
                    $scaledRadius = max(1, min(25, $radius));
                    $event->setRadius($scaledRadius);
                    unset($this->pendingRadiusChange[$exploder->getName()]);
                }
            }
        }
    }

    public function setPendingRadiusChange(Player $player, int $radius): void {
        $this->pendingRadiusChange[$player->getName()] = $radius;
    }
}
