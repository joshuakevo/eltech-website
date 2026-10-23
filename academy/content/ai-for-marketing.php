<?php
/**
 * Course: AI for Marketing & Content
 */

return [
    'description' => 'Create posts, posters and short videos that bring customers, in your own voice and in a fraction of the time. Learn to know your customer, write content that sells, make visuals, plan a month of posts and measure what works.',
    'free_lessons' => 2,
    'outcomes' => [
        'Describe your ideal customer and brand voice',
        'Write posts that get attention and action',
        'Create posters, product photos and short videos',
        'Plan and batch a month of content',
        'Measure results and market honestly',
    ],
    'lessons' => [

        /* ---------------------------------------------------------------- 1 */
        [
            'slug' => 'know-your-customer',
            'title' => 'Know your customer and your voice',
            'minutes' => 12,
            'summary' => 'Great marketing starts with who you are talking to and how you sound.',
            'body' => <<<'HTML'
<p class="lesson-hook">AI can write a hundred posts a minute. Without knowing your customer, they will all sound like everyone else's.</p>

<h2>Build a customer profile</h2>
<p>Describe your best customers to an AI assistant and ask it to turn your notes into a short profile:</p>
<ul>
  <li>Who are they? Age, work, where they live, how they buy.</li>
  <li>What problem do you solve for them?</li>
  <li>What stops them buying? Price, trust, distance, time?</li>
  <li>Where do they spend time? WhatsApp, Facebook, TikTok, radio?</li>
</ul>

<h2>Write your brand voice</h2>
<p>Your voice is how you sound every time. Pick three words, for example <em>friendly, trustworthy, local</em>, and add a few rules: <em>"Short sentences. Simple English. A little humour. Never pushy."</em> Save this as your <strong>voice guide</strong> and paste it into every content prompt.</p>

<div class="compare">
  <div class="compare-bad"><span>Without a voice guide</span><p>"Unlock unparalleled savings on premium groceries today!"</p></div>
  <div class="compare-good"><span>With a voice guide</span><p>"Fresh matooke just arrived, and it is cheaper than last week. Come early before it runs out!"</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>Ask AI: <em>"Interview me with 8 questions to build a profile of my ideal customer."</em> Answer them, then ask for a one-paragraph summary.</p></div>
</div>
HTML,
            'takeaways' => [
                'A clear customer profile makes AI content relevant.',
                'A short voice guide keeps every post sounding like you.',
                'Paste both into your prompts every time.',
            ],
            'quiz' => [
                [
                    'q' => 'Why give AI a customer profile?',
                    'options' => ['It makes posts longer', 'So the content speaks to real customer needs instead of being generic', 'It is required', 'To use more data'],
                    'answer' => 1,
                    'why' => 'Specific customers lead to specific, persuasive content.',
                ],
                [
                    'q' => 'What is a brand voice guide?',
                    'options' => ['Your logo', 'A few words and rules for how your business sounds', 'A radio advert', 'Your phone number'],
                    'answer' => 1,
                    'why' => 'It keeps your tone consistent across all content and staff.',
                ],
                [
                    'q' => 'Which question helps you understand what stops customers buying?',
                    'options' => ['"What colour is our shop?"', '"What worries do customers have about price, trust or distance?"', '"How many staff do we have?"', '"What is our tax ID?"'],
                    'answer' => 1,
                    'why' => 'Knowing the objections lets your content answer them.',
                ],
            ],
            'task' => [
                'title' => 'Profile and voice guide',
                'brief' => '<p>Use AI to create (1) a one-paragraph profile of your ideal customer and (2) a voice guide with three words and at least three writing rules.</p>',
                'submit_hint' => 'Paste your customer profile and your voice guide.',
            ],
        ],

        /* ---------------------------------------------------------------- 2 */
        [
            'slug' => 'posts-that-sell',
            'title' => 'Write posts that sell',
            'minutes' => 15,
            'summary' => 'Hooks, value and a clear call to action, written with AI.',
            'body' => <<<'HTML'
<p class="lesson-hook">People scroll past most posts in under two seconds. The first line decides whether they stop.</p>

<h2>The Hook, Value, Action formula</h2>
<ol class="formula">
  <li><strong>Hook</strong>: a first line that stops the scroll. A question, a surprising fact or a clear benefit.</li>
  <li><strong>Value</strong>: why it matters to the customer: the problem solved, the saving, the result.</li>
  <li><strong>Action</strong>: exactly what to do next. <em>"WhatsApp us on 07…"</em>, <em>"Visit us in Ntinda today."</em></li>
</ol>

<h2>A prompt that works</h2>
<p><em>"Using my customer profile and voice guide below, write 5 Facebook posts promoting our new school-shoe offer. Each under 60 words, with a strong first line and a clear call to action. Mix English with a little Luganda where natural."</em></p>

<h2>Make it local and real</h2>
<ul>
  <li>Use real prices, real places and real customer stories (with permission).</li>
  <li>Match the platform: WhatsApp Status is short and personal, Facebook can tell a story, LinkedIn is professional.</li>
  <li>Check every fact and price before posting.</li>
</ul>

<div class="callout callout-tip">
  <i class="bi bi-ui-checks"></i>
  <div><strong>Ask for variety</strong><p>Ask for 5 to 10 versions with different hooks, then pick the best. Use the others for later posts.</p></div>
</div>
HTML,
            'takeaways' => [
                'Hook, Value, Action: every post needs all three.',
                'Feed AI your customer profile and voice guide.',
                'Keep it local and real, and check every price.',
            ],
            'quiz' => [
                [
                    'q' => 'What is the job of the hook?',
                    'options' => ['To list every product', 'To stop people scrolling', 'To end the post', 'To add hashtags'],
                    'answer' => 1,
                    'why' => 'The first line decides whether anyone reads the rest.',
                ],
                [
                    'q' => 'Which is the clearest call to action?',
                    'options' => ['"Thanks for reading"', '"WhatsApp 0772 123456 to order before Friday"', '"We are the best"', '"Have a nice day"'],
                    'answer' => 1,
                    'why' => 'It says exactly what to do, how and by when.',
                ],
                [
                    'q' => 'What must you always check before posting AI-written content?',
                    'options' => ['The font', 'Facts and prices', 'The number of emojis', 'Nothing'],
                    'answer' => 1,
                    'why' => 'Wrong prices or facts cost money and trust.',
                ],
            ],
            'task' => [
                'title' => 'Five posts that sell',
                'brief' => '<p>Write five posts for a real product or offer using the Hook, Value, Action formula, your customer profile and your voice guide. Post at least one if you can.</p>',
                'submit_hint' => 'Paste your five posts and tell us which one you posted (or would post first) and why.',
            ],
        ],

        /* ---------------------------------------------------------------- 3 */
        [
            'slug' => 'visuals-and-video',
            'title' => 'Posters, photos and short video',
            'minutes' => 15,
            'summary' => 'Create visuals that look professional, using your phone and AI tools.',
            'body' => <<<'HTML'
<p class="lesson-hook">A good picture sells faster than a long caption. You can now make professional-looking visuals with a phone and a few AI tools.</p>

<h2>Product photos</h2>
<ul>
  <li>Shoot in daylight near a window, on a plain background.</li>
  <li>Use a background remover (in Canva, many phone apps and photo editors) to place products on a clean colour.</li>
  <li>Keep the colours of your brand consistent.</li>
</ul>

<h2>Posters and graphics</h2>
<ul>
  <li>Design tools such as Canva include AI features to create layouts and images from a description. Some features need a paid plan.</li>
  <li>AI image generators can create backgrounds and illustrations. Describe subject, style, colours and mood.</li>
  <li><strong>Add prices and contacts yourself</strong>: AI images can still get text wrong.</li>
</ul>

<h2>Short video and WhatsApp Status</h2>
<ul>
  <li>Ask AI for a 30-second script: a hook in the first 3 seconds, the product in action, a clear action at the end.</li>
  <li>Video apps such as CapCut can add captions automatically, which matters because many people watch without sound.</li>
  <li>Real people and real places build more trust than stock footage.</li>
</ul>

<div class="callout callout-warn">
  <i class="bi bi-person-check"></i>
  <div><strong>Respect people and rights</strong><p>Ask permission before showing customers or staff. Do not copy other brands' photos or logos, and never create fake people pretending to be real customers.</p></div>
</div>
HTML,
            'takeaways' => [
                'Daylight, plain backgrounds and background removal make product photos shine.',
                'Use AI for visuals, but add exact text yourself.',
                'Short videos need a strong first 3 seconds and captions.',
            ],
            'quiz' => [
                [
                    'q' => 'Why add prices to a poster yourself instead of asking the image generator?',
                    'options' => ['It is faster', 'AI images can still get text and numbers wrong', 'Prices are not allowed', 'Image generators cannot use colour'],
                    'answer' => 1,
                    'why' => 'Exact text belongs in a design tool where you control it.',
                ],
                [
                    'q' => 'Why add captions to short videos?',
                    'options' => ['They are required by law', 'Many people watch without sound', 'They make the file smaller', 'They hide mistakes'],
                    'answer' => 1,
                    'why' => 'Captions keep the message clear for silent viewers.',
                ],
                [
                    'q' => 'Which is NOT acceptable?',
                    'options' => [
                        'Using a customer\'s photo with their permission',
                        'Creating a fake "happy customer" with AI and presenting them as real',
                        'Removing the background from your own product photo',
                        'Adding captions to your video',
                    ],
                    'answer' => 1,
                    'why' => 'Presenting fake people as real customers misleads buyers and damages trust.',
                ],
            ],
            'task' => [
                'title' => 'Make one visual',
                'brief' => '<p>Create one marketing visual for a real product: either a poster or graphic, or a short video under 30 seconds with captions. Use at least one AI tool.</p>',
                'submit_hint' => 'Describe what you made and which tools you used. Share a link (for example Google Drive).',
            ],
        ],

        /* ---------------------------------------------------------------- 4 */
        [
            'slug' => 'plan-a-month',
            'title' => 'Plan a month of content in one sitting',
            'minutes' => 15,
            'summary' => 'Content pillars, a calendar and batching, so you post consistently.',
            'body' => <<<'HTML'
<p class="lesson-hook">Consistency beats brilliance. A business that posts helpful content three times a week for a year will beat one that posts a masterpiece once.</p>

<h2>Choose 3 to 4 content pillars</h2>
<p>Pillars are the themes you post about. For a pharmacy, for example:</p>
<div class="lesson-grid">
  <div><i class="bi bi-heart-pulse"></i><strong>Health tips</strong><span>Simple advice people can use today.</span></div>
  <div><i class="bi bi-bag-check"></i><strong>Products and offers</strong><span>What is new, what is on offer.</span></div>
  <div><i class="bi bi-people"></i><strong>Behind the scenes</strong><span>Your team, your shop, your values.</span></div>
  <div><i class="bi bi-chat-heart"></i><strong>Customer voices</strong><span>Real questions and real feedback.</span></div>
</div>

<h2>Let AI build the calendar</h2>
<p><em>"Using these pillars, my customer profile and voice guide, create a 4-week content calendar with 3 posts per week. Include the day, platform, pillar, a hook and a short description. Include any Ugandan public holidays this month."</em></p>
<p>Check the dates yourself, then edit and approve the plan.</p>

<h2>Batch and schedule</h2>
<ul>
  <li>Write all the posts for the week in one session.</li>
  <li>Make the visuals in one session.</li>
  <li>Schedule them. Meta Business Suite can schedule Facebook and Instagram posts in advance.</li>
</ul>
HTML,
            'takeaways' => [
                'Pick 3 to 4 content pillars.',
                'Let AI draft a monthly calendar, then check and approve it.',
                'Batch-create and schedule to stay consistent.',
            ],
            'quiz' => [
                [
                    'q' => 'What are content pillars?',
                    'options' => ['Posts about buildings', 'The 3 to 4 themes you post about regularly', 'Paid adverts only', 'Your logo colours'],
                    'answer' => 1,
                    'why' => 'Pillars give your content variety and direction.',
                ],
                [
                    'q' => 'What does batching mean?',
                    'options' => ['Posting everything at once', 'Creating many posts in one session, then scheduling them', 'Deleting old posts', 'Only posting on weekends'],
                    'answer' => 1,
                    'why' => 'Batching saves time and keeps you consistent.',
                ],
                [
                    'q' => 'AI suggests a post for a public holiday. What should you do?',
                    'options' => ['Post it without checking', 'Check the date and details are correct first', 'Ignore all holidays', 'Ask customers'],
                    'answer' => 1,
                    'why' => 'AI can get dates wrong. A quick check avoids embarrassment.',
                ],
            ],
            'task' => [
                'title' => 'Your content calendar',
                'brief' => '<p>Choose your content pillars and use AI to create a 4-week calendar with at least 3 posts per week. Write the full text for the first week.</p>',
                'submit_hint' => 'List your pillars, paste your calendar (or a link to it) and the full posts for week one.',
            ],
        ],

        /* ---------------------------------------------------------------- 5 */
        [
            'slug' => 'measure-and-stay-honest',
            'title' => 'Measure what works, and market honestly',
            'minutes' => 12,
            'summary' => 'Track the numbers that matter, test, improve, and keep customer trust.',
            'body' => <<<'HTML'
<p class="lesson-hook">Likes feel good, but they do not pay rent. Measure what leads to customers.</p>

<h2>Numbers that matter</h2>
<ul>
  <li><strong>Reach:</strong> how many people saw it.</li>
  <li><strong>Engagement:</strong> comments, shares and saves.</li>
  <li><strong>Enquiries:</strong> WhatsApp messages, calls and visits that came from the post.</li>
  <li><strong>Sales:</strong> ask new customers "How did you hear about us?" and write it down.</li>
</ul>

<h2>Test and improve</h2>
<p>Change one thing at a time: the hook, the picture or the time of posting. Compare results over two weeks. Paste your numbers into AI and ask: <em>"Which posts performed best, what do they have in common, and what should I post more of?"</em></p>

<h2>Stay honest</h2>
<ul>
  <li>No fake reviews or fake customers.</li>
  <li>Only promise what you can deliver. Real prices, real stock.</li>
  <li>If you use AI-generated people or voices, say so.</li>
  <li>Respect privacy: get permission before sharing customer photos or messages.</li>
</ul>

<div class="callout callout-tip">
  <i class="bi bi-trophy"></i>
  <div><strong>Your weekly 15 minutes</strong><p>Every week, note your top post, your number of enquiries, and one change to try next week. Small improvements add up fast.</p></div>
</div>
HTML,
            'takeaways' => [
                'Track enquiries and sales, not just likes.',
                'Change one thing at a time and compare.',
                'Honest marketing builds trust that lasts.',
            ],
            'quiz' => [
                [
                    'q' => 'Which number best shows a post brought business?',
                    'options' => ['Likes', 'WhatsApp enquiries and sales that came from it', 'Number of hashtags', 'Post length'],
                    'answer' => 1,
                    'why' => 'Enquiries and sales measure real business impact.',
                ],
                [
                    'q' => 'How should you test what works?',
                    'options' => ['Change everything every day', 'Change one thing at a time and compare results', 'Never change anything', 'Copy competitors exactly'],
                    'answer' => 1,
                    'why' => 'Changing one thing shows you what made the difference.',
                ],
                [
                    'q' => 'Which practice is honest marketing?',
                    'options' => ['Writing reviews for yourself', 'Advertising stock you do not have', 'Labelling AI-generated presenters as AI', 'Using a customer\'s photo without asking'],
                    'answer' => 2,
                    'why' => 'Being open about AI keeps your audience\'s trust.',
                ],
            ],
            'task' => [
                'title' => 'Your marketing scorecard',
                'brief' => '<p>Create a simple weekly scorecard with your key numbers (reach, engagement, enquiries, sales). Fill it in for at least one week, or use past posts, and ask AI what to improve.</p>',
                'submit_hint' => 'Share your scorecard numbers, your best post and the one change you will try next.',
            ],
        ],
    ],
];
