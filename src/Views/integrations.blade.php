@extends('laralum::layouts.master')
@section('icon', 'ion-android-share-alt')
@section('title', __('laralum_social::general.social_integrations'))
@section('subtitle', __('laralum_social::general.social_integrations'))
@section('css')
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.min.css">
@endsection
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_social::general.home')</a></li>
        <li><span>@lang('laralum_social::general.social_integrations')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-4@l"></div>
            <div class="uk-width-1-1@s uk-width-1-2@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_social::general.social_integrations')
                    </div>
                    <div class="uk-card-body">
                        <div uk-grid>
                            @php
                                $exceptions = [
                                    'github' => 'github-circle',
                                ];
                            @endphp
                            @foreach ($providers as $provider)
                                @php
                                    $icon = array_key_exists($provider, $exceptions) ? $exceptions[$provider] : $provider;
                                    $link = \Laralum\Social\Models\Social::where(['user_id' => Auth::id(), 'provider' => $provider])->first()
                                @endphp
                                <div class="uk-width-1-1@s uk-width-1-2@m">
                                    <center>
                                        @if ($link)
                                            <a href="{{ route('laralum_public::social.unlink', ['provider' => $provider]) }}" class="uk-button uk-button-default">
                                                <i class="mdi mdi-{{ $icon }}"></i> @lang('laralum_social::general.unlink', ['provider' => $provider])
                                            </a>
                                            <p>
                                                @lang('laralum_social::general.already_linked', ['date' => $link->created_at->diffForHumans()])
                                            </p>
                                        @else
                                            <a href="{{ route('laralum_public::social', ['provider' => $provider]) }}" class="uk-button uk-button-default">
                                                <i class="mdi mdi-{{ $icon }}"></i> {{ $provider }}
                                            </a>
                                            <p>
                                                @lang('laralum_social::general.not_linked')
                                            </p>
                                        @endif
                                    </center>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@s uk-width-1-4@l"></div>
        </div>
    </div>
@endsection
