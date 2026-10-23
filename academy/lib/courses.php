<?php
/**
 * Course catalogue. Live courses have their lessons in academy/content/<slug>.php.
 * To launch another course: write its content file, then set 'status' => 'live'.
 * 'price' is the default full-course price in UGX; the admin page (Prices tab) can override it.
 */

function course_catalog(): array
{
    return [
        'ai-beyond-chatgpt' => [
            'title' => 'AI Beyond ChatGPT',
            'tagline' => 'Meet the AI tools that do more than chat.',
            'level' => 'Beginner',
            'icon' => 'bi-stars',
            'status' => 'live',
            'price' => 38000, // UGX, full course
        ],
        'automate-with-ai' => [
            'title' => 'Automate Your Business with AI',
            'tagline' => 'Let AI handle the repetitive work.',
            'level' => 'Beginner to intermediate',
            'icon' => 'bi-gear-wide-connected',
            'status' => 'live',
            'price' => 60000, // UGX, full course
        ],
        'data-analysis-with-ai' => [
            'title' => 'Data Analysis with AI',
            'tagline' => 'Turn your numbers into decisions.',
            'level' => 'Intermediate',
            'icon' => 'bi-graph-up-arrow',
            'status' => 'live',
            'price' => 60000, // UGX, full course
        ],
        'ai-for-marketing' => [
            'title' => 'AI for Marketing & Content',
            'tagline' => 'Create more, faster, in your own voice.',
            'level' => 'Beginner',
            'icon' => 'bi-megaphone',
            'status' => 'live',
            'price' => 50000, // UGX, full course
        ],
        'ai-for-managers' => [
            'title' => 'AI for Managers & Teams',
            'tagline' => 'Lead your team into AI, safely.',
            'level' => 'All levels',
            'icon' => 'bi-people',
            'status' => 'live',
            'price' => 50000, // UGX, full course
        ],
        'build-apps-with-ai' => [
            'title' => 'Build Apps with AI',
            'tagline' => 'Turn your idea into a working tool, without code.',
            'level' => 'Intermediate',
            'icon' => 'bi-phone',
            'status' => 'live',
            'price' => 60000, // UGX, full course
        ],
    ];
}

// Full course (catalogue info + lessons) for a live course, or null
function load_course(string $slug): ?array
{
    $catalog = course_catalog();
    if (!isset($catalog[$slug]) || $catalog[$slug]['status'] !== 'live' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
        return null;
    }
    $content = require dirname(__DIR__) . '/content/' . $slug . '.php';
    return array_merge($catalog[$slug], $content, ['slug' => $slug, 'price' => course_prices()[$slug]]);
}

/** Current full-course prices in UGX: the admin's price if set, otherwise the default above. */
function course_prices(): array
{
    static $prices = null;
    if ($prices === null) {
        $prices = array_map(fn ($c) => (int) $c['price'], course_catalog());
        foreach (db()->query('SELECT course, price FROM academy_prices') as $row) {
            if (isset($prices[$row['course']])) {
                $prices[$row['course']] = (int) $row['price'];
            }
        }
    }
    return $prices;
}

function find_lesson(array $course, string $slug): ?int
{
    foreach ($course['lessons'] as $i => $lesson) {
        if ($lesson['slug'] === $slug) {
            return $i;
        }
    }
    return null;
}
