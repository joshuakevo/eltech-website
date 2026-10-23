<?php
/**
 * Course: AI for Managers & Teams
 */

return [
    'description' => 'Lead your team into AI with confidence. Understand what AI changes at work, find the best opportunities, set simple rules for safe use, bring people along, run a first pilot and choose the right tools and partners.',
    'free_lessons' => 2,
    'outcomes' => [
        'Explain what AI changes for your team',
        'Find and rank your best AI opportunities',
        'Write a simple, safe AI-use policy',
        'Run a 30-day AI pilot and measure it',
        'Choose AI tools and vendors wisely',
    ],
    'lessons' => [

        /* ---------------------------------------------------------------- 1 */
        [
            'slug' => 'what-ai-changes',
            'title' => 'What AI really changes at work',
            'minutes' => 12,
            'summary' => 'AI changes tasks, not whole jobs. Here is what that means for your team.',
            'body' => <<<'HTML'
<p class="lesson-hook">Your team is already using AI, with or without a plan. The question is whether they use it well and safely.</p>

<h2>Tasks, not jobs</h2>
<p>Most jobs are a bundle of tasks. AI is very good at some of them and poor at others:</p>
<div class="compare">
  <div class="compare-good"><span>AI helps a lot</span><p>Drafting letters and reports, summarising documents, answering routine questions, first analysis of data, translating.</p></div>
  <div class="compare-bad"><span>People stay essential</span><p>Judgement, relationships, negotiation, care for customers, accountability for decisions.</p></div>
</div>

<h2>What managers should expect</h2>
<ul>
  <li><strong>Speed:</strong> first drafts in minutes instead of hours.</li>
  <li><strong>Quality gaps narrow:</strong> less experienced staff can produce better work with good prompts.</li>
  <li><strong>New risks:</strong> confident mistakes, leaked data and over-reliance.</li>
  <li><strong>New skills:</strong> asking good questions and checking answers become core skills.</li>
</ul>

<div class="callout callout-tip">
  <i class="bi bi-person-badge"></i>
  <div><strong>Your role</strong><p>Set the direction, set the rules, and model good use yourself. Teams copy what their manager does.</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>Ask three team members: "Do you use AI at work? For what?" You may be surprised.</p></div>
</div>
HTML,
            'takeaways' => [
                'AI changes tasks inside jobs more than it replaces whole jobs.',
                'Expect faster drafts, but new risks too.',
                'Managers set direction, rules and an example.',
            ],
            'quiz' => [
                [
                    'q' => 'Which task is AI most helpful with?',
                    'options' => ['Negotiating with a key client', 'Drafting a first version of a monthly report', 'Taking responsibility for a decision', 'Comforting a grieving customer'],
                    'answer' => 1,
                    'why' => 'Drafting is a strength. Judgement, relationships and accountability stay with people.',
                ],
                [
                    'q' => 'What is a new risk AI brings to teams?',
                    'options' => ['Slower work', 'Confident mistakes and leaked data', 'Less email', 'Fewer meetings'],
                    'answer' => 1,
                    'why' => 'AI can be wrong with confidence, and data can be shared carelessly.',
                ],
                [
                    'q' => 'What is the manager\'s main role in AI adoption?',
                    'options' => ['Banning all AI', 'Setting direction and rules, and using it well themselves', 'Leaving it to IT', 'Buying the most tools'],
                    'answer' => 1,
                    'why' => 'Teams follow the direction, rules and example their manager sets.',
                ],
            ],
            'task' => [
                'title' => 'Your team\'s AI snapshot',
                'brief' => '<p>Find out how your team uses AI today: ask at least three people what they use, for which tasks, and any worries they have.</p>',
                'submit_hint' => 'Summarise what you learned: tools used, tasks, and the main worries or risks you noticed.',
            ],
        ],

        /* ---------------------------------------------------------------- 2 */
        [
            'slug' => 'find-opportunities',
            'title' => 'Find your best AI opportunities',
            'minutes' => 15,
            'summary' => 'Map tasks, score them and pick quick wins.',
            'body' => <<<'HTML'
<p class="lesson-hook">The best AI projects are rarely the most exciting. They are the boring, frequent tasks everyone complains about.</p>

<h2>Map your team's tasks</h2>
<p>List the main tasks your team does each week. For each, note: how often, how long, and how painful (errors, delays, frustration).</p>

<h2>Score impact and effort</h2>
<div class="lesson-grid">
  <div><i class="bi bi-lightning-charge"></i><strong>High impact, low effort</strong><span>Quick wins. Start here.</span></div>
  <div><i class="bi bi-flag"></i><strong>High impact, high effort</strong><span>Strategic projects. Plan these next.</span></div>
  <div><i class="bi bi-hand-thumbs-up"></i><strong>Low impact, low effort</strong><span>Nice to have. Let staff try freely.</span></div>
  <div><i class="bi bi-x-circle"></i><strong>Low impact, high effort</strong><span>Skip for now.</span></div>
</div>

<h2>Good first opportunities</h2>
<ul>
  <li>Meeting notes and action lists.</li>
  <li>Standard letters, notices and customer replies.</li>
  <li>Summaries of long reports for management.</li>
  <li>First-draft analysis of monthly figures.</li>
</ul>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>Give AI your task list and ask: <em>"Score each task for AI impact and effort from 1 to 5, and suggest the top 3 quick wins."</em> Then check its reasoning.</p></div>
</div>
HTML,
            'takeaways' => [
                'Map tasks by frequency, time and pain.',
                'Score impact and effort, and start with quick wins.',
                'Boring, frequent tasks are often the best opportunities.',
            ],
            'quiz' => [
                [
                    'q' => 'Which opportunity should you usually start with?',
                    'options' => ['High impact, high effort', 'High impact, low effort', 'Low impact, high effort', 'Whatever is newest'],
                    'answer' => 1,
                    'why' => 'Quick wins build confidence and show value fast.',
                ],
                [
                    'q' => 'Which is a good first AI opportunity?',
                    'options' => ['Replacing the board of directors', 'Turning meeting notes into action lists', 'Approving loans automatically', 'Firing staff'],
                    'answer' => 1,
                    'why' => 'It is frequent, low-risk and saves real time.',
                ],
                [
                    'q' => 'What three things should you note for each task?',
                    'options' => ['Colour, size, shape', 'How often, how long and how painful', 'Who likes it', 'Its cost in dollars only'],
                    'answer' => 1,
                    'why' => 'Frequency, time and pain show where AI will help most.',
                ],
            ],
            'task' => [
                'title' => 'Your opportunity map',
                'brief' => '<p>List at least eight team tasks with how often, how long and how painful they are. Score each for impact and effort and choose your top three quick wins.</p>',
                'submit_hint' => 'Share your task list with scores and your top three quick wins.',
            ],
        ],

        /* ---------------------------------------------------------------- 3 */
        [
            'slug' => 'safe-ai-rules',
            'title' => 'Simple rules for safe AI use',
            'minutes' => 15,
            'summary' => 'A one-page AI policy your team will actually follow.',
            'body' => <<<'HTML'
<p class="lesson-hook">Banning AI does not work: people use it anyway, without guidance. A short, clear policy works much better.</p>

<h2>Your one-page AI policy</h2>
<ol class="formula">
  <li><strong>Approved tools</strong>: which AI tools staff may use for work, and on which accounts.</li>
  <li><strong>Data rules</strong>: what must never go into AI tools: passwords, customer and member records, ID numbers, confidential contracts.</li>
  <li><strong>Human check</strong>: a person reviews anything that goes to customers or affects money, people or legal matters.</li>
  <li><strong>Openness</strong>: when to tell customers they are dealing with AI.</li>
  <li><strong>Questions</strong>: who to ask when unsure.</li>
</ol>

<h2>The law</h2>
<p>Uganda's <strong>Data Protection and Privacy Act, 2019</strong> applies to personal data your organisation collects and processes, including through AI tools. Organisations handling personal data are also expected to register with the Personal Data Protection Office (PDPO). If in doubt, get legal advice.</p>

<div class="callout callout-tip">
  <i class="bi bi-file-earmark-check"></i>
  <div><strong>Keep it to one page</strong><p>Short rules get read. Ask AI to draft your policy from the five headings above, then edit it for your organisation.</p></div>
</div>
HTML,
            'takeaways' => [
                'A short policy beats a ban.',
                'Cover tools, data, human checks, openness and who to ask.',
                'Personal data is protected by the Data Protection and Privacy Act, 2019.',
            ],
            'quiz' => [
                [
                    'q' => 'Why is a ban on AI usually a bad idea?',
                    'options' => ['AI is always safe', 'People use it anyway, without guidance', 'Bans are illegal', 'It costs money'],
                    'answer' => 1,
                    'why' => 'Clear guidance makes real-world use safer than a ban that is ignored.',
                ],
                [
                    'q' => 'Which should a data rule forbid putting into AI tools?',
                    'options' => ['A public product brochure', 'Member records with ID numbers', 'A blank template', 'A meeting agenda'],
                    'answer' => 1,
                    'why' => 'Personal data must be protected.',
                ],
                [
                    'q' => 'Which Ugandan law covers personal data processed through AI tools?',
                    'options' => ['The Data Protection and Privacy Act, 2019', 'The Traffic Act', 'No law applies', 'Only foreign laws'],
                    'answer' => 0,
                    'why' => 'It governs the collection and processing of personal data in Uganda.',
                ],
            ],
            'task' => [
                'title' => 'Draft your AI policy',
                'brief' => '<p>Write a one-page AI policy for your team with the five headings: approved tools, data rules, human check, openness and who to ask.</p>',
                'submit_hint' => 'Paste your one-page policy.',
            ],
        ],

        /* ---------------------------------------------------------------- 4 */
        [
            'slug' => 'bring-the-team-along',
            'title' => 'Bring your team along and run a pilot',
            'minutes' => 15,
            'summary' => 'Address fears, build skills and prove value with a 30-day pilot.',
            'body' => <<<'HTML'
<p class="lesson-hook">Tools are the easy part. People are the real project.</p>

<h2>Address the fears openly</h2>
<ul>
  <li><strong>"Will AI take my job?"</strong> Be honest about your plans. Show that the goal is removing boring work, not people.</li>
  <li><strong>"I am not technical."</strong> Most AI tools work with plain language. Start with simple wins.</li>
  <li><strong>"What if I make a mistake?"</strong> The policy and human checks are the safety net.</li>
</ul>

<h2>Build skills</h2>
<ul>
  <li>Pick one or two <strong>AI champions</strong> who enjoy it and can help others.</li>
  <li>Share good prompts in a team prompt library.</li>
  <li>Spend 15 minutes in a weekly meeting sharing one AI win.</li>
</ul>

<h2>Run a 30-day pilot</h2>
<ol>
  <li><strong>Pick one quick win</strong> from your opportunity map.</li>
  <li><strong>Measure before:</strong> how long it takes today and how many errors there are.</li>
  <li><strong>Run it for 30 days</strong> with a small group.</li>
  <li><strong>Measure after</strong> and collect feedback.</li>
  <li><strong>Decide:</strong> expand, adjust or stop.</li>
</ol>
HTML,
            'takeaways' => [
                'Talk about fears openly and honestly.',
                'Champions, a prompt library and shared wins build skills.',
                'A measured 30-day pilot proves value before you scale.',
            ],
            'quiz' => [
                [
                    'q' => 'What is an AI champion?',
                    'options' => ['A paid consultant', 'A team member who enjoys AI and helps colleagues', 'The most senior manager', 'An AI tool'],
                    'answer' => 1,
                    'why' => 'Champions spread skills and confidence from inside the team.',
                ],
                [
                    'q' => 'Why measure before the pilot starts?',
                    'options' => ['It is required by law', 'So you can show the real difference afterwards', 'To slow things down', 'It is not needed'],
                    'answer' => 1,
                    'why' => 'Without a baseline, you cannot prove the impact.',
                ],
                [
                    'q' => 'At the end of a pilot, what are the choices?',
                    'options' => ['Only expand', 'Expand, adjust or stop', 'Only stop', 'Start ten more pilots at once'],
                    'answer' => 1,
                    'why' => 'Evidence decides the next step, including stopping if it did not work.',
                ],
            ],
            'task' => [
                'title' => 'Your pilot plan',
                'brief' => '<p>Plan a 30-day pilot for one of your quick wins: the task, the small group, the champion, your before-measures and how you will decide at the end.</p>',
                'submit_hint' => 'Share your pilot plan with the measures you will track before and after.',
            ],
        ],

        /* ---------------------------------------------------------------- 5 */
        [
            'slug' => 'tools-and-roadmap',
            'title' => 'Choose tools wisely and set your roadmap',
            'minutes' => 15,
            'summary' => 'Questions to ask vendors, build or buy, and a 6-month plan.',
            'body' => <<<'HTML'
<p class="lesson-hook">Every month brings a new AI product promising miracles. Good managers ask the right questions before signing.</p>

<h2>Questions to ask any AI vendor</h2>
<ul>
  <li><strong>Data:</strong> Where is our data stored? Is it used to train their models? Can we delete it?</li>
  <li><strong>Cost:</strong> What is the full cost per user per month, including setup and training?</li>
  <li><strong>Fit:</strong> Does it work with the tools we already use?</li>
  <li><strong>Support:</strong> Is there local support or training?</li>
  <li><strong>Exit:</strong> If we stop, can we take our data with us?</li>
</ul>

<h2>Buy, configure or build?</h2>
<div class="lesson-grid">
  <div><i class="bi bi-bag"></i><strong>Buy</strong><span>Standard needs: use existing AI assistants and tools.</span></div>
  <div><i class="bi bi-sliders"></i><strong>Configure</strong><span>Set up custom assistants and automations on top of them.</span></div>
  <div><i class="bi bi-code-slash"></i><strong>Build</strong><span>Unique processes or data: a custom system built for you.</span></div>
</div>

<h2>Your 6-month roadmap</h2>
<ul>
  <li><strong>Month 1:</strong> policy and quick-win pilot.</li>
  <li><strong>Months 2–3:</strong> expand what worked, train the team.</li>
  <li><strong>Months 4–6:</strong> one bigger project, with clear measures.</li>
</ul>
HTML,
            'takeaways' => [
                'Ask vendors about data, cost, fit, support and exit.',
                'Buy for standard needs, configure for team needs, build for unique ones.',
                'A simple 6-month roadmap keeps progress steady.',
            ],
            'quiz' => [
                [
                    'q' => 'Which is the most important data question for a vendor?',
                    'options' => ['What colour is the logo?', 'Where is our data stored, and is it used to train their models?', 'How many staff do they have?', 'Where is their office party?'],
                    'answer' => 1,
                    'why' => 'Knowing where data goes protects your organisation and your customers.',
                ],
                [
                    'q' => 'When does building a custom system make sense?',
                    'options' => ['Always', 'When your process or data is unique and standard tools do not fit', 'Never', 'Only for very small tasks'],
                    'answer' => 1,
                    'why' => 'Custom systems pay off when off-the-shelf tools cannot fit how you work.',
                ],
                [
                    'q' => 'What should Month 1 of an AI roadmap include?',
                    'options' => ['A huge, expensive project', 'A policy and a quick-win pilot', 'Nothing', 'Replacing all systems'],
                    'answer' => 1,
                    'why' => 'Safe rules and a quick win create a strong foundation.',
                ],
            ],
            'task' => [
                'title' => 'Your 6-month AI roadmap',
                'brief' => '<p>Write a 6-month AI roadmap for your team or organisation, with what you will do each month, who owns it and how you will measure success.</p>',
                'submit_hint' => 'Paste your roadmap with owners and success measures.',
            ],
        ],
    ],
];
