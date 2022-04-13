<?php

use Ramapriya\Configuration;
use Ramapriya\Serializer;
use RdKafka\Conf;
use RdKafka\Producer;
use RdKafka\TopicConf;

/**
 * @var Configuration $configuration
 * @var Conf $conf
 * @var TopicConf $tc
 * @var DateTime $date
 * @var DateTimeZone $tz
 */

require_once dirname(__DIR__) . '/configs/bootstrap.php';

$producer = new Producer($conf);


$formattedDate = $date->format('d.m.Y H:i:s');


$topicName = 'IDontKnowHowSetFewPartitionsInTopic';
$key = 'topic_without_partitions';
$topic = $producer->newTopic($topicName, $tc);
$metadata = $producer->getMetadata(false, $topic, 2000);

if (!$metadata) {
    echo "Failed to get metadata, is broker down?\n";
    exit;
}

$message = [

    'file' => __FILE__,
    'key' => $key,
    'topic' => $topic->getName(),
    'broker_id' => $metadata->getOrigBrokerId(),
    'broker_name' => $metadata->getOrigBrokerName(),
    'timezone' => [
        'name' => $tz->getName(),
        'location' => $tz->getLocation(),
        'time' => $tz->getOffset(new DateTime())
    ],
    'configuration' => Serializer::serialize($configuration)

];

$headers = [
    'message_id' => uniqid('msg_'),
    'server_time' => Serializer::serialize([
        'timezone' => $date->getTimezone(),
        'ts' => $date->getTimestamp(),
        'date' => $formattedDate
    ])
];

$payload = Serializer::serialize($message);


$topic->producev(RD_KAFKA_PARTITION_UA, 0, $payload, $key, $headers);
$producer->poll(0);

$result = $producer->flush(-1);

if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
    throw new \RuntimeException('Was unable to flush, messages might be lost!');
}

