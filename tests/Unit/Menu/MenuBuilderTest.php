<?php

namespace App\Tests\Unit\Dto;

use Knp\Menu\MenuItem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Menu\MenuBuilder;
use Knp\Menu\MenuFactory;

class MenuBuilderTest extends WebTestCase
{
    /**
     * @var MenuBuilder
     */
    private $menuBuilder;

    protected function setUp()
    {
        $menuFactory = new MenuFactory();

        $this->menuBuilder = new MenuBuilder($menuFactory);
    }

    protected function tearDown()
    {
        $this->menuBuilder = null;
    }

    public function testCreateMainMenu()
    {
        $menu = $this->menuBuilder->createMainMenu([]);
        $this->assertEquals(MenuItem::class, get_class($menu));
    }

    public function testGetMenuItems()
    {
        $modelMenuItems = [
            'Home',
            'Battle',
            'Heroes',
        ];
        $menu = $this->menuBuilder->createMainMenu([]);

        $this->assertEquals($modelMenuItems, array_keys($menu->getChildren()));
    }

    public function testGetChildrenMenuItemsFromParentOfHeroes()
    {
        $modelChildrenMenuItems = [
            'Create new hero',
            'View all heroes',
        ];
        $menu = $this->menuBuilder->createMainMenu([]);

        $this->assertEquals($modelChildrenMenuItems, array_keys($menu->getChild('Heroes')->getChildren()));
    }
}
