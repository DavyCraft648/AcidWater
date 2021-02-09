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

	/** @var bool */
	private $raining = false;

	public function __construct(AcidWater $plugin)
	{
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick)
	{
		$onlinePlayers = $this->plugin->getServer()->getOnlinePlayers();
		$config = $this->plugin->getConfig();
		$poison1 = new EffectInstance(Effect::getEffect(Effect::POISON));
		$effect1 = $poison1->setVisible($config->getNested("effect.visible", true))
			->setDuration($config->getNested("acid.duration-ticks", 60))
			->setAmplifier($config->getNested("effect.amplifier", 0));
		if ($config->getnested("weather.realtime"))
		{
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
			if($result['status'] === 648 or $result['status'] === 400) return;
			foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
				if ((string)$result['list']['respon']['cuaca'] === "Rain"
					or (string)$result['list']['respon']['cuaca'] === "Thunderstorm"
				) {
					$pk = new LevelEventPacket();
					$pk->evid = LevelEventPacket::EVENT_START_RAIN;
					$pk->data = 1000 * 100;
					$onlinePlayer->dataPacket($pk);
					$this->raining = true;
				} else {
					$pk = new LevelEventPacket();
					$pk->evid = LevelEventPacket::EVENT_STOP_RAIN;
					$pk->data = 1000 * 100;
					$onlinePlayer->dataPacket($pk);
					$this->raining = false;
				}
			}
		}
		foreach ($onlinePlayers as $onlinePlayer) {
			if ($config->getNested("acid.water", true) and
				$this->plugin->isInWater($onlinePlayer)
			) $onlinePlayer->addEffect(clone $effect1);

			if ($config->getNested("acid.rain", true)
				and $this->raining
				and $this->plugin->canSeeSky($onlinePlayer)
			) $onlinePlayer->addEffect(clone $effect1);
		}
	}
}