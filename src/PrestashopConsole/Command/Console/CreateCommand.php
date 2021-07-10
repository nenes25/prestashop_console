<?php
/**
 * 2007-2019 Hennes Hervé
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@h-hennes.fr so we can send you a copy immediately.
 *
 * @author    Hennes Hervé <contact@h-hennes.fr>
 * @copyright 2007-2019 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

namespace PrestashopConsole\Command\Console;

use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CreateCommand
 * Command sample description
 */
class CreateCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('console:create:command')
            ->setDescription('Create a new command skeleton');
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        //This command can only be launched in php mode
        if ($this->getApplication()->getRunAs() == 'phar') {
            $output->writeln('<error>This command can only be run in php mode</error>');

            return self::RESPONSE_ERROR;
        }

        $helper = $this->getHelper('question');

        $commandName = $helper->ask($input, $output, $this->_getCommandNameQuestion());
        $commandDescription = $helper->ask($input, $output, $this->_getCommandDescriptionQuestion());
        $commandDomain = $helper->ask($input, $output, $this->_getCommandDomainQuestion());
        $commandClass = $helper->ask($input, $output, $this->_getCommandClassQuestion());

        try {
            $this->_createCommand(
                $commandName,
                $commandDescription,
                $commandDomain,
                $commandClass
            );
        } catch (RuntimeException $e) {
            $output->writeln('<error>Unable to generate the command :' . $e->getMessage() . '</error>');

            return self::RESPONSE_ERROR;
        }

        $output->writeln('<info>Command Created with success</info>');

        return self::RESPONSE_SUCCESS;
    }

    /**
     * Create the command file ( and directory if necessary )
     *
     * @param string $commandName
     * @param string $commandDescription
     * @param string $commandDomain
     * @param string $commandClass
     *
     * @throws RuntimeException
     *
     * @return void
     */
    protected function _createCommand($commandName, $commandDescription, $commandDomain, $commandClass)
    {
        $commandDir = str_replace('\\', '/', $commandDomain);
        $fileSystem = new Filesystem();
        $baseCommandPath = __DIR__ . '/Command/';
        $path = $baseCommandPath . $commandDir;

        // 1. check if the directory does not exists
        if (!$fileSystem->exists($path)) {
            try {
                $fileSystem->mkdir($path, 0755);
            } catch (IOException $e) {
                throw new RuntimeException('Unable to create command directory');
            }
        }

        // 2. Prepare the file content
        $fileContent = $this->_getBaseCommandContent();
        $fileContent = str_replace(
            [
                '{header}',
                '{className}',
                '{commandName}',
                '{CommandDescription}',
                '{commandDomain}',
            ],
            [
                $this->_getHeader(),
                $commandClass,
                $commandName,
                $commandDescription,
                $commandDomain,
            ],
            $fileContent
        );

        // 3. Create the file
        $fileName = $path . '/' . $commandClass . 'Command.php';
        try {
            $fileSystem->appendToFile($fileName, $fileContent);
        } catch (IOException $e) {
            throw new RuntimeException('Unable to create command class file');
        }
    }

    /**
     * Get command name question
     *
     * @return Question
     */
    protected function _getCommandNameQuestion()
    {
        $question = new Question('<question>Command Name (ex domain:action or domain:subdomain:action )</question>');
        $question->setNormalizer(function ($anwser) {
            return $anwser ? trim($anwser) : null;
        });
        $question->setValidator(function ($answer) {
            if ($answer === null || !preg_match('#^[a-z-]+:[a-z-]+(?::[a-z-]+)?$#', $answer)) {
                throw new RuntimeException('The command name is not valid, it must use a format like domain:action or domain:subdomain:action');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Get command description question
     *
     * @return Question
     */
    protected function _getCommandDescriptionQuestion()
    {
        $question = new Question('<question>Command description</question>');
        $question->setValidator(function ($answer) {
            if ($answer === null) {
                throw new RuntimeException('Please give a command description');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Get command domain question
     *
     * @return Question
     */
    protected function _getCommandDomainQuestion()
    {
        $question = new Question('<question>Command Domain ( ex : Module\Generate ) </question>');
        $question->setNormalizer(function ($anwser) {
            return $anwser ? trim($anwser) : null;
        });
        $question->setValidator(function ($answer) {
            if ($answer === null) {
                throw new RuntimeException('Please give a command domain');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Get Command Class Name
     *
     * @return Question
     */
    protected function _getCommandClassQuestion()
    {
        $question = new Question('<question>Command Class ex:  Debug</question>');
        $question->setNormalizer(function ($anwser) {
            $cleanAnswer = ($anwser ? trim($anwser) : null);
            if (null === $cleanAnswer) {
                return $cleanAnswer;
            }
            //If command Class end with Command we remove it
            if (preg_match('#Command$#', $cleanAnswer)) {
                $cleanAnswer = str_ireplace('Command', '', $cleanAnswer);
            }

            return $cleanAnswer;
        });
        $question->setValidator(function ($answer) {
            if ($answer === null) {
                throw new RuntimeException('Please give a command class name');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * Get base command content
     *
     * @return string
     */
    protected function _getBaseCommandContent()
    {
        return '<?php
        
         {header}
         
         namespace PrestashopConsole\Command\{commandDomain};
         
        use PrestashopConsole\Command\PrestashopConsoleAbstractCmd as Command;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;

        /**
         * Class {className}
         * Command sample description
         */
        class {className}Command extends Command
        {
            /**
             * @inheritDoc
             */
            protected function configure()
            {
                $this
                    ->setName(\'{commandName}\')
                    ->setDescription(\'{CommandDescription}\');
            }
        
            /**
             * @inheritDoc
             */
            public function execute(InputInterface $input, OutputInterface $output)
            {
                //@ToDO : Generate logic
                $output->writeln("it works");
            }
       
        }';
    }

    /**
     * Get Command Header
     *
     * @return string
     */
    protected function _getHeader()
    {
        return '
        /**
         * 2007-2020 Hennes Hervé
         *
         * NOTICE OF LICENSE
         *
         * This source file is subject to the Open Software License (OSL 3.0)
         * that is bundled with this package in the file LICENSE.txt.
         * It is also available through the world-wide-web at this URL:
         * https://opensource.org/licenses/OSL-3.0
         * If you did not receive a copy of the license and are unable to
         * obtain it through the world-wide-web, please send an email
         * to contact@h-hennes.fr so we can send you a copy immediately.
         *
         * @author    Hennes Hervé <contact@h-hennes.fr>
         * @copyright 2007-' . date('Y') . ' Hennes Hervé
         * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
         * http://www.h-hennes.fr/blog/
         */
         ';
    }
}
