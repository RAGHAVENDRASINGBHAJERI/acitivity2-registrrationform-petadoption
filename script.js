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
            usersList.innerHTML = data.users.map(user => `
                <div class="user-card">
                    <h3>${user.name}</h3>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                    <p><strong>Gender:</strong> ${user.gender}</p>
                    <p><strong>Birth Date:</strong> ${user.birth_date || 'N/A'}</p>
                    <p><strong>Website:</strong> ${user.website_url || 'N/A'}</p>
                    <p><strong>Salary Range:</strong> $${user.salary_range}</p>
                    <p><strong>Newsletter:</strong> ${user.newsletter ? 'Yes' : 'No'}</p>
                    <div class="user-actions">
                        <button class="edit-btn" onclick="editUser(${user.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteUser(${user.id})">Delete</button>
                    </div>
                </div>
            `).join('');
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