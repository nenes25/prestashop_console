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

namespace Hhennes\PrestashopConsole\Command\Module\Generate;

use http\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Question\Question;
use \ObjectModel;
use \Validate;

class ObjectModelCommand extends Command
{

    /** @var string Module Name */
    protected $_moduleName;

    /** @var Filesystem */
    protected $_fileSystem;

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('module:generate:model')
            ->setDescription('Generate module model object')
            ->addArgument('moduleName', InputArgument::REQUIRED, 'module name')
            ->addArgument('objectClass', InputArgument::OPTIONAL, 'object class');
    }

    /**
     * Execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = $input->getArgument('moduleName');
        $helper = $this->getHelper('question');
        $params = [];

        if ($objectClass = $input->getArgument('objectClass')) {
            $objectClass = ucfirst($objectClass);
        } else {
            $objectQuestion = new Question('<question>Model Class :</question>');
            $objectQuestion->setValidator(function ($answer) {
                if (!Validate::isFileName($answer)) {
                    throw new \RuntimeException('The className is not valid');
                }
                return $answer;
            });
            $objectClass = $helper->ask($input, $output, $objectQuestion);
        }

        /* //Objet Langue
         $hasLang = $helper->ask($input, $output, new ConfirmationQuestion('<question>has lang</question>',false,'/^(y|j)/i)'));
         //Objet Shop @todo Créer la table shop si nécessaire
         $hasShop = $helper->ask($input, $output, new ConfirmationQuestion('<question>has shop</question>',false,'/^(y|j)/i)'));*/

        /* 'fields' => array(
         'id_profile' => array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false),
         'id_authorization_role' => array('type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false),
         ),*/

        //Table name
        $tableName = $helper->ask($input, $output, new Question('<question>Table name :</question>', 'sample'));
        $primary = $helper->ask($input, $output, new Question('<question>Primary key :</question>', 'id_sample'));

        $params['table_name'] = $tableName;
        $params['primary'] = $primary;

        $fields = [];
        do {
            //Liste des champs
            //Nom du champ
            $name = $helper->ask($input, $output, new Question('<question>Field name :</question>', 'name'));

            //Is field Required
            $required = $helper->ask($input, $output, new ConfirmationQuestion('<question>Required field (y/n) default n :</question>', false, '/^(y|j)/i'));

            //Field Type
            $fieldTypes = $this->_getFieldTypes();
            $fieldQuestion = new Question('<question>Field type :</question>', 'string');
            $fieldQuestion->setAutocompleterValues($fieldTypes);
            $fieldQuestion->setValidator(function ($answer) use ($fieldTypes) {
                if ($answer !== null && !in_array($answer, $fieldTypes)) {
                    throw new RuntimeException('The field type must be part of the suggested');
                }
                return $answer;
            });
            $type = $helper->ask($input, $output, $fieldQuestion);

            //Field lang
            $lang = $helper->ask($input, $output, new ConfirmationQuestion('<question>Lang field (y/n) default n:</question>', false, '/^(y|j)/i'));

            //Field Validate rule
            $validationFunctions = $this->_getValidationFunctions();
            $validationQuestion = new Question('<question>Field validation :</question>');
            $validationQuestion->setAutocompleterValues($validationFunctions);
            $validationQuestion->setValidator(function ($answer) use ($validationFunctions) {
                if ($answer !== null && !in_array($answer, $validationFunctions)) {
                    throw new RuntimeException('The validate function must be part of the suggested');
                }
                return $answer;
            });
            $validation = $helper->ask($input, $output, $validationQuestion);

            //Field Length
            $length = $helper->ask($input, $output, new Question('<question>Field length :</question>'));

            $fields[] = [
                'name' => $name,
                'required' => $required,
                'lang' => $lang,
                'type' => $type,
                'validate' => $validation,
                'length' => $length,
            ];

            //Ask for create a new field
            $newField = $helper->ask(
                $input,
                $output,
                new ConfirmationQuestion('<question>Add another field (y/n) default n ?</question>', false, '/^(y|j)/i')
            );
        } while ($newField === true);

        $params['fields'] = $fields;

        //Ask if sql generation is needed
        $sql = $helper->ask(
            $input,
            $output,
            new ConfirmationQuestion('<question>Generate sql ?</question>', false, '/^(y|j)/i')
        );
        if ($sql) {
            $sqlQueries = $this->_generateSql($params);
        } else {
            $sqlQueries = [];
        }

        $defaultContent =
            str_replace(
                [
                    '{object}',
                    '{object_properties}',
                    '{object_definition}',
                    '{object_install}'
                ],
                [
                    $objectClass,
                    $this->_getObjectProperties($fields),
                    $this->_getObjectDefinition($params),
                    $this->_getObjectInstall($sqlQueries),
                ],
                $this->_getDefaultContent()
            );

        $this->_fileSystem = new Filesystem();
        $this->_moduleName = $moduleName;

        try {
            $this->_createDirectories();
            $this->_fileSystem->dumpFile(
                _PS_MODULE_DIR_ . $moduleName . '/classes/' . $objectClass . '.php',
                $defaultContent
            );
        } catch (IOException $e) {
            $output->writeln('<error>Unable to create model file</error>');
            return false;
        }

        $output->writeln('<info>Model file generated</info>');
    }


    /**
     * Get default Php content
     * @return string
     */
    protected function _getDefaultContent()
    {
        return
            '<?php
 ' . ModuleHeader::getHeader() . '

if (!defined(\'_PS_VERSION_\')) {
    exit;
}

class {object} extends ObjectModel
{

   /** @var int Object id */
    public $id;
    
    {object_properties}
    
    {object_definition}
    //@Todo generate content
    
    {object_install}
}
';
    }

    /**
     * Create directories
     * @todo Add index.php files
     */
    protected function _createDirectories()
    {
        if (!$this->_fileSystem->exists(_PS_MODULE_DIR_ . $this->_moduleName . '/classes')) {
            $this->_fileSystem->mkdir(_PS_MODULE_DIR_ . $this->_moduleName . '/classes', 0775);
        }
    }

    /**
     * Get Object properties from object fields
     * @param array $fields
     * @return string
     * @todo Comment field types
     */
    protected function _getObjectProperties(array $fields)
    {
        $fieldStr = '';
        foreach ($fields as $field) {
            $fieldStr .= 'public $' . $field['name'] . ';' . "\n";
        }
        return $fieldStr;
    }

    /**
     * Construct object definition
     * @param array $params
     * @return string
     */
    protected function _getObjectDefinition(array $params)
    {
        $hasLang = false;
        $defStr = 'public static $definition = [';
        $defStr .= "
        'table' => '" . $params['table_name'] . "',
        'primary' => '" . $params['primary'] . "',
        'fields' => [ \n";
        foreach ($params['fields'] as $field) {
            $type = $this->_getObjectModelFieldType($field['type']);
            $defStr .= "'" . $field['name'] . "' => [ 'type' => self::" . $type . ",";
            if ($field['validate']) {
                $defStr .= "'validate' => '" . $field['validate'] . "', ";
            }
            if ($field['length']) {
                $defStr .= "'length' => " . $field['length'] . ", ";
            }
            if ($field['lang'] == true) {
                $hasLang = true;
                $defStr .= "'lang' => true,";
            }
            $defStr .= "]\n";
            if ( $hasLang) {
                $defStr .= ",'multilang' => true \n";
            }
        }
        $defStr = rtrim($defStr, ',');
        $defStr .= ']
        ];';
        return $defStr;
    }

    /**
     * Construct object sql install
     * @param $sqlQueries
     */
    protected function _getObjectInstall(array $sqlQueries)
    {
        $installStr = '';
        if (!count($sqlQueries)) {
            return $installStr;
        }

        $installStr .= '
        /**
         * Model Sql installation
         * Add it in your module installation if necessary
         */ 
        public static function installSql(){'."\n";

        foreach ($sqlQueries as $query) {
            if ($query != '') {
                $installStr .= "\n".' Db::getInstance()->execute(
                "'.$query.'"
                );'."\n";
            }
        }
        $installStr .= '}';

        return $installStr;
    }
    /**
     * @param array $params
     * @return array
     */
    protected function _generateSql(array $params)
    {

        $hasLang = false;
        $sqlQueryString = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $params['table_name'] . '`(' . "\n";
        $sqlQueryString .= '`' . $params['primary'] . '` int(10) unsigned NOT NULL AUTO_INCREMENT,';

        $sqlQueryStringLang = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . $params['table_name'] . '_lang`(' . "\n";
        $sqlQueryStringLang .= '`' . $params['primary'] . '` int(10) unsigned NOT NULL AUTO_INCREMENT,' . "\n";
        $sqlQueryStringLang .= '`id_lang` int(10) unsigned NOT NULL ,' . "\n";

        foreach ($params['fields'] as $field) {

            //Required fields must be NOT NULL in database
            ($field['required'] !== false) ? $required = 'NOT NULL' : $required = '';

            $fieldString = '`' . $field['name'] . '`';

            switch ($field['type']) {
                case 'int':
                    $fieldLength = $field['length'] ? $field['length'] : 10;
                    $fieldString .= ' INT(' . $fieldLength . ') ' . $required . ' unsigned';
                    break;
                case 'bool':
                    //Bool type is required with default value
                    $fieldString .= ' TINYINT(1) NOT NULL unsigned DEFAULT 0';
                    break;
                case 'string':
                    $fieldLength = $field['length'] ? $field['length'] : 255;
                    $fieldString .= ' VARCHAR (' . $fieldLength . ') ' . $required . '';
                    break;
                case 'float':
                    break;
                case 'date':
                    $fieldString .= ' datetime NOT NULL';
                    break;
                case 'html':
                    $fieldString .= ' text';
                    break;

                //I don't know what to do with this fields type
                case 'nothing':
                case 'sql':
                    $fieldString = '';
                    break;
            }

            if ($field['lang'] !== false) {
                $hasLang = true;
                $sqlQueryStringLang .= $fieldString . "\n";
            } else {
                $sqlQueryString .= $fieldString . "\n";
            }
        }
        $sqlQueryString .= 'PRIMARY KEY (`' . $params['primary'] . '`)
        ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';
        $sqlQueryStringLang .= 'PRIMARY KEY (`' . $params['primary'] . '`,`id_lang`)
        ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        return [
            'object' => $sqlQueryString,
            'lang' => $hasLang ? $sqlQueryStringLang : '',
        ];
    }

    /**
     * Get ObjectModelField Type constant Key
     * @param $type
     * @return mixed
     */
    protected function _getObjectModelFieldType($type)
    {
        $key = 'TYPE_' . strtoupper($type);
        return $key;
    }

    /**
     * Get available fields type for object Model (string)
     * @return array
     */
    protected function _getFieldTypes()
    {
        return ['int', 'bool', 'string', 'float', 'date', 'html', 'nothing', 'sql'];
    }

    /**
     * Get field validation functions
     * @param $fieldType
     * @return array
     * @todo Manage by field type if possible
     */
    protected function _getValidationFunctions($fieldType = null)
    {
        $functions = [];
        try {
            $validation = new \ReflectionClass(Validate::class);
            foreach ($validation->getMethods() as $method) {
                $functions[] = $method->name;
            }
        } catch (\ReflectionException $e) {
        }
        return $functions;
    }
}
