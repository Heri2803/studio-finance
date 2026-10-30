@props([
    'mood' => 'hapy',
    'size' => 'md',
])

@php
    $dimensions = match($size) {
        'xs' => 'w-8 h-8',
        'sm' => 'w-11 h-11',
        'md' => 'w-16 h-16 sm:w-20 sm:h-20',
        'lg' => 'w-20 h-20 sm:w-28 sm:h-28',
        'xl' => 'w-24 h-24 sm:w-36 sm:h-36',
        default => 'w-16 h-16 sm:w-20 sm:h-20',
    };

    // Determine Role Accessories
    $role = 'none';
    if (str_starts_with($mood, 'payroll') || $mood === 'cashier') {
        $role = 'payroll';
    } elseif (str_starts_with($mood, 'talent') || $mood === 'scout') {
        $role = 'talent';
    } elseif (str_starts_with($mood, 'bookkeeper') || $mood === 'inspector') {
        $role = 'bookkeeper';
    }

    // Determine Emotion / Facial State
    $emotion = match($mood) {
        'payroll_happy', 'talent_happy', 'bookkeeper_happy', 'create', 'hapy' => 'happy',
        'payroll_alert', 'talent_alert', 'alert' => 'alert',
        'payroll_worried' => 'worried',
        'bookkeeper_angy', 'angy' => 'angy',
        'update', 'wink' => 'wink',
        'delete', 'sad' => 'sad',
        default => match($role) {
            'payroll' => 'happy',
            'talent' => 'happy',
            'bookkeeper' => 'happy',
            default => 'zen'
        }
    };
@endphp

<div {{ $attributes->merge(['class' => "relative inline-flex items-center justify-center shrink-0 $dimensions animate-cat-float cursor-pointer select-none"]) }}>
    <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-sm select-none">
        
        <!-- Base Head / Body -->
        <ellipse cx="60" cy="65" rx="46" ry="42" fill="#9CA3AF" stroke="#2D2B38" stroke-width="4"/>
        
        <!-- Ears Layer (with playful periodic ear twitches!) -->
        @if($emotion === 'sad' || $emotion === 'worried')
            <!-- Drooping / Nervous Ears -->
            <g class="cat-ear-left">
                <path d="M28 42L12 28C10 24 16 18 22 22L36 34" fill="#9CA3AF" stroke="#2D2B38" stroke-width="4" stroke-linejoin="round"/>
                <path d="M24 38L16 28L30 32" fill="#FBCFE8"/>
            </g>
            <g class="cat-ear-right">
                <path d="M92 42L108 28C110 24 104 18 98 22L84 34" fill="#9CA3AF" stroke="#2D2B38" stroke-width="4" stroke-linejoin="round"/>
                <path d="M96 38L104 28L90 32" fill="#FBCFE8"/>
            </g>
        @else
            <!-- Standard Upright Ears with cute twitches -->
            <g class="cat-ear-left">
                <path d="M26 40L14 16C12 12 18 8 24 12L38 28" fill="#9CA3AF" stroke="#2D2B38" stroke-width="4" stroke-linejoin="round"/>
                <path d="M24 34L18 20L32 28" fill="#FBCFE8"/>
            </g>
            <g class="cat-ear-right">
                <path d="M94 40L106 16C108 12 102 8 96 12L82 28" fill="#9CA3AF" stroke="#2D2B38" stroke-width="4" stroke-linejoin="round"/>
                <path d="M96 34L102 20L88 28" fill="#FBCFE8"/>
            </g>
        @endif

        <!-- Forehead Stripes (Only when no visor) -->
        @if($role !== 'payroll')
            <path d="M60 30V42" stroke="#4B5563" stroke-width="3.5" stroke-linecap="round"/>
            <path d="M50 33L54 44" stroke="#4B5563" stroke-width="3" stroke-linecap="round"/>
            <path d="M70 33L66 44" stroke="#4B5563" stroke-width="3" stroke-linecap="round"/>
        @endif

        <!-- Cheek Stripes -->
        <path d="M20 62L32 64" stroke="#4B5563" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M19 70L30 70" stroke="#4B5563" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M100 62L88 64" stroke="#4B5563" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M101 70L90 70" stroke="#4B5563" stroke-width="2.5" stroke-linecap="round"/>

        <!-- Nose -->
        <polygon points="60,67 55,62 65,62" fill="#F472B6"/>

        <!-- ================= FACIAL EXPRESSIONS ================= -->
        @if($emotion === 'happy')
            <!-- Sparkling Happy Eyes with Blinking -->
            <g class="cat-eyes-blink">
                <ellipse cx="44" cy="56" rx="6" ry="7" fill="#2D2B38"/>
                <circle cx="42" cy="53" r="2.5" fill="white"/>
                <circle cx="46" cy="58" r="1.2" fill="white"/>

                <ellipse cx="76" cy="56" rx="6" ry="7" fill="#2D2B38"/>
                <circle cx="74" cy="53" r="2.5" fill="white"/>
                <circle cx="78" cy="58" r="1.2" fill="white"/>
            </g>

            <!-- Smile -->
            <path d="M52 70C55 75 60 75 60 71C60 75 65 75 68 70" stroke="#2D2B38" stroke-width="3" stroke-linecap="round"/>

            <!-- Pink Blush Cheeks -->
            <circle cx="34" cy="67" r="5.5" fill="#FBCFE8" opacity="0.85"/>
            <circle cx="86" cy="67" r="5.5" fill="#FBCFE8" opacity="0.85"/>

            <!-- Floating Sparkle -->
            <path d="M104 36L108 44L104 52L100 44Z" fill="#FBBF24"/>

        @elseif($emotion === 'alert')
            <!-- Alert Round Eyes -->
            <circle cx="44" cy="56" r="6.5" fill="#2D2B38"/>
            <circle cx="43" cy="54" r="2.5" fill="white"/>

            <circle cx="76" cy="56" r="6.5" fill="#2D2B38"/>
            <circle cx="75" cy="54" r="2.5" fill="white"/>

            <!-- Small Open 'o' Mouth -->
            <ellipse cx="60" cy="72" rx="4" ry="5" fill="#2D2B38"/>

            <!-- Cheeks -->
            <circle cx="34" cy="67" r="4.5" fill="#FDE68A" opacity="0.9"/>
            <circle cx="86" cy="67" r="4.5" fill="#FDE68A" opacity="0.9"/>

        @elseif($emotion === 'worried')
            <!-- Worried Eyes -->
            <circle cx="44" cy="56" r="5.5" fill="#2D2B38"/>
            <circle cx="43" cy="54" r="2" fill="white"/>

            <circle cx="76" cy="56" r="5.5" fill="#2D2B38"/>
            <circle cx="75" cy="54" r="2" fill="white"/>

            <!-- Wavy Worried Mouth -->
            <path d="M53 72C56 70 59 74 62 71C65 69 67 72 68 71" stroke="#2D2B38" stroke-width="3" stroke-linecap="round" fill="none"/>

            <!-- Sweat Drop on Right Temple -->
            <path d="M96 46C96 46 93 51 93 53C93 55 94.5 56.5 96.5 56.5C98.5 56.5 100 55 100 53C100 51 96 46 96 46Z" fill="#60A5FA"/>

            <!-- Pale Blush Cheeks -->
            <circle cx="34" cy="67" r="4.5" fill="#FECDD3" opacity="0.75"/>
            <circle cx="86" cy="67" r="4.5" fill="#FECDD3" opacity="0.75"/>

        @elseif($emotion === 'angy')
            <!-- Angry Brows -->
            <path d="M36 47L50 54" stroke="#2D2B38" stroke-width="4" stroke-linecap="round"/>
            <path d="M84 47L70 54" stroke="#2D2B38" stroke-width="4" stroke-linecap="round"/>

            <ellipse cx="44" cy="58" rx="5" ry="5" fill="#2D2B38"/>
            <ellipse cx="76" cy="58" rx="5" ry="5" fill="#2D2B38"/>

            <!-- Frown -->
            <path d="M53 73C56 69 64 69 67 73" stroke="#2D2B38" stroke-width="3.5" stroke-linecap="round"/>

            <!-- Red Cheeks -->
            <circle cx="33" cy="66" r="6" fill="#EF4444" opacity="0.6"/>
            <circle cx="87" cy="66" r="6" fill="#EF4444" opacity="0.6"/>

            <!-- Anger Mark 💢 -->
            <path d="M96 28L106 38M106 28L96 38" stroke="#EF4444" stroke-width="3" stroke-linecap="round"/>

        @elseif($emotion === 'wink')
            <!-- Left Wink -->
            <path d="M38 58C42 52 48 52 52 57" stroke="#2D2B38" stroke-width="4.5" stroke-linecap="round" fill="none"/>
            <path d="M51 53L55 49" stroke="#2D2B38" stroke-width="3" stroke-linecap="round"/>

            <!-- Right Eye -->
            <ellipse cx="76" cy="56" rx="6.5" ry="7.5" fill="#2D2B38"/>
            <circle cx="73" cy="53" r="2.8" fill="white"/>
            <circle cx="78" cy="58" r="1.5" fill="white"/>

            <!-- Smile -->
            <path d="M53 70C56 74 64 74 67 70" stroke="#2D2B38" stroke-width="3.5" stroke-linecap="round"/>

            <circle cx="34" cy="66" r="5" fill="#FDE68A" opacity="0.9"/>
            <circle cx="86" cy="66" r="5" fill="#FDE68A" opacity="0.9"/>

        @elseif($emotion === 'sad')
            <!-- Sad Tearful Eyes -->
            <ellipse cx="44" cy="57" rx="6.5" ry="7" fill="#2D2B38"/>
            <ellipse cx="76" cy="57" rx="6.5" ry="7" fill="#2D2B38"/>
            <circle cx="43" cy="54" r="3" fill="#93C5FD"/>
            <circle cx="45" cy="59" r="1.5" fill="white"/>
            <circle cx="75" cy="54" r="3" fill="#93C5FD"/>
            <circle cx="77" cy="59" r="1.5" fill="white"/>

            <!-- Teardrop -->
            <path d="M36 67C36 67 33 72 33 74C33 76 34.5 77.5 36.5 77.5C38.5 77.5 40 76 40 74C40 72 36 67 36 67Z" fill="#60A5FA"/>

            <!-- Downward Mouth -->
            <path d="M54 73C57 70 63 70 66 73" stroke="#2D2B38" stroke-width="3.5" stroke-linecap="round"/>

            <circle cx="34" cy="67" r="4.5" fill="#FECDD3" opacity="0.8"/>
            <circle cx="86" cy="67" r="4.5" fill="#FECDD3" opacity="0.8"/>

        @else
            <!-- Relaxed Zen Eyes (⌒ ⌒) -->
            <path d="M38 57C41 52 49 52 52 57" stroke="#2D2B38" stroke-width="3.5" stroke-linecap="round" fill="none"/>
            <path d="M68 57C71 52 79 52 82 57" stroke="#2D2B38" stroke-width="3.5" stroke-linecap="round" fill="none"/>

            <!-- Soft Smile -->
            <path d="M54 70C57 73 63 73 66 70" stroke="#2D2B38" stroke-width="3" stroke-linecap="round"/>

            <!-- Soft Lilac Cheeks -->
            <circle cx="34" cy="66" r="5" fill="#DDD6FE" opacity="0.8"/>
            <circle cx="86" cy="66" r="5" fill="#DDD6FE" opacity="0.8"/>
        @endif

        <!-- ================= ROLE ACCESSORIES ================= -->
        @if($role === 'payroll')
            <!-- Green Cashier Sunvisor -->
            <path d="M30 38C30 38 48 30 60 30C72 30 90 38 90 38L96 46C74 38 46 38 24 46L30 38Z" fill="#10B981" stroke="#2D2B38" stroke-width="3"/>
            
            <!-- Golden Coin Bandana -->
            <path d="M42 96L60 114L78 96C72 99 48 99 42 96Z" fill="#F59E0B" stroke="#2D2B38" stroke-width="3"/>
            <circle cx="60" cy="103" r="4.5" fill="#FEF3C7" stroke="#2D2B38" stroke-width="1.5"/>
            <path d="M60 100V106" stroke="#2D2B38" stroke-width="1.5"/>

        @elseif($role === 'talent')
            <!-- Cute Glasses -->
            <circle cx="44" cy="56" r="10" stroke="#2D2B38" stroke-width="3.5" fill="none"/>
            <circle cx="76" cy="56" r="10" stroke="#2D2B38" stroke-width="3.5" fill="none"/>
            <path d="M54 56H66" stroke="#2D2B38" stroke-width="3.5"/>

            <!-- Mint Scout Bandana with ID Badge -->
            <path d="M42 96L60 114L78 96C72 99 48 99 42 96Z" fill="#065F46" stroke="#2D2B38" stroke-width="3"/>
            <rect x="55" y="99" width="10" height="8" rx="1.5" fill="#D1FAE5" stroke="#2D2B38" stroke-width="1.5"/>

        @elseif($role === 'bookkeeper')
            <!-- Monocle on Left Eye -->
            <circle cx="43" cy="56" r="11" stroke="#F59E0B" stroke-width="3.5" fill="#FEF3C7" fill-opacity="0.25"/>
            <path d="M36 64L28 72" stroke="#2D2B38" stroke-width="4" stroke-linecap="round"/>

            <!-- Lilac Bandana with Pencil -->
            <path d="M42 96L60 114L78 96C72 99 48 99 42 96Z" fill="#7C3AED" stroke="#2D2B38" stroke-width="3"/>
            <circle cx="60" cy="103" r="2" fill="#FEF3C7"/>

        @else
            <!-- Standard Color-Coded Bandana -->
            @php
                $bandanaColor = match($emotion) {
                    'happy' => '#10B981',
                    'alert' => '#F59E0B',
                    'worried', 'sad', 'angy' => '#F43F5E',
                    'wink' => '#8B5CF6',
                    default => '#8B5CF6',
                };
            @endphp
            <path d="M42 96L60 114L78 96C72 99 48 99 42 96Z" fill="{{ $bandanaColor }}" stroke="#2D2B38" stroke-width="3"/>
            <circle cx="60" cy="103" r="2" fill="white"/>
        @endif

    </svg>
</div>
