<?php

namespace BedrockPlugins\ItemSign\commands;

use BedrockPlugins\ItemSign\ItemSign;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class ItemClearSignCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        $this->setPermission("command.clearsign");
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) return;
        if (!$sender->hasPermission("command.clearsign")) {
            $sender->sendMessage(ItemSign::$prefix . "You don't have permissions to use this command");
            return;
        }
        $item = $sender->getInventory()->getItemInHand();
        if ($item->getId() == Item::AIR) {
            $sender->sendMessage(ItemSign::$prefix . "You have to hold an item in your hand");
            return;
        }
        $lore = $item->getLore();
        if ($lore == []) {
            $sender->sendMessage(ItemSign::$prefix . "This item is not signed");
            return;
        }
        $sender->sendMessage(ItemSign::$prefix . "Item signature has been cleared");
        $sender->getInventory()->setItemInHand($item->setLore([]));
    }

}