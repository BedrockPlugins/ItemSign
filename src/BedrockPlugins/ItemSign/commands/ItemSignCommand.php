<?php

namespace BedrockPlugins\ItemSign\commands;

use BedrockPlugins\ItemSign\ItemSign;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class ItemSignCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        $this->setPermission("command.itemsign");
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) return;
        if (!$sender->hasPermission("command.itemsign")) {
            $sender->sendMessage(ItemSign::$prefix . "You don't have permissions to use this command");
            return;
        }
        $item = $sender->getInventory()->getItemInHand();
        if ($item->getId() == Item::AIR) {
            $sender->sendMessage(ItemSign::$prefix . "You have to hold an item in your hand");
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage(ItemSign::$prefix . "You have to enter a message");
            return;
        }
        $message = implode(" ", $args);
        if (strlen($message) > ItemSign::getMaxLength()) {
            $sender->sendMessage(ItemSign::$prefix . "The maximum length is " . strval(ItemSign::getMaxLength()));
            return;
        }
        if (!ItemSign::getAllowColor()) {
            $message = str_replace("§", "", $message);
        }
        $lines = [];
        $lines[] = ItemSign::getSignColor() . $message;
        if (($msg = ItemSign::getSignMessage()) != null) {
            $msg = str_replace("{player}", $sender->getName(), $msg);
            $lines[] = $msg;
        }
        $sender->getInventory()->setItemInHand($item->setLore($lines));
        $sender->sendMessage(ItemSign::$prefix . "Item has been signed");
    }

}