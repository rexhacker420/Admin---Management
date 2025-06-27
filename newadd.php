<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .center-button {
            justify-content: center !important;
            margin-top: 100px;
        }


        .label-row {
            background-color: #212529;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .label-row .col {
            border-right: 1px solid #444;
        }

        .label-row .col:last-child {
            border-right: none;
        }

        .delete-btn {
            cursor: pointer;
            color: red;
            font-weight: bold;
        }

        /* Floating Form Style */
        .floating-form {
            position: absolute;
            top: 120px;
            left: 300px;
            z-index: 1050;
            background-color: #343a40;
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
            display: none;
        }

        .form-control {
            background-color: #495057;
            color: white;
            border: none;
        }
    </style>
</head>

<body class="bg-dark text-white">
    <!-- Sidebar -->
    <div class="sidebar shadow">
        <div>
            <h4 class="text-center py-3 border-bottom">Admin Panel</h4>
            <ul class="nav flex-column px-2">
                <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Status</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Setting</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
            </ul>
        </div>
        <div class="sidebar-footer">
            Developer ❤️ by Sanjeet Kalyan
        </div>
    </div>

    <!-- Floating Add Form -->
    <div class="floating-form" id="addForm">
        <h5>Add New User</h5>
        <div class="mb-2">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" id="inputName">
        </div>
        <div class="mb-2">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" id="inputUsername">
        </div>
        <div class="mb-2">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" id="inputPassword">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail">
        </div>
        <div class="d-flex justify-content-between">
            <button class="btn btn-success" onclick="submitForm()">Done</button>
            <button class="btn btn-secondary" onclick="closeForm()">Close</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Existing Button Row -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" onclick="showForm()">Add New Row</button>
        </div>


        <div id="tableContainer">
            <div class="row label-row text-center">
                <div class="col">Serial</div>
                <div class="col">Status</div>
                <div class="col">Name</div>
                <div class="col">Username</div>
                <div class="col">Password</div>
                <div class="col">Email</div>
                <div class="col">Address</div>
                <div class="col">Device</div>
                <div class="col">Action</div>
            </div>

            <div id="dataRows">
                <!-- Example row -->
                <div class="row text-center py-2 border-bottom align-items-center">
                    <div class="col">1</div>
                    <div class="col text-success">Active</div>
                    <div class="col">John Doe</div>
                    <div class="col">johnd</div>
                    <div class="col">••••••••</div>
                    <div class="col">john@example.com</div>
                    <div class="col">New York</div>
                    <div class="col">Chrome</div>
                    <div class="col">
                        <span class="delete-btn" onclick="confirmDelete(this)">🗑</span>
                        <span class="text-warning" style="cursor:pointer" onclick="showUpdateForm(this)">✏️</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="updateFormContainer" class="position-absolute bg-dark p-4 rounded shadow text-white"
        style="top: 20%; left: 30%; width: 40%; display: none; z-index: 999;">
        <h5 class="mb-3">Update Record</h5>
        <form onsubmit="submitUpdateForm(event)">
            <div class="mb-3">
                <label class="form-label">Select Serial</label>
                <select id="updateSerialSelect" class="form-select" required></select>
            </div>
            <div class="mb-3">
                <label class="form-label">Select Name</label>
                <input type="email" class="form-control" id="updateNameSelect" />
            </div>
            <div class="mb-3">
                <label class="form-label">New Status</label>
                <select id="updateStatus" class="form-select">
                    <option value="">-- Select --</option>
                    <option value="Active">Active</option>
                    <option value="Pending">Pending</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">New Email</label>
                <input type="email" class="form-control" id="updateEmail" />
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Update</button>
                <button type="button" class="btn btn-secondary" onclick="closeUpdateForm()">Cancel</button>
            </div>
        </form>
    </div>


    <div class="d-flex justify-content-center mb-3">
        <button class="btn btn-primary" onclick="showForm()">Add New Row</button>
    </div>


    <script>
        let serialCounter = 2;

        function showForm() {
            document.getElementById("addForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("addForm").style.display = "none";
        }

        function updateSerialNumbers() {
            const rows = document.querySelectorAll("#dataRows .row");
            const tableContainer = document.getElementById("tableContainer");
            const emptyState = document.getElementById("emptyState");

            rows.forEach((row, index) => {
                const serialCol = row.querySelector(".col");
                if (serialCol) {
                    serialCol.textContent = index + 1;
                }
            });

            // Toggle views
            if (rows.length === 0) {
                tableContainer.style.display = "none";
                emptyState.style.display = "block";
            } else {
                tableContainer.style.display = "block";
                emptyState.style.display = "none";
            }
        }




        function submitForm() {
            const name = document.getElementById("inputName").value;
            const username = document.getElementById("inputUsername").value;
            const password = document.getElementById("inputPassword").value;
            const email = document.getElementById("inputEmail").value;

            if (!name || !username || !password || !email) {
                alert("Please fill in all fields.");
                return;
            }

            const row = document.createElement("div");
            row.className = "row text-center py-2 border-bottom align-items-center";
            row.innerHTML = `
        <div class="col">${serialCounter++}</div>
        <div class="col text-secondary">Pending</div>
        <div class="col">${name}</div>
        <div class="col">${username}</div>
        <div class="col">••••••••</div>
        <div class="col">${email}</div>
        <div class="col">-</div>
        <div class="col">-</div>
        <div class="col"><span class="delete-btn" onclick="confirmDelete(this)">🗑</span></div>
      `;
            document.getElementById("dataRows").appendChild(row);

            // Reset form
            document.getElementById("inputName").value = "";
            document.getElementById("inputUsername").value = "";
            document.getElementById("inputPassword").value = "";
            document.getElementById("inputEmail").value = "";

            closeForm();
        }

        function confirmDelete(element) {
            if (confirm("Are you sure you want to delete this row?")) {
                element.closest(".row").remove();
                updateSerialNumbers();
                serialCounter--; // keep global counter consistent
            }
        }

        function showUpdateForm(button) {
            // Show the form
            document.getElementById("updateFormContainer").style.display = "block";

            // Optionally pre-fill Serial based on clicked row
            const row = button.closest(".row");
            const serial = row.querySelector(".col").textContent.trim();
            document.getElementById("updateSerial").value = serial;
        }

        function hideUpdateForm() {
            document.getElementById("updateFormContainer").style.display = "none";
        }

        // Called when 'Done' is clicked
        function openUpdateForm() {
            // Show form
            const form = document.getElementById("updateFormContainer");
            form.style.display = "block";

            const serialSelect = document.getElementById("updateSerialSelect");
            const nameSelect = document.getElementById("updateNameSelect");

            serialSelect.innerHTML = "";
            nameSelect.innerHTML = "";

            const rows = document.querySelectorAll("#dataRows .row");

            rows.forEach((row, index) => {
                const serialOption = document.createElement("option");
                serialOption.value = index;
                serialOption.textContent = index + 1;
                serialSelect.appendChild(serialOption);

                const name = row.querySelectorAll(".col")[2].textContent.trim();
                const nameOption = document.createElement("option");
                nameOption.value = index;
                nameOption.textContent = name;
                nameSelect.appendChild(nameOption);
            });
        }

        function closeUpdateForm() {
            document.getElementById("updateFormContainer").style.display = "none";
        }

        function submitUpdateForm(e) {
            e.preventDefault();

            const serialIndex = document.getElementById("updateSerialSelect").value;
            const nameIndex = document.getElementById("updateNameSelect").value;

            if (serialIndex !== nameIndex) {
                alert("Serial and Name do not match!");
                return;
            }

            const row = document.querySelectorAll("#dataRows .row")[serialIndex];
            const cols = row.querySelectorAll(".col");

            const newStatus = document.getElementById("updateStatus").value;
            const newEmail = document.getElementById("updateEmail").value;

            if (newStatus) {
                cols[1].textContent = newStatus;
                cols[1].className = "col text-" + (newStatus === "Active" ? "success" : newStatus === "Pending" ? "warning" : "secondary");
            }

            if (newEmail) {
                cols[5].textContent = newEmail;
            }

            closeUpdateForm();
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>




<script>
        let serialCounter = 2;

        function showForm() {
            document.getElementById("addForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("addForm").style.display = "none";
        }

        function updateSerialNumbers() {
            const rows = document.querySelectorAll("#dataRows .row");
            const tableContainer = document.getElementById("tableContainer");
            const emptyState = document.getElementById("emptyState");

            rows.forEach((row, index) => {
                const serialCol = row.querySelector(".col");
                if (serialCol) {
                    serialCol.textContent = index + 1;
                }
            });

            // Toggle views
            if (rows.length === 0) {
                tableContainer.style.display = "none";
                emptyState.style.display = "block";
            } else {
                tableContainer.style.display = "block";
                emptyState.style.display = "none";
            }
        }




        function submitForm() {
            const name = document.getElementById("inputName").value;
            const username = document.getElementById("inputUsername").value;
            const password = document.getElementById("inputPassword").value;
            const email = document.getElementById("inputEmail").value;

            if (!name || !username || !password || !email) {
                alert("Please fill in all fields.");
                return;
            }

            const row = document.createElement("div");
            row.className = "row text-center py-2 border-bottom align-items-center";
            row.innerHTML = `
        <div class="col">${serialCounter++}</div>
        <div class="col text-secondary">Pending</div>
        <div class="col">${name}</div>
        <div class="col">${username}</div>
        <div class="col">••••••••</div>
        <div class="col">${email}</div>
        <div class="col">-</div>
        <div class="col">-</div>
        <div class="col"><span class="delete-btn" onclick="confirmDelete(this)">🗑</span></div>
        <span class="text-warning" style="cursor:pointer" onclick="openUpdateForm()">✏️</span>
      `;
            document.getElementById("dataRows").appendChild(row);

            // Reset form
            document.getElementById("inputName").value = "";
            document.getElementById("inputUsername").value = "";
            document.getElementById("inputPassword").value = "";
            document.getElementById("inputEmail").value = "";

            closeForm();
        }

        function confirmDelete(element) {
            if (confirm("Are you sure you want to delete this row?")) {
                element.closest(".row").remove();
                updateSerialNumbers();
                serialCounter--; // keep global counter consistent
            }
        }

        function showUpdateForm(button) {
            // Show the form
            document.getElementById("updateFormContainer").style.display = "block";

            // Optionally pre-fill Serial based on clicked row
            const row = button.closest(".row");
            const serial = row.querySelector(".col").textContent.trim();
            document.getElementById("updateSerial").value = serial;
        }

        function hideUpdateForm() {
            document.getElementById("updateFormContainer").style.display = "none";
        }

        // Called when 'Done' is clicked
        function openUpdateForm() {
            // Show form
            const form = document.getElementById("updateFormContainer");
            form.style.display = "block";

            const serialSelect = document.getElementById("updateSerialSelect");
            const nameSelect = document.getElementById("updateNameSelect");

            serialSelect.innerHTML = "";
            nameSelect.innerHTML = "";

            const rows = document.querySelectorAll("#dataRows .row");

            rows.forEach((row, index) => {
                const serialOption = document.createElement("option");
                serialOption.value = index;
                serialOption.textContent = index + 1;
                serialSelect.appendChild(serialOption);

                const name = row.querySelectorAll(".col")[2].textContent.trim();
                const nameOption = document.createElement("option");
                nameOption.value = index;
                nameOption.textContent = name;
                nameSelect.appendChild(nameOption);
            });
        }

        function closeUpdateForm() {
            document.getElementById("updateFormContainer").style.display = "none";
        }

        function submitUpdateForm(e) {
            e.preventDefault();

            const serialIndex = document.getElementById("updateSerialSelect").value;
            const nameIndex = document.getElementById("updateNameSelect").value;

            if (serialIndex !== nameIndex) {
                alert("Serial and Name do not match!");
                return;
            }

            const row = document.querySelectorAll("#dataRows .row")[serialIndex];
            const cols = row.querySelectorAll(".col");

            const newStatus = document.getElementById("updateStatus").value;
            const newEmail = document.getElementById("updateEmail").value;

            if (newStatus) {
                cols[1].textContent = newStatus;
                cols[1].className = "col text-" + (newStatus === "Active" ? "success" : newStatus === "Pending" ? "warning" : "secondary");
            }

            if (newEmail) {
                cols[5].textContent = newEmail;
            }

            closeUpdateForm();
        }


         // Zoom Control
        // Add this to your existing script
        const zoomController = {
            init() {
                // Prevent keyboard zoom
                document.addEventListener('keydown', this.handleKeyZoom);

                // Prevent wheel zoom
                document.addEventListener('wheel', this.handleWheelZoom, { passive: false });

                // Prevent touch zoom
                document.addEventListener('touchmove', this.handleTouchZoom, { passive: false });
            },

            handleKeyZoom(e) {
                if ((e.ctrlKey || e.metaKey) &&
                    (e.key === '+' || e.key === '-' || e.key === '0' ||
                        e.key === '=' || e.key === '_')) {
                    e.preventDefault();
                }
            },

            handleWheelZoom(e) {
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                }
            },

            handleTouchZoom(e) {
                if (e.touches.length > 1) {
                    e.preventDefault();
                }
            }
        };

        // Initialize when your cursor initializes
        window.onload = function () {
            zoomController.init();
            // Your existing load code...
        };
    </script>