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

namespace Hhennes\PrestashopConsole\Command\Console;

use http\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateCommand
 * Command sample description
 */
class CreateCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('console:create:command')
            ->setDescription('Create a new command skeleton');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        /*
        Liste des arguments possible :
            Command name :
            Command description
            Command Domain
        */

        $helper = $this->getHelper('question');

        $commandName = $helper->ask($input, $output, $this->_getCommandNameQuestion());
        $commandDescription = $helper->ask($input, $output, $this->_getCommandDescriptionQuestion());
        $commandDomain = $helper->ask($input, $output, $this->_getCommandDomainQuestion());

        /*
         * Scenario :
         *
         *  Interactive mode : all data must be filled when console answer
         *
         *  Ask command name ( saisie libre + validation  )
         *  Ask command description( saisie libre + validation )
         *  Ask command domain ( liste des dossiers existants dans le dossier command + possibilité créé nouveau )
         *
         *  Proposer un nom de classe et un chemin de création
         *  Si validation création du fichier + du dossier
         *  Sinon ask className ( validation sans Command à la fin qui est rajouté automatique )
         *  ask directory ( in src/Hhennes/PrestashopConsole/Command )
         *
         */
    }


    /**
     * Get command name question
     * @return Question
     */
    protected function _getCommandNameQuestion()
    {
        $question = new Question('<question>Command Name (ex domain:action or domain:subdomain:action )</question>');
        $question->setNormalizer(function ($anwser) {
            return $anwser ? trim($anwser) : null;
        });
        $question->setValidator(function ($answer) {
            if ($answer === null || !preg_match('#^[a-z]+:[a-z]+(?::[a-z]+)?$#', $answer)) {
                throw new RuntimeException('The command name is not valid, it must use a format like domain:action or domain:subdomain:action');
            }
            return $answer;
        });

        return $question;
    }

    /**
     * Get command description question
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
     * @return Question
     */
    protected function _getCommandDomainQuestion()
    {
        $question = new Question('<question>Command Domain</question>');
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
     * Get base command content
     * @return string
     */
    protected function getBaseCommandContent()
    {
        return '
        <?php
        
         {header}
         
         namespace Hhennes\PrestashopConsole\Command\{commandDir};
         
        use Symfony\Component\Console\Command\Command;
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
                    ->setName({commandName})
                    ->setDescription({CommandDescription});
            }
        
            /**
             * @inheritDoc
             */
            public function execute(InputInterface $input, OutputInterface $output)
            {
                //@ToDO : Generate logic
                $output->writeln("it works");
            }
       
        ';
    }

    /**
     * @todo Stocker les éléments de génération dans un dossier à part
     */
    protected function _getHeader()
    {
    }
}
