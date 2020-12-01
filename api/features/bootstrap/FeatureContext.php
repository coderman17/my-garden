<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected string $responseBody;

    protected $responseHeaders;

    protected string $payloadBody;

    /**
     * @Given I have a valid payload:
     */
    public function iHaveAValidPayload(PyStringNode $string): void
    {
        $this->payloadBody = $string->getRaw();
    }

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @When I remove :element from the root of the payload
     */
    public function iRemoveElementFromTheRootOfThePayload($element)
    {
        $x = json_decode($this->payloadBody);
        unset($x->$element);
        $this->payloadBody = json_encode($x);
    }

    /**
     * @When I call :method :url
     */
    public function iCall($method, $url)
    {
        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => $method,
                'content' => $this->payloadBody
            ]
        ];
        $context  = stream_context_create($options);
        $this->responseBody = file_get_contents($url, false, $context);
        $this->responseHeaders = $http_response_header;
    }

    /**
     * @Then the response should have a status of :status
     */
    public function theResponseShouldHaveAStatusOf($status)
    {
        Assert::assertSame(
            $this->responseHeaders[0],
            $status
        );
    }
}
