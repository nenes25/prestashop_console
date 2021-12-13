<?php
/**
 * Since 2016 Hennes Hervé
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
 * @copyright Since 2016 Hennes Hervé
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * http://www.h-hennes.fr/blog/
 */

/**
 * Console wrapper in browser
 *
 * With this way you cannot have interaction with the console, every parameters should be provided in url
 *
 * WARNING: Remove this file after your works as been done as it can be a security issue to let it present
 *
 * Upload this file in the root directory of your prestashop
 * Call the page this way
 * http://www.site.com/prestashopConsoleWrapper.php?command=command:name:domain&arguments['argumentKey']=argumentValue&=options['optionkey']=value
 *
 * Several examples :
 *
 * Show help of the command admin:user:list
 * prestashopConsoleWrapper.php?command=admin:user:list&options[]=help
 * List only active modules
 * prestashopConsoleWrapper.php?command=module:list&options[]=active
 * List only active modules not from prestashop
 * prestashopConsoleWrapper.php?command=module:list&options[]=active&options[]=no-native
 *
 */
class PrestashopConsoleWrapper
{
    /**
     * @var array
     */
    private $params;

    private $command = "";

    private $arguments = "";

    private $options = "";

    /**
     * @param array $arguments
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->params = $params;
        $this->validateParams();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function validateParams()
    {
        $isValid = true;
        $errorMessage = "";

        //Arguments and options cannot be defined if not command is defined
        if (
            !array_key_exists('command', $this->params)
            && (array_key_exists('arguments', $this->params) || array_key_exists('options', $this->params))
        ) {
            $isValid = false;
            $errorMessage = "You cannot provid argument and/or option without a command name";
        }

        if (null !== ($command = $this->getCommand())) {
            if (preg_match('#^([A-z])*[^\s]*$#', $command)) {
                $this->command = " " . $command;
            } else {
                $isValid = false;
                $errorMessage = "Invalid command name";
            }
        }

        if (null !== ($command = $this->getCommand())) {
            $this->command = " " . $command;
        }

        if (null !== ($arguments = $this->getArguments())) {
            $this->arguments = " " . $arguments;
        }

        if (null !== ($options = $this->getOptions())) {
            $this->options = " " . $options;
        }

        if (false === $isValid) {
            throw new Exception($errorMessage);
        }
    }

    /**
     * Get provided command
     * @return string|null
     */
    protected function getCommand()
    {
        $command = null;
        if (array_key_exists('command', $this->params)) {
            $command = trim(strip_tags($this->params['command']));
        }
        return $command;
    }

    /**
     * Get provided arguments
     * @return string|null
     */
    protected function getArguments()
    {
        $arguments = null;
        if (
            array_key_exists('arguments', $this->params)
            && is_array($this->params['arguments'])
        ) {
            foreach ($this->params['arguments'] as $key => $value) {
                if (is_numeric($key)) {
                    $arguments .= ' --' . strip_tags($value);
                } else {
                    $arguments .= ' --' . strip_tags($key) . '="' . $value . '"';
                }
            }
        }
        return $arguments;
    }

    /**
     * Get provided options
     * @return string|null
     */
    protected function getOptions()
    {
        $options = null;
        if (
            array_key_exists('options', $this->params)
            && is_array($this->params['options'])
        ) {
            foreach ($this->params['options'] as $key => $value) {
                if (is_numeric($key)) {
                    $options .= ' --' . strip_tags($value);
                } else {
                    $options .= ' --' . strip_tags($key) . '="' . $value . '"';
                }
            }
        }
        return $options;
    }

    /**
     * Get Console parameters as a string
     * @return string
     */
    public function getCommandParametersString()
    {
        return $this->command . $this->arguments . $this->options;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prestashop Console wrapper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        h1 {
            font-size: 30px;
        }

        .console-output {
            background-color: #000;
            color: #FFF;
            max-height: 50em;
            overflow: scroll;
        }
    </style>
</head>
<body>
<h1>Prestashop Console wrapper</h1>
<?php if (!function_exists('exec') || !function_exists('shell_exec')): ?>
    <div class="alert alert-danger mt-3 align-center">
        The functions <strong>exec</strong> and/or <strong>shell_exec</strong> does not exists or are not allowed<br/>
        Sorry we can not run the console wrapper.<br/>
        You should check with your provider.
    </div>
<?php else: ?>
    <div class="console-output">
        <?php
        try {
            $wrapper = new PrestashopConsoleWrapper($_GET);
            $parameters = $wrapper->getCommandParametersString();
            //If you want to use another version of php you can provide it manually here
            $phpBin = shell_exec('which php5.6');
            exec(trim($phpBin) . ' prestashopConsole.phar' . $parameters, $consoleOutput);
            echo implode("<br />", $consoleOutput);
        } catch (Exception $e) {
            ?>
            <div class="alert alert-danger mt-3 align-center">
                Error when executing command : <br/><strong><?= $e->getMessage(); ?></strong>
            </div>
            <?php
        } ?>
    </div>
<?php endif; ?>
</body>
</html>

