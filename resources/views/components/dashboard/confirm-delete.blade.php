@props(['action', 'name', 'imported' => false])

@php($modalId = 'confirm-delete-' . md5($action))

<button type="button" class="btn btn-ghost-danger btn-sm" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Delete</button>

<div class="modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}-title" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                {!! $mdi('alert-outline', 'icon mb-2 text-danger icon-lg', 32) !!}
                <h3 id="{{ $modalId }}-title">Delete “{{ $name }}”?</h3>
                <div class="text-secondary">
                    This can't be undone.
                    @if ($imported)
                        It will come back the next time the import runs, unless the source removes it.
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col">
                            <form method="POST" action="{{ $action }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
