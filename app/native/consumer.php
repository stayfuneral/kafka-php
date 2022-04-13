<?php

use RdKafka\Conf;
use RdKafka\Consumer;
use RdKafka\TopicConf;

/**
 * @var Conf $conf
 * @var TopicConf $tc
 * @var DateTime $date
 * @var DateTimeZone $tz
 */

require_once dirname(__DIR__) . '/configs/bootstrap.php';

$consumer = new Consumer($conf);
$topic = $consumer->newTopic(DEFAULT_TOPIC_NAME, $tc);
$topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

while (true) {
    $message = $topic->consume(0, 1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            print_r($message);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
}



