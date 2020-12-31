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
    /**
     * @var array<\stdClass>|\stdClass
     */
    protected $actualResponseBody;

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
     * @Given I replace variables in the request body with the saved value
     */
    public function iReplaceVariablesInTheRequestBodyWithTheSavedValue()
    {
        $this->recursiveReplace($this->requestBody);
    }

    /**
     * @param mixed $iterable
     */
    protected function recursiveReplace(&$iterable): void
    {
        foreach ($iterable as &$item){
            if (is_object($item) || is_array($item)){
                $this->recursiveReplace($item);
            } else {
                if(!is_string($item)){
                    continue;
                }
                if(preg_match('/(?<={{).+(?=}})/', $item, $matches) == 1){
                    $item = $this->savedParams[$matches[0]];
                }
            }
        }
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
     * @When I save :param from the response as :name
     *
     * @param mixed $param
     * @param string|null $name
     */
    public function iSaveFromTheResponse($param, string $name = null): void
    {
        if ($name == null){
            $name = $param;
        }
        $this->savedParams[$name] = $this->actualResponseBody->$param;
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
     * @Then the response has a status of :status
     *
     * @param string $status
     */
    public function theResponseHasAStatusOf(string $status): void
    {
        Assert::assertSame(
            $status,
            $this->responseHeaders[0],
        );
    }

    /**
     * @When I expect the same as the request body but with the saved :param
     *
     * @param string $param
     */
    public function iExpectTheSameAsTheRequestBodyButWithTheSaved(string $param): void
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

    /**
     * @Then the response body should contain what is expected
     */
    public function theResponseBodyShouldContainWhatIsExpected(): void
    {
        Assert::assertContainsEquals(
            $this->expectedResponseBody,
            $this->actualResponseBody
        );
    }

    /**
     * @When I upsert to the root of the request body, an int of key :key and value :value
     *
     * @param string $key
     * @param int $value
     */
    public function iUpsertToTheRootOfTheRequestBodyAnIntOfKeyAndValue(string $key, int $value): void
    {
        $this->requestBody->$key = $value;
    }
}
