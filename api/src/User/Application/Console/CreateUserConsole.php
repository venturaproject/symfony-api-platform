<?php

declare(strict_types=1);

namespace App\User\Application\Console;

use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\CreateUser\CreateUserCommand; 
use App\User\Infrastructure\ValidationDTO\CreateUserInputDTO;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(
    name: 'app:create-user-cli',
    description: 'Creates a new user via CLI.',
    aliases: ['app:add-user-cli']
)]
class CreateUserConsole extends Command
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator 
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
    
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
    
        // Se pasa el rol "ROLE_USER" por defecto
        $createUserInputDTO = new CreateUserInputDTO($email, $password, $username, ["ROLE_USER"]);
    
        // Validación del DTO
        $errors = $this->validator->validate($createUserInputDTO);
    
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $io->error((string) $error->getMessage()); 
            }
            return Command::FAILURE;
        }
    
        // Al crear el comando, ya pasamos los roles por defecto
        $createUserCommand = new CreateUserCommand($username, $email, $password, ["ROLE_USER"]);
    
        try {
            // Ejecución del comando para crear el usuario
            $this->commandBus->execute($createUserCommand);
            $io->success('User successfully created via CLI!');
            $io->table(['Username', 'Email'], [[$username, $email]]);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Failed to create user: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

