# AcidWater - Make Water/Raining Dangerous
Make water, raining or both (can be edited in config) give players poison effect.

You can find the Nukkit version [here](https://cloudburstmc.org/resources/acidwater.263), and the sourcecode [here](https://github.com/PetteriM1/AcidWater).

### How to use
 - Get the compiled `.phar` file from poggit [here](https://poggit.pmmp.io/ci/DavyCraft648/AcidWater/~)
 - Put the `plugin.phar` file to your plugin folder


 - This plugin __doesn't have__ any commands and permissions (may add later?)
 - Acid rain __only__ accept realtime weather atm (may add later?)

### Config:
```yaml
acid:
  water: true
  rain: true
  duration-ticks: 60
  check-ticks: 20

effect:
  amplifier: 1
  visible: true

weather:
  realtime: true
  location: Jakarta
```
The config is easy to understand, right?

