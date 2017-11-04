<?php

namespace JoinStage;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\network\mcpe\protocol\TransferPacket;
use pocketmine\level\Position;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getLogger()->info("Hello World :3");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDisable(){
		$this->getLogger()->info("Goodbye World :3");
	}

	public function onMove(PlayerMoveEvent $event){
	$p = $event->getPlayer();
		if(
			(126 <= $p->x && $p->x <= 130) &&
			(4 <= $p->y && $p->y <= 6) &&
			(100 <= $p->z && $p->z <= 104)
		){
			// 入ってた！
			$p->sendMessage("資源ワールドに移動します");
			$p->teleport(new Position(203, 69, 65, $this->getServer()->getLevelByName("sigen")));
		}
	}

}
