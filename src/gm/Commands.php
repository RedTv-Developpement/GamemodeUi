<?php
namespace gm;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use gm\Main;

class Commands
{

	private function getLang($value)
	{
		return Main::getInstance()->lang->get($value);
	}
	
	public function setGamemode(CommandSender $sender, string $label, array $args)
	{	
		if($sender instanceof Player)
		{	
			if(empty($args[0]))
			{
				$this->OpenGamemodeUI($sender);
			} else {	
				$target = $sender->getServer()->getPlayer($args[0]);
				if(isset($target) && $target->isOnline())
				{
					$this->OpenGamemodeUI($target);
				} else {
					$sender->sendMessage($this->getLang("gamemode.prefix") . " " . $this->getLang("gamemode.player.is.not.online"));
				}
			}	
		} else {
			$sender->sendMessage($this->getLang("gamemode.prefix") . " " . $this->getLang("gamemode.console"));
		}
	}
	
	private function OpenGamemodeUI(Player $player)
	{	
		$form = new SimpleForm(function(Player $player, int $data = null)
		{
			if($data === null)
			{
				return true;
			}
			
			switch($data)
			{
				case 0:
					$player->setGamemode(0);
					$this->sendMessage($player, 0);
					break;
					
				case 1:
					$player->setGamemode(1);
					$this->sendMessage($player, 1);
					break;
				
				case 2:
					$player->setGamemode(2);	
					$this->sendMessage($player, 2);		
					break;
				
				case 3:
					$player->setGamemode(3);		
					$this->sendMessage($player, 3);		
					break;
				
			}	
		});
		$form->setTitle($this->getLang("gamemode.title"));	
		$form->setContent($this->getLang("gamemode.content"));	
		$form->addButton($this->getLang("gamemode.button.survival"));
		$form->addButton($this->getLang("gamemode.button.creative"));
		$form->addButton($this->getLang("gamemode.button.adventure"));
		$form->addButton($this->getLang("gamemode.button.spectator"));
		$form->sendToPlayer($player);
		return $form;	
	}
	
	private function sendMessage(Player $player, string $message)
	{	
		switch($message)
		{
			case 0:
				$player->sendMessage($this->getLang("gamemode.prefix") . " " . $this->getLang("gamemode.message.survival"));
				break;
				
			case 1:
				$player->sendMessage($this->getLang("gamemode.prefix") . " " . $this->getLang("gamemode.message.creative"));
				break;
			
			case 2:
				$player->sendMessage($this->getLang("gamemode.prefix") . " " . $this->getLang("gamemode.message.adventure"));
				break;
			
			case 3:
				$player->sendMessage($this->getLang("gamemode.prefix") . " " . $this->getLang("gamemode.message.spectator"));
				break;
		}
	}

}