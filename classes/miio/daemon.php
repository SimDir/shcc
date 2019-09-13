<?php

namespace miIO;

use DB;

class Daemon implements \SmartHome\DaemonInterface {

    const DAEMON_NAME='miio';

    private $storage;
    private $socketserver;
    private $devices;
    private $process_url;
    private $tokens=[];

    public function __construct($process_url) {
        $this->process_url=$process_url;
    }

    public function getName() {
        return self::DAEMON_NAME;
    }

    public function prepare() {
        $this->storage=new \SmartHome\DeviceMemoryStorage;
        $this->devices=$this->storage->getModuleDevices(self::DAEMON_NAME);
        $this->tokens=Tokens::getTokens();
        $this->socketserver=new SocketServer();
        $this->socketserver->setBroadcastSocket();
        SocketServer::sendDiscovery();
        DB::disconnect();
    }

    public function iteration() {
        $pkt=$this->socketserver->getPacket();
        if (!$pkt->isMiIOPacket()) {
            return;
        }
        $sid=$pkt->getDeviceId();
        if ($sid=='ffffffffffffffff') {
            return;
        }
        if (isset($this->devices[$sid])) {
            $this->devices[$sid]->update($pkt);
            $this->storage->setModuleDevices(self::DAEMON_NAME, $this->devices);
            $actions=$this->devices[$sid]->getActions();
            if (!is_null($actions)) {
                $data=['module'=>self::DAEMON_NAME, 'uid'=>$sid, 'data'=>$actions];
                file_get_contents($this->process_url.'?'.http_build_query($data));
            }
        } else {
            $this->devices[$sid]=new GenericDevice;
            if (isset($this->tokens[$sid])) {
                $this->devices[$sid]->setDeviceToken($this->tokens[$sid]);
            }
            $this->devices[$sid]->update($pkt);
            $this->storage->setModuleDevices(self::DAEMON_NAME, $this->devices);
        }
    }

    public function finish() {
        return;
    }

}
