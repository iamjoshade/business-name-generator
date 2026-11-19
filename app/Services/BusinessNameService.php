<?php

namespace App\Services;

use Random\Randomizer;

class BusinessNameService
{
    public const MAX_NAMES = 15;

    private Randomizer $randomizer;

    /**
     * Pass a seeded Randomizer in tests to get repeatable results:
     * new BusinessNameService(new Randomizer(new Mt19937(42)))
     */
    public function __construct(?Randomizer $randomizer = null)
    {
        $this->randomizer = $randomizer ?? new Randomizer();
    }

    /*
    |--------------------------------------------------------------------------
    | WORD BANKS
    |--------------------------------------------------------------------------
    */

    protected array $prefixes = [
        'fashion'   => ['Velvet', 'Luxe', 'Noir', 'Silk', 'Posh', 'Chic', 'Vogue', 'Drape', 'Thread', 'Stitch'],
        'tech'      => ['Swift', 'Nova', 'Apex', 'Pixel', 'Stack', 'Byte', 'Logic', 'Core', 'Nexus', 'Pulse'],
        'food'      => ['Spice', 'Savour', 'Flame', 'Harvest', 'Zest', 'Crisp', 'Golden', 'Hearth', 'Pepper', 'Feast'],
        'logistics' => ['Swift', 'Prime', 'Route', 'Arrow', 'Relay', 'Track', 'Cargo', 'Haul', 'Bridge', 'Link'],
        'finance'   => ['Vault', 'Crest', 'Summit', 'Apex', 'Prestige', 'Sterling', 'Prime', 'Capital', 'Shield', 'Trust'],
        'health'    => ['Vital', 'Cure', 'Bloom', 'Pulse', 'Renew', 'Heal', 'Thrive', 'Mend', 'Care', 'Nexus'],
        'education' => ['Bright', 'Sage', 'Lumen', 'Crest', 'Scholar', 'Peak', 'Aspire', 'Veritas', 'Coda', 'Axiom'],
        'retail'    => ['Prime', 'Select', 'Luxe', 'Bazaar', 'Crest', 'Haven', 'Corner', 'Depot', 'Hub', 'Square'],
        'beauty'    => ['Glow', 'Bloom', 'Radiance', 'Velvet', 'Petal', 'Luxe', 'Aura', 'Sheen', 'Silk', 'Bliss'],
        'legal'     => ['Crest', 'Shield', 'Summit', 'Pillar', 'Vantage', 'Axiom', 'Arbour', 'Charter', 'Prestige', 'Coda'],
        'default'   => ['Nova', 'Prime', 'Apex', 'Crest', 'Summit', 'Bright', 'Swift', 'Bold', 'Nexus', 'Vantage'],
    ];

    protected array $suffixes = [
        'fashion'   => ['Place', 'Studio', 'House', 'Atelier', 'Collective', 'Edit', 'Label', 'Co', 'Boutique', 'Thread'],
        'tech'      => ['Labs', 'Tech', 'HQ', 'Studio', 'Works', 'Systems', 'Hub', 'Cloud', 'Soft', 'IO'],
        'food'      => ['Kitchen', 'Bites', 'Grill', 'Table', 'Pantry', 'Eats', 'House', 'Bistro', 'Bowl', 'Co'],
        'logistics' => ['Express', 'Logistics', 'Movers', 'Haulage', 'Freight', 'Dispatch', 'Solutions', 'Co', 'Group', 'Services'],
        'finance'   => ['Capital', 'Finance', 'Group', 'Advisors', 'Solutions', 'Partners', 'Consulting', 'Wealth', 'Assets', 'Co'],
        'health'    => ['Clinic', 'Health', 'Wellness', 'Care', 'Medical', 'Centre', 'Plus', 'Solutions', 'Group', 'Co'],
        'education' => ['Academy', 'Institute', 'Learning', 'School', 'Centre', 'Education', 'Tutors', 'Hub', 'Training', 'Co'],
        'retail'    => ['Store', 'Shop', 'Mart', 'Hub', 'Plaza', 'Market', 'Centre', 'Outlet', 'Co', 'Place'],
        'beauty'    => ['Beauty', 'Salon', 'Studio', 'Bar', 'Lounge', 'Spa', 'Co', 'Place', 'Hub', 'Collective'],
        'legal'     => ['Law', 'Chambers', 'Legal', 'Solicitors', 'Partners', 'Associates', 'Counsel', 'Attorneys', 'Co', 'Group'],
        'default'   => ['Co', 'Group', 'Hub', 'Solutions', 'Place', 'Works', 'Studio', 'Partners', 'Services', 'HQ'],
    ];

    /*
    |--------------------------------------------------------------------------
    | BRAND PALETTES (per industry)
    |--------------------------------------------------------------------------
    */

    protected array $palettes = [
        'fashion' => [
            'primary'      => '#2C1810',
            'secondary'    => '#8B7355',
            'accent'       => '#C4A882',
            'light'        => '#E8D5BE',
            'background'   => '#F5EDE0',
            'font_heading' => 'Playfair Display',
            'font_body'    => 'Montserrat',
        ],
        'tech' => [
            'primary'      => '#0F2942',
            'secondary'    => '#185FA5',
            'accent'       => '#378ADD',
            'light'        => '#B5D4F4',
            'background'   => '#E6F1FB',
            'font_heading' => 'Space Grotesk',
            'font_body'    => 'Inter',
        ],
        'food' => [
            'primary'      => '#7A1F1F',
            'secondary'    => '#C4511A',
            'accent'       => '#E8834A',
            'light'        => '#F5C9A8',
            'background'   => '#FDF0E6',
            'font_heading' => 'Pacifico',
            'font_body'    => 'Lato',
        ],
        'logistics' => [
            'primary'      => '#0D2B45',
            'secondary'    => '#1B5E8C',
            'accent'       => '#F58220',
            'light'        => '#FDDCB5',
            'background'   => '#FFF6ED',
            'font_heading' => 'Oswald',
            'font_body'    => 'Barlow',
        ],
        'finance' => [
            'primary'      => '#1A2E1A',
            'secondary'    => '#2E5C2E',
            'accent'       => '#5A9E5A',
            'light'        => '#C0DD97',
            'background'   => '#EAF3DE',
            'font_heading' => 'Libre Baskerville',
            'font_body'    => 'Source Sans Pro',
        ],
        'health' => [
            'primary'      => '#0A3D4A',
            'secondary'    => '#0F6E56',
            'accent'       => '#1D9E75',
            'light'        => '#9FE1CB',
            'background'   => '#E1F5EE',
            'font_heading' => 'Nunito',
            'font_body'    => 'Nunito',
        ],
        'education' => [
            'primary'      => '#1A1A5E',
            'secondary'    => '#3434A8',
            'accent'       => '#7F77DD',
            'light'        => '#CECBF6',
            'background'   => '#EEEDFE',
            'font_heading' => 'Merriweather',
            'font_body'    => 'Open Sans',
        ],
        'retail' => [
            'primary'      => '#2C1654',
            'secondary'    => '#534AB7',
            'accent'       => '#D4537E',
            'light'        => '#F4C0D1',
            'background'   => '#FBEAF0',
            'font_heading' => 'Poppins',
            'font_body'    => 'Raleway',
        ],
        'beauty' => [
            'primary'      => '#3D1A2E',
            'secondary'    => '#993556',
            'accent'       => '#ED93B1',
            'light'        => '#F4C0D1',
            'background'   => '#FBEAF0',
            'font_heading' => 'Cormorant Garamond',
            'font_body'    => 'Lato',
        ],
        'legal' => [
            'primary'      => '#1A1A1A',
            'secondary'    => '#3D3D3A',
            'accent'       => '#888780',
            'light'        => '#D3D1C7',
            'background'   => '#F1EFE8',
            'font_heading' => 'Crimson Text',
            'font_body'    => 'Source Sans Pro',
        ],
        'default' => [
            'primary'      => '#0F2942',
            'secondary'    => '#185FA5',
            'accent'       => '#378ADD',
            'light'        => '#B5D4F4',
            'background'   => '#E6F1FB',
            'font_heading' => 'Inter',
            'font_body'    => 'Inter',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | TAGLINE TEMPLATES (per industry)
    |--------------------------------------------------------------------------
    */

    protected array $taglineTemplates = [
        'fashion' => [
            'Where style finds its place',
            'Dressed with intention',
            'Fashion, curated for you',
            'Wear what you mean',
            'Style that speaks for itself',
        ],
        'tech' => [
            'Built for what comes next',
            'Technology that works for you',
            'Smarter solutions, simpler life',
            'Code the future',
            'Where ideas become products',
        ],
        'food' => [
            'Good food, every time',
            'Taste the difference',
            'Made with care, served with pride',
            'Where flavour meets purpose',
            'Food worth coming back for',
        ],
        'logistics' => [
            'Delivered, every time',
            'Moving business forward',
            'Your cargo, our priority',
            'Fast, reliable, trackable',
            'Where logistics meet precision',
        ],
        'finance' => [
            'Your money, working harder',
            'Financial clarity for every business',
            'Built on trust, driven by results',
            'Wealth, managed properly',
            'Smart finance for growing businesses',
        ],
        'health' => [
            'Your health, our mission',
            'Care that goes further',
            'Better health, every day',
            'Wellness built around you',
            'Where care meets expertise',
        ],
        'education' => [
            'Learning that lasts',
            'Where potential becomes achievement',
            'Education that opens doors',
            'Built for curious minds',
            'Knowledge worth having',
        ],
        'retail' => [
            'Everything you need, in one place',
            'Shop smarter, live better',
            'Quality you can count on',
            'Where great products find great people',
            'More than a store',
        ],
        'beauty' => [
            'Beauty, redefined',
            'Feel it. Own it.',
            'Glow from the inside out',
            'Where confidence begins',
            'Beauty that means something',
        ],
        'legal' => [
            'The law, working for you',
            'Expertise you can trust',
            'Justice, clearly delivered',
            'Your rights, our priority',
            'Legal clarity for every business',
        ],
        'default' => [
            'Built for what matters',
            'Doing business, properly',
            'Serious tools for serious businesses',
            'Where ambition meets execution',
            'Small business, big potential',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | INDUSTRIES (drives the dropdown in the form)
    |--------------------------------------------------------------------------
    */

    /**
     * Industry keys that can be offered to the user, without the fallback.
     *
     * @return string[]
     */
    public function industries(): array
    {
        return array_values(array_diff(array_keys($this->prefixes), ['default']));
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE NAMES
    |--------------------------------------------------------------------------
    */

    /**
     * The full, unshuffled pool of unique name candidates.
     * Kept public so the patterns can be tested without fighting randomness.
     *
     * @return string[]
     */
    public function candidateNames(string $keywords, string $industry): array
    {
        $industry = strtolower($industry);
        $prefixes = $this->prefixes[$industry] ?? $this->prefixes['default'];
        $suffixes = $this->suffixes[$industry] ?? $this->suffixes['default'];

        // Clean and split keywords into individual words
        $words = collect(explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $keywords)))
            ->map(fn ($w) => ucfirst(strtolower(trim($w))))
            ->filter(fn ($w) => strlen($w) >= 2)
            ->values()
            ->toArray();

        $names = collect();

        // Pattern 1: Keyword + Suffix
        foreach ($words as $word) {
            foreach (array_slice($suffixes, 0, 5) as $suffix) {
                $names->push("{$word} {$suffix}");
            }
        }

        // Pattern 2: Prefix + Keyword
        foreach ($words as $word) {
            foreach (array_slice($prefixes, 0, 5) as $prefix) {
                $names->push("{$prefix} {$word}");
            }
        }

        // Pattern 3: Prefix + Suffix (industry-branded, no keyword)
        foreach (array_slice($prefixes, 0, 3) as $prefix) {
            foreach (array_slice($suffixes, 0, 3) as $suffix) {
                $names->push("{$prefix} {$suffix}");
            }
        }

        // Pattern 4: Keyword + Keyword (if multiple words provided)
        if (count($words) >= 2) {
            $names->push("{$words[0]} {$words[1]}");
            $names->push("{$words[1]} {$words[0]}");
        }

        return $names->unique()->values()->toArray();
    }

    /**
     * A random selection of up to MAX_NAMES names from the candidate pool.
     *
     * @return string[]
     */
    public function generateNames(string $keywords, string $industry): array
    {
        $shuffled = $this->randomizer->shuffleArray(
            $this->candidateNames($keywords, $industry)
        );

        return array_slice($shuffled, 0, self::MAX_NAMES);
    }

    /*
    |--------------------------------------------------------------------------
    | GET BRAND PALETTE
    |--------------------------------------------------------------------------
    */

    public function getBrandPalette(string $industry): array
    {
        $industry = strtolower($industry);

        return $this->palettes[$industry] ?? $this->palettes['default'];
    }

    /*
    |--------------------------------------------------------------------------
    | GET TAGLINES
    |--------------------------------------------------------------------------
    */

    public function getTaglines(string $industry): array
    {
        $industry = strtolower($industry);

        return $this->taglineTemplates[$industry] ?? $this->taglineTemplates['default'];
    }
}