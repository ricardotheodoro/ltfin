<x-auth-layout>

    <!--begin::Signin Form-->
    <form method="POST" action="{{ route('login') }}" class="form w-100" novalidate="novalidate" id="kt_sign_in_form">
        @csrf

        <!--begin::Heading-->
        <div class="text-center mb-10">
            <h1 class="text-dark mb-3">
                {{ __('Entrar no LTFin') }}
            </h1>

            <div class="text-gray-400 fw-bold fs-4">
                {{ __('Informe suas credenciais para continuar') }}
            </div>
        </div>
        <!--end::Heading-->

        @if (session('status'))
            <div class="mb-10 bg-light-success p-5 rounded">
                <div class="text-success">{{ session('status') }}</div>
            </div>
        @endif

        <!--begin::Input group: email-->
        <div class="fv-row mb-10">
            <label for="email" class="form-label fs-6 fw-bolder text-dark">{{ __('E-mail') }}</label>

            <input
                id="email"
                class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username"
                required
                autofocus
            />

            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group: email-->

        <!--begin::Input group: password-->
        <div class="fv-row mb-10">
            <div class="d-flex flex-stack mb-2">
                <label for="password" class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Senha') }}</label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">
                        {{ __('Esqueceu a senha?') }}
                    </a>
                @endif
            </div>

            <input
                id="password"
                class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                type="password"
                name="password"
                autocomplete="current-password"
                required
            />

            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <!--end::Input group: password-->

        <!--begin::Input group: remember-->
        <div class="fv-row mb-10">
            <label class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}/>
                <span class="form-check-label fw-bold text-gray-700 fs-6">{{ __('Lembrar de mim') }}</span>
            </label>
        </div>
        <!--end::Input group: remember-->

        <!--begin::Actions-->
        <div class="text-center">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                @include('partials.general._button-indicator', ['label' => __('Entrar')])
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Signin Form-->

</x-auth-layout>
