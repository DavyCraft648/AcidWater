<?php

namespace DavyCraft648\AcidWater;

use CortexPE\Main;
use DavyCraft648\AcidWater\task\AcidTask;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class AcidWater extends PluginBase
{
	private $raining = false;

	public function onEnable(): void
	{
		$this->saveDefaultConfig();
		$acidCheckTicks = $this->getConfig()->getNested("acid.check-ticks", 20);
		$this->getScheduler()->scheduleRepeatingTask(new AcidTask($this), $acidCheckTicks);
	}

	/**
	 * Set weather status to raining
	 * @param bool $raining
	 */
	public function setRaining(bool $raining = true)
	{
		$this->raining = $raining;
	}

	/**
	 * Check if level is raining|Check if it is raining
	 * @param Level|Player|null $level
	 * @return bool
	 */
	public function isRaining($level = null): bool
	{
		if (strtolower($this->getConfig()->getNested("weather.mode")) === "teaspoon") {
			$teaSpoonPlugin = $this->getServer()->getPluginManager()->getPlugin("TeaSpoon");
			if ($level instanceof Player){
				$level = $level->getLevel();
			}
			if (!is_null($teaSpoonPlugin) and ($level instanceof Level)) {
				$levelId = $level->getId();
				$weather = isset(Main::$weatherData[$levelId]) ? Main::$weatherData[$levelId] : null;
				if ($weather !== null and ($weather->isRainy() or $weather->isRainyThunder())) {
					$this->setRaining();
				} else $this->setRaining(false);
			}
		}
		return $this->raining;
	}

	/**
	 * Check if player is sheltering
	 * @param Player $player
	 * @return bool
	 */
	public function canSeeSky(Player $player): bool
	{
		$level = $player->getLevel();
		$pos = $player->getPosition();
		if ($level->isChunkLoaded($pos->x >> 4, $pos->z >> 4)) {
			$chunk = $level->getChunk($pos->x >> 4, $pos->z >> 4);
			return $pos->y >= $chunk->getHeightMap($pos->x & 15, $pos->z & 15);
		}
		return false;
	}

	/**
	 * Check if player is in water
	 * @param Player $player
	 * @return bool
	 */
	public function isInWater(Player $player): bool
	{
		$level = $player->getLevel();
		$blockIdAtHead = $level->getBlockIdAt($player->getFloorX(), $player->getFloorY() + 1, $player->getFloorZ());
		$blockIdAtFeet = $level->getBlockIdAt($player->getFloorX(), $player->getFloorY(), $player->getFloorZ());
		if ($blockIdAtHead === 9 or $blockIdAtHead === 8 or $blockIdAtFeet === 9 or $blockIdAtFeet === 8) return true;
		return false;
	}
}