<?php

namespace SchenkeIo\LaravelUrlCleaner\Tests\Unit\Data;

use PHPUnit\Framework\TestCase;
use SchenkeIo\LaravelUrlCleaner\Data\RuleDomain;
use SchenkeIo\LaravelUrlCleaner\Data\RuleKey;
use SchenkeIo\LaravelUrlCleaner\Data\RuleSet;
use SchenkeIo\LaravelUrlCleaner\Exceptions\DefectMaskException;

class RuleTest extends TestCase
{
    public function test_rule_key_validation()
    {
        $this->assertTrue(RuleKey::isValid('abc'));
        $this->assertTrue(RuleKey::isValid('abc*'));
        $this->assertFalse(RuleKey::isValid('abc@'));
    }

    public function test_rule_domain_validation()
    {
        $this->assertTrue(RuleDomain::isValid('example.com'));
        $this->assertTrue(RuleDomain::isValid('*.example.com'));
        $this->assertFalse(RuleDomain::isValid('any string fits pattern'));
    }

    public function test_rule_set_from_mask()
    {
        $ruleSet = RuleSet::fromMask('key@domain.com');
        $this->assertEquals('key', $ruleSet->ruleKey->__toString());
        $this->assertEquals('domain.com', $ruleSet->ruleDomain->__toString());

        $ruleSet2 = RuleSet::fromMask('key');
        $this->assertEquals('key', $ruleSet2->ruleKey->__toString());
        $this->assertEquals('', $ruleSet2->ruleDomain->__toString());
    }

    public function test_rule_set_throws_exception_on_invalid_mask()
    {
        $this->expectException(DefectMaskException::class);
        RuleSet::fromMask('a@b@c');
    }

    public function test_rule_set_to_string()
    {
        $ruleSet = RuleSet::fromMask('key@domain.com');
        $this->assertEquals('key@domain.com', $ruleSet->string());

        $ruleSet2 = RuleSet::fromMask('key');
        $this->assertEquals('key', $ruleSet2->string());
    }

    public function test_rule_set_is_equal()
    {
        $ruleSet1 = RuleSet::fromMask('key@domain.com');
        $ruleSet2 = RuleSet::fromMask('key@domain.com');
        $ruleSet3 = RuleSet::fromMask('other@domain.com');

        $this->assertTrue($ruleSet1->isEqual($ruleSet2));
        $this->assertFalse($ruleSet1->isEqual($ruleSet3));
    }

    public function test_rule_set_is_included_in()
    {
        $ruleSet1 = RuleSet::fromMask('abc@sub.domain.com');
        $ruleSet2 = RuleSet::fromMask('abc*@*.domain.com');

        $this->assertTrue($ruleSet1->isIncludedIn($ruleSet2));

        $ruleSet3 = RuleSet::fromMask('abc@domain.com');
        $this->assertFalse($ruleSet3->isIncludedIn($ruleSet1));
    }

    public function test_star_handler_matches()
    {
        $handler1 = new \SchenkeIo\LaravelUrlCleaner\Data\StarHandler('*abc*');
        $this->assertTrue($handler1->match('xabcy'));

        $handler2 = new \SchenkeIo\LaravelUrlCleaner\Data\StarHandler('abc*');
        $this->assertTrue($handler2->match('abcy'));
        $this->assertFalse($handler2->match('xabc'));

        $handler3 = new \SchenkeIo\LaravelUrlCleaner\Data\StarHandler('*abc');
        $this->assertTrue($handler3->match('xabc'));
        $this->assertFalse($handler3->match('abcy'));

        $handler4 = new \SchenkeIo\LaravelUrlCleaner\Data\StarHandler('abc');
        $this->assertTrue($handler4->match('abc'));
        $this->assertFalse($handler4->match('abcd'));
    }
}
