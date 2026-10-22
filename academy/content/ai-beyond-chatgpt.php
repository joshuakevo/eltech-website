<?php
/**
 * Course: AI Beyond ChatGPT
 *
 * Each lesson: slug, title, minutes, summary, body (HTML), takeaways,
 * quiz (answer = index of the correct option) and a practical task.
 */

return [
    'description' => 'You have probably asked ChatGPT a question. This course shows you what else AI can do for your work: the main tools, how to write prompts that get results, how to work with documents, images and voice, and how to use AI safely every day.',
    'free_lessons' => 2,
    'price' => null, // e.g. 'UGX 150,000' - shown on the unlock box when set
    'outcomes' => [
        'Choose the right AI tool for a task',
        'Write prompts that get useful answers first time',
        'Summarise documents and check facts with AI',
        'Create images and use voice tools',
        'Build a safe daily AI routine for your work',
    ],
    'lessons' => [

        /* ---------------------------------------------------------------- 1 */
        [
            'slug' => 'what-ai-really-is',
            'title' => 'What AI really is (and what it isn\'t)',
            'minutes' => 10,
            'summary' => 'Understand how AI assistants work, so you know when to trust them.',
            'body' => <<<'HTML'
<p class="lesson-hook">Using AI only to ask questions is like using a smartphone only to make calls. It works, but you are missing most of what it can do.</p>

<h2>How AI assistants work</h2>
<p>Tools like ChatGPT, Claude and Gemini are built on <strong>large language models</strong>. They learned patterns from huge amounts of text. When you type a request, the model predicts, word by word, what a good answer looks like.</p>
<p>That simple idea explains both their power and their weakness:</p>
<ul>
  <li><strong>Power:</strong> they are excellent at language: drafting, rewriting, summarising, explaining and translating.</li>
  <li><strong>Weakness:</strong> they predict what <em>sounds</em> right, not what they have checked. They can state wrong facts with full confidence. This is called a <strong>hallucination</strong>.</li>
</ul>

<h2>What AI is great at today</h2>
<div class="lesson-grid">
  <div><i class="bi bi-pencil"></i><strong>Drafting</strong><span>Letters, notices, proposals and posts in seconds.</span></div>
  <div><i class="bi bi-file-earmark-text"></i><strong>Summarising</strong><span>Long reports and documents turned into key points.</span></div>
  <div><i class="bi bi-arrow-repeat"></i><strong>Rewriting</strong><span>The same message for a customer, a manager or WhatsApp.</span></div>
  <div><i class="bi bi-lightbulb"></i><strong>Brainstorming</strong><span>Ideas for names, promotions and solutions.</span></div>
</div>

<h2>Where you must be careful</h2>
<ul>
  <li>Facts, figures, prices and dates. Always check them.</li>
  <li>Recent events, unless the tool shows you its sources.</li>
  <li>Legal, medical and financial decisions. AI can help you prepare, but a person decides.</li>
  <li>Private information about your customers, members or staff.</li>
</ul>

<div class="callout callout-tip">
  <i class="bi bi-person-badge"></i>
  <div><strong>The right mindset</strong><p>Treat AI like a fast, eager intern. It produces a brilliant first draft in seconds, needs clear instructions, and you always check the work before it goes out.</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (2 minutes)</strong><p>Open any AI assistant and type: <em>"Explain what a SACCO is to a 12-year-old. Then explain it again to a bank manager."</em> Notice how the same facts change completely for a different audience.</p></div>
</div>
HTML,
            'takeaways' => [
                'AI assistants predict likely words. They are brilliant with language but can be confidently wrong.',
                'Use them for drafts, summaries, rewriting and ideas.',
                'Check facts and keep a person in charge of important decisions.',
            ],
            'quiz' => [
                [
                    'q' => 'Why can an AI assistant give a wrong answer with full confidence?',
                    'options' => [
                        'It is designed to mislead people',
                        'It predicts likely words from patterns, rather than checking facts',
                        'It only happens when your internet is slow',
                        'It copies answers from the wrong website on purpose',
                    ],
                    'answer' => 1,
                    'why' => 'Language models predict what a good answer sounds like. Unless they are connected to sources, nothing checks the facts.',
                ],
                [
                    'q' => 'Which job is AI best suited for straight away?',
                    'options' => [
                        'Giving the final approval on a loan',
                        'Writing the first draft of a customer letter',
                        'Replacing your accountant\'s sign-off',
                        'Guaranteeing today\'s exchange rate',
                    ],
                    'answer' => 1,
                    'why' => 'Drafting is a strength. Approvals, sign-offs and live figures need a person or a trusted source.',
                ],
                [
                    'q' => 'What is the best way to think about an AI assistant?',
                    'options' => [
                        'An expert who is always right',
                        'A fast, eager intern whose work you check',
                        'A calculator for words',
                        'A replacement for your team',
                    ],
                    'answer' => 1,
                    'why' => 'Great first drafts, clear instructions needed, and you review the result.',
                ],
            ],
            'task' => [
                'title' => 'Your first real AI task',
                'brief' => '<p>Pick one real piece of writing you need this week, for example a customer email, a staff notice or a WhatsApp message.</p><ol><li>Ask an AI assistant to draft it.</li><li>Improve it twice with follow-ups such as <em>"make it shorter"</em> or <em>"make it warmer"</em>.</li><li>Fix anything that is wrong yourself.</li></ol>',
                'submit_hint' => 'Tell us what you chose, paste the final version, and name one thing you had to fix yourself.',
            ],
        ],

        /* ---------------------------------------------------------------- 2 */
        [
            'slug' => 'meet-the-ai-family',
            'title' => 'Meet the AI family',
            'minutes' => 12,
            'summary' => 'ChatGPT is one of many. Learn the main assistants and when to use each.',
            'body' => <<<'HTML'
<p class="lesson-hook">There is no single "best" AI. There is the best one for the job in front of you, and often it is already inside the tools you use.</p>

<h2>The big four assistants</h2>
<div class="tool-cards">
  <div><strong>ChatGPT</strong><span>by OpenAI</span><p>The best-known all-rounder: writing, analysis, images and voice conversations.</p></div>
  <div><strong>Claude</strong><span>by Anthropic</span><p>Strong at careful writing, long documents and analysis. Good for reports and policies.</p></div>
  <div><strong>Gemini</strong><span>by Google</span><p>Works inside Google tools such as Gmail, Docs and Sheets. Handy if your business runs on Google.</p></div>
  <div><strong>Copilot</strong><span>by Microsoft</span><p>Works inside Windows and Microsoft 365: Word, Excel, Outlook and Teams.</p></div>
</div>

<h2>Helpful specialists</h2>
<ul>
  <li><strong>Research tools</strong> such as Perplexity search the web and show their sources, so you can check them.</li>
  <li><strong>Design tools</strong> such as Canva now include AI features for posters and social media.</li>
  <li><strong>AI inside apps you already use</strong>, from your phone keyboard to your email, is growing every month.</li>
</ul>

<h2>Free or paid?</h2>
<p>Most assistants have a free version with limits. Paid plans usually give you stronger models, longer documents and more use per day. Prices and features change often, so compare before you pay, and start free.</p>

<div class="callout callout-tip">
  <i class="bi bi-compass"></i>
  <div><strong>How to choose</strong><p>Start with the assistant built into the tools you already use. For important tasks, try the same request in two assistants and keep the better answer.</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (3 minutes)</strong><p>Ask two different assistants: <em>"Give me 5 ideas to get more customers for a small pharmacy in Kampala."</em> Which answer is more practical for Uganda?</p></div>
</div>
HTML,
            'takeaways' => [
                'ChatGPT, Claude, Gemini and Copilot each have strengths.',
                'The assistant inside your everyday tools is often the easiest start.',
                'For important work, compare two assistants on the same task.',
            ],
            'quiz' => [
                [
                    'q' => 'Your team works in Gmail and Google Sheets every day. Which assistant fits in most naturally?',
                    'options' => ['Copilot', 'Gemini', 'A design tool', 'None of them'],
                    'answer' => 1,
                    'why' => 'Gemini is Google\'s assistant and works inside Google\'s tools.',
                ],
                [
                    'q' => 'Copilot is built into which family of tools?',
                    'options' => ['Google Workspace', 'Microsoft 365 and Windows', 'WhatsApp', 'Canva'],
                    'answer' => 1,
                    'why' => 'Copilot is Microsoft\'s assistant, inside Word, Excel, Outlook, Teams and Windows.',
                ],
                [
                    'q' => 'What is the smartest way to choose an assistant for an important task?',
                    'options' => [
                        'Always use the most famous one',
                        'Always pay for the most expensive plan',
                        'Try the same real task in two assistants and compare',
                        'Use whichever is newest',
                    ],
                    'answer' => 2,
                    'why' => 'Each tool has strengths. A quick side-by-side test on your real task shows you which one works best.',
                ],
            ],
            'task' => [
                'title' => 'Side-by-side test',
                'brief' => '<p>Choose one real task from your work. Give exactly the same request to two different AI assistants.</p><ol><li>Compare the answers for accuracy, usefulness and tone.</li><li>Decide which one you would use for this kind of task.</li></ol>',
                'submit_hint' => 'Tell us the task, the two assistants you used, and which one won and why.',
            ],
        ],

        /* ---------------------------------------------------------------- 3 */
        [
            'slug' => 'prompts-that-work',
            'title' => 'Prompts that get results',
            'minutes' => 15,
            'summary' => 'A simple 5-part formula that turns average answers into great ones.',
            'body' => <<<'HTML'
<p class="lesson-hook">Most disappointing AI answers come from short, vague requests. Give AI what a new staff member would need, and the quality jumps.</p>

<h2>The 5-part prompt</h2>
<ol class="formula">
  <li><strong>Role</strong>: who should the AI act as? <em>"You are an experienced SACCO manager."</em></li>
  <li><strong>Task</strong>: what exactly do you want? <em>"Write a notice about the annual general meeting."</em></li>
  <li><strong>Context</strong>: the facts it needs. <em>"Date 14 March, 10am, at our Ntinda office. Members must bring their ID."</em></li>
  <li><strong>Format</strong>: the shape of the answer. <em>"Under 120 words, with a clear heading."</em></li>
  <li><strong>Tone</strong>: how it should sound. <em>"Friendly but formal."</em></li>
</ol>

<div class="compare">
  <div class="compare-bad"><span>Weak prompt</span><p>"Write an AGM notice."</p></div>
  <div class="compare-good"><span>Strong prompt</span><p>"You are an experienced SACCO manager. Write a notice inviting members to our annual general meeting on 14 March at 10am at our Ntinda office. Members must bring their ID. Keep it under 120 words with a clear heading. Friendly but formal."</p></div>
</div>

<h2>Four habits of expert users</h2>
<ul>
  <li><strong>Ask for options:</strong> <em>"Give me three versions."</em> Then pick and mix.</li>
  <li><strong>Show an example:</strong> paste something you like and say <em>"in this style"</em>.</li>
  <li><strong>Improve in steps:</strong> <em>"Shorter." "Now for WhatsApp." "Add a call to action."</em></li>
  <li><strong>Let AI ask first:</strong> <em>"Before you start, ask me the three questions you need answered."</em></li>
</ul>

<div class="callout callout-tip">
  <i class="bi bi-bookmark-star"></i>
  <div><strong>Build a prompt library</strong><p>When a prompt works well, save it in a note on your phone. Next time, just change the details. This is how teams save hours every week.</p></div>
</div>
HTML,
            'takeaways' => [
                'Role, Task, Context, Format, Tone: give all five for strong results.',
                'Ask for options, show examples and improve step by step.',
                'Save prompts that work and reuse them.',
            ],
            'quiz' => [
                [
                    'q' => '"Write a sales post for our shop." Which part of the 5-part prompt is most clearly missing?',
                    'options' => ['Task', 'Context (what the shop sells, the offer, the audience)', 'Nothing is missing', 'The word "please"'],
                    'answer' => 1,
                    'why' => 'Without context the AI has to guess what you sell, who to target and what the offer is.',
                ],
                [
                    'q' => 'Why ask the AI to ask you questions before it starts?',
                    'options' => [
                        'It makes the AI work faster',
                        'It finds the missing information that would make the answer fit your situation',
                        'It is required by most tools',
                        'It stops the AI from making mistakes completely',
                    ],
                    'answer' => 1,
                    'why' => 'The questions reveal context you forgot to give, so the answer fits better.',
                ],
                [
                    'q' => 'The first answer is close but too long. What is the best next step?',
                    'options' => ['Start a new chat and retype everything', 'Reply "make it shorter, under 80 words"', 'Give up and write it yourself', 'Copy it as it is'],
                    'answer' => 1,
                    'why' => 'Improving in steps inside the same chat keeps all the context and is fastest.',
                ],
                [
                    'q' => 'What is a prompt library?',
                    'options' => ['A paid AI feature', 'Your saved collection of prompts that work well', 'A list of banned words', 'A book about AI'],
                    'answer' => 1,
                    'why' => 'Reusing proven prompts saves time and keeps quality consistent across your team.',
                ],
            ],
            'task' => [
                'title' => 'Write a 5-part prompt',
                'brief' => '<p>Write a 5-part prompt (Role, Task, Context, Format, Tone) for a real task in your work. Run it, then improve the answer with at least one follow-up.</p>',
                'submit_hint' => 'Paste your prompt, the final answer, and one sentence on how it compared with what you usually get.',
            ],
        ],

        /* ---------------------------------------------------------------- 4 */
        [
            'slug' => 'documents-and-research',
            'title' => 'AI for documents and research',
            'minutes' => 15,
            'summary' => 'Summarise long documents, pull out data and research with sources you can check.',
            'body' => <<<'HTML'
<p class="lesson-hook">A 40-page report, a pile of invoices, a question you need researched: this is where AI can save you hours, as long as you check its work.</p>

<h2>1. Summarise long documents</h2>
<p>Most assistants let you upload a PDF or Word file. Then ask for exactly what you need:</p>
<ul>
  <li><em>"Summarise this in one page for a busy director."</em></li>
  <li><em>"List every date, amount and deadline in this document."</em></li>
  <li><em>"What decisions does this report ask us to make?"</em></li>
</ul>

<h2>2. Turn paperwork into tables</h2>
<p>Upload a photo of a receipt, an invoice or a handwritten list and ask: <em>"Put this into a table with columns for item, quantity and price."</em> Then copy it into Excel or Google Sheets. <strong>Always check totals</strong>: AI can misread blurry numbers.</p>

<h2>3. Research with sources</h2>
<p>Use a tool that searches the web and shows its sources. Open the sources and check that they really say what the AI claims. If there is no source, treat the answer as a lead, not a fact.</p>

<div class="callout callout-tip">
  <i class="bi bi-search"></i>
  <div><strong>The fact-check question</strong><p>After any important answer, ask: <em>"What in your answer might be wrong, missing or out of date?"</em> AI is surprisingly good at pointing out its own weak spots.</p></div>
</div>

<div class="callout callout-warn">
  <i class="bi bi-shield-lock"></i>
  <div><strong>Protect private information</strong><p>Do not upload member lists, customer details, ID numbers or confidential contracts to AI tools unless your organisation has approved that tool. Remove names and numbers first when you can.</p></div>
</div>
HTML,
            'takeaways' => [
                'Ask for the specific summary you need: one page, key dates, decisions.',
                'AI can turn photos of paperwork into tables, but check the numbers.',
                'Use sources you can open, and ask AI what might be wrong.',
                'Keep personal and confidential data out of unapproved tools.',
            ],
            'quiz' => [
                [
                    'q' => 'AI turned a photo of 20 receipts into a table. What should you do before using it?',
                    'options' => ['Nothing, AI reads numbers perfectly', 'Check the totals and any unclear numbers against the receipts', 'Delete the receipts', 'Ask a different AI to agree'],
                    'answer' => 1,
                    'why' => 'AI can misread blurry or handwritten numbers. A quick check against the originals prevents costly errors.',
                ],
                [
                    'q' => 'A research answer has no sources. How should you treat it?',
                    'options' => ['As a confirmed fact', 'As a lead to check before you rely on it', 'As false', 'As more reliable than a website'],
                    'answer' => 1,
                    'why' => 'Without sources you cannot verify it. Use it as a starting point and check it.',
                ],
                [
                    'q' => 'Which of these should NOT go into an unapproved AI tool?',
                    'options' => ['A public news article', 'Your SACCO\'s full member list with phone numbers', 'A blank invoice template', 'A product brochure'],
                    'answer' => 1,
                    'why' => 'Personal data about members or customers must be protected. Use approved tools and remove identifying details.',
                ],
                [
                    'q' => 'Which follow-up helps you spot weak points in an AI answer?',
                    'options' => ['"Are you sure?"', '"What in your answer might be wrong, missing or out of date?"', '"Thank you"', '"Write it again"'],
                    'answer' => 1,
                    'why' => 'A specific question about weaknesses gets a useful list of things to check.',
                ],
            ],
            'task' => [
                'title' => 'Summarise and verify',
                'brief' => '<p>Take a document that is <strong>not confidential</strong>, for example a public report, a supplier brochure or a news article.</p><ol><li>Ask AI for a short summary and a table of its key facts or figures.</li><li>Check two of those facts against the original yourself.</li></ol>',
                'submit_hint' => 'Share the summary, the two facts you checked and whether the AI got them right. Add a link to the document if it is public.',
            ],
        ],

        /* ---------------------------------------------------------------- 5 */
        [
            'slug' => 'images-voice-video',
            'title' => 'Beyond text: images, voice and video',
            'minutes' => 12,
            'summary' => 'Create images, talk to AI and understand what AI video can do.',
            'body' => <<<'HTML'
<p class="lesson-hook">AI is no longer only about typing. You can show it a photo, talk to it out loud, and ask it to create pictures.</p>

<h2>Images</h2>
<ul>
  <li><strong>Create:</strong> describe the subject, style, colours and mood. <em>"A bright, friendly poster image of a modern pharmacy counter, blue and white colours, photo style."</em></li>
  <li><strong>Add text yourself:</strong> AI image tools can still get words and spelling wrong. Add your prices and contact details in a design tool such as Canva.</li>
  <li><strong>Understand images:</strong> snap a photo of a whiteboard, a stock list or a chart and ask AI to explain it or type it up.</li>
</ul>

<h2>Voice</h2>
<ul>
  <li>Use voice mode on your phone to think out loud, plan your day or practise a sales pitch.</li>
  <li>Dictate long messages instead of typing them, then ask AI to tidy them up.</li>
  <li>Some tools can transcribe and summarise meetings. Always tell people when a meeting is recorded.</li>
</ul>

<h2>Video</h2>
<p>New tools turn a script into a short video, or add AI voices and presenters. They are useful for training clips and product explainers. Use them honestly: label AI-generated people and voices, and never imitate a real person without their permission.</p>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (3 minutes)</strong><p>Take a photo of any handwritten list near you and ask an AI assistant to turn it into a neat table.</p></div>
</div>
HTML,
            'takeaways' => [
                'Describe images in detail: subject, style, colours and mood.',
                'Add important text to images yourself.',
                'Voice and photo input make AI faster to use on your phone.',
                'Be open about AI-generated people and voices.',
            ],
            'quiz' => [
                [
                    'q' => 'You need a promotional poster with your prices. What is the best approach?',
                    'options' => [
                        'Ask AI to put all the prices in the image and print it straight away',
                        'Create the image with AI, then add the prices and contacts yourself in a design tool',
                        'Never use AI for posters',
                        'Copy a competitor\'s poster',
                    ],
                    'answer' => 1,
                    'why' => 'AI images are great for visuals, but they can still get text wrong. Add exact details yourself.',
                ],
                [
                    'q' => 'What should you do before recording a meeting for an AI summary?',
                    'options' => ['Nothing', 'Tell the people in the meeting', 'Turn off your camera', 'Use a paid tool'],
                    'answer' => 1,
                    'why' => 'People should know when they are being recorded. It is respectful and often required.',
                ],
                [
                    'q' => 'Which is a responsible use of AI video?',
                    'options' => [
                        'Making a video of a real politician saying something they never said',
                        'A training clip with an AI presenter, clearly labelled as AI',
                        'Using a customer\'s face without asking',
                        'Hiding that a voice is AI-generated',
                    ],
                    'answer' => 1,
                    'why' => 'Clear labelling and consent keep AI video honest and protect your reputation.',
                ],
            ],
            'task' => [
                'title' => 'Make something visual',
                'brief' => '<p>Choose one:</p><ul><li><strong>A.</strong> Create an image for a real promotion or social media post, then add your text in a design tool.</li><li><strong>B.</strong> Photograph a handwritten list or whiteboard and have AI turn it into a table.</li></ul>',
                'submit_hint' => 'Tell us which option you chose and what you made. Share a link (for example Google Drive) and one thing you would improve.',
            ],
        ],

        /* ---------------------------------------------------------------- 6 */
        [
            'slug' => 'your-ai-toolkit',
            'title' => 'Your AI toolkit: safe, smart, every day',
            'minutes' => 15,
            'summary' => 'Put it all together into a safe daily routine that saves real time.',
            'body' => <<<'HTML'
<p class="lesson-hook">The people who get the most from AI are not the most technical. They are the ones who use it a little, every day, with good habits.</p>

<h2>Five safety rules</h2>
<ol>
  <li>Never paste passwords, PINs or mobile money codes into AI.</li>
  <li>Keep personal data out of unapproved tools. Uganda's Data Protection and Privacy Act, 2019 applies to personal data you handle, including in AI tools.</li>
  <li>Check facts, figures and anything that goes to a customer.</li>
  <li>A person makes the final decision on money, people and legal matters.</li>
  <li>Be open when customers are dealing with AI, for example a chatbot.</li>
</ol>

<h2>A simple daily routine</h2>
<div class="lesson-grid">
  <div><i class="bi bi-sunrise"></i><strong>Morning</strong><span>Summarise long emails or messages and plan the day.</span></div>
  <div><i class="bi bi-sun"></i><strong>During the day</strong><span>Draft replies, notices and posts. Rewrite for the right audience.</span></div>
  <div><i class="bi bi-sunset"></i><strong>End of day</strong><span>Turn notes into a short report or tomorrow's to-do list.</span></div>
</div>

<h2>Measure your impact</h2>
<p>For one week, note three tasks you do with AI and roughly how many minutes each one saved. Small savings add up: 20 minutes a day is more than an hour and a half every week.</p>

<div class="callout callout-tip">
  <i class="bi bi-signpost-split"></i>
  <div><strong>Where to go next</strong><p>Ready for more? Our next courses go deeper: <em>Automate Your Business with AI</em> and <em>Data Analysis with AI</em>.</p></div>
</div>
HTML,
            'takeaways' => [
                'Follow the five safety rules every time.',
                'Use AI a little every day: morning, during the day, end of day.',
                'Track minutes saved to see the real impact.',
            ],
            'quiz' => [
                [
                    'q' => 'Which of these should never be pasted into an AI assistant?',
                    'options' => ['A draft newsletter', 'Your mobile money PIN', 'A public price list', 'A meeting agenda'],
                    'answer' => 1,
                    'why' => 'Passwords, PINs and codes must never be shared with any AI tool.',
                ],
                [
                    'q' => 'Which Ugandan law covers personal data you handle, including in AI tools?',
                    'options' => ['The Data Protection and Privacy Act, 2019', 'The Traffic Act', 'The Copyright Act only', 'No law applies to AI'],
                    'answer' => 0,
                    'why' => 'The Data Protection and Privacy Act, 2019 governs how personal data is collected and processed in Uganda.',
                ],
                [
                    'q' => 'Who should make the final decision on a staff member\'s salary change?',
                    'options' => ['The AI assistant', 'A responsible person, using AI only to prepare', 'Whoever asks the AI first', 'The payroll system automatically'],
                    'answer' => 1,
                    'why' => 'Decisions about money and people stay with a person. AI can help prepare the information.',
                ],
                [
                    'q' => 'What is the best way to see if AI is really helping you?',
                    'options' => ['Use it as much as possible', 'Track the minutes it saves on a few tasks for a week', 'Ask the AI', 'Buy the most expensive plan'],
                    'answer' => 1,
                    'why' => 'Measuring time saved on real tasks shows the true impact and where to use AI more.',
                ],
            ],
            'task' => [
                'title' => 'Your personal AI plan',
                'brief' => '<p>Write your AI plan for the next month:</p><ol><li>Three tasks you will hand to AI every week.</li><li>The tool you will use for each.</li><li>One safety rule you will follow strictly.</li><li>How much time you expect to save each week.</li></ol>',
                'submit_hint' => 'Paste your plan. After a week, you can come back and update it with the real time you saved.',
            ],
        ],
    ],
];
