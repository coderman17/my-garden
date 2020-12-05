<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
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
     * @When I upsert to the root of the payload:
     */
    public function iUpsertToTheRootOfThePayload(PyStringNode $string)
    {
        $newPayload = array_merge(
            json_decode($this->payloadBody, TRUE),
            json_decode($string->getRaw(), TRUE)
        );
        $this->payloadBody = json_encode($newPayload);
    }

    /**
     *  @When I upsert to the root of the payload the key of :key with a string value of :length characters
     */
    public function iUpsertToTheRootOfThePayloadTheKeyOfWithAStringValueOfCharacters($key, $length)
    {
        $string = str_repeat('a', $length);
        $pyStringNode = new PyStringNode([
            0 => "{",
            1 => '        "' . $key . '": "' . $string . '"',
            2 => "}"
        ], 0);
        $this->iUpsertToTheRootOfThePayload($pyStringNode);
    }

    /**
     * @When I call :method :url
     */
    public function iCall(string $method, string $url): void
    {
        $options = [
            'http' => [
                'header'  => "Content-type: application/json",
                'method'  => $method,
                'content' => $this->payloadBody
            ]
        ];
        $context  = stream_context_create($options);
        try {
            $this->responseBody = file_get_contents($url, false, $context);
        } catch (Throwable $e){
            $this->responseBody = '';
        }
        $this->responseHeaders = $http_response_header;
    }

    /**
     * @Then the response should have a status of :status
     */
    public function theResponseShouldHaveAStatusOf($status)
    {
        Assert::assertSame(
            $status,
            $this->responseHeaders[0],
        );
    }
}
