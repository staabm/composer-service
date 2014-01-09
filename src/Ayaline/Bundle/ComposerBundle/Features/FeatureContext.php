<?php

namespace Ayaline\Bundle\ComposerBundle\Features;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ExpectationException;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\Step\When;

class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When /^I press "([^"]*)" after it is ready$/
     */
    public function iPressAfterItIsReady($buttonLabel)
    {
        $this->getSession()->wait(15000, '!$("button").hasClass("disabled")');

        return new When("I press \"$buttonLabel\"");
    }

    /**
     * @When /^I wait until the download button shows up$/
     */
    public function waitUntilTheDownloadButtonShowsUp()
    {
        $this->getSession()->wait(30000, '$("a#download-link").hasClass("in")');
    }

    /**
     * @Given /^I should see "([^"]*)" link$/
     */
    public function iShouldSeeLink($buttonLabel)
    {
        /** @var NodeElement $linkNode */
        $linkNode = $this->getSession()->getPage()->findLink('download-link');

        $hrefValue = $linkNode->getAttribute('href');
        if (empty($hrefValue)) {
            $message = 'Download link was not generated successfully.';
            throw new ExpectationException($message, $this->getSession());
        }
    }
}
