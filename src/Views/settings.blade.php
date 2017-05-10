@php
    $settings = \Laralum\Social\Models\Settings::first();
@endphp
<div uk-grid>
    @can('update', \Laralum\Social\Models\Settings::class)
        <div class="uk-width-1-1@s uk-width-1-5@l"></div>
        <div class="uk-width-1-1@s uk-width-3-5@l">
            <form class="uk-form-horizontal" method="POST" action="{{ route('laralum::social.settings') }}">
                {{ csrf_field() }}
                <fieldset class="uk-fieldset">

                    <div class="uk-margin">
                        <label class="uk-form-label">@lang('laralum_social::general.enabled')</label>
                        <div class="uk-form-controls">
                            <label><input name="enabled" @if($settings->enabled) checked @endif class="uk-checkbox" type="checkbox"> @lang('laralum_social::general.enabled')</label><br />
                            <small class="uk-text-meta">@lang('laralum_social::general.enabled_hp')</small>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label">@lang('laralum_social::general.allow_register')</label>
                        <div class="uk-form-controls">
                            <label><input name="allow_register" @if($settings->allow_register) checked @endif class="uk-checkbox" type="checkbox"> @lang('laralum_social::general.allow_register')</label><br />
                            <small class="uk-text-meta">@lang('laralum_social::general.allow_register_hp')</small>
                        </div>
                    </div>

                    @php
                        $fields = [
                            'facebook_client_id', 'facebook_client_secret',
                            'twitter_client_id', 'twitter_client_secret',
                            'linkedin_client_id', 'linkedin_client_secret',
                            'google_client_id', 'google_client_secret',
                            'github_client_id', 'github_client_secret',
                            'bitbucket_client_id', 'bitbucket_client_secret',
                        ];
                    @endphp

                    @foreach($fields as $field)
                        @php $mfield = ucfirst(str_replace('_', ' ', $field)); @endphp
                        <div class="uk-margin">
                            <label class="uk-form-label">{{ $mfield }}</label>
                            <div class="uk-form-controls">
                                <input value="{{ old($field, $settings->$field ? decrypt($settings->$field) : '') }}" name="{{ $field }}" class="uk-input" type="text" placeholder="@lang('laralum_social::general.field_ph', ['field' => $mfield])">
                                <small class="uk-text-meta">@lang('laralum_social::general.field_hp', ['field' => $mfield])</small>
                            </div>
                        </div>
                        @if (explode('_', $field)[2] == 'secret')
                            <div class="uk-margin">
                                <label class="uk-form-label">{{ ucfirst(explode('_', $field)[0]) }} Callback URL</label>
                                <div class="uk-form-controls">
                                    <input value="{{ route('laralum_public::social.callback', ['provider' => explode('_', $field)[0]]) }}" class="uk-input" type="text" disabled>
                                    <small class="uk-text-meta">@lang('laralum_social::general.callback_hp', ['field' => explode('_', $field)[0]])</small>
                                </div>
                            </div>
                        @endif
                    @endforeach



                    <div class="uk-margin uk-align-right">
                        <button type="submit" class="uk-button uk-button-primary">
                            <span class="ion-forward"></span>&nbsp; @lang('laralum_social::general.save')
                        </button>
                    </div>

                </fieldset>
            </form>
        </div>
        <div class="uk-width-1-1@s uk-width-1-5@l"></div>
    @else
        <div class="uk-width-1-1">
            <div class="content-background">
                <div class="uk-section uk-section-small uk-section-default">
                    <div class="uk-container uk-text-center">
                        <h3>
                            <span class="ion-minus-circled"></span>
                            @lang('laralum_social::general.unauthorized_action')
                        </h3>
                        <p>
                            @lang('laralum_social::general.unauthorized_desc')
                        </p>
                        <p class="uk-text-meta">
                            @lang('laralum_social::general.contact_webmaster')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endcan
</div>
