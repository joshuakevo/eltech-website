<?php
/**
 * Course: Automate Your Business with AI
 */

return [
    'description' => 'Stop doing the same task twice. Learn to spot work that can run on its own, build your first no-code automations, add AI to them, handle customer messages faster and give your team an AI assistant that knows your business.',
    'free_lessons' => 2,
    'outcomes' => [
        'Spot the tasks worth automating first',
        'Build a no-code automation from trigger to action',
        'Add AI steps that sort, summarise and draft',
        'Answer customers faster on WhatsApp',
        'Set up an AI assistant that knows your business',
    ],
    'lessons' => [

        /* ---------------------------------------------------------------- 1 */
        [
            'slug' => 'spot-what-to-automate',
            'title' => 'Spot what to automate',
            'minutes' => 12,
            'summary' => 'Find the tasks that eat your time and are easy to hand to a machine.',
            'body' => <<<'HTML'
<p class="lesson-hook">Most businesses lose hours every week copying data, sending the same reminders and chasing the same updates. That is exactly the work machines do best.</p>

<h2>The three-question test</h2>
<p>A task is a good candidate for automation when the answer to all three is <strong>yes</strong>:</p>
<div class="lesson-grid">
  <div><i class="bi bi-arrow-repeat"></i><strong>Is it repetitive?</strong><span>It happens daily or weekly, in the same way.</span></div>
  <div><i class="bi bi-signpost"></i><strong>Does it follow rules?</strong><span>You could write the steps on one page.</span></div>
  <div><i class="bi bi-laptop"></i><strong>Is it already digital?</strong><span>The information is in email, forms, sheets or apps.</span></div>
  <div><i class="bi bi-clock-history"></i><strong>Bonus: is it slow or error-prone?</strong><span>Then automating it also improves quality.</span></div>
</div>

<h2>Common wins for Ugandan businesses</h2>
<ul>
  <li>Sending loan or payment reminders before the due date.</li>
  <li>Copying orders or enquiries from a form into a spreadsheet.</li>
  <li>Sending a daily sales summary to the owner on WhatsApp or email.</li>
  <li>Welcoming new customers or members with the same set of information.</li>
  <li>Filing invoices and receipts into the right folder.</li>
</ul>

<h2>Map it before you automate it</h2>
<p>Write the task as a simple chain: <strong>when</strong> something happens, <strong>do</strong> these steps, <strong>then</strong> tell someone. For example: <em>When a customer fills the order form, add it to the orders sheet, then send a confirmation email.</em> If you cannot write the chain, the task is not ready to automate yet.</p>

<div class="callout callout-tip">
  <i class="bi bi-calculator"></i>
  <div><strong>Is it worth it?</strong><p>Minutes per task × times per week = minutes saved each week. A 5-minute task done 40 times a week is more than 3 hours. Start with the task that saves the most time for the least effort.</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>List five tasks you or your team repeat every week. Ask an AI assistant: <em>"Which of these tasks are best to automate first, and why?"</em></p></div>
</div>
HTML,
            'takeaways' => [
                'Automate tasks that are repetitive, rule-based and already digital.',
                'Write the task as a chain: when, do, then.',
                'Start with the biggest time saving for the least effort.',
            ],
            'quiz' => [
                [
                    'q' => 'Which task is the best first candidate for automation?',
                    'options' => [
                        'Negotiating a new supplier contract',
                        'Copying every web enquiry into a spreadsheet',
                        'Deciding who to promote',
                        'Comforting an upset customer',
                    ],
                    'answer' => 1,
                    'why' => 'It is repetitive, follows clear rules and the data is already digital.',
                ],
                [
                    'q' => 'What does "map it before you automate it" mean?',
                    'options' => [
                        'Draw your office layout',
                        'Write the task as a clear chain: when this happens, do these steps, then tell someone',
                        'Buy automation software first',
                        'Ask a developer to guess the steps',
                    ],
                    'answer' => 1,
                    'why' => 'A clear when-do-then chain is the blueprint every automation tool needs.',
                ],
                [
                    'q' => 'A 5-minute task is done 30 times a week. Roughly how much time could automation save?',
                    'options' => ['30 minutes', 'About 2.5 hours a week', '5 minutes', 'Nothing'],
                    'answer' => 1,
                    'why' => '5 minutes × 30 = 150 minutes, or 2.5 hours every week.',
                ],
            ],
            'task' => [
                'title' => 'Your automation shortlist',
                'brief' => '<p>List five repetitive tasks in your work. For each one, note how often it happens and how long it takes. Pick the best one and write it as a <em>when / do / then</em> chain.</p>',
                'submit_hint' => 'Share your five tasks with the time each takes, then the one you chose and its when / do / then chain.',
            ],
        ],

        /* ---------------------------------------------------------------- 2 */
        [
            'slug' => 'first-no-code-automation',
            'title' => 'Your first no-code automation',
            'minutes' => 15,
            'summary' => 'Triggers, actions and the tools that connect your apps without code.',
            'body' => <<<'HTML'
<p class="lesson-hook">You do not need to be a programmer to make your apps talk to each other. You need to understand two words: trigger and action.</p>

<h2>Triggers and actions</h2>
<ul>
  <li><strong>Trigger:</strong> the event that starts the automation. <em>A new form response. A new email. A new row in a sheet.</em></li>
  <li><strong>Action:</strong> what happens next. <em>Add a row. Send an email. Create a task. Post a message.</em></li>
</ul>
<p>One trigger can lead to several actions, and you can add conditions: <em>only if the amount is above UGX 500,000</em>.</p>

<h2>Tools that connect your apps</h2>
<div class="tool-cards">
  <div><strong>Zapier</strong><span>Easiest to start</span><p>Thousands of app connections and a simple step-by-step builder.</p></div>
  <div><strong>Make</strong><span>Visual and flexible</span><p>Drag-and-drop flows. Good value for more complex automations.</p></div>
  <div><strong>Power Automate</strong><span>For Microsoft users</span><p>Works closely with Outlook, Excel, Teams and SharePoint.</p></div>
  <div><strong>n8n</strong><span>For technical teams</span><p>Very flexible and can run on your own server.</p></div>
</div>
<p>Most have a free plan or trial. Check current prices before you commit, and start with the tool that connects to the apps you already use.</p>

<h2>A first example</h2>
<ol>
  <li><strong>Trigger:</strong> a customer fills your Google Form order.</li>
  <li><strong>Action 1:</strong> add the order to your "Orders" Google Sheet.</li>
  <li><strong>Action 2:</strong> email the customer a confirmation.</li>
  <li><strong>Action 3:</strong> email your sales team the order details.</li>
</ol>

<div class="callout callout-tip">
  <i class="bi bi-bug"></i>
  <div><strong>Always test with fake data first</strong><p>Run the automation with a test order before you switch it on for customers. Check every step did what you expected.</p></div>
</div>
HTML,
            'takeaways' => [
                'Every automation is a trigger plus one or more actions.',
                'Tools like Zapier, Make and Power Automate connect apps without code.',
                'Test with fake data before customers see it.',
            ],
            'quiz' => [
                [
                    'q' => 'In "When a new form response arrives, add it to a sheet", what is the trigger?',
                    'options' => ['Adding it to a sheet', 'A new form response arriving', 'The sheet', 'The internet'],
                    'answer' => 1,
                    'why' => 'The trigger is the event that starts the automation: a new form response.',
                ],
                [
                    'q' => 'Your business runs on Outlook, Excel and Teams. Which tool fits most naturally?',
                    'options' => ['Power Automate', 'A paper notebook', 'A design tool', 'None, it is impossible'],
                    'answer' => 0,
                    'why' => 'Power Automate is Microsoft\'s tool and works closely with Microsoft 365 apps.',
                ],
                [
                    'q' => 'What should you do before switching an automation on for real customers?',
                    'options' => ['Nothing', 'Test it with fake data and check every step', 'Pay for the most expensive plan', 'Tell customers to expect mistakes'],
                    'answer' => 1,
                    'why' => 'A test run catches mistakes before they reach customers.',
                ],
            ],
            'task' => [
                'title' => 'Build or design your first automation',
                'brief' => '<p>Using the task you chose in lesson 1, build it in a free automation tool. If you cannot build it yet, design it step by step: the trigger, each action, any conditions and how you would test it.</p>',
                'submit_hint' => 'Name the tool, describe the trigger and actions, and tell us how your test went (or how you would test it).',
            ],
        ],

        /* ---------------------------------------------------------------- 3 */
        [
            'slug' => 'add-ai-to-workflows',
            'title' => 'Add AI to your workflows',
            'minutes' => 15,
            'summary' => 'Let AI sort, summarise and draft inside your automations.',
            'body' => <<<'HTML'
<p class="lesson-hook">Normal automations move information. AI automations can also understand it: read an email, decide what it is about, and draft a reply.</p>

<h2>Three AI steps that add the most value</h2>
<div class="lesson-grid">
  <div><i class="bi bi-tags"></i><strong>Sort</strong><span>Is this enquiry about a loan, a complaint or a new order? Route it to the right person.</span></div>
  <div><i class="bi bi-card-text"></i><strong>Summarise</strong><span>Turn a long email or call notes into three bullet points for the manager.</span></div>
  <div><i class="bi bi-pencil-square"></i><strong>Draft</strong><span>Write a first reply in your tone, ready for a person to check and send.</span></div>
  <div><i class="bi bi-table"></i><strong>Extract</strong><span>Pull names, amounts and dates out of messages into a sheet.</span></div>
</div>

<h2>How it fits together</h2>
<p>Most automation tools now include an AI step. A typical flow:</p>
<ol>
  <li><strong>Trigger:</strong> new email to info@yourbusiness.</li>
  <li><strong>AI step:</strong> "Classify this email as Order, Complaint, Question or Other. Then summarise it in two sentences."</li>
  <li><strong>Condition:</strong> if Complaint, alert the manager on WhatsApp or email.</li>
  <li><strong>AI step:</strong> draft a polite reply and save it as a draft, not sent.</li>
</ol>

<h2>Keep a person in the loop</h2>
<p>Let AI prepare, and let people approve anything that goes to a customer or involves money. Start with drafts, not automatic sending. Once the drafts are consistently right, you can automate more.</p>

<div class="callout callout-warn">
  <i class="bi bi-shield-lock"></i>
  <div><strong>Mind the data</strong><p>Automations send your data to the tools you connect. Use tools your business has approved, and avoid sending ID numbers, PINs or account details through AI steps.</p></div>
</div>
HTML,
            'takeaways' => [
                'AI steps can sort, summarise, draft and extract.',
                'Combine them with conditions to route work to the right person.',
                'Start with drafts that people approve before anything is sent.',
            ],
            'quiz' => [
                [
                    'q' => 'Which job is an AI step best at inside an automation?',
                    'options' => [
                        'Approving a loan by itself',
                        'Classifying incoming emails by topic',
                        'Transferring money without checks',
                        'Signing contracts',
                    ],
                    'answer' => 1,
                    'why' => 'Classifying text is a strength. Decisions about money and contracts need a person.',
                ],
                [
                    'q' => 'When you first add AI-written replies, what is the safest setup?',
                    'options' => ['Send them automatically to every customer', 'Save them as drafts for a person to check', 'Never use AI for replies', 'Let customers edit them'],
                    'answer' => 1,
                    'why' => 'Drafts let you check quality before anything reaches a customer.',
                ],
                [
                    'q' => 'What should NOT pass through an AI step?',
                    'options' => ['A product question', 'A customer\'s mobile money PIN', 'A delivery address for an order', 'A complaint about late delivery'],
                    'answer' => 1,
                    'why' => 'PINs, passwords and similar secrets must never be sent to AI tools.',
                ],
            ],
            'task' => [
                'title' => 'Design an AI-powered flow',
                'brief' => '<p>Design an automation that uses at least one AI step (sort, summarise, draft or extract) for a real situation in your business. Write the exact instruction you would give the AI step.</p>',
                'submit_hint' => 'Describe the trigger, the AI step with its exact instruction, any condition, and where a person checks the result.',
            ],
        ],

        /* ---------------------------------------------------------------- 4 */
        [
            'slug' => 'whatsapp-and-customers',
            'title' => 'Faster replies on WhatsApp',
            'minutes' => 15,
            'summary' => 'Use WhatsApp Business features and AI to answer customers quickly and well.',
            'body' => <<<'HTML'
<p class="lesson-hook">In Uganda, customers expect answers on WhatsApp, and they expect them fast. The right setup lets a small team reply like a big one.</p>

<h2>Start with the free WhatsApp Business app</h2>
<ul>
  <li><strong>Quick replies:</strong> save answers to common questions and send them with a shortcut.</li>
  <li><strong>Greeting and away messages:</strong> greet new customers and reply automatically outside working hours.</li>
  <li><strong>Labels:</strong> tag chats as New order, Paid, Follow up.</li>
  <li><strong>Catalogue:</strong> show your products and prices inside WhatsApp.</li>
</ul>

<h2>Use AI to write better quick replies</h2>
<p>Ask an AI assistant: <em>"Write short, friendly WhatsApp replies for these 10 questions our customers ask. Use simple English."</em> Then edit them and save them as quick replies. Your whole team now answers consistently.</p>

<h2>When you outgrow the app</h2>
<p>Busy businesses move to the <strong>WhatsApp Business Platform</strong> through an approved provider. It supports chatbots, many agents on one number and connections to your systems. It has rules: customers must agree to receive messages, and messages you start must use pre-approved templates.</p>

<div class="callout callout-tip">
  <i class="bi bi-person-raised-hand"></i>
  <div><strong>Always offer a human</strong><p>If you use a chatbot, say it is automated and make it easy to reach a person. Customers forgive a bot that hands over quickly, not one that traps them.</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>Write down the five questions customers ask you most. Ask AI to draft a reply for each, under 40 words.</p></div>
</div>
HTML,
            'takeaways' => [
                'Quick replies, away messages and labels already save hours.',
                'AI helps you write clear, consistent replies for the whole team.',
                'The WhatsApp Business Platform adds bots and integrations, with opt-in rules.',
                'Always give customers an easy way to reach a person.',
            ],
            'quiz' => [
                [
                    'q' => 'Which free WhatsApp Business app feature answers customers outside working hours?',
                    'options' => ['Labels', 'Away message', 'Catalogue', 'Status'],
                    'answer' => 1,
                    'why' => 'The away message replies automatically when you are not available.',
                ],
                [
                    'q' => 'What is one rule of the WhatsApp Business Platform?',
                    'options' => [
                        'You can message anyone without permission',
                        'Customers must agree to receive messages, and messages you start use approved templates',
                        'Only one person can use it',
                        'It cannot connect to other systems',
                    ],
                    'answer' => 1,
                    'why' => 'Opt-in and approved templates protect customers from spam.',
                ],
                [
                    'q' => 'What makes a customer chatbot acceptable to customers?',
                    'options' => ['Hiding that it is a bot', 'Saying it is automated and offering an easy way to reach a person', 'Never letting them talk to staff', 'Long answers'],
                    'answer' => 1,
                    'why' => 'Honesty plus a quick handover to a human keeps trust.',
                ],
            ],
            'task' => [
                'title' => 'Your WhatsApp reply kit',
                'brief' => '<p>Create a set of at least five quick replies for your most common customer questions, using AI to draft them. Write a greeting message and an away message too.</p>',
                'submit_hint' => 'Paste your five quick replies, your greeting message and your away message.',
            ],
        ],

        /* ---------------------------------------------------------------- 5 */
        [
            'slug' => 'ai-assistant-for-your-team',
            'title' => 'An AI assistant that knows your business',
            'minutes' => 15,
            'summary' => 'Give your team an assistant trained on your own documents, and measure the impact.',
            'body' => <<<'HTML'
<p class="lesson-hook">Imagine every staff member having a helper that knows your price list, policies and procedures, and answers in seconds. You can set this up in an afternoon.</p>

<h2>Custom assistants</h2>
<p>Several AI tools let you create your own assistant with fixed instructions and your own files, for example <strong>custom GPTs</strong> in ChatGPT, <strong>Projects</strong> in Claude and <strong>Gems</strong> in Gemini. Availability depends on the plan you use.</p>

<h2>What to give it</h2>
<ol>
  <li><strong>Instructions:</strong> who it helps, its tone, and what it must never do. <em>"Never promise discounts. If unsure, say so and refer to the branch manager."</em></li>
  <li><strong>Knowledge:</strong> your price list, product sheets, policies and FAQs.</li>
  <li><strong>Examples:</strong> two or three good answers, so it copies your style.</li>
</ol>

<div class="callout callout-warn">
  <i class="bi bi-shield-lock"></i>
  <div><strong>Only share what you would show a new employee</strong><p>Do not upload customer or member records. Keep the assistant to general business knowledge, and check your plan's data settings.</p></div>
</div>

<h2>Measure, maintain, improve</h2>
<ul>
  <li><strong>Measure:</strong> note how long tasks took before and after. Track errors and customer response times.</li>
  <li><strong>Maintain:</strong> give every automation and assistant an owner. Update files when prices or policies change.</li>
  <li><strong>Improve:</strong> each month, ask your team what still takes too long. That is your next automation.</li>
</ul>
HTML,
            'takeaways' => [
                'Custom assistants combine instructions, your documents and examples.',
                'Share only what a new employee could see.',
                'Give every automation an owner, and measure time saved.',
            ],
            'quiz' => [
                [
                    'q' => 'What three things make a strong custom assistant?',
                    'options' => [
                        'A logo, a name and a colour',
                        'Clear instructions, your business documents and example answers',
                        'Customer passwords',
                        'As many files as possible, of any kind',
                    ],
                    'answer' => 1,
                    'why' => 'Instructions set the rules, documents give knowledge, and examples set the style.',
                ],
                [
                    'q' => 'Which file should NOT be uploaded to a team assistant?',
                    'options' => ['The price list', 'The returns policy', 'The full member list with phone numbers and balances', 'Product descriptions'],
                    'answer' => 2,
                    'why' => 'Personal and financial data about members must be protected.',
                ],
                [
                    'q' => 'Why give every automation an owner?',
                    'options' => [
                        'So someone keeps it updated and fixes it when something changes',
                        'So they can be blamed',
                        'It is required by law',
                        'To make it run faster',
                    ],
                    'answer' => 0,
                    'why' => 'Prices, policies and apps change. An owner keeps automations accurate.',
                ],
            ],
            'task' => [
                'title' => 'Your automation plan',
                'brief' => '<p>Write a one-page plan: the custom assistant you would build (its instructions and which documents it gets), plus the three automations you will run in the next month, each with an owner and the time you expect to save.</p>',
                'submit_hint' => 'Paste your assistant instructions, list its documents, and list your three automations with owners and expected time saved.',
            ],
        ],
    ],
];
