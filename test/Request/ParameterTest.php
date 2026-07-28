<?php

declare(strict_types=1);

namespace JiNexus\Http\Test\Request;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JiNexus\Http\Request\Parameter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Parameter::class)]
final class ParameterTest extends TestCase
{
    #[Test]
    public function it_is_countable_and_iterable(): void
    {
        // Assert on the class's interface list rather than a typed instance,
        // so the checks reflect real contracts instead of folding to constants.
        $interfaces = class_implements(Parameter::class);

        self::assertContains(Countable::class, $interfaces);
        self::assertContains(IteratorAggregate::class, $interfaces);
    }

    #[Test]
    public function it_defaults_to_an_empty_bag(): void
    {
        $parameter = new Parameter();

        self::assertCount(0, $parameter);
        self::assertSame([], $parameter->all());
    }

    #[Test]
    public function it_exposes_the_data_it_was_constructed_with(): void
    {
        $parameter = new Parameter(['a' => 1, 'b' => 2]);

        self::assertCount(2, $parameter);
        self::assertSame(['a' => 1, 'b' => 2], $parameter->all());
    }

    #[Test]
    public function count_reflects_the_number_of_parameters(): void
    {
        // Uses the Countable contract directly.
        self::assertSame(3, new Parameter(['a' => 1, 'b' => 2, 'c' => 3])->count());
    }

    #[Test]
    public function has_reports_presence(): void
    {
        $parameter = new Parameter(['present' => 'x']);

        self::assertTrue($parameter->has('present'));
        self::assertFalse($parameter->has('absent'));
    }

    #[Test]
    public function has_is_true_for_a_key_whose_value_is_null(): void
    {
        // array_key_exists semantics, not isset.
        $parameter = new Parameter(['nullable' => null]);

        self::assertTrue($parameter->has('nullable'));
    }

    #[Test]
    public function get_returns_the_stored_value(): void
    {
        $parameter = new Parameter(['id' => 42]);

        self::assertSame(42, $parameter->get('id'));
    }

    #[Test]
    public function get_returns_null_by_default_when_key_is_missing(): void
    {
        self::assertNull(new Parameter()->get('missing'));
    }

    #[Test]
    public function get_returns_the_supplied_default_when_key_is_missing(): void
    {
        self::assertSame('fallback', new Parameter()->get('missing', 'fallback'));
    }

    #[Test]
    public function get_returns_null_value_over_default_when_key_exists(): void
    {
        // The key exists with a null value, so the default must NOT be used.
        $parameter = new Parameter(['nullable' => null]);

        self::assertNull($parameter->get('nullable', 'default'));
    }

    #[Test]
    public function add_merges_new_keys_and_overwrites_existing_ones(): void
    {
        $parameter = new Parameter(['a' => 1, 'b' => 2]);

        $parameter->add(['b' => 20, 'c' => 3]);

        self::assertSame(['a' => 1, 'b' => 20, 'c' => 3], $parameter->all());
        self::assertCount(3, $parameter);
    }

    #[Test]
    public function add_with_no_argument_leaves_the_bag_unchanged(): void
    {
        $parameter = new Parameter(['a' => 1]);

        $parameter->add();

        self::assertSame(['a' => 1], $parameter->all());
    }

    #[Test]
    public function remove_deletes_a_key(): void
    {
        $parameter = new Parameter(['a' => 1, 'b' => 2]);

        $parameter->remove('a');

        self::assertFalse($parameter->has('a'));
        self::assertSame(['b' => 2], $parameter->all());
        self::assertCount(1, $parameter);
    }

    #[Test]
    public function remove_of_a_missing_key_is_a_no_op(): void
    {
        $parameter = new Parameter(['a' => 1]);

        $parameter->remove('does-not-exist');

        self::assertSame(['a' => 1], $parameter->all());
    }

    #[Test]
    public function get_iterator_returns_an_array_iterator_over_the_data(): void
    {
        $data = ['a' => 1, 'b' => 2];
        $parameter = new Parameter($data);

        $iterator = $parameter->getIterator();

        self::assertInstanceOf(ArrayIterator::class, $iterator);
        self::assertSame($data, iterator_to_array($iterator));
    }

    #[Test]
    public function it_can_be_iterated_with_foreach(): void
    {
        $parameter = new Parameter(['a' => 1, 'b' => 2]);

        $collected = iterator_to_array($parameter);

        self::assertSame(['a' => 1, 'b' => 2], $collected);
    }
}
