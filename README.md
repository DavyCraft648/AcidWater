# AcidWater - Make Water/Raining Dangerous
Make water, raining or both (can be edited in config) give players poison effect.

You can find the Nukkit version [here](https://cloudburstmc.org/resources/acidwater.263), and the sourcecode [here](https://github.com/PetteriM1/AcidWater).

### How to use
 - Get the compiled `.phar` file from poggit [here](https://poggit.pmmp.io/ci/DavyCraft648/AcidWater/~)
 - Put the `plugin.phar` file to your plugin folder


 - This plugin __doesn't have__ any commands and permissions (may add later?)
 - Acid rain accept real-time weather and TeaSpoon (may add later?)

### Config:
```yaml
acid:

  # Acid water feature: true|false
  # Default: true
  water: true

  # Acid rain feature: true|false
  # Default: true
  rain: true

  # Acid duration in ticks
  # 1 second = 20 ticks
  # Default: 60
  duration-ticks: 60

  # Acid check in ticks
  # Default: 20
  check-ticks: 20

effect:

  # Effect amplifier
  # Default: 1
  amplifier: 1

  # Effect visibility: true|false
  # Default: true
  visible: true

weather:

  # Weather mode: RealTime|TeaSpoon
  # Default: RealTime
  mode: RealTime

  # Weather location IRL (Only works when weather mode set to RealTime)
  # Default: Jakarta
  location: Jakarta
```
The config is easy to understand, right?

