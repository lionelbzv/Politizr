<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ModerationConstants;
use Politizr\Constant\UserConstants;
use Politizr\Constant\ReputationConstants;

use Politizr\Model\PUser;
use Politizr\Model\PUReputation;
use Politizr\Model\PMUserModerated;

/**
 * Functional service for moderation management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class ModerationService
{
    private $eventDispatcher;

    private $userManager;

    private $globalTools;

    private $logger;

    /**
     *
     * @param @event_dispatcher
     * @param @politizr.manager.user
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $eventDispatcher,
        $userManager,
        $globalTools,
        $logger
    ) {
        $this->eventDispatcher = $eventDispatcher;

        $this->userManager = $userManager;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    // **************************************************************************************** //
    //                                      GLOBAL MODERATION                                   //
    // **************************************************************************************** //

    /**
     * Add a PMUserModerated object
     *
     * @param PUser $user
     * @param int $moderationType
     * @param string $objectName
     * @param int $objectId
     * @param int $scoreEvolution
     * @return PMUserModerated
     */
    public function addUserModerated(
        PUser $user,
        $moderationType,
        $objectName,
        $objectId,
        $scoreEvolution
    ) {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }
        
        $mUser = $this->userManager->createUserModerated(
            $user->getId(),
            $moderationType,
            $objectName,
            $objectId,
            $scoreEvolution
        );

        return $mUser;
    }

    /**
     * Send email notif to moderated user
     *
     * @param PUser $user
     * @param PMUserModerated $userModerated
     */
    public function notifUser(PUser $user, PMUserModerated $userModerated)
    {
        if ($user->getBanned()) {
            $this->eventDispatcher->dispatch('moderation_banned', new GenericEvent($user));
        } else {
            $this->eventDispatcher->dispatch('moderation_notification', new GenericEvent($userModerated));
        }
    }

    // **************************************************************************************** //
    //                                      USER MODERATION                                     //
    // **************************************************************************************** //

    /**
     *
     * @param PUser $user
     * @param int $scoreEvolution
     * @return boolean
     */
    public function updateUserReputation(PUser $user, $scoreEvolution)
    {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }
        
        $userId = $user->getId();

        if ($scoreEvolution) {
            $con = \Propel::getConnection('default');
            $con->beginTransaction();
            try {
                for ($i = 0; $i > $scoreEvolution; $i--) {
                    $puReputation = new PUReputation();
                    $puReputation->setPRActionId(ReputationConstants::ACTION_ID_R_ADMIN_NEG);
                    $puReputation->setPUserId($userId);
                    $puReputation->save();
                }

                $con->commit();
            } catch (\Exception $e) {
                $con->rollback();
                throw new InconsistentDataException(sprintf('Rollback reputation evolution user id-%s.', $userId));
            }
        }
    }

    /**
     * Create an archive of input user
     *
     * @param PUser $user
     * @return PMUserHistoric
     */
    public function archiveUser(PUser $user)
    {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }
        
        $mUser = $this->userManager->createArchive($user);

        return $mUser;
    }

    /**
     * Update user to put in ban mode
     *
     * @param PUser $user
     * @param int $nbDays
     * @return PUser
     */
    public function banUser(PUser $user, $nbDays = null)
    {
        if (!$user) {
            throw new InconsistentDataException('User null');
        }

        $nbTotal = $user->getBannedNbTotal();
        if (!$nbTotal) {
            $nbTotal = 0;
        }

        $user->setBanned(true);
        $user->setOnline(false);
        $user->setBannedNbDaysLeft($nbDays);
        $user->setBannedNbTotal($nbTotal + 1);

        $user->setPUStatusId(UserConstants::STATUS_EXCLUDED);

        $user->save();

        return $user;
    }
}
