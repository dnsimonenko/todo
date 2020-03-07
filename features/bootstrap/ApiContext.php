<?php declare(strict_types=1);

namespace App\Behat;

use App\Behat\Model\RequestModel;
use App\Behat\Storage;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Exception;
use PHPUnit\Framework\Assert as Assertions;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\KernelInterface;

class ApiContext implements KernelAwareContext
{
    /** @var array */
    protected $placeholders = [];

    /** @var KernelInterface */
    protected $kernel;

    /** @var array */
    protected $headers = [];

    /** @var RequestModel */
    protected $request;

    /** @var HttpResponse */
    protected $response;

    /**
     * Sets place holder for replacement.
     *
     * you can specify placeholders, which will
     * be replaced in URL, request or response body.
     *
     * @param string $key token name
     * @param string $value replace value
     */
    protected function setPlaceholder(string $key, string $value)
    {
        $this->placeholders[$key] = $value;
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    protected function replacePlaceholder(string $string): string
    {
        foreach ($this->placeholders as $key => $val) {
            $string = str_replace($key, $val, $string);
        }

        return $string;
    }

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     *
     * @throws Exception
     */
    public function beforeScenario(): void
    {
        $this->request = new RequestModel();
        Storage::set('request', $this->request);
        Storage::set('response', null);
    }

    /**
     * Sends HTTP request to specific URL with raw body from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $string request body
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with body:$/
     */
    public function iSendARequestWithBody(string $method, string $url, PyStringNode $string)
    {
        $this->request->setMethod($method);
        $this->request->setUrl($this->prepareUrl($url));

        $fields = $this->toJSON($string->getRaw());
        $this->request->setParameters($fields);
        $this->request->setContent(json_encode($fields));

        $this->sendRequest();
    }

    /**
     * Sends HTTP request to specific URL with raw body from PyString.
     *
     * @param string $method request method
     * @param string $url relative url
     * @param PyStringNode $string request body
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with body and signature:$/
     */
    public function iSendARequestWithBodyAndSignature(string $method, string $url, PyStringNode $string)
    {
        $this->iSendARequestWithBody($method, $url, $string);
    }

    /**
     * Sends a HTTP request without parameters.
     *
     * @When I send a :method request to :url
     */
    public function iSendARequestTo($method, $url)
    {
        $this->request->setMethod($method);
        $this->request->setUrl($this->prepareUrl($url));

        $this->sendRequest();
    }

    /**
     * @Given /^request is sent$/
     */
    public function requestIsSent()
    {
        $this->sendRequest();
    }

    /**
     * @Given /^request uri path (?:is|should be) "([^"]+)"$/
     *
     * @param string $uri
     */
    public function requestUriPathIs(string $uri): void
    {
        $this->request->setUrl($this->prepareUrl($uri));
    }

    /**
     * @Given /^request method is "(GET|HEAD|POST|PUT|DELETE|OPTIONS|PATCH)"$/
     *
     * @param string $method
     */
    public function requestMethodIs(string $method): void
    {
        $this->request->setMethod($method);
    }

    /**
     * @Given /^request body contains the following json:$/
     *
     * @param PyStringNode $json
     */
    public function requestBodyContainsTheFollowingJson(PyStringNode $json)
    {
        $json = Storage::replaceVariables($json->getRaw());
        $this->request->setContent($json);
    }

    /**
     * @Given /^"([^"]+)" header (?:is|should be) set to "([^"]*)"$/
     *
     * @param string $name
     * @param string $value
     */
    public function headerIsSetTo(string $name, string $value): void
    {
        $this->request->setHeader($name, $value);
    }

    /**
     * Sends a HTTP request without parameters.
     *
     * @Given I add :header header with value :value
     */
    public function addHeader(string $name, string $value)
    {
        if (Storage::get($value)) {
            $value = Storage::get($value);
        }

        $this->request->setHeader($name, $value);
    }

    /**
     * Prepare URL by replacing placeholders and trimming slashes.
     *
     * @param string $url
     *
     * @return string
     */
    protected function prepareUrl(string $url): string
    {
        $url = Storage::replaceVariables($url);

        return $this->replacePlaceholder($url);
    }

    protected function sendRequest()
    {
        ob_start();
        $this->response = $this->kernel->handle($this->request->createRequest());
        ob_end_clean();

        //Set the data from the response to the storage
        Storage::setData($this->response->getContent() ? $this->retrieveData() : []);
        Storage::set('response', $this->response);
    }

    /**
     * @param null|string $name
     *
     * @return mixed
     */
    protected function retrieveData(string $name = null)
    {
        $data = @json_decode($this->response->getContent(), true);

        Assertions::assertTrue(
            ($data !== null && json_last_error() === JSON_ERROR_NONE),
            'The response json is not formatted correctly. (1): ' . $this->response->getContent()
        );

        if ($name) {
            Assertions::assertArrayHasKey(
                $name,
                $data,
                'Response does not contain required attribute "' . $name . '"'
            );

            if (!array_key_exists($name, $data)) {
                $language = new \Symfony\Component\ExpressionLanguage\ExpressionLanguage();
                try {
                    return $language->evaluate($name, $data);
                } catch (\Symfony\Component\ExpressionLanguage\SyntaxError $e) {
                    throw new \InvalidArgumentException(sprintf('Key %s does not exists in array with keys %s', $name, implode(', ', array_keys($data))));
                }
            }

            return $data[$name];
        }

        return $data;
    }

    /**
     * Checks, that current page response status is equal to specified
     * Example: Then the response code should be 200
     * Example: And the response code should be 400.
     *
     * @Then /^(?:the )?response (?:status )?code should be (?P<code>\d+)$/
     */
    public function assertResponseStatus($code)
    {
        $actual = $this->response->getStatusCode();
        $message = sprintf('Current response status code is %d, but %d expected.', $actual, $code);
        try {
            Assertions::assertSame((int) $code, (int) $actual, $message);
        } catch (ExpectationFailedException $e) {
            throw new ExpectationFailedException(
                sprintf(
                    "%s \nResponse body:\n %s",
                    $e->getMessage(),
                    $this->response->getContent()
                )
            );
        }
    }

    /**
     * Prints last response body as array with symfony dump command.
     *
     * @Then dump response
     */
    public function dumpResponse()
    {
        $content = $this->response->getContent();
        $json = json_decode($content, true);
        $output = (JSON_ERROR_NONE === json_last_error()) ? $json : $content;
        var_export($output);
    }

    /**
     * @param PyStringNode $jsonString
     *
     * @throws \RuntimeException
     *
     * @Then /^(?:the )?response (?:body )?should contain (?:the following )?json:$/
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString)
    {
        $etalon = @json_decode($this->replacePlaceholder(trim($jsonString->getRaw())), true);

        if (null === $etalon || JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(
                "Can not convert etalon to json:\n" . $this->replacePlaceholder($jsonString->getRaw())
            );
        }

        $actual = @json_decode($this->response->getContent(), true);

        if (null === $actual || JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(
                "Can not convert actual to json:\n" . $this->replacePlaceholder($this->response->getContent())
            );
        }

        $this->validateFields($actual, $etalon);
    }

    /**
     * @param array $data
     * @param array $definition
     */
    protected function validateFields(array $data, array $definition = [])
    {
        foreach ($definition as $field => $value) {
            $this->validateField($data, $field, $value);
        }
    }

    /**
     * @param array $data
     * @param string $field
     * @param        $expectedValue
     */
    protected function validateField(array $data, $field, $expectedValue)
    {
        if (\is_string($field) && ('?' === mb_substr($field, 0, 1))) {
            $field = mb_substr($field, 1);

            if (!\array_key_exists($field, $data)) {
                return;
            }
        }

        Assertions::assertArrayHasKey(
            $field,
            $data,
            'The key "' . $field . '" is not present in the array. Array is: ' . print_r($data, true)
            . '. Available keys are: ' . json_encode(array_keys($data))
        );

        $actualValue = $data[$field];

        // for fields with array of similar data inside
        if (\is_string($expectedValue) && $type = self::getTypeDefinition($expectedValue)) {
            $errorMessage = sprintf(
                'The value "%s" of the field "%s" is not of type "%s"',
                $actualValue,
                $field,
                $type
            );
            switch ($type) {
                case 'integer':
                case 'int':
                    Assertions::assertTrue(\is_int($actualValue), $errorMessage);
                    break;
                case 'string':
                    Assertions::assertTrue(\is_string($actualValue), $errorMessage);
                    break;
                case 'float':
                case 'double':
                    Assertions::assertTrue(\is_float($actualValue) || \is_int($actualValue), $errorMessage);
                    break;
                case 'bool':
                case 'boolean':
                    Assertions::assertTrue(\is_bool($actualValue), $errorMessage);
                    break;
                case 'array':
                    Assertions::assertTrue(
                        \is_array($actualValue) && $this->isArraySequential($actualValue),
                        $errorMessage
                    );
                    break;
                case 'object':
                    Assertions::assertTrue(
                        \is_array($actualValue) && !$this->isArraySequential($actualValue),
                        $errorMessage
                    );
                    break;
                case 'datetime':
                    try {
                        new \DateTime((string) $actualValue);
                    } catch (Exception $ex) {
                        Assertions::assertTrue(false, $errorMessage);
                    }
                    break;
                default:
                    throw new \RuntimeException(sprintf('Not implemented type check for "%s"', $type));
            }
        } elseif (\is_array($expectedValue)) {
            Assertions::assertTrue(
                \is_array($actualValue),
                'The field "' . $field . '" is not an array. It\'s type is "' . \gettype($actualValue) . '"'
            );

            // validate each member of array separately as regular field
            $this->validateFields($actualValue, $expectedValue);
        } elseif (\is_string($expectedValue) && self::isRegExp($expectedValue)) {
            Assertions::assertRegExp(
                $expectedValue,
                (string) $actualValue,
                'The value "' . $actualValue . '" of the field "' . $field . '" does not match pattern "'
                . $expectedValue . '"'
            );
        } else {
            //Bool to string
            if (\is_bool($actualValue)) {
                $actualValue = ($actualValue) ? 'true' : 'false';
            }
            if (\is_bool($expectedValue)) {
                $expectedValue = ($expectedValue) ? 'true' : 'false';
            }
            Assertions::assertTrue(
                \is_string($actualValue) || is_numeric($actualValue),
                'The value of the field "' . $field . '" must be of type string.  "'
                . \gettype($actualValue) . '" returned.'
            );
            Assertions::assertEquals(
                $expectedValue,
                $actualValue,
                'The value "' .
                (string) $actualValue . '" of the field "' .
                $field . '" does not match expected "' .
                (string) $expectedValue . '"'
            );
        }
    }

    public static function getTypeDefinition(string $string): ?string
    {
        $allowedTypes = [
            'string',
            'bool',
            'boolean',
            'integer',
            'int',
            'float',
            'double',
            'array',
            'object',
            'datetime',
        ];
        $type = null;
        if (preg_match('|\@(.*)|', $string, $matches) && \in_array($matches[1], $allowedTypes, true)) {
            $type = $matches[1];
        }

        return $type;
    }

    public static function isRegExp(string $string)
    {
        return 1 === preg_match('/^\/.+\/$/', $string) || 1 === preg_match('/^~.+~$/', $string);
    }

    private function isArraySequential(array $arr): bool
    {
        return 0 === \count($arr) && array_keys($arr) === range(0, \count($arr) - 1);
    }

    protected function toJSON($string, $asAssocArray = true)
    {
        $string = (string) $string;
        $string = $this->replacePlaceholder(trim($string));
        $fields = @json_decode($string, $asAssocArray);

        Assertions::assertTrue(
            ($fields !== null && json_last_error() === JSON_ERROR_NONE),
            'The body is not formatted correctly'
        );

        return $fields;
    }
}
