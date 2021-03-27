<?php

declare(strict_types=1);

/*
 * This file is part of app-skeleton.
 *
 * Copyright (c) 2020 Damien Carcel <damien.carcel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carcel\User\Infrastructure\UserInterface\Cli;

use Carcel\User\Application\Command\CreateUser as CreateUserCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUser extends Command
{
    protected static $defaultName = 'carcel:user:create';

    private ValidatorInterface $validator;
    private MessageBusInterface $bus;

    public function __construct(ValidatorInterface $validator, MessageBusInterface $bus)
    {
        parent::__construct();

        $this->validator = $validator;
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a user.')
            ->addArgument('firstname', InputArgument::REQUIRED)
            ->addArgument('lastname', InputArgument::REQUIRED)
            ->addArgument('email', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $firstname */
        $firstname = $input->getArgument('firstname');
        /** @var string $lastname */
        $lastname = $input->getArgument('lastname');
        /** @var string $email */
        $email = $input->getArgument('email');
        /** @var string $password */
        $password = $input->getArgument('password');

        $command = new CreateUserCommand($firstname, $lastname, $email, $password);

        $violations = $this->validator->validate($command);
        if (0 < $violations->count()) {
            $output->writeln('Cannot create user because of the following violations:');
            foreach ($violations as $violation) {
                $output->writeln($violation->getMessage());
            }
        }

        $this->bus->dispatch($command);

        return 0;
    }
}
