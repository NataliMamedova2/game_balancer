<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

class HeroControllerTest extends WebTestCase
{
    /**
     * @var
     */
    private $em;

    /**
     * @var Application
     */
    private $application;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->application = new Application(static::$kernel);

        // удаляет базу
        $command = new DropDatabaseDoctrineCommand();
        $this->application->add($command);

        $input = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ]);

        try {
            $command->run($input, new NullOutput());

            $connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();

            if ($connection->isConnected()) {
                $connection->close();
            }

            $command = new CreateDatabaseDoctrineCommand();
            $this->application->add($command);
            $input = new ArrayInput(['command' => 'doctrine:database:create']);
            $command->run($input, new NullOutput());

            $command = new CreateSchemaDoctrineCommand();
            $this->application->add($command);
            $input = new ArrayInput(['command' => 'doctrine:schema:create']);
            $command->run($input, new NullOutput());

            $this->em = static::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();

            $client = static::createClient();
            $loader = new ContainerAwareLoader($client->getContainer());
            $loader->loadFromDirectory(static::$kernel->locateResource('App\DataFixtures\AppFixtures'));
            $purger = new ORMPurger($this->em);
            $executor = new ORMExecutor($this->em, $purger);
            $executor->execute($loader->getFixtures());
        } catch (\Exception $e) {
            return;
        }
    }

    /**
     * @dataProvider  getPublicUrls
     */
    public function testPublicUrls(string $url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            sprintf('The %s public URL is correctly', $url)
        );
    }

    public function testFormForCreateNewHero()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hero/add');

        $form = $crawler->filter('button')->form();
        $form['hero[name]'] = 'Hero3';
        $form['hero[attack]'] = 5;
        $form['hero[abilityAttack]'] = 1;
        $form['hero[defence]'] = 0;
        $form['hero[abilityDefence]'] = 0;
        $crawler = $client->submit($form);

        $this->assertNotNull($crawler);
    }

    /**
     * @return \Generator
     */
    public function getPublicUrls()
    {
        yield ['/hero/'];
        yield ['/hero/add'];
    }
}
