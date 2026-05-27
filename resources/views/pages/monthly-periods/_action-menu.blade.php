<div class="d-flex justify-content-end flex-shrink-0">
    <a href="{{ route('monthly-periods.show', $model) }}"
       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
       title="{{ __('Abrir lançamentos') }}">
        {!! theme()->getSvgIcon('icons/duotune/general/gen019.svg', 'svg-icon-3') !!}
    </a>

    <form method="POST"
          action="{{ route('monthly-periods.destroy', $model) }}"
          class="d-inline"
          onsubmit="return confirm('{{ __('Excluir este mês e todos os lançamentos?') }}');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                title="{{ __('Excluir') }}">
            {!! theme()->getSvgIcon('icons/duotune/general/gen027.svg', 'svg-icon-3') !!}
        </button>
    </form>
</div>
