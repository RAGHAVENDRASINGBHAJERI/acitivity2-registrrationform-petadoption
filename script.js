function showTab(tabName, element) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabName).classList.add('active');
    if (element) element.classList.add('active');
    
    if (tabName === 'users') {
        loadUsers();
    }
}

function updateSalary(value) {
    document.getElementById('salaryValue').textContent = value;
}

document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = document.getElementById('userId').value;
    
    const url = userId ? 'api.php?action=update' : 'api.php?action=create';
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(userId ? 'User updated successfully!' : 'User registered successfully!');
            this.reset();
            document.getElementById('userId').value = '';
            updateSalary(50000);
            loadUsers();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
});

function loadUsers() {
    fetch('api.php?action=read')
    .then(response => response.json())
    .then(data => {
        const usersList = document.getElementById('usersList');
        if (data.success && data.users.length > 0) {
            usersList.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>Uploaded File</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Birth Date</th>
                            <th>Birth Time</th>
                            <th>Birth Month</th>
                            <th>Birth Week</th>
                            <th>Birth DateTime</th>
                            <th>Website</th>
                            <th>Gender</th>
                            <th>Color</th>
                            <th>Salary</th>
                            <th>Bio</th>
                            <th>Newsletter</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.users.map(user => `
                            <tr>
                                <td>${user.profile_image ? (user.file_type && user.file_type.startsWith('image/') ? `<img src="data:${user.file_type};base64,${user.profile_image}" alt="Profile" class="profile-img" onerror="this.style.display='none'; this.parentNode.innerHTML='Invalid Image'">` : `<a href="data:${user.file_type};base64,${user.profile_image}" download="${user.file_name || 'file'}">${user.file_name || 'File'}</a>`) : 'No File'}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.phone || 'N/A'}</td>
                                <td>${user.birth_date || 'N/A'}</td>
                                <td>${user.birth_time || 'N/A'}</td>
                                <td>${user.birth_month || 'N/A'}</td>
                                <td>${user.birth_week || 'N/A'}</td>
                                <td>${user.birth_datetime || 'N/A'}</td>
                                <td>${user.website_url || 'N/A'}</td>
                                <td>${user.gender}</td>
                                <td><div style="width:20px;height:20px;background:${user.profile_color};border-radius:50%;"></div></td>
                                <td>$${user.salary_range}</td>
                                <td>${user.bio || 'N/A'}</td>
                                <td>${user.newsletter ? 'Yes' : 'No'}</td>
                                <td>
                                    <button class="edit-btn" onclick="editUser(${user.id})">Edit</button>
                                    <button class="delete-btn" onclick="deleteUser(${user.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        } else {
            usersList.innerHTML = '<p>No users found.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('usersList').innerHTML = '<p>Error loading users.</p>';
    });
}
function editUser(id) {
    fetch(`api.php?action=read&id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.user;
            const form = document.getElementById('registrationForm');
            
            document.getElementById('userId').value = user.id;
            form.name.value = user.name;
            form.email.value = user.email;
            form.password.value = '';
            form.phone.value = user.phone || '';
            form.birth_date.value = user.birth_date || '';
            form.birth_time.value = user.birth_time || '';
            form.birth_month.value = user.birth_month || '';
            form.birth_week.value = user.birth_week || '';
            form.birth_datetime.value = user.birth_datetime || '';
            form.website_url.value = user.website_url || '';
            form.gender.value = user.gender;
            form.profile_color.value = user.profile_color;
            form.salary_range.value = user.salary_range;
            form.bio.value = user.bio || '';
            form.newsletter.checked = user.newsletter == 1;
            
            updateSalary(user.salary_range);
            showTab('register');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading user data');
    });
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch('api.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully!');
                loadUsers();
            } else {
                alert('Error deleting user: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}

function cancelEdit() {
    document.getElementById('registrationForm').reset();
    document.getElementById('userId').value = '';
    updateSalary(50000);
}