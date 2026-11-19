<?php

namespace Tests\Unit;

use App\Services\BusinessNameService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

class BusinessNameServiceTest extends TestCase
{
    private function seeded(int $seed = 42): BusinessNameService
    {
        return new BusinessNameService(new Randomizer(new Mt19937($seed)));
    }

    public static function industries(): array
    {
        $industries = (new BusinessNameService())->industries();

        return array_combine($industries, array_map(fn ($i) => [$i], $industries));
    }

    /*
    |--------------------------------------------------------------------------
    | Name patterns (tested on the unshuffled pool)
    |--------------------------------------------------------------------------
    */

    public function test_single_keyword_builds_all_three_patterns(): void
    {
        $names = (new BusinessNameService())->candidateNames('bakery', 'fashion');

        $this->assertContains('Bakery Studio', $names);   // keyword + suffix
        $this->assertContains('Velvet Bakery', $names);   // prefix + keyword
        $this->assertContains('Velvet Place', $names);    // prefix + suffix
        $this->assertCount(19, $names);                   // 5 + 5 + 9, no keyword pair
    }

    public function test_two_keywords_also_build_keyword_pairs_in_both_orders(): void
    {
        $names = (new BusinessNameService())->candidateNames('red shoes', 'fashion');

        $this->assertContains('Red Shoes', $names);
        $this->assertContains('Shoes Red', $names);
        $this->assertCount(31, $names);                   // 10 + 10 + 9 + 2
    }

    public function test_keywords_are_cleaned_before_use(): void
    {
        $names = (new BusinessNameService())->candidateNames('  fresh!!  bread 123 a ', 'food');

        $this->assertContains('Fresh Kitchen', $names);
        $this->assertContains('Bread Kitchen', $names);
        $this->assertContains('Fresh Bread', $names);

        foreach ($names as $name) {
            $this->assertDoesNotMatchRegularExpression('/[^A-Za-z ]/', $name);
            $this->assertStringNotContainsString('A Kitchen', $name); // one-letter word dropped
        }
    }

    public function test_empty_keywords_fall_back_to_industry_branded_names_only(): void
    {
        $names = (new BusinessNameService())->candidateNames('', 'tech');

        $this->assertCount(9, $names);
        $this->assertContains('Swift Labs', $names);
    }

    public function test_candidates_contain_no_duplicates(): void
    {
        $names = (new BusinessNameService())->candidateNames('swift swift', 'tech');

        $this->assertSame(count($names), count(array_unique($names)));
    }

    public function test_industry_is_case_insensitive(): void
    {
        $service = new BusinessNameService();

        $this->assertSame(
            $service->candidateNames('bakery', 'fashion'),
            $service->candidateNames('bakery', 'FASHION')
        );
    }

    public function test_unknown_industry_uses_the_default_word_banks(): void
    {
        $names = (new BusinessNameService())->candidateNames('xyz', 'not-a-real-industry');

        $this->assertContains('Xyz Co', $names);
        $this->assertContains('Nova Xyz', $names);
    }

    #[DataProvider('industries')]
    public function test_every_industry_has_enough_words_for_every_pattern(string $industry): void
    {
        $names = (new BusinessNameService())->candidateNames('bakery', $industry);

        $this->assertCount(19, $names);
    }

    /*
    |--------------------------------------------------------------------------
    | Randomness
    |--------------------------------------------------------------------------
    */

    public function test_generate_names_returns_at_most_fifteen_unique_names(): void
    {
        $names = (new BusinessNameService())->generateNames('red shoes', 'fashion');

        $this->assertCount(BusinessNameService::MAX_NAMES, $names);
        $this->assertSame(count($names), count(array_unique($names)));
    }

    public function test_generate_names_returns_everything_when_the_pool_is_small(): void
    {
        $names = (new BusinessNameService())->generateNames('', 'tech');

        $this->assertCount(9, $names);
    }

    public function test_generated_names_always_come_from_the_candidate_pool(): void
    {
        $service = new BusinessNameService();
        $pool    = $service->candidateNames('red shoes', 'fashion');

        foreach ($service->generateNames('red shoes', 'fashion') as $name) {
            $this->assertContains($name, $pool);
        }
    }

    public function test_the_same_seed_gives_the_same_names(): void
    {
        $this->assertSame(
            $this->seeded(42)->generateNames('red shoes', 'fashion'),
            $this->seeded(42)->generateNames('red shoes', 'fashion')
        );
    }

    public function test_different_seeds_give_a_different_selection(): void
    {
        $this->assertNotSame(
            $this->seeded(1)->generateNames('red shoes', 'fashion'),
            $this->seeded(2)->generateNames('red shoes', 'fashion')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Palettes and taglines
    |--------------------------------------------------------------------------
    */

    #[DataProvider('industries')]
    public function test_every_palette_is_complete_and_uses_valid_hex_colours(string $industry): void
    {
        $palette = (new BusinessNameService())->getBrandPalette($industry);

        foreach (['primary', 'secondary', 'accent', 'light', 'background'] as $key) {
            $this->assertArrayHasKey($key, $palette);
            $this->assertMatchesRegularExpression('/^#[0-9A-F]{6}$/i', $palette[$key]);
        }

        $this->assertNotEmpty($palette['font_heading']);
        $this->assertNotEmpty($palette['font_body']);
    }

    public function test_unknown_industry_gets_the_default_palette(): void
    {
        $service = new BusinessNameService();

        $this->assertSame(
            $service->getBrandPalette('default'),
            $service->getBrandPalette('not-a-real-industry')
        );
    }

    #[DataProvider('industries')]
    public function test_every_industry_has_taglines(string $industry): void
    {
        $taglines = (new BusinessNameService())->getTaglines($industry);

        $this->assertNotEmpty($taglines);
        $this->assertContainsOnly('string', $taglines);
    }

    public function test_unknown_industry_gets_the_default_taglines(): void
    {
        $service = new BusinessNameService();

        $this->assertSame(
            $service->getTaglines('default'),
            $service->getTaglines('not-a-real-industry')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Industries list
    |--------------------------------------------------------------------------
    */

    public function test_industries_list_excludes_the_default_fallback(): void
    {
        $industries = (new BusinessNameService())->industries();

        $this->assertContains('fashion', $industries);
        $this->assertNotContains('default', $industries);
        $this->assertCount(10, $industries);
    }
}
