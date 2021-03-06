<?php

namespace EasyIM\TencentIM\Group;

use EasyIM\Kernel\BaseClient;
use EasyIM\Kernel\Exceptions\InvalidConfigException;
use EasyIM\Kernel\Support\Collection;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class OperateClient
 *
 * @package EasyIM\TencentIM\Group
 * @author  yingzhan <519203699@qq.com>
 */
class OperateClient extends BaseClient
{
    /**
     * Mass prohibitions and cancellations chat.
     *
     * @param string $groupId
     * @param array  $membersAccount
     * @param int    $shutUpTime
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function forbidMsg(string $groupId, array $membersAccount, int $shutUpTime)
    {
        $params = [
            'GroupId' => $groupId,
            'Members_Account' => $membersAccount,
            'ShutUpTime' => $shutUpTime
        ];

        return $this->httpPostJson('group_open_http_svc/forbid_send_msg', $params);
    }

    /**
     * Get forbidden group member list.
     *
     * @param string $groupId
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException|GuzzleException
     */
    public function getGroupShut(string $groupId)
    {
        $params = [
            'GroupId' => $groupId
        ];

        return $this->httpPostJson('group_open_http_svc/get_group_shutted_uin', $params);
    }

    /**
     * Transfer group owner.
     *
     * @param string $groupId
     * @param string $newOwnerAccount
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function changeOwner(string $groupId, string $newOwnerAccount)
    {
        $params = [
            'GroupId' => $groupId,
            'NewOwner_Account' => $newOwnerAccount
        ];

        return $this->httpPostJson('group_open_http_svc/change_group_owner', $params);
    }

    /**
     * Set member unread message count.
     *
     * @param string $groupId
     * @param string $memberAccount
     * @param int    $unreadMsgNum
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function setUnreadMsgNum(string $groupId, string $memberAccount, int $unreadMsgNum)
    {
        $params = [
            'GroupId' => $groupId,
            'Member_Account' => $memberAccount,
            'UnreadMsgNum' => $unreadMsgNum
        ];

        return $this->httpPostJson('group_open_http_svc/set_unread_msg_num', $params);
    }
}
