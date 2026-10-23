<?php
/**
 * Course: Build Apps with AI
 */

return [
    'description' => 'Turn your idea into a working tool without writing code. Learn what you can build with no-code and AI tools, plan your app on one page, build from a spreadsheet, use AI app builders and chatbots, and launch safely, knowing when it is time to call a developer.',
    'free_lessons' => 2,
    'outcomes' => [
        'Know what you can build without code',
        'Plan an app on a single page',
        'Turn a spreadsheet into a working app',
        'Build with AI app builders and chatbot tools',
        'Launch safely and know when to call a developer',
    ],
    'lessons' => [

        /* ---------------------------------------------------------------- 1 */
        [
            'slug' => 'what-you-can-build',
            'title' => 'What you can build without code',
            'minutes' => 12,
            'summary' => 'Forms, data apps, chatbots and simple websites: what is possible today.',
            'body' => <<<'HTML'
<p class="lesson-hook">Ten years ago, even a simple app needed a developer. Today you can build useful tools in an afternoon, if you choose the right kind.</p>

<h2>Four things non-coders build well</h2>
<div class="lesson-grid">
  <div><i class="bi bi-ui-checks"></i><strong>Forms and checklists</strong><span>Orders, registrations, inspections, surveys.</span></div>
  <div><i class="bi bi-table"></i><strong>Data apps</strong><span>A phone app on top of a spreadsheet: stock, deliveries, field visits.</span></div>
  <div><i class="bi bi-chat-dots"></i><strong>Chatbots</strong><span>Answer common questions from your own documents.</span></div>
  <div><i class="bi bi-window"></i><strong>Simple websites</strong><span>Landing pages, menus, event pages.</span></div>
</div>

<h2>The tool families</h2>
<ul>
  <li><strong>Form tools:</strong> Google Forms, Microsoft Forms.</li>
  <li><strong>Spreadsheet-to-app tools:</strong> such as AppSheet and Glide.</li>
  <li><strong>AI app builders:</strong> describe an app in plain English and get a working first version.</li>
  <li><strong>AI assistants:</strong> custom assistants that answer from your files.</li>
</ul>
<p>Tools and prices change quickly, so check the current free plans before you choose.</p>

<div class="callout callout-tip">
  <i class="bi bi-signpost-2"></i>
  <div><strong>Know the limits</strong><p>No-code is perfect for internal tools and prototypes. Handling money, many users or sensitive data usually needs a properly built system.</p></div>
</div>
HTML,
            'takeaways' => [
                'Forms, data apps, chatbots and simple websites are easy wins.',
                'Pick the tool family that matches your idea.',
                'No-code is great for prototypes and internal tools.',
            ],
            'quiz' => [
                [
                    'q' => 'Which is a good no-code project?',
                    'options' => ['A national banking system', 'A stock-check app on top of a spreadsheet', 'A payment processor', 'A new operating system'],
                    'answer' => 1,
                    'why' => 'Internal data apps are exactly what no-code tools do well.',
                ],
                [
                    'q' => 'What do spreadsheet-to-app tools do?',
                    'options' => ['Print spreadsheets', 'Turn a spreadsheet into a phone or web app', 'Delete data', 'Send faxes'],
                    'answer' => 1,
                    'why' => 'They put an app interface on top of your sheet.',
                ],
                [
                    'q' => 'When does no-code usually reach its limits?',
                    'options' => ['Simple forms', 'Handling money, many users or sensitive data', 'Checklists', 'Event pages'],
                    'answer' => 1,
                    'why' => 'These need stronger security and reliability than most no-code tools offer.',
                ],
            ],
            'task' => [
                'title' => 'Your app idea',
                'brief' => '<p>Describe one tool that would help your work: who uses it, what problem it solves, and which type it is (form, data app, chatbot or website).</p>',
                'submit_hint' => 'Describe the problem, the users, the type of tool and why it would help.',
            ],
        ],

        /* ---------------------------------------------------------------- 2 */
        [
            'slug' => 'plan-on-one-page',
            'title' => 'Plan your app on one page',
            'minutes' => 15,
            'summary' => 'Users, problem, data and screens: the plan that saves you hours.',
            'body' => <<<'HTML'
<p class="lesson-hook">Most failed apps fail before building starts, because nobody was clear about what they were for.</p>

<h2>The one-page plan</h2>
<ol class="formula">
  <li><strong>Problem</strong>: one sentence. <em>"Field officers lose track of which members they visited."</em></li>
  <li><strong>Users</strong>: who uses it and on what device. <em>"6 field officers, on Android phones."</em></li>
  <li><strong>Data</strong>: what information is stored. <em>"Member name, area, visit date, notes, photo."</em></li>
  <li><strong>Screens</strong>: what each screen shows or does. <em>"List of members, Add visit, Today's visits."</em></li>
  <li><strong>Success</strong>: how you will know it works. <em>"Every visit recorded the same day."</em></li>
</ol>

<h2>Let AI challenge your plan</h2>
<p>Paste your plan and ask: <em>"Act as an experienced app designer. What is missing or unclear in this plan? What could go wrong? Suggest the simplest version I could build first."</em></p>

<div class="callout callout-tip">
  <i class="bi bi-scissors"></i>
  <div><strong>Start smaller than you think</strong><p>Build the smallest version that solves the main problem. You can add features once people are using it.</p></div>
</div>
HTML,
            'takeaways' => [
                'Plan the problem, users, data, screens and success first.',
                'Ask AI to find gaps in your plan.',
                'Build the smallest useful version first.',
            ],
            'quiz' => [
                [
                    'q' => 'Which is the best problem statement?',
                    'options' => ['"We need an app"', '"Field officers lose track of which members they visited"', '"Apps are modern"', '"Make it nice"'],
                    'answer' => 1,
                    'why' => 'It is specific and says exactly what the app must fix.',
                ],
                [
                    'q' => 'Why plan the data before building?',
                    'options' => ['It is not needed', 'Because screens and features depend on what information you store', 'To make the app slower', 'Because AI requires it'],
                    'answer' => 1,
                    'why' => 'The data is the foundation of every data app.',
                ],
                [
                    'q' => 'What should your first version be?',
                    'options' => ['Every feature you can imagine', 'The smallest version that solves the main problem', 'A copy of a big company\'s app', 'Nothing until it is perfect'],
                    'answer' => 1,
                    'why' => 'Small first versions get used, tested and improved quickly.',
                ],
            ],
            'task' => [
                'title' => 'Your one-page app plan',
                'brief' => '<p>Write the one-page plan for your app idea (problem, users, data, screens, success). Then ask AI to critique it and improve your plan.</p>',
                'submit_hint' => 'Paste your final plan and the most useful change AI suggested.',
            ],
        ],

        /* ---------------------------------------------------------------- 3 */
        [
            'slug' => 'spreadsheet-to-app',
            'title' => 'From spreadsheet to app',
            'minutes' => 15,
            'summary' => 'Build a working phone app on top of a well-structured sheet.',
            'body' => <<<'HTML'
<p class="lesson-hook">If your data lives in a spreadsheet, you are halfway to an app already.</p>

<h2>1. Structure the sheet properly</h2>
<ul>
  <li>One table per sheet tab: Members, Visits, Products…</li>
  <li>One header row with clear column names, and no merged cells.</li>
  <li>One row per record, with a unique ID column.</li>
  <li>Consistent formats: dates as dates, numbers as numbers.</li>
</ul>
<p>Ask AI: <em>"Here are my columns. Suggest a clean structure for an app, with tabs and column types."</em></p>

<h2>2. Connect an app builder</h2>
<p>Spreadsheet-to-app tools such as AppSheet and Glide read your sheet and generate a first app automatically: a list, a detail view and an add form. Then you adjust the screens, labels and who can see what.</p>

<h2>3. Test with real users</h2>
<ul>
  <li>Give it to two or three users for a week.</li>
  <li>Watch them use it: where do they get stuck?</li>
  <li>Fix the biggest problem, then test again.</li>
</ul>

<div class="callout callout-warn">
  <i class="bi bi-people"></i>
  <div><strong>Control access</strong><p>Decide who can view, add and edit data. Only share the app with the people who need it, using their work accounts.</p></div>
</div>
HTML,
            'takeaways' => [
                'A clean sheet structure is the foundation of a good app.',
                'App builders generate a first version from your sheet.',
                'Test with real users and control who has access.',
            ],
            'quiz' => [
                [
                    'q' => 'Which sheet structure works best for an app?',
                    'options' => ['Merged cells and colours', 'One header row, one row per record and a unique ID column', 'Everything in one cell', 'Several tables on one tab'],
                    'answer' => 1,
                    'why' => 'App builders need clean, consistent tables.',
                ],
                [
                    'q' => 'What is the best way to find problems in your new app?',
                    'options' => ['Guess', 'Watch real users try it', 'Ask AI only', 'Add more features'],
                    'answer' => 1,
                    'why' => 'Real users show you where the app is confusing.',
                ],
                [
                    'q' => 'Who should have access to the app?',
                    'options' => ['Anyone with the link', 'Only the people who need it, with their work accounts', 'The public', 'No one'],
                    'answer' => 1,
                    'why' => 'Limiting access protects your data.',
                ],
            ],
            'task' => [
                'title' => 'Build a data app',
                'brief' => '<p>Structure a spreadsheet for your app idea and build a first version with a spreadsheet-to-app tool. Test it with at least one other person. (No personal data needed: use sample data.)</p>',
                'submit_hint' => 'Describe your sheet structure, the tool you used, what the tester found, and share a link or screenshot if you can.',
            ],
        ],

        /* ---------------------------------------------------------------- 4 */
        [
            'slug' => 'ai-app-builders-and-chatbots',
            'title' => 'AI app builders and chatbots',
            'minutes' => 15,
            'summary' => 'Describe an app in plain English, and build a chatbot that knows your business.',
            'body' => <<<'HTML'
<p class="lesson-hook">The newest tools let you describe an app in a few sentences and get a working version in minutes. The skill is now in describing it well.</p>

<h2>Prompting an app builder</h2>
<p>Use your one-page plan as the prompt:</p>
<div class="compare">
  <div class="compare-bad"><span>Weak</span><p>"Make me a booking app."</p></div>
  <div class="compare-good"><span>Strong</span><p>"Build a simple booking page for a salon in Kampala. Customers choose a service (list below), a date and a time between 9am and 7pm, and enter their name and phone. Show a confirmation. Staff see a list of today's bookings. Blue and white design, mobile first."</p></div>
</div>
<p>Then improve step by step: <em>"Add a price next to each service." "Make the buttons bigger on phones."</em></p>

<h2>A chatbot that knows your business</h2>
<ol>
  <li>Collect your FAQs, price list and policies in one document.</li>
  <li>Create a custom assistant in an AI tool and upload the document.</li>
  <li>Write rules: <em>"Only answer from the document. If unsure, give our WhatsApp number."</em></li>
  <li>Test it with 20 real customer questions before sharing it.</li>
</ol>

<div class="callout callout-tip">
  <i class="bi bi-search"></i>
  <div><strong>Test like a customer</strong><p>Ask tricky and rude questions too. A good chatbot stays polite, stays accurate and hands over to a person when it does not know.</p></div>
</div>
HTML,
            'takeaways' => [
                'Detailed, plan-based prompts build better apps.',
                'Improve generated apps in small steps.',
                'A chatbot should answer from your documents and hand over when unsure.',
            ],
            'quiz' => [
                [
                    'q' => 'What makes a strong prompt for an AI app builder?',
                    'options' => ['One word', 'A clear description of users, features, rules and design', 'A picture of a competitor', 'Lots of emojis'],
                    'answer' => 1,
                    'why' => 'The more specific the description, the closer the first version.',
                ],
                [
                    'q' => 'What rule should a business chatbot follow?',
                    'options' => ['Make up answers if unsure', 'Answer from your documents, and give a contact when unsure', 'Never mention the business', 'Always offer discounts'],
                    'answer' => 1,
                    'why' => 'It keeps answers accurate and builds trust.',
                ],
                [
                    'q' => 'How should you test a chatbot before launch?',
                    'options' => ['Ask it one easy question', 'Ask it at least 20 real questions, including tricky ones', 'Do not test it', 'Ask it to test itself'],
                    'answer' => 1,
                    'why' => 'Realistic testing shows how it behaves with real customers.',
                ],
            ],
            'task' => [
                'title' => 'Build with AI',
                'brief' => '<p>Choose one: (A) build a first version of your app with an AI app builder using a detailed prompt, or (B) build a business chatbot from your FAQ document and test it with at least 10 questions.</p>',
                'submit_hint' => 'Share your prompt or chatbot rules, what worked, what did not, and a link if you can.',
            ],
        ],

        /* ---------------------------------------------------------------- 5 */
        [
            'slug' => 'launch-safely',
            'title' => 'Launch safely, and when to call a developer',
            'minutes' => 12,
            'summary' => 'Security basics, backups and the signs you have outgrown no-code.',
            'body' => <<<'HTML'
<p class="lesson-hook">A tool that works on your phone is a great start. A tool people rely on every day needs a little more care.</p>

<h2>Launch checklist</h2>
<ul>
  <li><strong>Access:</strong> only the right people can log in. Remove people who leave.</li>
  <li><strong>Data:</strong> collect only what you need. Protect personal data.</li>
  <li><strong>Backups:</strong> export or copy your data regularly.</li>
  <li><strong>Owner:</strong> one person is responsible for the tool and its updates.</li>
  <li><strong>Help:</strong> users know who to ask when something breaks.</li>
</ul>

<h2>Signs you need a developer</h2>
<div class="lesson-grid">
  <div><i class="bi bi-cash-coin"></i><strong>Money</strong><span>The tool takes payments, holds balances or calculates loans.</span></div>
  <div><i class="bi bi-people"></i><strong>Scale</strong><span>Hundreds of users, or the tool gets slow.</span></div>
  <div><i class="bi bi-shield-lock"></i><strong>Sensitive data</strong><span>Medical, financial or ID data needs strong security.</span></div>
  <div><i class="bi bi-plug"></i><strong>Integrations</strong><span>It must connect to mobile money, banks or other systems.</span></div>
</div>

<div class="callout callout-tip">
  <i class="bi bi-lightbulb"></i>
  <div><strong>Your prototype is not wasted</strong><p>A working no-code prototype is the best brief a developer can get. It shows exactly what you need, which saves time and money when the real system is built.</p></div>
</div>
HTML,
            'takeaways' => [
                'Check access, data, backups, ownership and support before launch.',
                'Money, scale, sensitive data and integrations need a developer.',
                'A prototype makes a custom build faster and cheaper.',
            ],
            'quiz' => [
                [
                    'q' => 'Which is part of a safe launch?',
                    'options' => ['Sharing the app link publicly', 'Regular backups and removing access when people leave', 'Collecting as much data as possible', 'No owner'],
                    'answer' => 1,
                    'why' => 'Backups and access control protect your data.',
                ],
                [
                    'q' => 'Which is a sign you need a developer?',
                    'options' => ['A simple event form', 'The tool must take mobile money payments and calculate loan balances', 'A staff checklist', 'A one-page menu'],
                    'answer' => 1,
                    'why' => 'Money and integrations need strong security and reliability.',
                ],
                [
                    'q' => 'Why is a no-code prototype useful when you hire a developer?',
                    'options' => ['It is not useful', 'It shows exactly what you need, which saves time and money', 'Developers must use it as is', 'It replaces the need for a contract'],
                    'answer' => 1,
                    'why' => 'A working prototype is a clear, practical brief.',
                ],
            ],
            'task' => [
                'title' => 'Your launch plan',
                'brief' => '<p>Write a launch plan for the tool you built: access, data, backups, owner and support. Then decide honestly: can it stay no-code, or does it need a developer? Explain why.</p>',
                'submit_hint' => 'Paste your launch plan and your decision with reasons.',
            ],
        ],
    ],
];
