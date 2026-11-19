<?php

namespace Tests\Feature;

use App\Services\BusinessNameService;
use PHPUnit\Framework\Attributes\DataProvider;
use Random\Engine\Mt19937;
use Random\Randomizer;
use Tests\TestCase;

class NameGeneratorTest extends TestCase
{
    public static function industries(): array
    {
        $industries = (new BusinessNameService())->industries();

        return array_combine($industries, array_map(fn ($i) => [$i], $industries));
    }

    public static function invalidInput(): array
    {
        return [
            'missing keywords'         => [['keywords' => '', 'industry' => 'fashion'], 'keywords'],
            'keywords without letters' => [['keywords' => '123 !!', 'industry' => 'fashion'], 'keywords'],
            'keywords too long'        => [['keywords' => str_repeat('a', 101), 'industry' => 'fashion'], 'keywords'],
            'missing industry'         => [['keywords' => 'bakery', 'industry' => ''], 'industry'],
            'unknown industry'         => [['keywords' => 'bakery', 'industry' => 'plumbing'], 'industry'],
        ];
    }

    public function test_the_form_page_loads_without_results(): void
    {
        $this->get(route('generator.index'))
            ->assertOk()
            ->assertSee('Free Business Name Generator UK')
            ->assertSee('Choose an industry')
            ->assertDontSee('Your business names');
    }

    public function test_the_industry_dropdown_lists_every_industry_from_the_service(): void
    {
        $response = $this->get(route('generator.index'))->assertOk();

        foreach ((new BusinessNameService())->industries() as $industry) {
            $response->assertSee('value="' . $industry . '"', false);
        }
    }

    public function test_a_valid_submission_shows_names_taglines_and_palette(): void
    {
        $this->app->instance(
            BusinessNameService::class,
            new BusinessNameService(new Randomizer(new Mt19937(1)))
        );

        $this->post(route('generator.generate'), ['keywords' => 'red shoes', 'industry' => 'fashion'])
            ->assertOk()
            ->assertSee('Your business names')
            ->assertSee('Where style finds its place')   // a fashion tagline
            ->assertSee('#2C1810')                       // fashion primary colour
            ->assertSee('Playfair Display')              // fashion heading font
            ->assertViewHas('names', fn (array $names) => count($names) === BusinessNameService::MAX_NAMES);
    }

    #[DataProvider('industries')]
    public function test_every_industry_can_be_submitted(string $industry): void
    {
        $this->post(route('generator.generate'), ['keywords' => 'bakery', 'industry' => $industry])
            ->assertOk()
            ->assertViewHasAll(['names', 'taglines', 'palette']);
    }

    #[DataProvider('invalidInput')]
    public function test_invalid_input_is_rejected(array $input, string $field): void
    {
        $this->from(route('generator.index'))
            ->post(route('generator.generate'), $input)
            ->assertRedirect(route('generator.index'))
            ->assertSessionHasErrors($field);
    }

    public function test_the_keywords_are_kept_when_validation_fails(): void
    {
        $this->from(route('generator.index'))
            ->post(route('generator.generate'), ['keywords' => 'bakery', 'industry' => ''])
            ->assertSessionHasErrors('industry')
            ->assertSessionHasInput('keywords', 'bakery');
    }

    public function test_generated_names_never_contain_markup(): void
    {
        $this->post(route('generator.generate'), ['keywords' => '<script>alert(1)</script> bakery', 'industry' => 'food'])
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false);
    }
}
