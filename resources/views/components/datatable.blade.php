<div class="card">
    <div class="card-body">

        <!-- TOP BAR -->
        <div class="d-flex justify-content-between align-items-center mb-3">

            <!-- Search -->
            <input type="text"
                   class="form-control datatable-search"
                   data-table="{{ $id }}"
                   placeholder="Search..."
                   style="width:250px;">

            <!-- Add Button -->
            @if(isset($addRoute))
                <a href="{{ $addRoute }}"
                   class="btn btn-primary">
                    + Add {{ $addLabel ?? 'New' }}
                </a>
            @endif

        </div>

        <table class="table table-bordered datatable"
               id="{{ $id }}"
               data-url="{{ $route }}"
               data-model="{{ $model }}"
               data-columns='@json($columns)'>

            <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column['label'] }}</th>
                    @endforeach
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>

        <div class="datatable-pagination mt-3"
             data-table="{{ $id }}"></div>

    </div>
</div>

