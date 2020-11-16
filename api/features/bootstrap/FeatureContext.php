<?php /** @noinspection PhpUnused */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    protected string $responseBody;

    protected array $responseHeaders;

    protected array $savedParams;

    protected string $payloadBody;

    /**
     * @Given I have a valid payload:
     *
     * @param PyStringNode $string
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
     *
     * @param string $element
     */
    public function iRemoveElementFromTheRootOfThePayload(string $element)
    {
        $x = json_decode($this->payloadBody);

        unset($x->$element);

        $this->payloadBody = json_encode($x);
    }

    /**
     * @When I upsert to the root of the payload:
     *
     * @param PyStringNode $string
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
     * @When I upsert to the root of the payload, a string of key :key and length :length
     *
     * @param mixed $key
     * @param int $length
     */
    public function iUpsertToTheRootOfThePayloadAStringOfKeyAndLength($key, int $length)
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
     *
     * @param string $method
     * @param string $url
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
     * @When I save :param from the response
     *
     * @param mixed $param
     */
    public function iSaveFromTheResponse($param)
    {
        $bodyArray = json_decode($this->responseBody, true);

        $this->savedParams[$param] = $bodyArray[$param];
    }


    /**
     * @When I call :method :url appending the saved :param
     *
     * @param string $method
     * @param string $url
     * @param string $param
     */
    public function iCallAppendingTheSaved(string $method, string $url, string $param): void
    {
        $this->iCall($method, $url . $this->savedParams[$param]);
    }

    /**
     * @Then the response should have a status of :status
     *
     * @param string $status
     */
    public function theResponseShouldHaveAStatusOf(string $status)
    {
        Assert::assertSame(
            $status,
            $this->responseHeaders[0],
        );
    }
}
