<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\entity\object\PrimedTNT;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event) {
        $tnt = $event->getEntity();
        if ($tnt instanceof PrimedTNT) {
            $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
            $blastRadius = $config->get("blast-radius", 4); // Default blast radius is 4.
            
            $scaledRadius = max(1, min(8, $blastRadius));
            $event->setRadius($scaledRadius);
        }
    }
}
