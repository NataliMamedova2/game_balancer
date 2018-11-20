<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(
            [
             'class' => 'nav navbar-navnav',
             'id' => 'navbar',
            ]
        );

        $menu->addChild('Home', ['route' => 'homepage'])
             ->setAttribute('icon', 'icon-list');

        $menu->addChild('Battle', ['route' => 'battle_index'])
             ->setAttribute('icon', 'icon-list');

        $menu->addChild('Heroes', ['label' => 'Heroes'])
             ->setAttribute('dropdown', true)
             ->setAttribute('icon', 'icon-user')
             ->setAttribute('class', 'dropdown');

        $menu['Heroes']->addChild('Create new hero', ['route' => 'hero_add'])
                       ->setAttribute('icon', 'icon-edit');
        $menu['Heroes']->addChild('View all heroes', ['route' => 'hero_index'])
                       ->setAttribute('icon', 'icon-edit');

        return $menu;
    }
}
