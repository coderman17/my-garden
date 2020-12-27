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

    /**
     * @var array<mixed, mixed>
     */
    protected array $responseHeaders;

    /**
     * @var array<string, mixed>
     */
    protected array $savedParams;

    protected string $payloadBody;

    protected string $expectedResponseBody;

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
    public function iRemoveElementFromTheRootOfThePayload(string $element): void
    {
        $x = json_decode($this->payloadBody);

        unset($x->$element);

        $body = json_encode($x);

        Assert::assertNotFalse(
            $body
        );

        $this->payloadBody = $body;
    }

    /**
     * @When I upsert to the root of the payload:
     *
     * @param PyStringNode $string
     */
    public function iUpsertToTheRootOfThePayload(PyStringNode $string): void
    {
        $this->payloadBody = $this->mergeEncodedPayloads(
            $this->payloadBody,
            $string->getRaw()
        );
    }

    protected function mergeEncodedPayloads(string $one, string $two): string
    {
        $newPayload = array_merge(
            json_decode($one, TRUE),
            json_decode($two, TRUE)
        );

        return json_encode($newPayload);
    }

    /**
     * @When I upsert to the root of the payload, a string of key :key and length :length
     *
     * @param mixed $key
     * @param int $length
     */
    public function iUpsertToTheRootOfThePayloadAStringOfKeyAndLength($key, int $length): void
    {
        $string = str_repeat('a', $length);

        $this->payloadBody = $this->mergeEncodedPayloads(
            $this->payloadBody,
            '{"' . $key . '": "' . $string . '"}'
        );
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

        /** @phpstan-ignore-next-line too magical for stan I think*/
        $this->responseHeaders = $http_response_header;
    }

    /**
     * @When I save :param from the response
     *
     * @param mixed $param
     */
    public function iSaveFromTheResponse($param): void
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
    public function theResponseShouldHaveAStatusOf(string $status): void
    {
        Assert::assertSame(
            $status,
            $this->responseHeaders[0],
        );
    }

    /**
     * @When I expect the payload in the response with the saved :param
     */
    public function iExpectThePayloadInTheResponseWithTheSaved(string $param): void
    {
        $expected = json_decode($this->responseBody);

        $expected->$param = $this->savedParams[$param];

        $expected = json_encode($expected);

        Assert::assertNotFalse(
            $expected
        );

        $this->expectedResponseBody = $expected;
    }

    /**
     * @Then the response body should be as expected
     */
    public function theResponseBodyShouldBeAsExpected()
    {
        Assert::assertEquals(
            json_decode($this->expectedResponseBody),
            json_decode($this->responseBody)
        );
    }


}
