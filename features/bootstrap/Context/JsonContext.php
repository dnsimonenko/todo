<?php declare(strict_types=1);

namespace App\Behat\Context;

use App\Behat\Json\Json;
use App\Behat\Json\JsonInspector;
use App\Behat\Storage;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

class JsonContext implements Context
{
    protected $inspector;

    public function __construct($evaluationMode = 'javascript')
    {
        $this->inspector = new JsonInspector($evaluationMode);
    }

    protected function getJson()
    {
        return new Json(Storage::get('response'));
    }

    /**
     * @Given the JSON is:
     */
    public function jsonIs(PyStringNode $json)
    {
        Storage::set('response', $json->getRaw());
    }

    /**
     * Checks, that given JSON node is equal to given value.
     *
     * @Then the JSON node :node should be equal to :text
     */
    public function theJsonNodeShouldBeEqualTo($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertEquals($text, $actual, sprintf("The node value is '%s'", json_encode($actual)));
    }

    /**
     * Checks, that given JSON nodes are equal to givens values.
     *
     * @Then the JSON nodes should be equal to:
     */
    public function theJsonNodesShouldBeEqualTo(TableNode $nodes)
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldBeEqualTo($node, $text);
        }
    }

    /**
     * Checks, that given JSON node matches given pattern.
     *
     * @Then the JSON node :node should match :pattern
     */
    public function theJsonNodeShouldMatch($node, $pattern)
    {
        $json = $this->getJson();

        $actual = (string) $this->inspector->evaluate($json, $node);

        Assert::assertRegExp($pattern, $actual);
    }

    /**
     * Checks, that given JSON node is null.
     *
     * @Then the JSON node :node should be null
     */
    public function theJsonNodeShouldBeNull($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertSame(null, $actual, sprintf('The node value is `%s`', json_encode($actual)));
    }

    /**
     * Checks, that given JSON node is not null.
     *
     * @Then the JSON node :node should not be null
     */
    public function theJsonNodeShouldNotBeNull($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertNotSame(null, $actual, sprintf('The node %s should not be null', json_encode($actual)));
    }

    /**
     * Checks, that given JSON node is true.
     *
     * @Then the JSON node :node should be true
     */
    public function theJsonNodeShouldBeTrue($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertTrue($actual, sprintf('The node value is `%s`', json_encode($actual)));
    }

    /**
     * Checks, that given JSON node is false.
     *
     * @Then the JSON node :node should be false
     */
    public function theJsonNodeShouldBeFalse($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertFalse($actual, sprintf('The node value is `%s`', json_encode($actual)));
    }

    /**
     * Checks, that given JSON node is equal to the given string.
     *
     * @Then the JSON node :node should be equal to the string :text
     */
    public function theJsonNodeShouldBeEqualToTheString($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertInternalType('string', $text);
        Assert::assertEquals($actual, $text, sprintf('The node value is `%s`', json_encode($actual)));
    }

    /**
     * Checks, that given JSON node is equal to the given number.
     *
     * @Then the JSON node :node should be equal to the number :number
     */
    public function theJsonNodeShouldBeEqualToTheNumber($node, $number)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== (float) $number && $actual !== (int) $number) {
            throw new \Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node has N element(s).
     *
     * @Then the JSON node :node should have :count element(s)
     */
    public function theJsonNodeShouldHaveElements($node, $count)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertSame((int) $count, \count((array) $actual));
    }

    /**
     * Checks, that given JSON node has more than N element(s).
     *
     * @Then the JSON node :node should have more than :count element(s)
     */
    public function theJsonNodeShouldHaveElementsMoreThan($node, $count)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertGreaterThan((int) $count, \count((array) $actual));
    }

    /**
     * Checks, that given JSON node contains given value.
     *
     * @Then the JSON node :node should contain :text
     */
    public function theJsonNodeShouldContain($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertContains($text, (string) $actual);
    }

    /**
     * Checks, that given JSON nodes contains values.
     *
     * @Then the JSON nodes should contain:
     */
    public function theJsonNodesShouldContain(TableNode $nodes)
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldContain($node, $text);
        }
    }

    /**
     * Checks, that given JSON node does not contain given value.
     *
     * @Then the JSON node :node should not contain :text
     */
    public function theJsonNodeShouldNotContain($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        Assert::assertNotContains($text, (string) $actual);
    }

    /**
     * Checks, that given JSON nodes does not contain given value.
     *
     * @Then the JSON nodes should not contain:
     */
    public function theJsonNodesShouldNotContain(TableNode $nodes)
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldNotContain($node, $text);
        }
    }

    /**
     * Checks, that given JSON node exist.
     *
     * @Then the JSON node :name should exist
     */
    public function theJsonNodeShouldExist($name)
    {
        $json = $this->getJson();

        try {
            $node = $this->inspector->evaluate($json, $name);
        } catch (\Exception $e) {
            throw new \Exception("The node '$name' does not exist.");
        }

        return $node;
    }

    /**
     * Checks, that given JSON node does not exist.
     *
     * @Then the JSON node :name should not exist
     */
    public function theJsonNodeShouldNotExist($name)
    {
        $json = $this->getJson();
        $node = null;

        try {
            $node = $this->inspector->evaluate($json, $name);
        } catch (\Exception $e) {
            // continue
        }

        if ($node) {
            throw new \Exception("The node '$name' exists.");
        }
    }

    /**
     * @Then the JSON should be equal to:
     */
    public function theJsonShouldBeEqualTo(PyStringNode $content)
    {
        $actual = $this->getJson();

        try {
            $expected = new Json($content);
        } catch (\Exception $e) {
            throw new \Exception('The expected JSON is not a valid');
        }

        Assert::assertSame((string) $expected, (string) $actual, "The json is equal to:\n" . $actual->encode());
    }
}
