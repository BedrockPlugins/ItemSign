<?php

namespace BedrockPlugins\ItemSign;

use BedrockPlugins\ItemSign\commands\ItemClearSignCommand;
use BedrockPlugins\ItemSign\commands\ItemSignCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class ItemSign extends PluginBase {

    public static $prefix = TextFormat::AQUA . "ItemSign " . TextFormat::DARK_GRAY . "» " . TextFormat::GRAY;

    private static $maxlength = 20, $allowcolor = true, $signmessage = "§7Item signed by {player}", $signcolor = TextFormat::GRAY;

    public function onEnable() {
        $this->saveResource("config.yml", false);

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        if ($config->exists("maxlength") && is_numeric($config->get("maxlength"))) {
            self::$maxlength = $config->get("maxlength");
        }
        if ($config->exists("allowcolor") && is_bool($config->get("allowcolor"))) {
            self::$allowcolor = $config->get("allowcolor");
        }
        if ($config->exists("signmessage")) {
            self::$signmessage = $config->get("signmessage");
        }
        if ($config->exists("signcolor")) {
            self::$signcolor = $config->get("signcolor");
        }

        $this->getServer()->getCommandMap()->register("itemsign", new ItemSignCommand("itemsign", "Signs the item you're holding", null, ["sign"]));
        $this->getServer()->getCommandMap()->register("itemclearsign", new ItemClearSignCommand("itemclearsign", "Clears the signature of an item", null, ["signclear"]));

    }

    public static function getMaxLength() : int {
        return self::$maxlength;
    }

    public static function getAllowColor() : bool {
        return self::$allowcolor;
    }

    public static function getSignMessage() : string {
        return self::$signmessage;
    }

    public static function getSignColor() : string {
        return self::$signcolor;
    }

}