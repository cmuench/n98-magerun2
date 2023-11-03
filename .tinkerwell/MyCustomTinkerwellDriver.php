<?php

class MyCustomTinkerwellDriver extends TinkerwellDriver
{
    private $application;

    /**
     * Determine if the driver can be used with the selected project path.
     * You most likely want to check the existence of project / framework specific files.
     *
     * @param string $projectPath
     * @return bool
     */
    public function canBootstrap($projectPath)
    {
        // if symfony is installed we can bootstrap this project
        return file_exists($projectPath . '/vendor/symfony/console/Application.php');
    }

    /**
     * Bootstrap the application so that any executed can access the application in your desired state.
     *
     * @param string $projectPath
     */
    public function bootstrap($projectPath)
    {
        $this->application = include_once $projectPath . '/src/bootstrap.php';
    }

    public function getAvailableVariables()
    {
        return [
            'application' => $this->application,
            'app' => $this->application,
            'om' => $this->application->getObjectManager(),
            'di' => $this->application->getObjectManager(),
        ];
    }
}
