<?php

namespace Prodigious\Sonata\MenuBundle\Controller;

use Prodigious\Sonata\MenuBundle\Model\MenuItemInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MenuItemAdminController extends CRUDController
{

    /**
     * @param integer $id
     */
    public function toggleAction($id)
    {
        
        /** @var MenuItemInterface $object */
        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        $object->setEnabled(!$object->getEnabled());

        $m = $this->get('doctrine.orm.entity_manager');
        $m->persist($object);
        $m->flush();

        return new RedirectResponse($this->get('sonata.admin.route.default_generator')
            ->generateUrl(
                $this->get('prodigious_sonata_menu.admin.menu'),
                'items',
                ['id' => $object->getMenu()->getId()]
            )
        );
    }

    public function listAction()
    {
        if (!($parentAdmin = $this->admin->getParent())) {
            return parent::listAction();
        }

        $request = $this->getRequest();
        $id = $request->get($parentAdmin->getIdParameter());
        $menu = $parentAdmin->getObject($id);

        if (empty($menu)) {
            return parent::listAction();
        }

        $url = $this->admin->getParent()->generateObjectUrl('items', $menu, array('id' => $menu->getId()));

        return new RedirectResponse($url);
    }

    /**
     * {@inheritdoc}
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();
        $response = parent::redirectTo($object, $request);

        if (null !== $request->get('btn_update_and_list') || null !== $request->get('btn_create_and_list') || $this->getRestMethod() === 'DELETE') {
            $url = $this->admin->generateUrl('list');

            if(!empty($object) && $object instanceof MenuItemInterface) {
                $menu = $object->getMenu();

                if($menu && $this->admin->isChild()) {
                    $url = $this->admin->getParent()->generateObjectUrl('items', $menu, array('id' => $menu->getId()));
                }
            }

            $response->setTargetUrl($url);
        }

        return $response;
    }
}
