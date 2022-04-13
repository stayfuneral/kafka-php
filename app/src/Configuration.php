<?php

namespace Ramapriya;

use RdKafka\Conf;
use RdKafka\TopicConf;

final class Configuration
{
    private static ?Configuration $instance = null;

    private Conf $conf;
    private TopicConf $topicConf;

    public static function createInstance(array $configs = []): ?Configuration
    {
        if(is_null(self::$instance)) {
            self::$instance = new self($configs);
        }

        return self::$instance;
    }

    private function __construct(private array $configs = [])
    {
        $this->setConf(new Conf());
        $this->setTopicConf(new TopicConf());
    }


    /**
     * @return Conf
     */
    public function getConf(): Conf
    {
        return $this->conf;
    }

    /**
     * @param Conf $conf
     */
    public function setConf(Conf $conf): void
    {
        $this->conf = $conf;

        if(isset($this->configs['global'])) {
            $this->setConfigs($this->conf, $this->configs['global']);
        }
    }

    /**
     * @return TopicConf
     */
    public function getTopicConf(): TopicConf
    {
        return $this->topicConf;
    }

    /**
     * @param TopicConf $topicConf
     */
    public function setTopicConf(TopicConf $topicConf): void
    {
        $this->topicConf = $topicConf;

        if(isset($this->configs['topic'])) {
            $this->setConfigs($this->topicConf, $this->configs['topic']);
        }
    }

    private function setConfigs(Conf|TopicConf $conf, $configs)
    {
        foreach ($configs as $name => $config) {
            $conf->set($name, $config);
        }
    }

}