document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".datatable").forEach(table => {

        let url = table.dataset.url;
        let model = table.dataset.model;
        let columns = JSON.parse(table.dataset.columns);
        let tableId = table.id;

        let currentSearch = '';

        loadData();

        // SEARCH
        document.querySelector(`.datatable-search[data-table="${tableId}"]`)
            .addEventListener('keyup', function () {
                currentSearch = this.value;
                loadData();
            });

        function loadData(page = 1) {
            fetch(`${url}?page=${page}&search=${currentSearch}`)
                .then(res => res.json())
                .then(response => {

                    let tbody = table.querySelector("tbody");
                    tbody.innerHTML = '';

                    response.data.forEach(row => {

                        let tr = document.createElement("tr");

                        columns.forEach(col => {
                            tr.innerHTML += `<td>${row[col.field] ?? ''}</td>`;
                        });

                        // STATUS BADGE
                        let badge = '';
                        if (row.status == 1)
                            badge = `<span class="badge bg-success">Active</span>`;
                        else if (row.status == 0)
                            badge = `<span class="badge bg-warning">Inactive</span>`;
                        else
                            badge = `<span class="badge bg-danger">Deleted</span>`;

                        tr.innerHTML += `<td>${badge}</td>`;

                        // ACTION BUTTONS
                        tr.innerHTML += `
                            <td>
                               <a href="/admin/users/edit/${row.id}"class="btn btn-sm btn-primary">Edit</a>

                                <button class="btn btn-sm btn-danger action-btn"
                                    data-id="${row.id}"
                                    data-status="-1">Delete</button>

                                <button class="btn btn-sm btn-success action-btn"
                                    data-id="${row.id}"
                                    data-status="1">Active</button>

                                <button class="btn btn-sm btn-warning action-btn"
                                    data-id="${row.id}"
                                    data-status="0">Inactive</button>
                            </td>
                        `;

                        tbody.appendChild(tr);
                    });

                    attachActionEvents();
                    renderPagination(response);
                });
        }

        function attachActionEvents() {
            document.querySelectorAll(".action-btn").forEach(btn => {

                btn.addEventListener("click", function () {

                    fetch(`/admin/${model.toLowerCase()}/update/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            id: this.dataset.id,
                            status: this.dataset.status
                        })
                    })
                    .then(res => res.json())
                    .then(() => loadData());
                });
            });
        }

        function renderPagination(response) {
            let paginationDiv = document.querySelector(`.datatable-pagination[data-table="${tableId}"]`);
            paginationDiv.innerHTML = '';

            for (let i = 1; i <= response.last_page; i++) {
                paginationDiv.innerHTML += `
                    <button class="btn btn-sm btn-secondary page-btn"
                        data-page="${i}">
                        ${i}
                    </button>
                `;
            }

            paginationDiv.querySelectorAll('.page-btn')
                .forEach(btn => {
                    btn.addEventListener('click', function () {
                        loadData(this.dataset.page);
                    });
                });
        }

    });

});