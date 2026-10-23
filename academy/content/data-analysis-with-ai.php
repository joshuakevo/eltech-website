<?php
/**
 * Course: Data Analysis with AI
 */

return [
    'description' => 'Your sales, loans and expenses already tell a story. Learn to ask the right questions, clean messy spreadsheets, get formulas and pivot tables written for you, analyse data in plain English and turn the results into decisions.',
    'free_lessons' => 2,
    'outcomes' => [
        'Turn business questions into clear measures',
        'Clean messy spreadsheets quickly with AI help',
        'Use formulas and pivot tables without memorising them',
        'Ask questions of your data in plain English',
        'Build charts that lead to decisions',
    ],
    'lessons' => [

        /* ---------------------------------------------------------------- 1 */
        [
            'slug' => 'ask-the-right-questions',
            'title' => 'Start with the right question',
            'minutes' => 12,
            'summary' => 'Good analysis starts with a decision you need to make, not with the data.',
            'body' => <<<'HTML'
<p class="lesson-hook">Many people open a spreadsheet and stare at it, hoping for insight. Analysts do the opposite: they start with a question.</p>

<h2>From decision to question to measure</h2>
<div class="compare">
  <div class="compare-bad"><span>Vague</span><p>"How is the business doing?"</p></div>
  <div class="compare-good"><span>Clear</span><p>"Which products made the most profit last month, and should we restock them first?"</p></div>
</div>
<ol>
  <li><strong>Decision:</strong> what will you do differently depending on the answer?</li>
  <li><strong>Question:</strong> what do you need to know to decide?</li>
  <li><strong>Measure:</strong> which number answers it? Sales per product, loans in arrears, average basket size…</li>
</ol>

<h2>Useful measures by business</h2>
<div class="lesson-grid">
  <div><i class="bi bi-bank"></i><strong>SACCO / lender</strong><span>Portfolio at risk, repayment rate, new members per month.</span></div>
  <div><i class="bi bi-shop"></i><strong>Shop / supermarket</strong><span>Sales per day, top products, stock that does not move.</span></div>
  <div><i class="bi bi-capsule"></i><strong>Pharmacy</strong><span>Items near expiry, fast movers, margin per category.</span></div>
  <div><i class="bi bi-cup-hot"></i><strong>Restaurant</strong><span>Busy hours, best dishes, cost of ingredients per plate.</span></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>Tell an AI assistant what your business does and ask: <em>"What are the 5 most useful numbers I should track every week, and why?"</em></p></div>
</div>
HTML,
            'takeaways' => [
                'Start with the decision, then the question, then the measure.',
                'A clear question beats a big spreadsheet.',
                'Pick a few measures that really matter to your business.',
            ],
            'quiz' => [
                [
                    'q' => 'Which is the best analysis question?',
                    'options' => [
                        '"Tell me everything about my data"',
                        '"Which branch had the most loans in arrears last quarter, so we know where to follow up?"',
                        '"Is my data good?"',
                        '"Make a chart"',
                    ],
                    'answer' => 1,
                    'why' => 'It is specific, measurable and linked to an action.',
                ],
                [
                    'q' => 'What should you define first?',
                    'options' => ['The chart colours', 'The decision you need to make', 'The software', 'The number of rows'],
                    'answer' => 1,
                    'why' => 'The decision tells you which question and measure matter.',
                ],
                [
                    'q' => 'Which is a useful measure for a pharmacy?',
                    'options' => ['Number of chairs', 'Items close to expiry', 'Wall colour', 'Number of light bulbs'],
                    'answer' => 1,
                    'why' => 'Expiring stock is money at risk and needs action.',
                ],
            ],
            'task' => [
                'title' => 'Your three key questions',
                'brief' => '<p>Write three business decisions you face regularly. For each, write the question you need answered and the measure (number) that answers it.</p>',
                'submit_hint' => 'List three decision → question → measure chains for your business.',
            ],
        ],

        /* ---------------------------------------------------------------- 2 */
        [
            'slug' => 'clean-your-data',
            'title' => 'Clean messy data with AI',
            'minutes' => 15,
            'summary' => 'Fix duplicates, spelling differences, dates and blanks before you analyse.',
            'body' => <<<'HTML'
<p class="lesson-hook">"Kampala", "kampala " and "KLA" are the same place to you, but three different places to a spreadsheet. Messy data gives wrong answers.</p>

<h2>The usual mess</h2>
<ul>
  <li>Duplicate rows (the same sale entered twice).</li>
  <li>Different spellings and extra spaces in names.</li>
  <li>Dates written in different ways (03/04/2026 vs 4 March 2026).</li>
  <li>Numbers stored as text, or with "UGX" typed inside the cell.</li>
  <li>Blank cells where a value should be.</li>
</ul>

<h2>Let AI write the fix</h2>
<p>Describe the problem and ask for the formula or steps. For example:</p>
<ul>
  <li><em>"In Excel, column B has customer names with extra spaces and mixed capitals. Give me a formula to clean them."</em> → <code>=PROPER(TRIM(B2))</code></li>
  <li><em>"Column D has amounts like 'UGX 45,000'. How do I turn them into numbers?"</em></li>
  <li><em>"How do I remove duplicate rows in Google Sheets?"</em> → Data › Data cleanup › Remove duplicates.</li>
</ul>
<p>Ask the AI to <strong>explain</strong> the formula too. Understanding it means you can fix it later.</p>

<div class="callout callout-tip">
  <i class="bi bi-files"></i>
  <div><strong>Golden rule: keep the original</strong><p>Always clean a copy. Keep the raw data untouched in its own sheet, so you can start again if something goes wrong.</p></div>
</div>
HTML,
            'takeaways' => [
                'Duplicates, spellings, dates, text-numbers and blanks cause wrong results.',
                'Describe the mess to AI and ask for the formula, plus an explanation.',
                'Always work on a copy of your data.',
            ],
            'quiz' => [
                [
                    'q' => 'What does =PROPER(TRIM(B2)) do?',
                    'options' => [
                        'Deletes column B',
                        'Removes extra spaces and capitalises each word',
                        'Adds up column B',
                        'Sorts the names',
                    ],
                    'answer' => 1,
                    'why' => 'TRIM removes extra spaces and PROPER capitalises each word.',
                ],
                [
                    'q' => 'Why should you clean a copy, not the original?',
                    'options' => ['It is faster', 'So you can always go back if a fix goes wrong', 'Spreadsheets require it', 'It uses less memory'],
                    'answer' => 1,
                    'why' => 'The untouched original is your safety net.',
                ],
                [
                    'q' => 'Why ask AI to explain a formula it gives you?',
                    'options' => [
                        'To make the answer longer',
                        'So you understand it and can check or fix it later',
                        'Because formulas do not work otherwise',
                        'It is not useful',
                    ],
                    'answer' => 1,
                    'why' => 'Understanding the formula helps you trust it and adapt it.',
                ],
            ],
            'task' => [
                'title' => 'Clean a real sheet',
                'brief' => '<p>Take a copy of a real spreadsheet from your work (remove personal details first). Find at least three types of mess and fix them, using AI to get the formulas or steps.</p>',
                'submit_hint' => 'Describe the three problems you found, the formulas or steps you used, and how many rows were affected.',
            ],
        ],

        /* ---------------------------------------------------------------- 3 */
        [
            'slug' => 'formulas-and-pivots',
            'title' => 'Formulas and pivot tables, with AI help',
            'minutes' => 15,
            'summary' => 'Get the formulas you need and summarise thousands of rows in seconds.',
            'body' => <<<'HTML'
<p class="lesson-hook">You do not need to memorise formulas any more. You need to describe what you want clearly, and know how to check the result.</p>

<h2>Formulas on request</h2>
<p>Tell AI your columns and what you need:</p>
<ul>
  <li><em>"Column A is date, B is product, C is amount. Give me a formula for total sales of 'Sugar 1kg'."</em> → <code>=SUMIF(B:B,"Sugar 1kg",C:C)</code></li>
  <li><em>"Look up each product's price from the 'Prices' sheet."</em> → <code>XLOOKUP</code></li>
  <li><em>"Mark loans as Late if the due date has passed and the balance is above zero."</em> → <code>IF</code> with <code>AND</code></li>
</ul>

<h2>Pivot tables: your summary machine</h2>
<p>A pivot table turns thousands of rows into a short summary: total sales per product, loans per branch, expenses per month. In Excel and Google Sheets: select your data, then Insert › Pivot table, and drag fields into Rows and Values.</p>
<p>Ask AI: <em>"I want total sales per product per month from this data. How do I set up the pivot table?"</em></p>

<h2>AI inside your spreadsheet</h2>
<p>Some paid plans add AI directly into spreadsheets, such as Copilot in Excel and Gemini in Google Sheets. They can create formulas and summaries from a typed request. Free AI assistants work well too: just describe your columns.</p>

<div class="callout callout-tip">
  <i class="bi bi-check2-square"></i>
  <div><strong>Check one row by hand</strong><p>After any new formula, work out one or two rows yourself. If they match, the formula is probably right.</p></div>
</div>
HTML,
            'takeaways' => [
                'Describe your columns and goal, and AI writes the formula.',
                'Pivot tables summarise large data in seconds.',
                'Check a row or two by hand before trusting a formula.',
            ],
            'quiz' => [
                [
                    'q' => 'What helps AI write the right formula?',
                    'options' => ['Saying "make a formula"', 'Describing what is in each column and the result you want', 'Sending the file name only', 'Using capital letters'],
                    'answer' => 1,
                    'why' => 'Column details and a clear goal give AI what it needs.',
                ],
                [
                    'q' => 'What is a pivot table best for?',
                    'options' => ['Typing data', 'Summarising many rows, like total sales per product per month', 'Drawing logos', 'Sending emails'],
                    'answer' => 1,
                    'why' => 'Pivot tables group and total data quickly.',
                ],
                [
                    'q' => 'How do you quickly check a new formula?',
                    'options' => ['Trust it', 'Work out one or two rows by hand and compare', 'Delete it', 'Ask a friend to guess'],
                    'answer' => 1,
                    'why' => 'A manual check catches most formula mistakes.',
                ],
            ],
            'task' => [
                'title' => 'Summarise your data',
                'brief' => '<p>Using a real (anonymised) sheet, create one formula with AI help and one pivot table that answers one of your key questions from lesson 1.</p>',
                'submit_hint' => 'Share the formula and what it does, describe your pivot table (rows, values), and the answer it gave you.',
            ],
        ],

        /* ---------------------------------------------------------------- 4 */
        [
            'slug' => 'analyse-in-plain-english',
            'title' => 'Analyse your data in plain English',
            'minutes' => 15,
            'summary' => 'Upload a spreadsheet to an AI assistant and ask it questions, safely.',
            'body' => <<<'HTML'
<p class="lesson-hook">Assistants such as ChatGPT and Claude can read a spreadsheet you upload and answer questions about it, often with charts. It feels like having an analyst on call.</p>

<h2>A good conversation with your data</h2>
<ol>
  <li><em>"Describe this data: what columns are there and what period does it cover?"</em></li>
  <li><em>"Which 5 products had the highest sales? Show a table."</em></li>
  <li><em>"Compare this month with last month. What changed the most?"</em></li>
  <li><em>"Is anything unusual, like a sudden drop or a strange value?"</em></li>
</ol>

<h2>Trust, but verify</h2>
<ul>
  <li>Ask how it calculated each number: <em>"Show the steps."</em></li>
  <li>Check one key number yourself with a pivot table or SUMIF.</li>
  <li>Watch for wrong assumptions, such as treating a refund as a sale.</li>
</ul>

<div class="callout callout-warn">
  <i class="bi bi-shield-lock"></i>
  <div><strong>Anonymise first</strong><p>Remove or replace names, phone numbers and ID numbers before uploading. Use "Customer 1, Customer 2", or delete those columns if you do not need them for the question.</p></div>
</div>

<div class="callout callout-try">
  <i class="bi bi-play-circle"></i>
  <div><strong>Try it now (5 minutes)</strong><p>Upload a small anonymised sheet and ask: <em>"What are the three most interesting things in this data for a business owner?"</em></p></div>
</div>
HTML,
            'takeaways' => [
                'Start by asking AI to describe the data, then ask specific questions.',
                'Ask for the steps and check key numbers yourself.',
                'Remove personal details before uploading.',
            ],
            'quiz' => [
                [
                    'q' => 'What is a good first question after uploading a spreadsheet?',
                    'options' => ['"Fix my business"', '"Describe this data: what columns are there and what period does it cover?"', '"Delete the data"', '"Is AI smart?"'],
                    'answer' => 1,
                    'why' => 'It confirms the AI understood your data before you rely on its answers.',
                ],
                [
                    'q' => 'What should you remove before uploading customer sales data?',
                    'options' => ['Product names', 'Customer names, phone numbers and ID numbers', 'Dates', 'Amounts'],
                    'answer' => 1,
                    'why' => 'Personal details are not needed for most analysis and must be protected.',
                ],
                [
                    'q' => 'AI says sales rose 40%. What should you do?',
                    'options' => ['Celebrate and announce it', 'Ask for the steps and check the key numbers yourself', 'Ignore it', 'Upload more data'],
                    'answer' => 1,
                    'why' => 'Verifying protects you from wrong assumptions in the calculation.',
                ],
            ],
            'task' => [
                'title' => 'Interview your data',
                'brief' => '<p>Upload an anonymised spreadsheet to an AI assistant. Ask at least five questions, including one about something unusual. Verify one answer yourself.</p>',
                'submit_hint' => 'List your questions, the most useful answer, and how you verified one of the numbers.',
            ],
        ],

        /* ---------------------------------------------------------------- 5 */
        [
            'slug' => 'charts-and-decisions',
            'title' => 'Charts that lead to decisions',
            'minutes' => 15,
            'summary' => 'Show the answer clearly, then turn it into action.',
            'body' => <<<'HTML'
<p class="lesson-hook">A chart is not decoration. It should make one point so clearly that the reader knows what to do next.</p>

<h2>Pick the right chart</h2>
<div class="lesson-grid">
  <div><i class="bi bi-graph-up"></i><strong>Line</strong><span>Change over time: sales per week.</span></div>
  <div><i class="bi bi-bar-chart"></i><strong>Bar</strong><span>Comparing items: sales per product or branch.</span></div>
  <div><i class="bi bi-table"></i><strong>Table</strong><span>When people need exact numbers.</span></div>
  <div><i class="bi bi-speedometer"></i><strong>Single number</strong><span>One key figure, like today's sales, with a comparison.</span></div>
</div>

<h2>Make the point obvious</h2>
<ul>
  <li><strong>Title = the message:</strong> "Sugar sales fell 30% in March", not "Sales chart".</li>
  <li>Highlight what matters in one colour, and grey the rest.</li>
  <li>Remove clutter: extra lines, 3D effects and too many labels.</li>
</ul>

<h2>From numbers to decisions</h2>
<p>Build a simple weekly dashboard of 3 to 5 key measures. Review it at the same time every week and ask three questions: <em>What changed? Why? What will we do?</em> Write the actions down and check them next week.</p>

<div class="callout callout-tip">
  <i class="bi bi-exclamation-diamond"></i>
  <div><strong>Be careful with forecasts</strong><p>AI can project trends, but the future depends on things the data does not know, like a new competitor or a holiday. Treat forecasts as a guide, not a promise.</p></div>
</div>
HTML,
            'takeaways' => [
                'Choose the chart type for the job: time, comparison, exact numbers or one key figure.',
                'Make the title state the message.',
                'Review a small dashboard weekly and write down actions.',
            ],
            'quiz' => [
                [
                    'q' => 'Which chart is best for sales per week over three months?',
                    'options' => ['Line chart', 'Pie chart', '3D bar chart', 'A photo'],
                    'answer' => 0,
                    'why' => 'Line charts show change over time clearly.',
                ],
                [
                    'q' => 'Which chart title is best?',
                    'options' => ['"Chart 1"', '"Sales data"', '"Branch B loans in arrears doubled in June"', '"Numbers"'],
                    'answer' => 2,
                    'why' => 'A title that states the message tells the reader what matters.',
                ],
                [
                    'q' => 'What three questions should a weekly review answer?',
                    'options' => [
                        'Who, where, when',
                        'What changed? Why? What will we do?',
                        'How many charts? Which colours? What font?',
                        'None, just look at it',
                    ],
                    'answer' => 1,
                    'why' => 'They turn numbers into decisions and actions.',
                ],
            ],
            'task' => [
                'title' => 'Your one-page dashboard',
                'brief' => '<p>Create a simple one-page dashboard with 3 to 5 key measures for your business, using at least one chart with a message title. Write down one decision it helps you make.</p>',
                'submit_hint' => 'Describe your measures, your chart and its title, and the decision it supports. Share a link if you can.',
            ],
        ],
    ],
];
