<?php

namespace App\Providers;

use App\Models\Award;
use App\Models\MusicNowPlaying;
use App\Models\ProgrammingTime;
use App\Models\TabNavLink;
use App\Models\Talk;
use App\Models\TimelineEntry;
use App\Models\TopMenuEntry;
use App\Models\TopMenuSubEntry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $tabEntries = [
            'welcome' => new TabNavLink(['name' => 'Welcome', 'path' => 'welcome', 'icon' => 'home']),
            'about' => new TabNavLink(['name' => 'About me', 'path' => 'about', 'icon' => 'account']),
            'interests' => new TabNavLink(['name' => 'My Interests', 'path' => 'interests', 'icon' => 'heart']),
            'blog' => new TabNavLink(['name' => 'Blog', 'path' => 'blog.index', 'icon' => 'post']),
            'contact' => new TabNavLink(['name' => 'Contact me', 'path' => 'contact', 'icon' => 'email'])
        ];
        $topLevelPath = explode('/', request()->path())[0];
        $active = request()->is('/') ? 'welcome' : $topLevelPath;

        $taggedVersion = ltrim(config('app.version'), 'v'); // git tags are "v0.0.2"; the view adds its own "v"

        try {
            $latestTrack = MusicNowPlaying::where('played_at', '>', now()->addMinutes(-5))
                ->latest('played_at')
                ->first();
        } catch (\Exception $e) {
            $latestTrack = null;
        }

        try {
            $latestProgrammingTime = ProgrammingTime::getLatest();
            $programmingTime = $latestProgrammingTime ? $latestProgrammingTime->time_text : null;
        } catch (\Exception $e) {
            $programmingTime = null;
        }

        $programmingTimeUrl = env('WAKAPI_LEADERBOARD_URL');

        $topMenuEntries = [
            new TopMenuEntry([
                'name' => 'File',
                'subEntries' => [
                    new TopMenuSubEntry(['name' => 'New File', 'url' => 'editor', 'icon' => 'file-plus']),
                    new TopMenuSubEntry(['name' => 'Save File', 'url' => 'javascript:window.saveFile()', 'icon' => 'content-save', 'enabled' => request()->is('editor')]),
                ]
            ]),
            new TopMenuEntry([
                'name' => 'Socials',
                'subEntries' => collect(config('socials.platforms'))
                    ->map(fn ($platform) => new TopMenuSubEntry([
                        'name' => $platform['name'],
                        'url' => $platform['url'],
                        'icon' => $platform['icon'],
                        'use-simple-icons' => $platform['use-simple-icons'] ?? false,
                    ]))
                    ->values()
                    ->toArray()
            ]),
        ];

        $timelineEntries = [
            new TimelineEntry([
                'title' => 'Software engineer',
                'company' => 'Capital One',
                'company_url' => 'https://www.capitalone.co.uk/',
                'start_date' => \Carbon\Carbon::createFromDate(2025, 9, 8),
                'end_date' => null,
                'description' => 'Capital One do credit cards. And they do them rather well, shown by the numerous awards they have won recognising their excellence for both customers and employees: they\'ve been recognised with a rare 5-star rating by Fairer Finance for their credit builder card, and have achieved 15th in Great Place to Work® 2025 rankings. I joined as part of the graduate scheme in September 2025, working in an interdisciplinary team that focuses on customer retention. My work mostly involves using Java.',
                'icon' => 'credit-card',
            ]),
            new TimelineEntry([
                'title' => 'Software developer',
                'company' => 'Savoy Systems',
                'company_url' => 'https://savoysystems.co.uk/',
                'start_date' => \Carbon\Carbon::createFromDate(2024, 06, 10),
                'end_date' => \Carbon\Carbon::createFromDate(2025, 07, 31),
                'description' => 'Savoy Systems makes ticketing software for cinemas and theatres. The small company size naturally makes this a full-stack development role, working on our backend server, web server, and desktop and mobile apps. I was involved in a large project to port a Delphi desktop app to a clean web interface in Blazor C#, which allows our clients to conveniently manage their venue in a web browser alongside useful analytics. I also got stuck in with various bug fixes, new features, and improvements to our workflow by introducing GitHub projects.',
                'icon' => 'drama-masks',
            ]),
            new TimelineEntry([
                'title' => 'Mathematics PhD',
                'company' => 'University of Nottingham',
                'company_url' => 'https://nottingham.ac.uk/',
                'start_date' => \Carbon\Carbon::createFromDate(2020, 10, 01),
                'end_date' => \Carbon\Carbon::createFromDate(2024, 05, 24),
                'description' => 'Worked on an EPSRC-funded on a project titled "Placental haemodynamics: A computational study of maternal blood flow and oxygen transport in the human placenta" under the supervision of Paul Houston, Matthew Hubbard, and Reuben O\'Dea. The main aims of the project were to create a mathematical model of blood flow and nutrient transport in the human placenta, simulating the dynamics using a computer.',
                'icon' => 'test-tube',
                'buttons' => [
                    ['url' => 'https://r.blakey.family/phd', 'text' => 'Read thesis'],
                    ['url' => 'https://doi.org/10.1002/mrm.30379', 'text' => 'Read paper on synthetic MRI']
                ]
            ]),
            new TimelineEntry([
                'title' => 'MMath degree',
                'company' => 'University of Nottingham',
                'company_url' => 'https://nottingham.ac.uk/',
                'start_date' => \Carbon\Carbon::createFromDate(2016, 10, 01),
                'end_date' => \Carbon\Carbon::createFromDate(2020, 06, 01),
                'description' => 'A challenging integrated-master’s degree over 4 years, with the school ranked within the top-10 nationally for research power and research quality in 2014 Research Excellence Framework. I specialised in fluid mechanics and numerical methods during my studies.',
                'icon' => 'school',
                'buttons' => [
                    ['url' => 'https://r.blakey.family/blakeyfem', 'text' => 'Read dissertation']
                ]
            ]),
        ];

        $awards = [
            new Award([
                'title' => 'PGR Demonstrator Award',
                'date' => \Carbon\Carbon::createFromDate(2022, 07, 01),
                'description' => 'Awarded in July 2022 for work delivering the Python component MTHS1001 (first-year non-Maths Core Maths module).',
                'image' => null,
                'link' => null,
                'featured' => true
            ]),
            new Award([
                'title' => 'Outstanding Contribution to the Postgraduate Community',
                'date' => \Carbon\Carbon::createFromDate(2022, 05, 01),
                'description' => 'Awarded at the 2022 Tri-Campus Awards Ceremony. This award recognises the contributions that postgraduate researchers have made to the university’s research community.',
                'image' => 'resources/img/outstanding-contribution-award.jpg',
                'link' => null,
                'featured' => true
            ]),
            new Award([
                'title' => 'Chief Scout’s Commendation for Good Service',
                'date' => \Carbon\Carbon::createFromDate(2021, 01, 01),
                'description' => 'Awarded in respect of not less than 5 years good service, which stands out. It should be regarded as the Chief Scout’s recognition of the very real contribution made to the Scouts by the individual concerned.',
                'image' => 'resources/img/commendation-award.jpg',
                'link' => null,
                'featured' => true
            ]),
            new Award([
                'title' => 'Elizabeth and JD Marsden Prize (1st Place)',
                'date' => \Carbon\Carbon::createFromDate(2020, 8, 01),
                'description' => 'The Elizabeth and JD Marsden prizes were founded in 1953 “to encourage not only the development of the mind (with which education seems to be mainly concerned) but also to encourage and reward the cultivation of those qualities which are the essence of character and personality” and by these prizes to “provide pecuniary help to students on leaving the University to enter upon their life’s work.” The prizes are “awarded to students who are adjudged the best students of the year — who have gained high distinction in their examinations, and who have throughout their University careers been of exemplary conduct and given proofs of outstanding character and personality.”',
                'image' => null,
                'link' => null,
                'featured' => true
            ]),
        ];

        $talks = [
            new Talk([
                'title' => 'C# in the Browser',
                'description' => 'In this talk we\'ll explore Blazor, Microsoft\'s framework for building web apps with .NET, and how it can leverage WebAssembly to run C# directly in the browser.',
                'image' => 'resources/img/dotnet-notts-thumbnail.avif',
                'pdf_link' => 'https://files.blakey.family/DotNetNotts_2026.pdf',
                'pptx_link' => 'https://files.blakey.family/DotNetNotts_2026.pptx',
                'rsvp_link' => null,
                'rsvp_expiry' => null,
                'featured' => true,
                'code_link' => 'https://github.com/adam-blakey/BlazorTalk',
                'recording_link' => 'https://youtu.be/8r_uJh0upFo',
                'demo_link' => 'https://blazor.blakey.family'
            ]),
            new Talk([
                'title' => 'Lights, Camera, Blazor',
                'description' => 'This talk is very similar to the more-recent \'C# in the Browser\' talk; it takes you through what Blazor is, what WebAssembly is and how it differs from JavaScript, as well as some of the challenges we at Savoy Systems have come across while rewriting our software in Blazor.',
                'image' => 'resources/img/techfast-thumbnail.avif',
                'pdf_link' => 'https://files.blakey.family/TechFast_2025.pdf',
                'pptx_link' => 'https://files.blakey.family/TechFast_2025.pptx',
                'rsvp_link' => null,
                'rsvp_expiry' => null,
                'featured' => true,
                'code_link' => 'https://github.com/adam-blakey/BlazorTalk/tree/TechFast2025',
                'recording_link' => 'https://youtu.be/cAUVci6NP8s',
                'demo_link' => 'https://techfast.blakey.family'
            ]),
            new Talk([
                'title' => 'Placental Haemodynamics',
                'description' => 'Talk given several times during my PhD. It gives an introduction to FEMs, placentas, and porous medium modelling. Contains results on flow and transport with the additional of septal veins.',
                'image' => 'resources/img/placental-haemodynamics-thumbnail.jpg',
                'pdf_link' => 'https://files.blakey.family/Placental_Haemodynamics_May23.pdf',
                'pptx_link' => 'https://r.blakey.family/ph-nov22',
                'featured' => true,
                'code_link' => null,
                'recording_link' => null,
                'demo_link' => null
            ]),
            new Talk([
                'title' => 'A recipe for adaptive hp-FEMs',
                'description' => 'Overview of FEMs with an interactive example. Shows code I wrote in C++ during my Master’s dissertation',
                'image' => 'resources/img/blakey-bakery-thumbnail.png',
                'pdf_link' => 'https://files.blakey.family/Blakey-Bakery-—-hp-FEM-Recipes.pdf',
                'pptx_link' => 'https://r.blakey.family/blakeybakery',
                'featured' => true,
                'code_link' => null,
                'recording_link' => null,
                'demo_link' => 'https://fem.blakey.family'
            ]),
            new Talk([
                'title' => 'Maths “Careers” Talk',
                'description' => 'Written in the first year of My PhD, giving an insight into the before and during of my PhD.',
                'image' => 'resources/img/careers-talk-thumbnail.png',
                'pdf_link' => 'https://files.blakey.family/Maths-Career-Event-20210215.pdf',
                'pptx_link' => null,
                'featured' => true,
                'code_link' => null,
                'recording_link' => null,
                'demo_link' => null
            ]),
        ];

        View::share('layoutTabEntries', $tabEntries);
        View::share('layoutActiveTab', $active);
        View::share('layoutTopMenuEntries', $topMenuEntries);
        View::share('layoutTimelineEntries', $timelineEntries);
        View::share('layoutAwards', collect($awards)->where('featured', true));
        View::share('layoutTalks', collect($talks)->where('featured', true));
        View::share('layoutTaggedVersion', $taggedVersion);
        View::share('layoutLatestTrack', $latestTrack);
        View::share('layoutProgrammingTime', $programmingTime);
        View::share('layoutProgrammingTimeUrl', $programmingTimeUrl);
    }
}
