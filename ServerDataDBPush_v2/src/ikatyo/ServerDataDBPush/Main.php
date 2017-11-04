<?php
namespace ikatyo\ServerDataDBPush;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use PDO;

class Main extends PluginBase implements Listener{
	function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getLogger()->info("§aServerDataDBPush§eを読み込みました。");
		try{
            		$pdo = new PDO('mysql:host=(各自変更);dbname=(各自変更);charset=utf8','(各自変更)','(各自変更)',
            		array(PDO::ATTR_EMULATE_PREPARES => false));
            		$this->getLogger()->info("\n§aDBAccessCheck\nAccess:OK");
                }catch (PDOException $e){
        		$this->getLogger()->info("\n§aDBAccessCheck\nAccess:NG");
        	}

		$stmt = $pdo -> prepare("INSERT INTO server_status (type, system_time) VALUES (:type, :system_time)");
		$s_status = "起動";
        $time_data = date("Y/m/d H:i:s");
        $stmt->bindParam(':type', $s_status, PDO::PARAM_STR);
		$stmt->bindParam(':system_time', $time_data, PDO::PARAM_STR);
        $stmt->execute();
	}

	public function onDisable(){
		try{
            $pdo = new PDO('mysql:host=(各自変更);dbname=(各自変更);charset=utf8','(各自変更)','(各自変更)',
            array(PDO::ATTR_EMULATE_PREPARES => false));
            $this->getLogger()->info("\n§aDBAccessCheck\nAccess:OK");
                }catch (PDOException $e){
        	$this->getLogger()->info("\n§aDBAccessCheck\nAccess:NG");
        }

		$stmt = $pdo -> prepare("INSERT INTO server_status (type, system_time) VALUES (:type, :system_time)");
		$s_status = "停止";
    	$time_data = date("Y/m/d H:i:s");
        $stmt->bindParam(':type', $s_status, PDO::PARAM_STR);
		$stmt->bindParam(':system_time', $time_data, PDO::PARAM_STR);
        $stmt->execute();
	}
    
    //暴言を検出してDBにPush
    public function onChat(PlayerChatEvent $e){
        $chat = $e->getMessage();
        $p = $e->getPlayer();
		$name = $p->getName();
        $ng = [
        		//死ね
        		"しーね",
	        	"しね",
		        "死ね",
		        "シネ",
        		//バカ
		        "ばーか",
		        "ばか",
		        "バカ",
		        //アホ
		        "あーほ",
		        "あほ",
		        "アホ",
		        //下ネタ
		        "ホモ",
		        "ほも",
		        "チンチン",
		        "ちんちん",
		        "?ん?ん",
		        "おっぱい",
		        "オッパイ",
		        "マンコ",
		        "まんこ",
		        //クソ鯖
		        "糞鯖",
		        "くそさば",
		        "クソサバ",
		        "くそ鯖",
		        "クソ鯖"
        ];
        if($this->isMatch($chat,$ng)){//一致してたら動く
            $this->chatngDBPush($name, $chat);
            $e->sendMessage("§c[Warn]禁止ワードが検出されました。場合によっては処罰される可能性があります");
        }else{
            $this->chatDBPush($name, $chat);
        }
    }
    function isMatch($text,array $keywords){
        foreach($keywords as $keyword){
            if(strpos($text,$keyword) !== false) return true;
        }
        return false;
    }

    //暴言検出時専用のPushイベント
    function chatngDBPush($name,$chat){
        try{
            $pdo = new PDO('mysql:host=(各自変更);dbname=(各自変更);charset=utf8','(各自変更)','(各自変更)',
            array(PDO::ATTR_EMULATE_PREPARES => false));
            //一々メッセージ出さなくていいよね。アクセス拒否の時だけ表示されるように仕様変更した。
                }catch (PDOException $e){
            $this->getLogger()->info("\n§aDBAccess:Error!");
        }

        $stmt = $pdo -> prepare("INSERT INTO log_word (name, chat, get_time) VALUES (:name, :chat, :get_time)");
        
        $time = date("Y/m/d H:i:s");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':chat', $chat, PDO::PARAM_STR);
        $stmt->bindParam(':get_time', $time, PDO::PARAM_STR);
        $stmt->execute();
    }

    //チャットログ専用のPushイベント
    function chatDBPush($name,$chat){
        try{
            $pdo = new PDO('mysql:host=(各自変更);dbname=(各自変更);charset=utf8','(各自変更)','(各自変更)',
            array(PDO::ATTR_EMULATE_PREPARES => false));
            //一々メッセージ出さなくていいよね。アクセス拒否の時だけ表示されるように仕様変更した。
                }catch (PDOException $e){
            $this->getLogger()->info("\n§aDBAccessCheck\nAccess:NG");
        }

        $stmt = $pdo -> prepare("INSERT INTO chat_log (name, chat, get_time) VALUES (:name, :chat, :get_time)");
        
        $time = date("Y/m/d H:i:s");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':chat', $chat, PDO::PARAM_STR);
        $stmt->bindParam(':get_time', $time, PDO::PARAM_STR);
        $stmt->execute();
    }
}
