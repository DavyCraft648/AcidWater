<?php

namespace DavyCraft648\AcidWater;

use DavyCraft648\AcidWater\task\AcidTask;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class AcidWater extends PluginBase
{
	public function onEnable(): void
	{
		$this->saveDefaultConfig();
		$this->getScheduler()->scheduleRepeatingTask(new AcidTask($this), $this->getConfig()->getNested("acid.check-ticks", 20));
	}

	public function canSeeSky(Player $player): bool
	{
		$level = $player->getLevel();
		$server = $this->getServer();
		$pos = $player->getPosition();
		if ($server->getName() === "Altay") {
			return $level->canSeeSky($pos);
		} else {
			if($level->isChunkLoaded($pos->x >> 4, $pos->z >> 4)){
				$chunk = $level->getChunk($pos->x >> 4, $pos->z >> 4);
				return $pos->y >= $chunk->getHeightMap($pos->x & 15, $pos->z & 15);
			}
		}
		return false;
	}

	public function isInWater(Player $player): bool
	{
		$level = $player->getLevel();
		$blocks = [
			$level->getBlockIdAt($player->getFloorX(), $player->getFloorY() + 1, $player->getFloorZ()),
			$level->getBlockIdAt($player->getFloorX(), $player->getFloorY(), $player->getFloorZ())
		];
		foreach ($blocks as $blockId) if ($blockId === 9 or $blockId === 8) return true;
		return false;
	}
}