<section class="newsletter-widget-center flex items-center justify-center min-h-[350px] pt-12 pb-12" @if ($config['background_image']) style="background-image: url({{ RvMedia::getImageUrl($config['background_image']) }}); background-repeat: no-repeat; background-size: cover;" @endif>
    <div class="w-full max-w-xl mx-auto bg-transparent">
        @if($config['title'])
            <h2 class="text-3xl md:text-4xl font-bold text-center text-white mb-4 uppercase tracking-wide">
                {!! BaseHelper::clean($config['title']) !!}
            </h2>
        @endif
        @if($config['subtitle'])
            <p class="text-center text-lg text-white/70 mb-8">
                {!! BaseHelper::clean($config['subtitle']) !!}
            </p>
        @endif
        <div class="newsletter-form-outer flex flex-col items-center w-full">
            <div class="w-full max-w-sm flex flex-col items-center">
                <form class="flex flex-col items-center gap-2" method="POST" action="{{ route('public.newsletter.subscribe') }}">
                    @csrf
                    <input type="email" name="email" placeholder="E-Mail" required class="w-full max-w-[400px] px-4 py-3 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/40 mx-auto" />
                    @if (!empty($config['note']))
                        <span class="newsletter-note text-xs text-center text-white/80 block mt-2">{!! BaseHelper::clean($config['note']) !!}</span>
                    @endif
                    <button type="submit" class="newsletter-submit-btn px-4 py-2 bg-black text-white font-semibold rounded-[10px] hover:bg-gray-800 transition-colors duration-200 mx-auto mt-1">{{ __('Subscribe') }}</button>
                </form>
            </div>
        </div>
        <div class="newsletter-message newsletter-success-message text-center mt-3" style="display: none"></div>
        <div class="newsletter-message newsletter-error-message text-center mt-3" style="display: none"></div>
    </div>
    <style>
        .newsletter-widget-center {
            background-color: #be9b5b;
            padding-top: 48px;
            padding-bottom: 48px;
        }
        .newsletter-form-outer input[type="email"],
        .newsletter-form-outer button[type="submit"],
        .newsletter-form-outer .newsletter-submit-btn {
            max-width: 160px;
            min-width: 100px;
            width: 100%;
            height: 38px;
            border-radius: 10px !important;
            margin-left: auto;
            margin-right: auto;
            display: block;
            font-size: 1rem;
        }
        .newsletter-form-outer .newsletter-note {
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
        .newsletter-form-outer form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        .newsletter-form-outer input[type="email"] {
            max-width: 400px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
    </style>
</section>
