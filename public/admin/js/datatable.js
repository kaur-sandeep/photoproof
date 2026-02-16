document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".datatable").forEach(table => {

        let url = table.dataset.url;
        let model = table.dataset.model;
        let columns = JSON.parse(table.dataset.columns);
        let tableId = table.id;

        let currentSearch = '';

        loadData();

        // SEARCH
        let searchInput = document.querySelector(`.datatable-search[data-table="${tableId}"]`);
        if(searchInput){
            searchInput.addEventListener('keyup', function () {
                currentSearch = this.value;
                loadData();
            });
        }

        // function loadData(page = 1) {
        //     fetch(`${url}?page=${page}&search=${currentSearch}`)
        //         .then(res => res.json())
        //         .then(response => {

        //             let tbody = table.querySelector("tbody");
        //             tbody.innerHTML = '';

        //             let startSerial = (page - 1) * response.per_page;
        //             imageCount = 0;
        //             response.data.forEach((row, index) => {
        //                 let tr = document.createElement("tr");

        //                 // 1. Serial number
        //                 tr.innerHTML += `<td>${startSerial + index + 1}</td>`;
        //                  //2. Profile image 
        //                 let imageContent = row.profile_image
        //                     ? `<img src="/storage/profile/${row.profile_image}" width="40" height="40" class="rounded-circle">`
        //                     : `<span class="text-muted">No Image</span>`;

        //                 // Append the profile image content and the profile image status
        //                 tr.innerHTML += `<td>${imageContent}</td>`;


        //                 // 3. Name
        //                 tr.innerHTML += `<td>${row.name ?? ''}</td>`;

        //                 // 4. Email
        //                 tr.innerHTML += `<td>${row.email ?? ''}</td>`;

        //                 // 5. Number / Phone
        //                 tr.innerHTML += `<td>${row.phone_number ?? ''}</td>`;
                        

        //                 // 6. Status button
        //                 let statusButton = '';
        //                 let newStatus = '';
        //                 if (row.status == 0 || row.status === '0') {
        //                     statusButton = `<button class="btn btn-sm btn-success toggle-status-btn" data-id="${row.id}" data-status="${row.status}">Active</button>`;
        //                     newStatus = 1;
        //                 } else if (row.status == 1 || row.status === '1') {
        //                     statusButton = `<button class="btn btn-sm btn-warning toggle-status-btn" data-id="${row.id}" data-status="${row.status}">Inactive</button>`;
        //                     newStatus = 0;
        //                 } else if(row.status == -1 || row.status === '-1') {
        //                     statusButton = `<span class="badge bg-danger">Deleted</span>`;
        //                 }
        //                 tr.innerHTML += `<td>${statusButton}</td>`;

        //                 // 7. Actions
        //                 let actionHtml = `
        //                     <td>
        //                         <a href="/admin/${model.toLowerCase()}/edit/${row.id}" class="btn btn-sm btn-primary">Edit</a>
        //                         <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
        //                     </td>
        //                 `;
        //                 tr.innerHTML += actionHtml;

        //                 tbody.appendChild(tr);

        //                 if(row.status != -1) {
        //                     attachToggleStatusEvent(row.id, row.status, newStatus);
        //                 }
        //             });

        //             attachActionEvents();
        //             renderPagination(response, page);
        //             renderRecordCount(response);
        //             renderImageCount(imageCount);
        //         });
        // }

        function loadData(page = 1) {
    fetch(`${url}?page=${page}&search=${currentSearch}`)
        .then(res => res.json())
        .then(response => {

            let tbody = table.querySelector("tbody");
            tbody.innerHTML = '';

            let startSerial = (page - 1) * response.per_page;
            imageCount = 0;

            response.data.forEach((row, index) => {
                let tr = document.createElement("tr");

                // 1. Serial number
                tr.innerHTML += `<td>${startSerial + index + 1}</td>`;

                // 2. Profile image
                let imageContent = row.profile_image
                    ? `<img src="/storage/profile/${row.profile_image}" width="40" height="40" class="rounded-circle">`
                    : `<span class="text-muted">No Image</span>`;
                tr.innerHTML += `<td>${imageContent}</td>`;

                // 3. Name
                tr.innerHTML += `<td>${row.name ?? ''}</td>`;

                // 4. Email
                tr.innerHTML += `<td>${row.email ?? ''}</td>`;

                // 5. Number / Phone
                tr.innerHTML += `<td>${row.phone_number ?? ''}</td>`;

                // 6. Image count
                // tr.innerHTML += `<td>${row.photo_count ?? 0}</td>`; // Display the number of images uploaded by the user

                tr.innerHTML += `<td class="photo-count" data-user-id="${row.id}">${row.photo_count ?? 0}</td>`;
                // 7. Status button
                let statusButton = '';
                let newStatus = '';
                if (row.status == 0 || row.status === '0') {
                    statusButton = `<button class="btn btn-sm btn-success toggle-status-btn" data-id="${row.id}" data-status="${row.status}">Active</button>`;
                    newStatus = 1;
                } else if (row.status == 1 || row.status === '1') {
                    statusButton = `<button class="btn btn-sm btn-warning toggle-status-btn" data-id="${row.id}" data-status="${row.status}">Inactive</button>`;
                    newStatus = 0;
                } else if(row.status == -1 || row.status === '-1') {
                    statusButton = `<span class="badge bg-danger">Deleted</span>`;
                }
                tr.innerHTML += `<td>${statusButton}</td>`;

                // 8. Actions
                let actionHtml = `
                    <td>
                        <a href="/admin/${model.toLowerCase()}/edit/${row.id}" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
                    </td>
                `;
                tr.innerHTML += actionHtml;

   

                tbody.appendChild(tr);

                if(row.status != -1) {
                    attachToggleStatusEvent(row.id, row.status, newStatus);
                }
                let photoCountCell = tr.querySelector('.photo-count');
                        if (photoCountCell) {
                            photoCountCell.addEventListener('click', function () {
                                let userId = this.dataset.userId; // Get user ID
                                window.location.href = `${model.toLowerCase()}/photos/${userId}`; // Redirect to photos page
                            });
                        }
            });

            attachActionEvents();
            renderPagination(response, page);
            renderRecordCount(response);
            renderImageCount(imageCount);
        });
}


        // STATUS TOGGLE EVENT
        function attachToggleStatusEvent(id, currentStatus, newStatus) {
            let statusBtn = document.querySelector(`.toggle-status-btn[data-id="${id}"]`);
            if (statusBtn) {
                statusBtn.addEventListener("click", function () {
                    let msg = currentStatus == 1 || currentStatus === '1' 
                        ? "Are you sure you want to deactivate this user?" 
                        : "Are you sure you want to activate this user?";
                    if (confirm(msg)) {
                        updateStatus(id, newStatus);
                    }
                });
            }
        }

        // DELETE CONFIRM
        function attachActionEvents() {
            document.querySelectorAll(".delete-btn").forEach(btn => {
                btn.addEventListener("click", function () {
                    let id = this.dataset.id;
                    if (confirm("Are you sure you want to delete this user?")) {
                        updateStatus(id, -1); // Mark as deleted
                    }
                });
            });
        }

        // AJAX UPDATE STATUS
        function updateStatus(id, status) {
            fetch(`/admin/${model.toLowerCase()}/update/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id: id, status: status })
            })
            .then(res => res.json())
            .then(() => loadData()); // Reload table
        }

        // PAGINATION
        function renderPagination(response, currentPage) {
            let paginationDiv = document.querySelector(`.datatable-pagination[data-table="${tableId}"]`);
            if(!paginationDiv) return;
            paginationDiv.innerHTML = '';

            for (let i = 1; i <= response.last_page; i++) {
                let btnClass = i == currentPage ? 'btn-primary' : 'btn-secondary';
                paginationDiv.innerHTML += `
                    <button class="btn btn-sm ${btnClass} page-btn" data-page="${i}">${i}</button>
                `;
            }

            paginationDiv.querySelectorAll('.page-btn')
                .forEach(btn => {
                    btn.addEventListener('click', function () {
                        loadData(this.dataset.page);
                    });
                });
        }

        // RECORD COUNT
        function renderRecordCount(response) {
    let recordCountDiv = document.querySelector(`.datatable-record-count[data-table="${tableId}"]`);
    if (!recordCountDiv) return;

    recordCountDiv.innerHTML = `Total Users: ${response.total} | Showing ${response.data.length} per page`;
}
  function renderImageCount(count) {
            let imageCountDiv = document.querySelector(`.datatable-image-count[data-table="${tableId}"]`);
            if (!imageCountDiv) return;

            console.log(`Image count: ${count}`);  // Debugging image count
            imageCountDiv.innerHTML = `Users with Profile Images: ${count}`;
        }

    });

});