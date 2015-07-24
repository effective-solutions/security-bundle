<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 7/22/15
 * Time: 2:49 PM
 */

namespace EffectiveSolutions\SecurityBundle\Command;

use Base\DataAccessBundle\Entity\Role;
use Base\DataAccessBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('register')
            ->setDescription('Initial Registration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $role = new Role();
        $role->setDescription('Admin');
        $role->setMetacode('ROLE_ADMIN');

        $user = new User();
        $user->setUsername('admin');
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encoded = $encoder->encodePassword($user, 'admin');
        $user->setPassword($encoded);
        $user->setRole($role);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $em->persist($user);
        $em->flush();

        $output->writeln("<info>Successfully Registered</info>");
    }

}