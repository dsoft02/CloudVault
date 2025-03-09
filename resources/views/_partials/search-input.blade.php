<div class="col-sm-6 col-12 col-auto file-1">
    <form action="{{ route('search') }}" method="GET">
        <div class="input-group mb-2">
            <input type="text" name="query" class="form-control"
                placeholder="Search files and folders..." value="{{ $query ?? '' }}">
            <button class="btn ripple btn-primary" type="submit">Search</button>
        </div>
    </form>
</div>