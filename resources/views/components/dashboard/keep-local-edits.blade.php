<div class="mb-3">
    <input type="hidden" name="keep_local_edits" value="0">
    <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" name="keep_local_edits" value="1" @checked(old('keep_local_edits', true))>
        <span class="form-check-label">Keep local edits</span>
    </label>
    <small class="form-hint">This item is imported. While this is on, the import leaves it alone. Turn it off and save to let the import overwrite it again.</small>
</div>
