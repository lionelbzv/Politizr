<?php
namespace Politizr\AdminBundle\Twig;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Model\PUserQuery;
use Politizr\Model\PMUserModeratedQuery;

use Politizr\Model\PUser;
use Politizr\Model\PMUserModerated;

use Politizr\AdminBundle\Form\Type\PMUserModeratedType;

/**
 * Moderation admin twig extension
 *
 * @author Lionel Bouzonville
 */
class PolitizrAdminModerationExtension extends \Twig_Extension
{
    private $documentService;
    
    private $formFactory;
    private $router;
    private $logger;

    /**
     *
     * @param politizr.functional.document
     * @param form.factory
     * @param router
     * @param logger
     */
    public function __construct(
        $documentService,
        $formFactory,
        $router,
        $logger
    ) {
        $this->documentService = $documentService;
        
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              FONCTIONS ET FILTRES                                        */
    /* ######################################################################################################## */


    /**
     *  Renvoie la liste des filtres
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'linkedModeration',
                array($this, 'linkedModeration'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new \Twig_SimpleFilter(
                'linkedBanned',
                array($this, 'linkedBanned'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }


    /**
     *  Renvoie la liste des fonctions
     */
    public function getFunctions()
    {
        return array(
            'adminModerationAlertNew'  => new \Twig_SimpleFunction(
                'adminModerationAlertNew',
                array($this, 'adminModerationAlertNew'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            'adminModerationAlertListing'  => new \Twig_SimpleFunction(
                'adminModerationAlertListing',
                array($this, 'adminModerationAlertListing'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
        );
    }


    /* ######################################################################################################## */
    /*                                              FILTERS                                                     */
    /* ######################################################################################################## */

    /**
     * Moderation notification HTML rendering
     *
     * @param PMUserModerated $userModerated
     * @param string $type html or txt mail
     * @return html
     */
    public function linkedModeration(\Twig_Environment $env, PMUserModerated $userModerated, $type)
    {
        // $this->logger->info('*** linkedModeration');
        // $this->logger->info('$userModerated = '.print_r($userModerated, true));
        // $this->logger->info('$type = '.print_r($type, true));

        // User
        $author = PUserQuery::create()->findPk($userModerated->getPUserId());

        if ($author->isQualified()) {
            $profileSuffix = 'E';
        } else {
            $profileSuffix = 'C';
        }

        $authorUrl = null;
        if ($author) {
            $authorUrl = $this->router->generate('UserDetail', array('slug' => $author->getSlug()), true);
        }

        // Update attributes depending of context
        $attr = $this->documentService->computeDocumentContextAttributes(
            $userModerated->getPObjectName(),
            $userModerated->getPObjectId()
        );

        $subject = $attr['subject'];
        $title = $attr['title'];
        $url = $attr['url'];
        $document = $attr['document'];
        $documentUrl = $attr['documentUrl'];

        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_notification.html.twig',
            array(
                'type' => $type,
                'userModerated' => $userModerated,
                'subject' => $subject,
                'title' => $title,
                'url' => $url,
                'author' => $author,
                'authorUrl' => $authorUrl,
                'document' => $document,
                'documentUrl' => $documentUrl,
            )
        );

        return $html;
    }

    /**
     * Moderation banned HTML rendering
     *
     * @param PUser $user
     * @param string $type html or txt mail
     * @return html
     */
    public function linkedBanned(\Twig_Environment $env, PUser $user, $type)
    {
        // $this->logger->info('*** linkedBanned');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$type = '.print_r($type, true));

        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_banned.html.twig',
            array(
                'type' => $type,
                'user' => $user,
            )
        );

        return $html;
    }

    /* ######################################################################################################## */
    /*                                              FUNCTIONS                                                   */
    /* ######################################################################################################## */

    /**
     * Create moderation alert form
     *
     * @param string $objectClass
     * @param int $objectId
     * @param int $userId
     * @return string
     */
    public function adminModerationAlertNew(\Twig_Environment $env, $objectClass, $objectId, $userId)
    {
        // $this->logger->info('*** adminModerationAlertNew');
        // $this->logger->info('$objectClass = '.print_r($objectClass, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));

        switch ($objectClass) {
            case ObjectTypeConstants::TYPE_DEBATE:
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                break;
            case ObjectTypeConstants::TYPE_USER:
                break;
            default:
                throw new InconsistentDataException(sprintf('Object class %s not managed.'), $objectClass);
        }

        $userModerated = new PMUserModerated();

        $userModerated->setPUserId($userId);
        $userModerated->setPObjectId($objectId);
        $userModerated->setPObjectName($objectClass);

        $form = $this->formFactory->create(new PMUserModeratedType(), $userModerated);

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );

        return $html;
    }

    /**
     * Moderation alert listing (historic) for a user
     *
     * @param int $userId
     * @return string
     */
    public function adminModerationAlertListing(\Twig_Environment $env, $userId)
    {
        // $this->logger->info('*** adminModerationAlertListing');
        // $this->logger->info('$objectClass = '.print_r($objectClass, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));

        $moderations = PMUserModeratedQuery::create()
                                ->filterByPUserId($userId)
                                ->orderByCreatedAt('desc')
                                ->find();

        // Construction du rendu du tag
        $html = $env->render(
            'PolitizrAdminBundle:Fragment\\Moderation:_listing.html.twig',
            array(
                'moderations' => $moderations,
            )
        );

        return $html;
    }

    public function getName()
    {
        return 'admin_moderation_extension';
    }
}
