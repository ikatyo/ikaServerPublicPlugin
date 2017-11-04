<?php
namespace ikatyo\EasyWastingReport;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\utils\config;
use PDO;

class Main extends PluginBase{
	function onEnable(){
		$this->getLogger()->info("§aEasyWastingReport§eを読み込みました。");
		$this->saveDefaultConfig();
	}
    
	//function onCommand(CommandSender $sender, Command $command, $label, array $args){
	//v1.2対応のため書き換え↓
	function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        $level = $sender->getLevel();
        $l = $level->getFolderName();
        $x = $sender->getX();
        $y = $sender->getY();
        $z = $sender->getZ();
		$u = $sender->getName();
		
		$cmd = strtolower($command->getName());

		switch($cmd){
	    	case "reportdata":
				$sender->sendMessage("§a================== §cReportData §a==================");
				$sender->sendMessage("報告する際§c必ず§f表示内容をスクリーンショットを撮影し");
				$sender->sendMessage("§c[重要]荒らされた該当箇所のスクリーンショットと一緒にアップロードして下さい");
				$sender->sendMessage("§c場合によっては対処できない可能性があります。");
				$sender->sendMessage("レポート書出日時:".date("H時i分s秒"));
				$sender->sendMessage("該当サーバ:§a".$s_name);
				$sender->sendMessage("対象地座標: §aX:".$x." §dY:".$y." §bZ:".$z);
				$sender->sendMessage("報告者名:".$u);
				$sender->sendMessage("World名:".$l);
				$sender->sendMessage("§a================================================");

				try{
		            $pdo = new PDO('mysql:host=(各自変更);dbname=(各自変更);charset=utf8','(各自変更)','(各自変更)',
		            array(PDO::ATTR_EMULATE_PREPARES => false));
		            $this->getLogger()->info("\n§aDBAccessCheck\nAccess:OK");
		                }catch (PDOException $e){
		        	$this->getLogger()->info("\n§aDBAccessCheck\n§cAccess:NG\nエラー内容: ".$e);
		        }

				$stmt = $pdo -> prepare("INSERT INTO report_data (server_name,w_name,t_data,point_x,point_y,point_z,u_name) VALUES (:server_name,:w_name,:t_data,:point_x,:point_y,:point_z,:u_name)");
				//DBに記録する内容を定義

				//対象サーバ名定義
				$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
				$s_name = $config->get("ServerName");
		        //対象ワールド定義
		        $w_name = $l;
				//時刻
		        $time_data = date("Y/m/d H:i:s");
		        //X座標
		        $point_x = $x;
		        //Y座標
		        $point_y = $y;
		        //Z座標
		        $point_z = $z;
		        //報告者名(コマンド実行者)
		        $u_name = $u;
		        //DataPush準備
		        $stmt->bindParam(':server_name', $s_name, PDO::PARAM_STR);
		        $stmt->bindParam(':w_name', $w_name, PDO::PARAM_STR);
		        $stmt->bindParam(':t_data', $time_data, PDO::PARAM_STR);
		        $stmt->bindParam(':point_x', $point_x, PDO::PARAM_STR);
		        $stmt->bindParam(':point_y', $point_y, PDO::PARAM_STR);
		        $stmt->bindParam(':point_z', $point_z, PDO::PARAM_STR);
				$stmt->bindParam(':u_name', $u_name, PDO::PARAM_STR);
			//DataPush!
		        $stmt->execute();
	    	break;
	    }
	}
}
