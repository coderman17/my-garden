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
    protected \stdClass $actualResponseBody;

    /**
     * @var array<mixed, mixed>
     */
    protected array $responseHeaders;

    /**
     * @var array<string, mixed>
     */
    protected array $savedParams;

    protected string $generatedParameter;

    protected \stdClass $requestBody;

    protected \stdClass $expectedResponseBody;

    /**
     * @Given I have a request body:
     *
     * @param PyStringNode $string
     */
    public function iHaveARequestBody(PyStringNode $string): void
    {
        $this->requestBody = json_decode($string->getRaw());
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
     * @When I remove :element from the root of the request body
     *
     * @param string $element
     */
    public function iRemoveElementFromTheRootOfTheRequestBody(string $element): void
    {
        unset($this->requestBody->$element);
    }

    /**
     * @When I upsert to the root of the request body:
     *
     * @param PyStringNode $string
     */
    public function iUpsertToTheRootOfTheRequestBody(PyStringNode $string): void
    {
        $incomingArray = json_decode($string->getRaw(), true);

        foreach ($incomingArray as $k => $v){
            $this->requestBody->$k = $v;
        }
    }

    /**
     * @When I upsert to the root of the request body, a string of key :key and length :length
     *
     * @param mixed $key
     * @param int $length
     *
     * @noinspection PhpMethodNamingConventionInspection it's only just over, and carefully written
     */
    public function iUpsertToTheRootOfTheRequestBodyAStringOfKeyAndLength($key, int $length): void
    {
        $string = str_repeat('a', $length);

        $this->requestBody->$key = $string;
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
                'content' => json_encode($this->requestBody)
            ]
        ];

        $context  = stream_context_create($options);

        try {
            $body = file_get_contents($url, false, $context);
            $this->actualResponseBody = json_decode($body);

        } catch (Throwable $e){
            $this->actualResponseBody = new \stdClass();

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
        $this->savedParams[$param] = $this->actualResponseBody->$param;
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
     * @When I expect the request body as the response body with the saved :param
     *
     * @param string $param
     */
    public function iExpectTheRequestBodyAsTheResponseBodyWithTheSaved(string $param): void
    {
        $this->expectedResponseBody = clone $this->requestBody;

        $this->expectedResponseBody->$param = $this->savedParams[$param];
    }

    /**
     * @Then the response body should be as expected
     */
    public function theResponseBodyShouldBeAsExpected(): void
    {
        Assert::assertEquals(
            $this->expectedResponseBody,
            $this->actualResponseBody
        );
    }
}
