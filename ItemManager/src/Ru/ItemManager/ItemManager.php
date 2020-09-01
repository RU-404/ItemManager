<?php

/**
 *  _____                _____
 * |_   _|              |  __ \
 *   | |  __ _ _ __ ___ | |__) |   _
 *   | | / _` | '_ ` _ \|  _  / | | |
 *  _| || (_| | | | | | | | \ \ |_| | ___
 * |_____\__,_|_| |_| |_|_|  \_\__,_|(___)
 *
 * @name : ItemManager
 * @author : IamRu_
 * @api : 3.x.x
 * @github : github.com/RU-404
 */

namespace Ru\ItemManager;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class ItemManager extends PluginBase implements Listener
{
    /**@var string*/
    public static $sy = "§b[ §f! §b]§f ";

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "iv"){
            if (!$sender->isOp()){
                $sender->sendMessage(self::$sy . "권한이 존재하지 않습니다!");
                return false;
            }
            if (!$sender instanceof Player) {
                $sender->sendMessage(self::$sy . "인게임에서 실행해주세요!");
                return false;
            }else{
                if (isset($args[0])){
                    switch ($args[0]){
                        case "name":
                        case "nm":
                            if (!isset($args[1])){
                                $sender->sendMessage(self::$sy."/iv [ name:nm ] [ 설정할 이름 ] - 아이템의 이름을 바꿉니다");
                                return false;
                            }
                            if ($sender->getInventory()->getItemInHand()->getId() === 0){
                                $sender->sendMessage(self::$sy."공기의 이름은 바꿀 수 없습니다!");
                                return false;
                            }
                            else{
                                $sender->sendMessage(self::$sy."아이템의 이름을 {$args[1]} §r§f(으)로 바꿨습니다!");
                                $sender->getInventory()->setItemInHand($sender->getInventory()->getItemInHand()->setCustomName("§r§f".$args[1]));
                                return true;
                            }
                        case "description":
                        case "des":
                            if (!isset($args[1])){
                                $sender->sendMessage(self::$sy."/iv [ description:des ] [ 추가할 설명 ] - 아이템의 설명을 추가합니다");
                                return false;
                            }
                            if ($sender->getInventory()->getItemInHand()->getId() === 0){
                                $sender->sendMessage(self::$sy."공기의 설명은 바꿀 수 없습니다!");
                                return false;
                            }
                            else{
                                $l = $sender->getInventory()->getItemInHand()->getLore();
                                isset($l) ? $lore = $l : $lore = [];
                                array_push($lore,"§r§f".$args[1]);
                                $sender->sendMessage(self::$sy."아이템의 설명에 {$args[1]} §r§f(을)를 추가했습니다!");
                                $sender->getInventory()->setItemInHand($sender->getInventory()->getItemInHand()->setLore($lore));
                                return true;
                            }
                        case "meta":
                        case "damage":
                            if (!isset($args[1])){
                                $sender->sendMessage(self::$sy."/iv [ meta:damage ] [ 설정할 데미지 ] - 아이템의 데미지를 설정합니다");
                                return false;
                            }
                                if ($sender->getInventory()->getItemInHand()->getId() === 0){
                                $sender->sendMessage(self::$sy."공기의 데미지는 바꿀 수 없습니다!");
                                return false;
                            }
                                if (!is_numeric($args[1]) or $args[1]<0 or $args[1]>=32766){
                                $sender->sendMessage(self::$sy."데미지는 0보다 크고 32766보다 작은 수여야 합니다!");
                                return false;
                            }
                            else{
                                $meta = round($args[1]);
                                $sender->sendMessage(self::$sy."아이템의 데미지를 {$meta} §r§f(으)로 변경했습니다!");
                                $sender->getInventory()->setItemInHand($sender->getInventory()->getItemInHand()->setDamage($args[1]));
                                return true;
                            }
                        case "see":
                            $item = $sender->getInventory()->getItemInHand();
                            $sender->sendMessage(self::$sy."{$item->getId()} : {$item->getDamage()}");
                            return true;
                        default:
                            $sender->sendMessage(self::$sy."/iv [ name:nm ] [ 설정할 이름 ] - 아이템의 이름을 바꿉니다");
                            $sender->sendMessage(self::$sy."/iv [ description:des ] [ 추가할 설명 ] - 아이템의 설명을 추가합니다");
                            $sender->sendMessage(self::$sy."/iv [ meta:damage ] [ 설정할 데미지 ] - 아이템의 데미지를 설정합니다");
                            $sender->sendMessage(self::$sy."/iv see - 손에 든 아이템의 정보를 확인합니다");
                            return false;
                    }
                }else{
                    $sender->sendMessage(self::$sy."/iv [ name:nm ] [ 설정할 이름 ] - 아이템의 이름을 바꿉니다");
                    $sender->sendMessage(self::$sy."/iv [ description:des ] [ 추가할 설명 ] - 아이템의 설명을 추가합니다");
                    $sender->sendMessage(self::$sy."/iv [ meta:damage ] [ 설정할 데미지 ] - 아이템의 데미지를 설정합니다");
                    $sender->sendMessage(self::$sy."/iv see - 손에 든 아이템의 정보를 확인합니다");
                    return false;
                }
            }
        }elseif ($command->getName() === "clear"){
            if (!$sender->isOp()){
                $sender->sendMessage(self::$sy . "권한이 존재하지 않습니다!");
                return false;
            }
            if (!$sender instanceof Player) {
                $sender->sendMessage(self::$sy . "인게임에서 실행해주세요!");
                return false;
            }else{
                if (isset($args[0])){
                    if ($this->getServer()->getPlayer($args[0]) === null){
                        $sender->sendMessage(self::$sy . "존재하지 않는 플레이어입니다!");
                        return false;
                    }else{
                        $this->getServer()->getPlayer($args[0])->getInventory()->setContents([]);
                        $this->getServer()->getPlayer($args[0])->getArmorInventory()->setContents([]);
                        $sender->sendMessage(self::$sy . "성공적으로 {$args[0]} 플레이어의 인벤토리를 청소했습니다!");
                        $this->getLogger()->info("{$args[0]} 님의 인벤토리를 {$sender->getName()} 님이 청소했습니다!");
                        return true;
                    }
                }else{
                    $sender->getInventory()->setContents([]);
                    $sender->getArmorInventory()->setContents([]);
                    $sender->sendMessage(self::$sy . "성공적으로 인벤토리를 비웠습니다!");
                    return true;
                }
            }
        }
        return false;
    }
}
