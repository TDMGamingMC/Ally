<?php
namespace AE;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;

class Main extends PluginBase implements Listener{
	public $request = array();
	public $queue = array();
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this ,$this);
		@mkdir($this->getDataFolder());
@mkdir($this->getDataFolder()."Players/");	
	}
	
	
public function onPlayerLogin(PlayerPreLoginEvent $event){
        $ign = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        $file = ($this->getDataFolder()."Players/".$ign.".yml");  
            if(!file_exists($file)){
                $this->PlayerFile = new Config($this->getDataFolder()."Players/".$ign.".yml", Config::YAML);
                $this->PlayerFile->set($player->getName()." Allies!");
                $this->PlayerFile->save();
            }
        }
        
public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
 if(strtolower($cmd->getName()) === "ally") {
            if(isset($args[0])){
                $name = $args[0];
                $target = $this->getServer()->getPlayer($name);
                if($sender instanceof Player){
                if($target === null){
                	$sender->sendMessage("that player is not online!");
                	return false;
                }
                if($target instanceof Player){
                $sender->sendMessage("Ally request sent to ".$target->getName());
                 $target->sendMessage($sender->getName()." Wants to be allys! please do /accept to accpet thier allyship request!");
              //not needed rn  $task = new accept($this, $target);
		 //not needed rn	$this->getServer()->getScheduler()->scheduleDelayedTask($task, 600);
		 	//should this work?(below)
		$this->addQueue($target,$sender);
		$this->addRequest($sender,$target);
		 	return true;
            }
                }else{
                	$sender->sendMessage("Please do this command in game!");
                	return false;
                }
                }
 }
 if(strtolower($cmd->getName()) === "accept"){
 	 $this->RequesterFile = new Config($this->getDataFolder()."Players/".$this->queue[$target->getName()]["Requester"].".yml", Config::YAML);
 	 $this->PlayerFile = new Config($this->getDataFolder()."Players/".$sender->getName().".yml", Config::YAML);
 if(in_array($sender->getName(),$this->queue)){
 	$sender->sendMessage("Request from ".$this->queue[$target->getName()]["Requester"]." Accepted!");
        $this->RequesterFile->set($sender->getName(),"True");
        $this->PlayerFile->set($this->queue[$target->getName()]["Requester"],"True");
 	return true;
 }else{
 	$sender->sendMessage("You have no request!");
 	return false;
 }
}
}
public function addQueue(Player $p1, Player $p2){
	$this->queue[$p1->getName()] = array(
		"Requester" => $p2->getName());
}

public function addRequest(Player $p1, Player $p2){
	$this->request = array(
		"Sender" => $p1->getName(),
		"Target" => $p2->getName());
}

}

            
        
