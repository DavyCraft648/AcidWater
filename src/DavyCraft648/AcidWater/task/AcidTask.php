<?php

namespace DavyCraft648\AcidWater\task;

use DavyCraft648\AcidWater\AcidWater;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\scheduler\Task;

class AcidTask extends Task
{
	/** @var AcidWater  */
	private $plugin;

	public function __construct(AcidWater $plugin)
	{
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick)
	{
		$onlinePlayers = $this->plugin->getServer()->getOnlinePlayers();
		$config = $this->plugin->getConfig();
		$poison = new EffectInstance(Effect::getEffect(Effect::POISON));
		$effect = $poison->setVisible($config->getNested("effect.visible", true))
			->setDuration($config->getNested("acid.duration-ticks", 60))
			->setAmplifier($config->getNested("effect.amplifier", 0));
		$weatherModeConfig = $config->getnested("weather.mode", "realtime");
		if (strtolower($weatherModeConfig) === "realtime") {
			$location = $config->getNested("weather.location", "Jakarta");
			$url = "https://rest.farzain.com/api/cuaca.php?id={$location}&apikey=O8mUD3YrHIy9KM1fMRjamw8eg";
			try {
				$contents = file_get_contents($url, true);
				$data = json_decode($contents, true);
				$result = [
					'status' => $data['status'],
					'list' => $data
				];
			} catch (\Exception $e) {
				$result = ['status' => 648];
			}
			$status = $result['status'];
			if ($status === 648 or $status === 400) return;
			$cuaca = (string) $result['list']['respon']['cuaca'];
			foreach ($onlinePlayers as $onlinePlayer) {
				$pks = [
					new LevelEventPacket(),
					new LevelEventPacket(),
				];
				$pks[0]->evid = LevelEventPacket::EVENT_STOP_RAIN;
				$pks[0]->data = 1000 * 100;
				$pks[1]->evid = LevelEventPacket::EVENT_STOP_THUNDER;
				$pks[1]->data = 1000 * 100;
				switch ($cuaca) {
					case "Rain":
						$pks[0]->evid = LevelEventPacket::EVENT_START_RAIN;
						$pks[0]->data = 1000 * 100;
						$this->plugin->setRaining();
						break;
					case "Thunderstorm":
						$pks[0]->evid = LevelEventPacket::EVENT_START_RAIN;
						$pks[0]->data = 1000 * 100;
						$pks[1]->evid = LevelEventPacket::EVENT_START_THUNDER;
						$pks[1]->data = 1000 * 100;
						$this->plugin->setRaining();
						break;
					default:
						$this->plugin->setRaining(false);
						break;
				}
				foreach ($pks as $pk) {
					$onlinePlayer->dataPacket($pk);
				}
			}
		}
		foreach ($onlinePlayers as $onlinePlayer) {
			$isInWater = $this->plugin->isInWater($onlinePlayer);
			$canSeeSky = $this->plugin->canSeeSky($onlinePlayer);
			$isRaining = $this->plugin->isRaining($onlinePlayer);
			if ($config->getNested("acid.water", true) and $isInWater) {
				$onlinePlayer->addEffect(clone $effect);
			}
			if ($config->getNested("acid.rain", true)) {
				if ($isRaining and $canSeeSky) {
					$onlinePlayer->addEffect(clone $effect);
				}
			}
		}
	}
}