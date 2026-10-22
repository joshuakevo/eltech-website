<?php
/**
 * Course catalogue. Live courses have their lessons in academy/content/<slug>.php.
 * To launch another course: write its content file, then set 'status' => 'live'.
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
        ],
        'automate-with-ai' => [
            'title' => 'Automate Your Business with AI',
            'tagline' => 'Let AI handle the repetitive work.',
            'level' => 'Beginner to intermediate',
            'icon' => 'bi-gear-wide-connected',
            'status' => 'soon',
        ],
        'data-analysis-with-ai' => [
            'title' => 'Data Analysis with AI',
            'tagline' => 'Turn your numbers into decisions.',
            'level' => 'Intermediate',
            'icon' => 'bi-graph-up-arrow',
            'status' => 'soon',
        ],
        'ai-for-marketing' => [
            'title' => 'AI for Marketing & Content',
            'tagline' => 'Create more, faster, in your own voice.',
            'level' => 'Beginner',
            'icon' => 'bi-megaphone',
            'status' => 'soon',
        ],
        'ai-for-managers' => [
            'title' => 'AI for Managers & Teams',
            'tagline' => 'Lead your team into AI, safely.',
            'level' => 'All levels',
            'icon' => 'bi-people',
            'status' => 'soon',
        ],
        'build-apps-with-ai' => [
            'title' => 'Build Apps with AI',
            'tagline' => 'Turn your idea into a working tool, without code.',
            'level' => 'Intermediate',
            'icon' => 'bi-phone',
            'status' => 'soon',
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
    return array_merge($catalog[$slug], $content, ['slug' => $slug]);
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
