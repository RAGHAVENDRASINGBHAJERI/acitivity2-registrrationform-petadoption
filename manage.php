<!DOCTYPE html>
<html>
<head>
    <title>Manage Pet Adoptions</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="form-container" style="max-width: 800px;">
        <h2>All Adoption Applications</h2>
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="index.html" style="color: #667eea; text-decoration: none;">← Back to Adoption Form</a>
        </div>
        
        <?php
        require_once 'config.php';
        
        // Handle delete
        if (isset($_GET['delete'])) {
            $stmt = $pdo->prepare("DELETE FROM registrations WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            echo "<div class='result'><p>Application deleted successfully!</p></div>";
        }
        
        // Handle update
        if ($_POST && isset($_POST['update'])) {
            $stmt = $pdo->prepare("UPDATE registrations SET name=?, email=?, phone=?, pet_type=? WHERE id=?");
            $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['pet_type'], $_POST['id']]);
            echo "<div class='result'><p>Application updated successfully!</p></div>";
        }
        
        // Fetch all admissions
        $stmt = $pdo->query("SELECT * FROM registrations ORDER BY created_at DESC");
        $admissions = $stmt->fetchAll();
        ?>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr style="background: #667eea; color: white;">
                <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Email</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Phone</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Pet Type</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
            </tr>
            <?php foreach ($admissions as $reg): ?>
            <tr id="row-<?= $reg['id'] ?>">
                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($reg['name']) ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($reg['email']) ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($reg['phone']) ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($reg['pet_type']) ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;">
                    <button onclick="editRow(<?= $reg['id'] ?>)" style="background: #28a745; padding: 5px 10px; margin: 2px;">Edit</button>
                    <button onclick="deleteReg(<?= $reg['id'] ?>)" style="background: #dc3545; padding: 5px 10px; margin: 2px;">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function deleteReg(id) {
            if (confirm('Delete this adoption application?')) {
                window.location.href = 'manage.php?delete=' + id;
            }
        }
        
        function editRow(id) {
            const row = document.getElementById('row-' + id);
            const cells = row.getElementsByTagName('td');
            
            cells[0].innerHTML = '<input type="text" value="' + cells[0].textContent + '" id="name-' + id + '">';
            cells[1].innerHTML = '<input type="email" value="' + cells[1].textContent + '" id="email-' + id + '">';
            cells[2].innerHTML = '<input type="tel" value="' + cells[2].textContent + '" id="phone-' + id + '">';
            cells[3].innerHTML = '<select id="pet_type-' + id + '"><option value="Dog">Dog</option><option value="Cat">Cat</option><option value="Rabbit">Rabbit</option></select>';
            cells[4].innerHTML = '<button onclick="saveRow(' + id + ')" style="background: #007cba; padding: 5px 10px;">Save</button>';
            
            document.getElementById('pet_type-' + id).value = cells[3].querySelector('select').previousSibling.textContent;
        }
        
        function saveRow(id) {
            const formData = new FormData();
            formData.append('update', '1');
            formData.append('id', id);
            formData.append('name', document.getElementById('name-' + id).value);
            formData.append('email', document.getElementById('email-' + id).value);
            formData.append('phone', document.getElementById('phone-' + id).value);
            formData.append('pet_type', document.getElementById('pet_type-' + id).value);
            
            fetch('manage.php', { method: 'POST', body: formData })
                .then(() => location.reload());
        }
    </script>
</body>
</html>