<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\Player;
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
            if (isset($this->pendingRadiusChange[$event->getPlayer()->getName()])) {
                $radius = $this->pendingRadiusChange[$event->getPlayer()->getName()];
                $scaledRadius = max(1, min(25, $radius));
                $event->setRadius($scaledRadius);
                unset($this->pendingRadiusChange[$event->getPlayer()->getName()]);
            }
        }
    }
}
