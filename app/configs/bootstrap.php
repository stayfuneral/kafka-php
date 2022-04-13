<?php

use Ramapriya\Configuration;
use RdKafka\Conf;
use RdKafka\TopicConf;

const DEFAULT_TOPIC_NAME = 'IDontKnowHowSetFewPartitionsInTopic';
const DEFAULT_GROUP_ID = 'kafka_dev';

require_once dirname(__DIR__) . '/vendor/autoload.php';

$configs = [
    'global' => [
        'bootstrap.servers' => 'kafka',
        'enable.auto.commit' => 'false',
        'group.id' => DEFAULT_GROUP_ID,
        'client.id' => DEFAULT_GROUP_ID,
        'log_level' => (string) LOG_DEBUG,
        'debug' => 'all'
    ],
    'topic' => [
        'auto.commit.interval.ms' => '100',
        'offset.store.method' => 'broker',
        'auto.commit.enable' => 'false'
    ]
];

$configuration = Configuration::createInstance($configs);

$conf = $configuration->getConf();
$tc = $configuration->getTopicConf();

$tz = new DateTimeZone('Asia/Novosibirsk');
$date = new DateTime();
