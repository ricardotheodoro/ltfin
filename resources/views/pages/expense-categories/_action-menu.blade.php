<div class="d-flex justify-content-end flex-shrink-0">
    <a href="{{ route('expense-categories.edit', $model) }}"
       class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
       title="{{ __('Editar') }}">
        {!! theme()->getSvgIcon('icons/duotune/art/art005.svg', 'svg-icon-3') !!}
    </a>

    <form method="POST"
          action="{{ route('expense-categories.destroy', $model) }}"
          class="d-inline"
          onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta categoria?') }}');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                title="{{ __('Excluir') }}">
            {!! theme()->getSvgIcon('icons/duotune/general/gen027.svg', 'svg-icon-3') !!}
        </button>
    </form>
</div>
