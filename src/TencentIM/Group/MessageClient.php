<?php


namespace EasyIM\TencentIM\Group;


use EasyIM\Kernel\BaseClient;
use EasyIM\Kernel\Contracts\MessageInterface;
use EasyIM\Kernel\Exceptions\InvalidArgumentException;
use EasyIM\Kernel\ParameterList;
use EasyIM\TencentIM\Kernel\OfflinePushInfo\OfflinePushElem;

/**
 * Class MessageClient
 *
 * @package EasyIM\TencentIM\Group
 * @author  yingzhan <519203699@qq.com>
 */
class MessageClient extends BaseClient
{
    /**
     * Send normal messages in groups.
     *
     * @param string               $groupId
     * @param MessageInterface     $msgBody
     * @param string|null          $fromAccount
     * @param string|null          $msgPriority
     * @param array|null           $forbidCallbackControl
     * @param OfflinePushElem|null $offlinePushInfo
     * @param int|null             $onlineOnlyFlag
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidArgumentException
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMsg(
        string $groupId,
        MessageInterface $msgBody,
        string $fromAccount = null,
        string $msgPriority = null,
        array $forbidCallbackControl = null,
        OfflinePushElem $offlinePushInfo = null,
        int $onlineOnlyFlag = null
    ) {
        $params = [
            'GroupId' => $groupId,
            'Random'  => msgRandom(7),
            'MsgBody' => $msgBody->transformToArray()
        ];
        $fromAccount && $params['From_Account'] = $fromAccount;
        $msgPriority && $params['MsgPriority'] = $msgPriority;
        $forbidCallbackControl && $params['ForbidCallbackControl'] = $forbidCallbackControl;
        $offlinePushInfo && $params['OfflinePushInfo'] = $offlinePushInfo->transformToArray();
        $onlineOnlyFlag && $params['OnlineOnlyFlag'] = $onlineOnlyFlag;

        return $this->httpPostJson('group_open_http_svc/send_group_msg', $params);
    }

    /**
     * Send system notification in group.
     *
     * @param string     $groupId
     * @param string     $content
     * @param array|null $toMembersAccount
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendNotification(
        string $groupId,
        string $content,
        array $toMembersAccount = null
    ) {
        $params = [
            'GroupId' => $groupId,
            'Content' => $content
        ];
        $toMembersAccount && $params['ToMembers_Account'] = $toMembersAccount;

        return $this->httpPostJson('group_open_http_svc/send_group_system_notification', $params);
    }

    /**
     * Recall group message.
     *
     * @param string        $groupId
     * @param ParameterList $msgSeqList
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function recallGroupMsg(string $groupId, ParameterList $msgSeqList)
    {
        $params = [
            'GroupId'    => $groupId,
            'MsgSeqList' => $msgSeqList()
        ];

        return $this->httpPostJson('group_open_http_svc/group_msg_recall', $params);
    }

    /**
     * Delete messages sent by the specified user.
     *
     * @param string $groupId
     * @param string $senderAccount
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteGroupMsg(string $groupId, string $senderAccount)
    {
        $params = [
            'GroupId'        => $groupId,
            'Sender_Account' => $senderAccount
        ];

        return $this->httpPostJson('group_open_http_svc/delete_group_msg_by_sender', $params);
    }

    /**
     * Pull group historical news.
     *
     * @param string   $groupId
     * @param int      $reqMsgNumber
     * @param int|null $reqMsgSeq
     *
     * @return array|\EasyIM\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyIM\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMsgSimple(string $groupId, int $reqMsgNumber, int $reqMsgSeq = null)
    {
        $params = [
            'GroupId'      => $groupId,
            'ReqMsgNumber' => $reqMsgNumber
        ];
        $reqMsgSeq && $params['ReqMsgSeq'] = $reqMsgSeq;

        return $this->httpPostJson('group_open_http_svc/group_msg_get_simple', $params);
    }
}