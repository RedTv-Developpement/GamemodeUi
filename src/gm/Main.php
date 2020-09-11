<?php
namespace gm;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\MainLogger;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use gm\Commands;

class Main extends PluginBase implements Listener
{

	private static $main;
	private static $devMod = false;
	public $config, $lang;
	
	public function unloadCommands(array $commands = [])
  	{
               $map = Server::getInstance()->getCommandMap();
               foreach ($commands as $cmd)
               {
                       $command = $map->getCommand($cmd);
                       if ($command !== null)
                       {
                             $map->unregister($command);
                       }
               }
 	}
	
	private function registerEvent()
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public static function getInstance(): Main
	{
		return self::$main;
	}
	
	public function onLoad()
	{
		self::$main = $this;
	//	$this->config = New Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->lang = New Config($this->getDataFolder() . "Lang.yml", Config::YAML);
		if(!file_exists($this->getDataFolder() . "Lang.yml")){
			$this->saveResource("Lang.yml");
		}
		$this->setConfLang();
		MainLogger::getLogger()->notice("§4If you have just installed the plugin, please restart your server so that the plugin is properly configured ^^");
		MainLogger::getLogger()->notice($this->lang->get("gamemode.prefix") . " " . $this->lang->get("gamemode.loading"));
	}
	
	public function onEnable()
	{
		if(self::$devMod != false)
		{
			$this->getServer()->shutdown();
			MainLogger::getLogger()->warning($this->lang->get("gamemode.prefix") . " " . $this->lang->get("gamemode.devmod"));
		}
		else
		{
			if(!$this->getPluginFormUi())
			{
				MainLogger::getLogger()->info($this->lang->get("gamemode.prefix") . " " . $this->lang->get("gamemode.fromapi"));
			}
			$this->registerEvent();
			$this->unloadCommands(["gamemode"]);
			MainLogger::getLogger()->notice($this->lang->get("gamemode.prefix") . " " . $this->lang->get("gamemode.enabling"));
		}
	}
	
	public function onDisable()
	{
		MainLogger::getLogger()->notice($this->lang->get("gamemode.prefix") . " " . $this->lang->get("gamemode.disabling"));
	}
	
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
	{
		$commandList = New Commands();
		
		switch($command->getName())
		{
			case strtolower("gamemode"):
				$commandList->setGamemode($sender, $label, $args);
				break;
			case strtolower("gm"):
				$commandList->setGamemode($sender, $label, $args);
				break;
		}
		
		return false;
	}
	
	private function getPluginFormUi()
	{
		return $this->getServer()->getPluginManager()->getPlugin("FormAPI");
	}
	
	public function getConfigs()
	{
		return $this->config;
	}
	
	private function setConfLang()
	{
		$conf = New Config($this->getDataFolder() . "Lang.yml", Config::YAML);
		if(file_exists($this->getDataFolder() . "Lang.yml")){
			$conf->set("gamemode.prefix", "§cGamemodeUi >>");
			$conf->set("gamemode.loading", "§eLoading §5GamemodeUi §ein progress...");
			$conf->set("gamemode.enabling", "§aEnabling §5GamemodeUi §ein progress...");
			$conf->set("gamemode.disabling", "§4Disabling §5GamemodeUi §4in progress...");
			$conf->set("gamemode.fromapi", "§cFormAPI is not installed: §5https://poggit.pmmp.io/r/101416/libFormAPI_dev-3.phar");
			$conf->set("gamemode.devmod", "§cThe DevMod has been activated so the server has been stopped ...");
			
			$conf->set("gamemode.title", "§e<< GamemodeUi >>");
			$conf->set("gamemode.content", "§eSelect Your GameMode.");
			$conf->set("gamemode.button.survival", "§bSurvival");
			$conf->set("gamemode.button.creative", "§bCreative");
			$conf->set("gamemode.button.adventure", "§bAdventure");
			$conf->set("gamemode.button.spectator", "§bSpectator");
			
			$conf->set("gamemode.message.survival", "§eYou are now in §aSurvival§e.");
			$conf->set("gamemode.message.creative", "§eYou are now in §aCreative§e.");
			$conf->set("gamemode.message.adventure", "§eYou are now in §aAdventure§e.");
			$conf->set("gamemode.message.spectator", "§eYou are now in §aSpectator§e.");
			$conf->set("gamemode.console", "§cThis command cannot be executed on the console !");
			$conf->set("gamemode.player.is.not.online", "§cThe player is not online !");
			$conf->save();
			$conf->reload();
		}
	}
}
