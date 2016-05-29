<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace UserBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
/**
 * A command console that creates users and stores them in the database.
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php app/console app:add-user
 *
 * To output detailed information, increase the command verbosity:
 *
 *     $ php app/console app:add-user -vv
 *
 * See http://symfony.com/doc/current/cookbook/console/console_command.html
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class AddUserCommand extends ContainerAwareCommand
{
    const MAX_ATTEMPTS = 5;
    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            // a good practice is to use the 'app:' prefix to group all your custom application commands
            ->setName('user:add')
            ->setDescription('Creates users and stores them in the database')
            ->setHelp($this->getCommandHelp())
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see http://symfony.com/doc/current/components/console/console_arguments.html
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addOption('is-admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
            ->addOption('is-superadmin', null, InputOption::VALUE_NONE, 'If set, the user is created as a super administrator')
        ;
    }
    /**
     * This method is executed before the interact() and the execute() methods.
     * It's main purpose is to initialize the variables used in the rest of the
     * command methods.
     *
     * Beware that the input options and arguments are validated after executing
     * the interact() method, so you can't blindly trust their values in this method.
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }
    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('username') && null !== $input->getArgument('password')) {
            return;
        }
        // multi-line messages can be displayed this way...
        $output->writeln('');
        $output->writeln('Add User Command Interactive Wizard');
        $output->writeln('-----------------------------------');
        // ...but you can also pass an array of strings to the writeln() method
        $output->writeln(array(
            '',
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php app/console user:add username',
            '',
        ));
        $output->writeln(array(
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
            '',
        ));
        // See http://symfony.com/doc/current/components/console/helpers/questionhelper.html
        $console = $this->getHelper('question');
        // Ask for the username if it's not defined
        $username = $input->getArgument('username');
        if (null === $username) {
            $question = new Question(' > <info>Username</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The username cannot be empty');
                }
                return $answer;
            });
            $question->setMaxAttempts(self::MAX_ATTEMPTS);
            $username = $console->ask($input, $output, $question);
            $input->setArgument('username', $username);
        } else {
            $output->writeln(' > <info>Username</info>: '.$username);
        }
        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null === $password) {
            $question = new Question(' > <info>Password</info> (your type will be hidden): ');
            $question->setValidator(array($this, 'passwordValidator'));
            $question->setHidden(true);
            $question->setMaxAttempts(self::MAX_ATTEMPTS);
            $password = $console->ask($input, $output, $question);
            $input->setArgument('password', $password);
        } else {
            $output->writeln(' > <info>Password</info>: '.str_repeat('*', strlen($password)));
        }
    }
    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);
        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('password');
        $isAdmin = $input->getOption('is-admin');
        $isSuperAdmin = $input->getOption('is-superadmin');
        // first check if a user with the same username already exists
        $existingUser = $this->entityManager->getRepository('UserBundle:User')->findOneBy(array('username' => $username));
        if (null !== $existingUser) {
            throw new \RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
        }
        // create the user and encode its password
        $user = new User();
        $user->setUsername($username);
        if ($isAdmin) {
            $user->setRoles(array('ROLE_ADMIN'));
        } elseif ($isSuperAdmin) {
            $user->setRoles(array('ROLE_SUPER_ADMIN'));
        } else {
            $user->setRoles(array('ROLE_USER'));
        }
        // See http://symfony.com/doc/current/book/security.html#security-encoding-password
        $encoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $output->writeln('');
        $output->writeln(sprintf('[OK] %s was successfully created: %s (%s)', $isAdmin || $isSuperAdmin ? 'Administrator user' : 'User', $user->getUsername()));
        if ($output->isVerbose()) {
            $finishTime = microtime(true);
            $elapsedTime = $finishTime - $startTime;
            $output->writeln(sprintf('[INFO] New user database id: %d / Elapsed time: %.2f ms', $user->getId(), $elapsedTime*1000));
        }
    }
    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function passwordValidator($plainPassword)
    {
        if (empty($plainPassword)) {
            throw new \Exception('The password can not be empty');
        }
        if (strlen(trim($plainPassword)) < 4) {
            throw new \Exception('The password must be at least 4 characters long');
        }
        return $plainPassword;
    }
    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp()
    {
        return <<<HELP
The <info>%command.name%</info> command creates new users and saves them in the database:
  <info>php %command.full_name%</info> <comment>username password</comment>
By default the command creates regular users. To create administrator users,
add the <comment>--is-admin</comment> option:
  <info>php %command.full_name%</info> username password <comment>--is-admin</comment>
If you omit any of the three required arguments, the command will ask you to
provide the missing values:
  # command will ask you for all arguments
  <info>php %command.full_name%</info>
HELP;
    }
}