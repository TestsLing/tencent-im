<?php

namespace EasyIM\TencentIM\SingleChat;

use EasyIM\Kernel\BaseClient;
use EasyIM\Kernel\Contracts\MessageInterface;
use EasyIM\Kernel\Support\Arr;
use EasyIM\TencentIM\Kernel\Constant\SingleChatConstant;
use EasyIM\TencentIM\Kernel\OfflinePushInfo\OfflinePushElem;

/**
 * Class Client
 *
 * @package EasyIM\TencentIM\SingleChat
 * @author  longing <hacksmile@126.com>
 */
class Client extends BaseClient
{
    /**
     * Send single message.
     *
     * @param string               $toAccount
     * @param MessageInterface     $message
     * @param string|null          $fromAccount
     * @param int                  $msgLifeTime
     * @param int                  $syncOtherMachine
     * @param OfflinePushElem|null $offlinePushInfo
     * @param array                $forbidCallbackControl
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMsg(
        string $toAccount,
        MessageInterface $message,
        string $fromAccount = null,
        int $msgLifeTime = 604800,
        int $syncOtherMachine = 2,
        OfflinePushElem $offlinePushInfo = null,
        array $forbidCallbackControl = []
    ) {
        $params = [
            'To_Account' => $toAccount,
            'MsgRandom' => msgRandom(),
            'MsgBody' => $message->transformToArray(),
            'MsgTimeStamp' => time(),
            'SyncOtherMachine' => $syncOtherMachine,
            'MsgLifeTime' => $msgLifeTime,
            'ForbidCallbackControl' => $forbidCallbackControl
        ];


        Arr::setNotNullValue($params, 'From_Account', $fromAccount);
        Arr::setNotNullValue($params, 'OfflinePushInfo', $offlinePushInfo && $offlinePushInfo->transformToArray());

        return $this->httpPostJson('openim/sendmsg', $params);
    }


    /**
     * Batch send single chat.
     *
     * @param array                $toAccount
     * @param MessageInterface     $message
     * @param string|null          $fromAccount
     * @param int                  $syncOtherMachine
     * @param OfflinePushElem|null $offlinePushInfo
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batchSendMsg(
        array $toAccount,
        MessageInterface $message,
        string $fromAccount = null,
        int $syncOtherMachine = SingleChatConstant::UN_SYNC_OTHER_MACHINE,
        OfflinePushElem $offlinePushInfo = null
    ) {
        $params = [
            'To_Account' => $toAccount,
            'MsgRandom' => msgRandom(),
            'MsgBody' => $message->transformToArray(),
            'SyncOtherMachine' => $syncOtherMachine,
        ];

        Arr::setNotNullValue($params, 'From_Account', $fromAccount);
        Arr::setNotNullValue($params, 'OfflinePushInfo', $offlinePushInfo && $offlinePushInfo->transformToArray());

        return $this->httpPostJson('openim/batchsendmsg', $params);
    }


    /**
     *
     * Import single chat message.
     *
     * @param string           $toAccount
     * @param string           $fromAccount
     * @param MessageInterface $message
     * @param int              $syncFromOldSystem
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function importMsg(
        string $toAccount,
        string $fromAccount,
        MessageInterface $message,
        int $syncFromOldSystem = SingleChatConstant::SYNC_FROM_OLD_SYSTEM_COUNT
    ) {
        $params = [
            'To_Account' => $toAccount,
            'From_Account' => $fromAccount,
            'MsgRandom' => msgRandom(),
            'MsgBody' => $message->transformToArray(),
            'MsgTimeStamp' => time(),
            'SyncFromOldSystem' => $syncFromOldSystem
        ];

        return $this->httpPostJson('openim/importmsg', $params);
    }


    /**
     * Query single chat message.
     *
     * @param string      $fromAccount
     * @param string      $toAccount
     * @param int         $maxCnt
     * @param int         $minTime
     * @param int         $maxTime
     * @param string|null $lastMsgKey
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryMsg(
        string $fromAccount,
        string $toAccount,
        int $minTime,
        int $maxTime,
        int $maxCnt = 60,
        string $lastMsgKey = null
    ) {
        $params = [
            'From_Account' => $fromAccount,
            'To_Account' => $toAccount,
            'MaxCnt' => $maxCnt,
            'MinTime' => $minTime,
            'MaxTime' => $maxTime,
        ];

        Arr::setNotNullValue($params, 'LastMsgKey', $lastMsgKey);

        return $this->httpPostJson('openim/admin_getroammsg', $params);
    }

    /**
     * Withdraw single chat message.
     *
     * @param string $fromAccount
     * @param string $toAccount
     * @param string $msgKey
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function withdrawMsg(string $fromAccount, string $toAccount, string $msgKey)
    {
        $params = [
            'From_Account' => $fromAccount,
            'To_Account' => $toAccount,
            'MsgKey' => $msgKey,
        ];

        return $this->httpPostJson('openim/admin_msgwithdraw', $params);
    }
}
