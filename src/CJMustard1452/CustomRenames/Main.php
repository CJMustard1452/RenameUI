<?php

namespace CJMustard1452\CustomRenames;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\FormAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	public $economyAPI;

	public function onEnable(){
		$this->economyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function (Player $sender, ?array $data = null){
			if($data == true){
				if(strlen(implode($data)) >= 1){
					if($this->getServer()->getPlayer($sender->getName())->getInventory()->getItemInHand()->getId() >= 1){
						if($this->economyAPI->myMoney($sender) >= 10000){
							$item = $this->getServer()->getPlayer($sender->getName())->getInventory()->getItemInHand()->setCustomName(implode(str_replace("&", "§", $data)));
							$this->getServer()->getPlayer($sender->getName())->getInventory()->setItemInHand($item);
							$this->economyAPI->reduceMoney($sender, 10000);
							$sender->sendMessage("§aItem renamed to " . implode(str_replace("&", "§", $data)) . "§r§a.");
						}else{
							$sender->sendMessage("§cYou do not have enough money to buy this.");
						}
					}else{
						$sender->sendMessage("§cYou are not holding an item.");
					}
				}else{
					$sender->sendMessage("§cPlease enter a name.");
				}
			}
			return true;
		});
		$form->setTitle("§l§7(§eRename§7)");
		$form->addLabel("§7Type your custom rename in the field below, use §l§f& §r§7for colors. \n \nColors: §f&f§7, §e&e§7, §b&b§7, §l&l§r§7, §a&a§7, §d&d§7, §0&0§7, §1&1§7, §2&2§7, §3&3§7, §4&4§7, §c&c§7, §5&5§7, §6&6§7, §7&7§7, §8&8§7, §9&9 \n \n§4NOTE §7Renames cost $10,000.");
		$form->addInput("Custom Item Name");
		$form->sendToPlayer($sender);
		return true;
	}
}
