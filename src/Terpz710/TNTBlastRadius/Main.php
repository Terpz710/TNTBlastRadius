<?php

namespace Terpz710\TNTBlastRadius;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use Terpz710\TNTBlastRadius\TNTCommand;
use Terpz710\TNTBlastRadius\TNTForm;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("tntradius", new TNTCommand($this));
    }
}
